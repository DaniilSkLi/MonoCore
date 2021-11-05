<?php

define("CORE", __DIR__ . "/");
define("CORE_CFG", json_decode(file_get_contents(CORE . "data/core.json"), true));
define("CORE_LOAD", json_decode(file_get_contents(CORE . "data/load.json"), true));



class Core
{
    public static function Start()
    {
        self::Connect();
    }

    private static function Connect()
    {
        /* First load */
        $first = CORE_LOAD["load"]["files"]["first"];

        /* Load file-exceptions */
        $nonLoad = CORE_LOAD["load"]["files"]["exceptions"];
        $experimentalMode = CORE_LOAD["load"]["mode"]["experimental"];
        $experimental = CORE_LOAD["load"]["files"]["experimental"];
        $experimentalUnLoad = CORE_LOAD["load"]["files"]["experimentalUnLoad"];



        /* Search all core modules */
        $files = self::rsearch(__DIR__, "php");
        for ($i = 0; $i < count($files); $i++) {
            $files[$i] = str_replace(__DIR__, "", $files[$i]);
        }



        /* Diff to get all core modules without service files */
        $load = array_diff($files, $nonLoad);
        if (!$experimentalMode)
            $load = array_diff($load, $experimental);
        else
            $load = array_diff($load, $experimentalUnLoad);



        /* Add first load modules to up array */
        $load = array_merge($first, $load);



        /* Load */
        foreach ($load as $file) {
            require_once __DIR__ . $file;
        }



        /* Init file */
        require_once __DIR__ . "/" . "init.php";
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
