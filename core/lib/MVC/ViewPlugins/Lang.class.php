<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 06.04.15 - 17:35.
 */
namespace DripsPHP\MVC\ViewPlugins;

use DripsPHP\MVC\IViewPlugin;
use DripsPHP\MVC\View;

/**
 * Class Lang.
 *
 * view plugin for placeholders for multilingual views
 */
class Lang implements IViewPlugin
{
    protected $template;

    /**
     * compiles the template.
     *
     * @param $template
     * @param $name
     *
     * @return string
     */
    public function compile(View $view)
    {
        $this->template = $view->getTemplate();
        $this->replace();

        return $this->template;
    }

    /**
     * replacing the placeholders.
     * Syntax: @TRANSLATE[key.key2.key3](param1, param2, param3)
     * params are optional!
     */
    protected function replace()
    {
        //preg_match_all('/@TRANSLATE\[(.*)\](\((.*)\))?/i', $this->template, $matches, PREG_OFFSET_CAPTURE);
        preg_match_all('/@TRANSLATE\[([^\]]+)*\](\(([^\]]+)*\))?/i', $this->template, $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $replaceWith = '<?php printf(\\DripsPHP\\App::$dictionary';
                $replace = $matches[0][$i][0];
                $keys = $matches[1][$i][0];
                $replaceWith .= '["'.str_replace('.', '"]["', $keys).'"]';

                if (!empty($matches[3][$i])) {
                    $params = $matches[3][$i][0];
                    $replaceWith .= ', "'.str_replace(',', '","', $params).'"';
                }
                $replaceWith .= '); ?>';
                $this->template = str_replace($replace, $replaceWith, $this->template);
            }
        }
    }
}
