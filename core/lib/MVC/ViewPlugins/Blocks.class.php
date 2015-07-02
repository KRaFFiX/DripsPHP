<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 13:15.
 */
namespace DripsPHP\MVC\ViewPlugins;

use DripsPHP\MVC\IViewPlugin;
use DripsPHP\MVC\View;

/**
 * Class Blocks.
 *
 * Plugin inherit templates and use blocks for replacing elements
 */
class Blocks implements IViewPlugin
{
    protected $template;
    protected $extends = array();
    protected $blocks = array();

    /**
     * returns the compiled template.
     *
     * @param $template
     *
     * @return mixed
     */
    public function compile($template, $name)
    {
        $this->template = $template;
        // has extends?
        if ($this->findExtends()) {
            // get extends template
            $this->extends['tpl'] = $this->getTemplate($this->extends['view']);
            $this->findBlocks($this->extends['tpl']);
            $this->findBlocks($this->template);
            $this->compileTemplate();
        }

        return $this->template;
    }

    /**
     * tries to find an extend into the template
     * Syntax: <!-- @EXTENDS(your.views.template) -->.
     *
     * @return bool
     */
    public function findExtends()
    {
        $matches = array();
        // Syntax: <!-- @EXTENDS(your.views.template) -->
        preg_match('/<!-- @EXTENDS\((.*)\) -->/i', $this->template, $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches)) {
            $startPos = $matches[0][1];
            $extendView = $matches[1][0];
            $this->extends = ['startpos' => $startPos, 'view' => $extendView];

            return true;
        }

        return false;
    }

    /**
     * compile the template which should be used as parent.
     *
     * @param $tpl
     *
     * @return string
     *
     * @throws Exception
     */
    public function getTemplate($tpl)
    {
        $extendsView = new View();

        return $extendsView->make($tpl);
    }

    /**
     * find blocks in a string
     * Syntax: <!-- @SECTION(name) -->
     * Syntax: <!-- @END(name) -->.
     *
     * @param $src
     */
    public function findBlocks($src)
    {
        $blocks = array();
        // find start tags
        $sectionMatches = array();
        // Syntax: <!-- @SECTION(name) -->
        preg_match_all('/<!-- @SECTION\((.*)\) -->/i', $src, $sectionMatches, PREG_OFFSET_CAPTURE);
        if (!empty($sectionMatches)) {
            for ($i = 0; $i < count($sectionMatches[0]); $i++) {
                $startPos = $sectionMatches[0][$i][1];
                $name = $sectionMatches[1][$i][0];
                $blocks[$name] = array('startpos' => $startPos);
            }
        }
        // find end tags
        $endMatches = array();
        // Syntax: <!-- @END(name) -->
        preg_match_all('/<!-- @END\((.*)\) -->/i', $src, $endMatches, PREG_OFFSET_CAPTURE);
        if (!empty($endMatches)) {
            for ($i = 0; $i < count($endMatches[0]); $i++) {
                $name = $endMatches[1][$i][0];
                $endPos = $endMatches[0][$i][1];
                $endPos += strlen("<!-- @END($name) -->");
                if (array_key_exists($name, $blocks)) {
                    $blocks[$name]['endpos'] = $endPos;
                    $diff = $endPos - $blocks[$name]['startpos'];
                    $blocks[$name]['content'] = substr($src, $blocks[$name]['startpos'], $diff);
                    $this->blocks[$name] = $blocks[$name];
                }
            }
        }
    }

    /**
     * replaces the blocks syntax with the correct contents or removes it.
     */
    public function compileTemplate()
    {
        $extendsTpl = $this->extends['tpl'];
        foreach ($this->blocks as $name => $block) {
            $extendsTpl = preg_replace('/<!-- @SECTION\('.$name.'\) -->(.+)<!-- @END\('.$name.'\) -->/is', $block['content'], $extendsTpl);
        }
        // Replace block definitions
        $this->template = self::removeWrong($extendsTpl);
    }

    public static function removeWrong($template)
    {
        $template = preg_replace('/<!-- @SECTION\((.*)\) -->/i', '', $template);
        $template = preg_replace('/<!-- @END\((.*)\) -->/i', '', $template);

        return $template;
    }
}
