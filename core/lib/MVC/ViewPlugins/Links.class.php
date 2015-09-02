<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 09.08.15 - 18:23.
 */
namespace DripsPHP\MVC\ViewPlugins;

use DripsPHP\MVC\IViewPlugin;
use DripsPHP\MVC\View;
use DripsPHP\Routing\Redirect;

/**
 * Class Links.
 *
 * view plugin for placeholders for links
 */
class Links implements IViewPlugin
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
     * Syntax: @LINK(route)
     */
    protected function replace()
    {
        //preg_match_all('/@LINK\((.*)\)/i', $this->template, $matches);
        preg_match_all('/@LINK\(([^\)]+)*\)/i', $this->template, $matches);
        if (!empty($matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $replaceWith = Redirect::link($matches[1][$i]);
                $replace = $matches[0][$i];
                $this->template = str_replace($replace, $replaceWith, $this->template);
            }
        }
    }
}
