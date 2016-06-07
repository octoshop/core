#!/bin/bash

echo "octoshop.dev" > /etc/hostname
echo "127.0.0.1 octoshop.dev dev localhost" > /etc/hosts

export DEBIAN_FRONTEND=noninteractive

apt-get update -q && apt-get install -qy git unzip zsh
chsh -s /bin/zsh && chsh -s /bin/zsh ubuntu

debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

apt-get install -qy mysql-server mysql-client nginx php-fpm

echo "CREATE USER 'octoshop'@'localhost' IDENTIFIED BY 'octoshop'" | mysql -u root -proot
echo "CREATE DATABASE octoshop" | mysql -u root -proot
echo "GRANT ALL ON octoshop.* TO 'octoshop'@'localhost'" | mysql -u root -proot
echo "FLUSH PRIVILEGES" | mysql -u root -proot

apt-get install -qy php-mysql php-mbstring php-curl php-gd php-intl php-mcrypt php-xml php-zip

cat >> /etc/nginx/sites-available/default <<EOF
server {
    listen 80;
    listen [::]:80;

    server_name octoshop.dev;

    root /var/www/octoshop.dev;
    index index.php;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi.conf;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

chown -R www-data:www-data /var/www; cd /var/www
sudo -u www-data composer create-project october/october octoshop.dev dev-develop
cd octoshop.dev
sed -i 's/localhost/octoshop.dev/' config/app.php
sed -i "s/edgeUpdates' => false/edgeUpdates' => true/" config/cms.php
sed -i "s/disableCoreUpdates' => false/disableCoreUpdates' => true/" config/cms.php
sed -i "s/convertLineEndings' => false/convertLineEndings' => true/" config/cms.php
sed -i "s/'database'  => 'database'/'database'  => 'octoshop'/" config/database.php
sed -i "s/'username'  => 'root'/'username'  => 'octoshop'/" config/database.php
sed -i "s/'password'  => ''/'password'  => 'octoshop'/" config/database.php
php artisan october:up

# Install this last to speed up composer on 1strun
sudo apt-get install -qy php-xdebug

systemctl restart nginx
systemctl restart php7.0-fpm
