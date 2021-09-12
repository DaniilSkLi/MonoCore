<?php

class MONO_JSON {
    public static function Encode($var)
    {
        return json_encode($var,  JSON_UNESCAPED_UNICODE);
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
            $file = new MONO_File($json);
            $json = $file->Read();
            $file->Close();
        }

        return json_decode($json, true);
    }
}