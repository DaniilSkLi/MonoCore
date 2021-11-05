<?php

class MONO_include {

    private static $css = "";
    private static $js = "";
    private static $font = "";



    /* CSS */
    public static function css($url)
    {
        if (file_exists($url))
            self::$css .= file_get_contents($url);
    }

    public static function css_array($array)
    {
        foreach ($array as $url) {
            self::css($url);
        }
    }

    public static function getCSS()
    {
        echo "<style>". self::$css ."</style>";
    }



    /* JS */
    public static function js($url)
    {
        if (file_exists($url))
            self::$js .= file_get_contents($url);
    }
    public static function js_array($array)
    {
        foreach ($array as $url)
        {
            self::js($url);
        }
    }

    public static function getJS()
    {
        echo "<script>". self::$js ."</script>";
    }



    /* Font */
    public static function font($url, $name)
    {
        self::$font .= "<style>@font-face { font-family: $name; src: url($url); }</style>";
    }
    public static function font_array($array)
    {
        foreach ($array as $name => $url) {
            self::font($url, $name);
        }
    }

    public static function getFont()
    {
        echo "<style>". self::$font ."</style>";
    }
}



function getHead()
{
    MONO_include::getFont();
    MONO_include::getCSS();

    MONO_Hooks::do_action("getHead");
}

function getFooter()
{
    MONO_Hooks::do_action("getFooter");

    MONO_include::getJS();
}