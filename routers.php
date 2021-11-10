<?php



MONO_Router::add(">MonoCore_Info", function() {
    $info = MONO_Info::coreAll();

    foreach ($info as $val => $i) {
        echo $val . " = " . $i . "<br>";
    }

    die();
});