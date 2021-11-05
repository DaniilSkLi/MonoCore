<?php

function MONO_Debug($var)
{
    if (CORE_CFG["settings"]["debug"])
    {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}