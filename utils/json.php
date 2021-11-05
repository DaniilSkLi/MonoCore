<?php

class MONO_JSON {
    public static function Encode($var, $file = false)
    {
        $json = json_encode($var,  JSON_UNESCAPED_UNICODE);
        if ($file)
        {
            file_put_contents($file, "$json");
        }

        return $json;
    }

    public static function Decode($json, $file = NULL)
    {
        if ($file == NULL)
        {
            if (file_exists($json))
                $file = true;
            else
                $file = false;
        }

        if ($file)
        {
            $json = file_get_contents($json);
        }

        return json_decode($json, true);
    }
}