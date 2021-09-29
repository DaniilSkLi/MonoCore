<?php

function MONO_Redirect($url, $js = false, $tab = false) {
    if ($js)
    {
        if ($tab)
        {
            echo "<script>window.open('".$url."')</script>";
        }
        else
        {
            echo "<script>window.location.href = '".$url."'</script>";
        }
    }
    else
    {
        header("Location: " . $url);
    }
    die();

}