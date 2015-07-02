<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 31.01.15 - 16:35.
 */
namespace DripsPHP\Debug;

/**
 * Class Page.
 *
 * used for creating the debug-page
 */
abstract class Page
{
    /**
     * initialize the debug-page.
     */
    public static function create()
    {
        self::initPage();
        self::reset();
    }

    /**
     * checks if it necessary to create the debug-page checks if errors occured
     * which should be shown.
     *
     * @return bool
     */
    public static function mustDebug()
    {
        if (isset($_ENV['DP_DEBUG']) && !empty($_ENV['DP_DEBUG'])) {
            return true;
        }
        $_ENV['DP_DEBUG'] = array();

        return false;
    }

    /**
     * resets the error list.
     */
    private static function reset()
    {
        unset($_ENV['DP_DEBUG']);
    }

    /**
     * include page-layout / output.
     */
    private static function initPage()
    {
        include __DIR__.'/page/layout.php';
    }
}
