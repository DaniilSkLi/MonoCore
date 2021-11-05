<?php

class MONO_Error {
    public static function CoreError($error, $solutions = array(), $fatal = true, $l = null, $f = null) {
        if ($fatal)
        {
            $call = debug_backtrace();
            if ($l == null) $l = $call[0]["line"];
            if ($f == null) $f = $call[0]["file"];

            $solutions[count($solutions)] = "Line: " . $l;
            $solutions[] = "File: " .  $f;

            $info = CORE_CFG["info"];

            require_once __DIR__ . "/errorTemplate.php";

            die();
        }
        else
        {
            // TODO: log in file.
        }
    }
}



set_error_handler(function($n, $m, $f, $l) {
    if (CORE_CFG["settings"]["errors"])
        MONO_Error::CoreError($m, [], true, $l, $f);
}, E_WARNING);