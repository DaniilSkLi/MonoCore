<?php

class MONO_Update {
    public static function Check() {
        $V = self::CurrentVersion();
        $sV = self::ServerVersion();

        if ($V == $sV) {
            return false;
        }
        else {
            return array(
                "new" => $sV,
                "current" => $V
            );
        }
    }

    public static function Update() {
        if (self::Check() != false) {
            $upIni = new MONO_ini(__DIR__ . "/../data/update.ini");
            echo "<script>window.open('". $upIni->Get("updateSource") ."')</script>";
        }
        else {
            return false;
        }
    }

    public static function CurrentVersion() {
        $ini = new MONO_ini(__DIR__ . "/../data/info.json");
        $V = $ini->Get("version");
        return $V;
    }

    public static function ServerVersion() {
        $upIni = new MONO_ini(__DIR__ . "/../data/update.ini");
        $server = $upIni->Get("newVersionFile");
        $serverIni = explode(";\n", file_get_contents($server));

        $V = self::CurrentVersion();
        $sV = $V;
        foreach ($serverIni as $var) {
            $tmp = explode(" = ", $var);
            if ($tmp[0] == "version") {
                $sV = str_replace("\"", "", $tmp[1]);
                break;
            }
        }

        return $sV;
    }
}