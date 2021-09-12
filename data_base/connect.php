<?php

class MONO_Connect {
    private $x = array("PDO" => null, "TablePrefix");

    public function Try() {
        try {
            $MONO_HOST = MONO_JSON::Decode(CORE . "Data/connect.json");

            $this->x["TablePrefix"] = $MONO_HOST["table_prefix"];
            $this->x["PDO"] = new PDO('mysql:dbname='.$MONO_HOST["db"].';host='.$MONO_HOST["host"] . ";charset=utf8", $MONO_HOST["login"], $MONO_HOST["password"]);

            unset($ini);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function __get($name) {
        if (isset($this->x[$name])) {
            $r = $this->x[$name];
            return $r;
        }
        else {
            return null;
        }
    }
}