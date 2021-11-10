<?php

class MONO_Info {
    public static function PHP($need = null) {

        if ($need != null) {
            $info = self::get();

            $php = $info["minPHP"];

            return true; // TODO: <===
        }

        else
            return phpversion();

    }

    public static function get()
    {
        return MONO_JSON::Decode(CORE . "data/info.json");
    }

    public static function core() {
        return self::CoreAll()["version"];
    }

    public static function coreAll() {
        return MONO_JSON::decode(CORE . "data/info.json");
    }
}