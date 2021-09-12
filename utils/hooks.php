<?php

class MONO_Hooks {
    private static $hooks = array();

    public static function add_action($tag, $callback)
    {
        $hooks = self::$hooks;

        if (!isset($hooks[$tag]))
        {
            $hooks[$tag] = array();
        }

        $hooks[$tag][] = $callback;
        self::$hooks = $hooks;
    }

    public static function do_action($tag)
    {
        $hooks = self::$hooks;

        if (isset($hooks[$tag]))
        {
            foreach ($hooks[$tag] as $func) {
                $func();
            }
        }
    }
}