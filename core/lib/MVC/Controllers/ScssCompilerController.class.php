<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 28.03.15 - 15:31.
 */
namespace DripsPHP\MVC\Controllers;

use DripsPHP\MVC\Controller;
use DripsPHP\Cache\CompileCache;
use DripsPHP\Compiler\SCSS\ScssCompiler;
use DripsPHP\Routing\Response;
use Exception;

/**
 *	Class ScssCompilerController.
 *
 *	used for auto compiling scss files
 */
abstract class ScssCompilerController extends Controller
{
    protected $scssdir;

    /**
     *	default-directory is src/CALLED_PACKAGE/assets/scss.
     *
     *	@throws ScssDirNotFoundException
     */
    public function __construct()
    {
        $this->init();
        if (!isset($this->scssdir)) {
            $this->scssdir = 'src/'.explode('\\', get_called_class())[0].'/assets/scss';
        }
        if (!is_dir($this->scssdir)) {
            throw new ScssDirNotFoundException();
        }
    }

    /**
     *	handle controller requests
     *	creates a "text/css" response.
     *
     *	@param $method
     *	@param $params = array()
     *
     *	@return string
     */
    public function request($method, $params = array())
    {
        Response::$type = 'text/css';

        return $this->load($params['file']);
    }

    /**
     *	loads $file and compiles file if it is not in cache.
     *
     *	@param $file
     *
     *	@throws ScssFileNotFoundException
     *
     *	@return string
     */
    public function load($file)
    {
        $path = $this->scssdir.'/'.$file;
        if (is_file($path)) {
            $filename = str_replace('\\', '.', __NAMESPACE__).'.'.$file;
            $cache = new CompileCache($filename, $path);

            return $cache->get(function () use ($path) {
                $scssCompiler = new ScssCompiler();

                return $scssCompiler->compileFile($path);
            });
        }
        throw new ScssFileNotFoundException();
    }
}

class ScssDirNotFoundException extends Exception
{
}

class ScssFileNotFoundException extends Exception
{
}
