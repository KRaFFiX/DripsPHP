<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 01.07.15 - 17:00.
 */
namespace DripsPHP\API;

use DripsPHP\App;

/**
 * class Updater
 *
 * used for updating the drips framework
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
     * checks if there is a new update available for drips
     *
     * @return boolean
     */
    public function hasUpdate(){
        $version = App::VERSION;

        $request = file_get_contents($this->source."/update-drips");
        $result = json_decode($request, true);

        if(!array_key_exists("error", $result) && array_key_exists("VERSION", $result)){
            return $result["VERSION"] != $version;
        }

        return false;
    }

    /**
     * returns the downloadlink of the update for drips
     *
     * @return string
     */
    public function getDownloadLink(){
        return $this->source."/download-drips";
    }

    /**
     * downloads and installs the update
     *
     * @return bool
     */
    public function download(){
        $content = file_get_contents($this->getDownloadLink());
        $name = "drips";
        $path = "core/tmp/$name.tar.gz";
        if(file_put_contents($path, $content) !== false){
            exec("cd core/tmp && tar -xf $name.tar.gz && rm $name.tar.gz");
            $request = file_get_contents($this->source."/update-drips");
            $result = json_decode($request, true);
            $name = $result["VERSION"];
            $dir = "core/tmp/$name";
            $dest = "./";
            self::recursiveCopy($dir, $dest);
            exec("cd core/tmp && rm -r $name");
            return true;
        }
        return false;
    }

    /**
     * used for installing the plugin
     *
     * copies files from one location to another one => recursive copy
     *
     * @param $src
     * @param $dest
     */
    public static function recursiveCopy($src, $dest){
        $files = scandir($src);
        foreach($files as $file){
            $src_path = "$src/$file";
            $dest_path = "$dest/$file";
            if($file != "." && $file != ".."){
                if(is_dir($src_path)){
                    if(!is_dir($dest_path)){
                        mkdir($dest_path);
                    }
                    self::recursiveCopy($src_path, $dest_path);
                } elseif(is_file($src_path)){
                    copy($src_path, $dest_path);
                }
            }
        }
    }
}
