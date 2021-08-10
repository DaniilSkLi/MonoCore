<?php

class MONO_include {
    /* CSS */
    public static function css($url) {
        echo "<link rel='stylesheet' href=".$url.">";
    }
    public static function css_array($array) {
        foreach ($array as $url) {
            echo "<link rel='stylesheet' href=".$url.">";
        }
    }

    /* JS */
    public static function js($url) {
        echo "<script src=".$url."></script>";
    }
    public static function js_array($array) {
        foreach ($array as $url) {
            echo "<script src=".$url."></script>";
        }
    }

    /* Font */
    public static function font($url) {
        self::css($url);
    }
    public static function MONO_include_font_array($array) {
        self::css_array($array);
    }
}