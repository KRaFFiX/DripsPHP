<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 01.07.15 - 13:50.
 */
namespace DripsPHP\Plugin;

/**
 * class Updater
 *
 * used for communication with drips store and updating or downloading plugins
 */
class Updater
{
    protected $source = "http://drips.prowect.com/store";

    public function __construct($source = null)
    {
        if($source !== null){
            $this->source = $source;
        }
    }

    /**
     * checks if there is a new update available for the plugin named $pluginname
     *
     * @param $pluginname
     * @return boolean
     */
    public function hasUpdate($pluginname){
        $plugin = PluginHandler::getClass($pluginname);
        $version = $plugin::VERSION;
        $name = $plugin::NAME;

        $request = file_get_contents($this->source."/info/$name");
        $result = json_decode($request, true);

        if(!array_key_exists("error", $result) && array_key_exists("VERSION", $result)){
            return $result["VERSION"] != $version;
        }

        return false;
    }

    /**
     * returns the downloadlink of the update for plugin
     *
     * @param $pluginname
     * @return string
     */
    public function getDownloadLink($pluginname){
        return $this->source."/download/$pluginname";
    }

    /**
     * downloads and installs the plugin
     *
     * @param $name
     * @param $url
     * @return bool
     */
    public function download($name, $url){
        $content = file_get_contents($url);
        $path = "core/tmp/$name.tar.gz";
        if(file_put_contents($path, $content) !== false){
            exec("cd core/tmp && tar -xf $name.tar.gz && rm $name.tar.gz");
            $dir = "core/tmp/$name";
            $dest = "plugins/$name";
            DripsPHP\API\Updater::recursiveCopy($dir, $dest);
            exec("cd core/tmp && rm -r $name");
            return true;
        }
        return false;
    }
}
