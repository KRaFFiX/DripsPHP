<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 13:05.
 */
namespace DripsPHP\MVC;

use DripsPHP\Config\Config;
use DripsPHP\Debug\ExceptionHandler;
use DripsPHP\ClassLoader\Path;
use DripsPHP\ClassLoader\ClassLoader;
use DripsPHP\Form\Form;
use Exception;
use DripsPHP\MVC\ViewPlugins\Blocks;

/**
 * Class View.
 *
 * represents view of mvc pattern
 */
class View
{
    protected $vars = array();
    protected $objs = array();
    protected $plugindir = 'ViewPlugins';
    protected $template = '';
    protected $evalDir = 'core/tmp/';
    protected $path;
    protected static $generated = 0;

    public function __construct()
    {
        self::$generated++;
    }

    /**
     * assign a variable to the template
     * which will be replaced in the template
     * if using the right syntax.
     *
     * @param $key
     * @param $value
     */
    public function assign($key, $value, $overwrite = true)
    {
        if (is_object($value)) {
            $this->objs[$key] = $value;
            if($value instanceof Form){
                $this->assign($key, $value->toArray());
            }
        } else {
            if(!$overwrite && isset($this->vars[$key])){
                $this->vars[$key] .= $value;
            } else {
                $this->vars[$key] = $value;
            }
        }
    }

    /**
     * returns if $key is assigned
     *
     * @param $key
     *
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->vars) || array_key_exists($key, $this->objs);
    }

    /**
     * returns the path to a template.
     *
     * @param $template
     *
     * @return string
     */
    public static function resolveTpl($template)
    {
        if (file_exists($template)) {
            // SHOULD NOT BE USED!!!
            $viewPath = $template;
        } else {
            $viewPath = new Path($template);
            $viewPath = ClassLoader::getClassFile($viewPath->getClass());
        }

        return $viewPath;
    }

    /**
     * generates or compiles the view.
     *
     * @param $template
     *
     * @return string
     *
     * @throws Exception
     */
    public function make($template)
    {
        $viewPath = self::resolveTpl($template);
        if (file_exists($viewPath)) {
            $this->template = file_get_contents($viewPath);
            $this->replaceVars();
            $this->replaceMethods();
            $this->compile($template);
            $this->compilePHP($viewPath);
        } else {
            throw new Exception("View not found: $template in $viewPath!");
        }
        self::$generated--;

        return $this->template;
    }

    /**
     * replacing the assigned vars in the template
     * Syntax: {{{ var }}} or {{{ var.exists() }}}.
     */
    protected function replaceVars()
    {
        // Syntax: {{{ var }}}
        // replace $vars array
        $this->recursiveArrayWalk('', $this->vars);
        // Replace non-declared ones {{{ *** }}} and {{{***}}}
        $this->template = preg_replace('/\{\{\{ \w{1,}\.exists\(\) \}\}\}/', 'false', $this->template);
        $this->template = preg_replace('/\{\{\{\w{1,}\.exists\(\)\}\}\}/', 'false', $this->template);
        $this->template = preg_replace('/\{\{\{ \w{1,} \}\}\}/', '', $this->template);
        $this->template = preg_replace('/\{\{\{\w{1,}\}\}\}/', '', $this->template);
    }

    /**
     * used for replacing vars which are multidimensional arrays.
     *
     * @param $key
     * @param $array
     */
    protected function recursiveArrayWalk($key, $array)
    {
        foreach ($array as $key2 => $value) {
            // build correct key
            if (empty($key)) {
                $useKey = $key2;
            } else {
                $useKey = "$key.$key2";
            }
            if (!is_array($value)) {
                // replace single value / no array
                $this->template = str_replace('{{{ '.$useKey.' }}}', $value, $this->template);
                $this->template = str_replace('{{{'.$useKey.'}}}', $value, $this->template);
                // replace exists
                $this->template = str_replace('{{{ '.$useKey.'.exists() }}}', "true", $this->template);
                $this->template = str_replace('{{{'.$useKey.'.exists()}}}', "true", $this->template);
            } else {
                // recursive array walk
                $this->recursiveArrayWalk($useKey, $value);
            }
        }
    }

    /**
     * replaces object-methods (e.g. used for forms).
     */
    protected function replaceMethods()
    {
        foreach ($this->objs as $key => $value) {
            $methods = get_class_methods($value);
            foreach ($methods as $method) {
                $this->template = str_replace("<!-- $key.$method() -->", '<?= $this'."->$key->$method(); ?>", $this->template);
            }
        }
        //$this->template = str_replace('<!-- \w -->', '', $this->template);
    }

    /**
     * applies the plugins to the template.
     */
    protected function compile($template)
    {
        $this->path = $template;
        // does the plugin directory exist?
        $this->plugindir = 'core/lib/MVC/'.$this->plugindir.'/';
        if (is_dir($this->plugindir)) {
            // load plugins
            foreach (glob($this->plugindir.'*.class.php') as $viewPlugin) {
                // convert path to namespace + class
                $pluginObj = str_replace('/', '\\', str_replace('.class.php', '', str_replace('core/lib', '/DripsPHP', $viewPlugin)));
                ViewPlugin::register($viewPlugin, $pluginObj);
            }
        }

        $plugins = ViewPlugin::getAll();
        foreach ($plugins as $class) {
            $plugin = new $class();

            if ($plugin instanceof IViewPlugin) {
                $this->template = $plugin->compile($this);
            }
        }
        #if (self::$generated == 1 && class_exists('\\DripsPHP\\MVC\\ViewPlugins\\Blocks')) {
            #$this->template = Blocks::removeWrong($this->template);
        #}
    }

    /**
     * sets the template
     *
     * @param $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * returns the template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * compiles the php-code in the template.
     *
     * @return string
     */
    protected function compilePHP($viewPath)
    {
        // generate an unique id
        $id = uniqid();
        $tpl = $this->evalDir.$id;
        // copy errors
        $current_errors = isset($_ENV['DP_DEBUG']) ? $_ENV['DP_DEBUG'] : array();
        // start buffer
        ob_start();
        // write template in to a file
        file_put_contents($tpl, $this->template);
        // include template file - so phpcode will automatically be parsed
        try {
            include $tpl;
        } catch (Exception $e) {
            ExceptionHandler::handle($e);
        }
        // delete template file
        unlink($tpl);
        // save output of the template file to the template
        $this->template = ob_get_contents();
        // close the buffer
        ob_end_clean();
        // errors occured?
        if (Config::get('debug-on') && isset($_ENV['DP_DEBUG']) && (count($current_errors) < $_ENV['DP_DEBUG'])) {
            foreach ($_ENV['DP_DEBUG'] as $key => $val) {
                if (!array_key_exists($key, $current_errors)) {
                    if (is_array($_ENV['DP_DEBUG'][$key])) {
                        // Error
                        if (strstr($_ENV['DP_DEBUG'][$key]['file'], 'core/tmp/') !== false) {
                            $_ENV['DP_DEBUG'][$key]['file'] = $viewPath;
                        }
                    }
                }
            }
        }
    }

    /**
     * used for direct access to $objs->property. if it does not exist it will
     * return null.
     *
     * @param $key
     *
     * @return string|null
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->objs)) {
            return $this->objs[$key];
        }

        return;
    }
}
