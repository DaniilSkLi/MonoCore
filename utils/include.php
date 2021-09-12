<?php

class MONO_include {

    /* CSS */
    public static function css($url) {
        echo "<link rel='stylesheet' href=".$url.">";
    }
    public static function css_array($array) {
        foreach ($array as $url) {
            self::css($url);
        }
    }

    /* JS */
    public static function js($url) {
        echo "<script src=".$url."></script>";
    }
    public static function js_array($array) {
        foreach ($array as $url) {
            self::js($url);
        }
    }

    /* Font */
    public static function font($url, $name) {
        echo "<style>@font-face { font-family: $name; src: url($url); }</style>";
    }
    public static function font_array($array) {
        foreach ($array as $name => $url) {
            self::font($url, $name);
        }
    }
}