<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 27.03.15 - 08:34.
 */
namespace DripsPHP\MVC\Controllers;

use DripsPHP\MVC\Controller;
use DripsPHP\Cache\CompileCache;
use DripsPHP\Compiler\LESS\LessCompiler;
use DripsPHP\Routing\Response;
use Exception;

/**
 *	Class LessCompilerController.
 *
 *	used for auto compiling less files
 */
abstract class LessCompilerController extends Controller
{
    protected $lessdir;

    /**
     *	default-directory is src/CALLED_PACKAGE/assets/less.
     *
     *	@throws LessDirNotFoundException
     */
    public function __construct()
    {
        $this->init();
        if (!isset($this->lessdir)) {
            $this->lessdir = 'src/'.explode('\\', get_called_class())[0].'/assets/less';
        }
        if (!is_dir($this->lessdir)) {
            throw new LessDirNotFoundException();
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
     *	@throws LessFileNotFoundException
     *
     *	@return string
     */
    public function load($file)
    {
        $path = $this->lessdir.'/'.$file;
        if (is_file($path)) {
            $filename = str_replace('\\', '.', __NAMESPACE__).'.'.$file;
            $cache = new CompileCache($filename, $path);

            return $cache->get(function () use ($path) {
                $lessCompiler = new LessCompiler();

                return $lessCompiler->compileFile($path);
            });
        }
        throw new LessFileNotFoundException();
    }
}

class LessDirNotFoundException extends Exception
{
}

class LessFileNotFoundException extends Exception
{
}
