<?php namespace Octoshop\Core\Updates;

use File;
use Lang;
use System\Classes\PluginManager;
use System\Classes\VersionManager;
use October\Rain\Database\Updates\Migration as BaseMigration;
use October\Rain\Exception\SystemException;

class Migration extends BaseMigration
{
    protected $pluginManager;

    protected $versionMap = [
        'lite' => [
            'code' => 'Feegleweb.OctoshopLite',
            'namespace' => 'Feegleweb\OctoshopLite',
        ],
        'full' => [
            'code' => 'Feegleweb.Octoshop',
            'namespace' => 'Feegleweb\Octoshop',
        ],
    ];

    public function __construct()
    {
        $this->pluginManager = PluginManager::instance();
    }

    public function upgradingFrom($version)
    {
        // Returns true even if disabled
        return $this->pluginManager->hasPlugin($this->resolvePluginNamespace($version));
    }

    public function disablePlugin($version)
    {
        $code = $this->resolvePluginCode($version);

        // Only returns true if installed AND enabled
        if ($this->pluginManager->exists($code)) {
            $this->pluginManager->disablePlugin($code, true);
        }
    }

    public function purgePlugin($version)
    {
        $code = $this->resolvePluginCode($version);

        if ($path = $this->pluginManager->getPluginPath($code)) {
            File::deleteDirectory($path);
        }

        VersionManager::instance()->purgePlugin($code);
    }

    public function resolvePluginCode($version)
    {
        $code = in_array($version, $this->versionMap) ? $this->versionMap[$version]['code'] : null;

        if (!$code) {
            throw new ApplicationException(Lang::get('octoshop.core::lang.migration.invalid_version'));
        }

        return $code;
    }

    public function resolvePluginNamespace($version)
    {
        $namespace = in_array($version, $this->versionMap) ? $this->versionMap[$version]['namespace'] : null;

        if (!$namespace) {
            throw new ApplicationException(Lang::get('octoshop.core::lang.migration.invalid_version'));
        }

        return $namespace;
    }
}
