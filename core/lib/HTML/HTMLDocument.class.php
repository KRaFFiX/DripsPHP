<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 25.01.15 - 13:29.
 */
namespace DripsPHP\HTML;

/**
 * Class HTMLDocument.
 *
 * represents a html document as php object
 */
class HTMLDocument
{
    protected $title = '';
    protected $body = '';
    protected $stylesheets = array();
    protected $javascripts = array();
    protected $meta = array();
    protected $head = array();

    /**
     * set the <title> of the html document.
     *
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * returns the title of the html document.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * returns the body of the html document.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * sets the body of the html document.
     *
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * appends the current html body.
     *
     * @param $body
     */
    public function appendBody($body)
    {
        $this->setBody($this->getBody().$body);
    }

    /**
     * returns stylesheets of the html document.
     *
     * @return array
     */
    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    /**
     * adds a new stylesheet to the html document.
     *
     * @param $stylesheet
     */
    public function addStylesheet($stylesheet)
    {
        $this->stylesheets[] = $stylesheet;
    }

    /**
     * returns javascripts of the html document.
     *
     * @return array
     */
    public function getJavascripts()
    {
        return $this->javascripts;
    }

    /**
     * adds a new javascript to the html document.
     *
     * @param $javascript
     */
    public function addJavascript($javascript)
    {
        $this->javascripts[] = $javascript;
    }

    /**
     * returns the meta-data of the html document.
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * adds new meta data to html document.
     *
     * @param $name
     * @param $content
     */
    public function addMeta($name, $content)
    {
        $this->meta[$name] = $content;
    }

    /**
     * returns the head of the html document but not all - just these which are
     * added via addHead method.
     *
     * @return array
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * adds a new entry to the head
     * f.e. <style>.
     *
     * @param $head
     */
    public function addHead($head)
    {
        $this->head[] = $head;
    }

    /**
     * generates from a array a string with an special pattern.
     *
     * @param $pattern
     * @param $array
     *
     * @return string
     */
    private function ArrayToTag($pattern, $array)
    {
        $str = '';
        foreach ($array as $value) {
            $str .= str_replace('[here]', $value, $pattern);
        }

        return $str;
    }

    /**
     * generates the html document and returns it.
     *
     * @return string
     */
    public function __toString()
    {
        $str = '<!DOCTYPE html>';
        $str .= "<html>\n";
        $str .= "<head>\n";
        // Meta
        foreach ($this->meta as $key => $value) {
            $str .= "    <meta name='$key' content='$value'/>\n";
        }
        // Head
        $str .= $this->ArrayToTag("  [here]\n", $this->head);
        // Title
        $str .= '    <title>'.$this->title."</title>\n";
        // Stylesheets
        $str .= $this->ArrayToTag("  <link rel='stylesheet' href='[here]'/>\n", $this->stylesheets);
        // Javascripts
        $str .= $this->ArrayToTag("  <script type='text/javascript' src='[here]'></script>\n", $this->javascripts);
        $str .= "</head>\n";
        $str .= "<body>\n";
        // Body
        $str .= '    '.$this->body."\n";
        $str .= "</body>\n";
        $str .= "</html>\n";

        return $str;
    }
}
