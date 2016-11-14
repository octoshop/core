# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
    config.vm.box = "ubuntu/xenial64"

    config.vm.network "forwarded_port", guest: 80, host: 8100

    config.vm.provider "virtualbox" do |vb|
        vb.name = "Octoshop Core Devel"
        vb.linked_clone = true
        vb.memory = 1024
    end

    # Disable this line on first run of vagrant up.
    # It won't be able to provision otherwise because composer
    # needs to create the october project above the shared folder.
    config.vm.synced_folder "../addressbook", "/var/www/octoshop.dev/plugins/octoshop/addressbook",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../base-theme", "/var/www/octoshop.dev/themes/base",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../checkout", "/var/www/octoshop.dev/plugins/octoshop/checkout",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "./", "/var/www/octoshop.dev/plugins/octoshop/core",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../geozones", "/var/www/octoshop.dev/plugins/octoshop/geozones",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../payment", "/var/www/octoshop.dev/plugins/octoshop/payment",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../seo", "/var/www/octoshop.dev/plugins/octoshop/seo",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../shipping", "/var/www/octoshop.dev/plugins/octoshop/shipping",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../stock", "/var/www/octoshop.dev/plugins/octoshop/stock",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../treecat", "/var/www/octoshop.dev/plugins/octoshop/treecat",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true
    config.vm.synced_folder "../units", "/var/www/octoshop.dev/plugins/octoshop/units",
        create: true, group: "www-data", owner: "www-data"
        #disabled: true

    config.vm.provision :shell, path: "vagrant/bootstrap.sh"
end
