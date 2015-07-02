<?php
    /**
     * SCSSPHP
     *
     * @copyright 2012-2014 Leaf Corcoran
     *
     * @license http://opensource.org/licenses/gpl-license GPL-3.0
     * @license http://opensource.org/licenses/MIT MIT
     *
     * @link http://leafo.net/scssphp
     */

    namespace DripsPHP\Compiler\SCSS\Formatter;

    use DripsPHP\Compiler\SCSS\Formatter as FormatterClass;

    /**
     * SCSS expanded formatter
     *
     * @author Leaf Corcoran <leafot@gmail.com>
     */
    class Expanded extends FormatterClass
    {
        public function __construct()
        {
            $this->indentLevel = 0;
            $this->indentChar = '  ';
            $this->break = "\n";
            $this->open = ' {';
            $this->close = '}';
            $this->tagSeparator = ', ';
            $this->assignSeparator = ': ';
        }
    }