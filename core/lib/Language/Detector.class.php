<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 02.04.15 - 14:50.
 */
namespace DripsPHP\Language;

/**
 * Class Detector.
 *
 * used for detecting language which should be used
 */
class Detector
{
    protected static $supported_languages = array();
    protected static $default_language;
    protected static $current_language;

    /**
     * sets current language.
     *
     * @param $current_language
     */
    public static function setCurrentLanguage($current_language)
    {
        self::$current_language = $current_language;
    }

    /**
     * returns the current language.
     *
     * @return string
     */
    public static function getCurrentLanguage()
    {
        if (empty(self::$supported_languages)) {
            return self::$default_language;
        }
        if (empty(self::$current_language)) {
            self::setCurrentLanguage(self::getPreferredLanguage());
        }

        return self::$current_language;
    }

    /**
     * returns preferred language (for current user).
     *
     * @return string
     */
    public static function getPreferredLanguage()
    {
        if (!self::hasCookieLanguage()) {
            $language = self::getBrowserLanguage();
        } else {
            $language = self::getCookieLanguage();
        }
        if (self::isLanguageSupported($language)) {
            return $language;
        }

        return self::getSupportedLanguages()[0];
    }

    /**
     * sets the default language.
     *
     * @param $default_language
     */
    public static function setDefaultLanguage($default_language)
    {
        self::$default_language = $default_language;
    }

    /**
     * returns the default language.
     *
     * @return string
     */
    public static function getDefaultLanguage()
    {
        return self::$default_language;
    }

    /**
     * sets supported languages.
     *
     * @param $supported_languages
     */
    public static function setSupportedLanguages($supported_languages)
    {
        self::$supported_languages = $supported_languages;
    }

    /**
     * returns supported languages.
     *
     * @return array
     */
    public static function getSupportedLanguages()
    {
        return self::$supported_languages;
    }

    /**
     * returns if $language is supported.
     *
     * @param $language
     *
     * @return bool
     */
    public static function isLanguageSupported($language)
    {
        return in_array($language, self::getSupportedLanguages());
    }

    /**
     * checks if 2 languages are the same or different
     * if they are the same it will return the supported language.
     *
     * @param $supported_lang
     * @param $preferred_lang
     *
     * @return string|bool
     */
    public static function compareLanguages($supported_lang, $preferred_lang)
    {
        $supported = strtolower($supported_lang);
        $preferred = strtolower($preferred_lang);
        if (($supported == $preferred) || (substr($supported, 0, 2) == substr($preferred, 0, 2))) {
            return $supported_lang;
        }

        return false;
    }

    /**
     * sets cookie for saving user language.
     *
     * @param $cookie_language
     */
    public static function setCookieLanguage($cookie_language)
    {
        setcookie('dp-lang', $cookie_language, time() + 3600 * 24 * 365, '/');
        $_COOKIE['dp-lang'] = $cookie_language;
    }

    /**
     * returns if language cookie does already exist.
     *
     * @return bool
     */
    public static function hasCookieLanguage()
    {
        return isset($_COOKIE['dp-lang']);
    }

    /**
     * returns the language from the cookie. If the cookie is not set, it will
     * return the default language.
     *
     * @return string
     */
    public static function getCookieLanguage()
    {
        if (self::hasCookieLanguage()) {
            $cookie_lang = $_COOKIE['dp-lang'];
            if (in_array($cookie_lang, self::getSupportedLanguages())) {
                return $cookie_lang;
            }
        }

        return self::getDefaultLanguage();
    }

    /**
     * returns the language which are preferred from the web browser.
     *
     * @param array $supported_languages
     * @param $http_accept_language
     *
     * @return string
     */
    public static function getBrowserLanguage($supported_languages = array(), $http_accept_language = null)
    {
        $accept_language = $http_accept_language;
        if (empty($supported_languages)) {
            $supported_languages = self::getSupportedLanguages();
        }
        if ($accept_language == null) {
            $accept_language = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        if (empty($accept_language)) {
            return self::getDefaultLanguage();
        }

        $splitted_accept_languages = preg_split('/,\s*/', $accept_language);
        $languages = array();
        $current_language = self::getDefaultLanguage();
        $current_quality = 0;

        foreach ($splitted_accept_languages as $language) {
            $parts = explode(';', $language);
            if (count($parts) < 2) {
                $language_code = strtolower($language);
                $quality = 1;
            } else {
                $language_code = strtolower($parts[0]);
                $quality = substr($parts[1], 2);
            }
            $quality *= 10;

            foreach ($supported_languages as $supported_lang) {
                $result = self::compareLanguages($supported_lang, $language_code);
                if ($result) {
                    if (!in_array($result, $languages)) {
                        $found = false;
                        foreach ($languages as $val) {
                            if (is_array($val) && in_array($result, $val)) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            if (!isset($languages[$quality])) {
                                $languages[$quality] = array();
                            }
                            $languages[$quality][] = $result;
                        }
                    }
                }
            }
        }

        if (empty($languages)) {
            return $current_language;
        }

        ksort($languages);

        $lang = array_pop($languages);
        if (is_array($lang)) {
            $lang = $lang[0];
        }

        return $lang;
    }
}
