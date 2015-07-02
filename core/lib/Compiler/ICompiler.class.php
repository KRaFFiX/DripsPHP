<?php
    /**
     * Created by Prowect
     * Author: Raffael Kessler
     * Date: 01.02.15 - 16:43
     */

    namespace DripsPHP\Compiler;


    interface ICompiler
    {
        public function compile($string);
    }