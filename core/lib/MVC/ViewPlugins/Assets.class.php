<?php

/**
 * Created by Prowect
 * Author: Lars Müller
 * Date: 12.08.15 - 19:28.
 */
namespace DripsPHP\MVC\ViewPlugins;

use DripsPHP\MVC\IViewPlugin;
use DripsPHP\MVC\View;
use DripsPHP\Routing\Redirect;

/**
 * Class Assets.
 *
 * view plugin for placeholders for assets
 */
class Assets implements IViewPlugin
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
     * Syntax: @ASSET(name [, package])
     */
    protected function replace()
    {
        //preg_match_all('/@LINK\((.*)\)/i', $this->template, $matches);
        preg_match_all('/@ASSET\(([^\)]+)*\)/i', $this->template, $matches);
        if (!empty($matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $parameters = array_filter(array_map('trim', explode(',', $matches[1][$i])));
                if(isset($parameters[1])) {
                    $replaceWith = Redirect::asset($parameters[0], $parameters[1]);
                } else {
                    $replaceWith = Redirect::asset($parameters[0]);
                }
                $replace = $matches[0][$i];
                $this->template = str_replace($replace, $replaceWith, $this->template);
            }
        }
    }
}
