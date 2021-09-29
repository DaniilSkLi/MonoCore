<?php

class MONO_Error {
    public static function CoreError($error, $solutions = array()) {
        $info = MONO_JSON::Decode(CORE . "data/info.json");

        require_once __DIR__ . "/errorTemplate.php";

        die();
    }
}



set_error_handler(function($n, $m, $f, $l) {
    MONO_Error::CoreError($m, array("Line: " . $l, "File: " . $f));
});