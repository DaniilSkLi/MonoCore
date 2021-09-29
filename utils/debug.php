<?php


if (MONO_JSON::Decode(CORE . "data/settings.json")["debug"])
{
    function MONO_Debug($var)
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}
else
{
    function MONO_Debug($var)
    {

    }
}