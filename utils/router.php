<?php

class MONO_Router {
    private static $routes = array();

    private static function current() {
        if (isset($_GET['route']))
            return $_GET['route'];

        return "";
    }

    private static function beautifyUrl($url) {
        $url = preg_replace("/[^\/\w&?%]/i", "", $url);
        $url = trim($url, "/");

        return $url;
    }

    private static function decompose($url) {
        $arr = explode("/", $url);

        return $arr;
    }

    public static function add($route, $func) {
        self::$routes[$route] = $func;
    }

    public static function check() {
        $tree = self::decompose(self::beautifyUrl(self::current()));
        $r = self::$routes;

        foreach ($r as $route => $func) {
            $localTree = self::decompose(self::beautifyUrl($route));

            if ($route[0] == ">") {
                $tree = self::decompose(self::beautifyUrl(strtolower(self::current())));
                $localTree = self::decompose(self::beautifyUrl(strtolower($route)));
            }



            if ($tree == $localTree) {
                $func($tree);

                break;
            }
        }
    }
}