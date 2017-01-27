<?php namespace Octoshop\Core\Util;

use File;
use Cache;
use Config;
use Lang;
use Cms\Classes\Page;
use Cms\Classes\Controller;
use ApplicationException;

trait UrlMaker
{
    protected $url = null;

    protected static $urlPageName = null;

    public function getUrlAttribute()
    {
        if ($this->url === null) {
            $this->url = $this->makeUrl();
        }

        return $this->url;
    }

    public function setUrlAttribute($value)
    {
        $this->url = $value;
    }

    public function setUrlPageName($pageName)
    {
        static::$urlPageName = $pageName;
    }

    public function getUrlPageName()
    {
        if (static::$urlPageName !== null) {
            return static::$urlPageName;
        }

        /*
         * Cache
         */
        $key = 'urlMaker'.$this->urlComponentName.crc32(get_class($this));

        $cached = Cache::get($key, false);
        if ($cached !== false && ($cached = @unserialize($cached)) !== false) {
            $filePath = array_get($cached, 'path');
            $mtime = array_get($cached, 'mtime');
            if (!File::isFile($filePath) || ($mtime != File::lastModified($filePath))) {
                $cached = false;
            }
        }

        if ($cached !== false) {
            return static::$urlPageName = array_get($cached, 'fileName');
        }

        $page = Page::whereComponent($this->urlComponentName, 'isPrimary', '1')->first();

        if (!$page) {
            throw new ApplicationException(sprintf(
                Lang::get('octoshop.core::lang.urlmaker.no_primary_component'),
                $this->urlComponentName,
                get_class($this)
            ));
        }

        $baseFileName = $page->getBaseFileName();
        $filePath = $page->getFilePath();

        $cached = [
            'path'     => $filePath,
            'fileName' => $baseFileName,
            'mtime'    => @File::lastModified($filePath)
        ];

        Cache::put($key, serialize($cached), Config::get('cms.parsedPageCacheTTL', 1440));

        return static::$urlPageName = $baseFileName;
    }

    /**
     * Returns an array of values to use in URL generation.
     * @return @array
     */
    public function getUrlParams()
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
        ];
    }

    protected function makeUrl()
    {
        $controller = Controller::getController() ?: new Controller;

        return $controller->pageUrl($this->getUrlPageName(), $this->getUrlParams());
    }

}
