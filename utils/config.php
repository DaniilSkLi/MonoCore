<?php

class MONO_Config {
    private $table;

    public function __construct($table) {
        $this->table = $table;

        MONO_DB::create($table, function($t) {
            $t->string("name");
            $t->json("value");
        });
    }

    public function Set($name, $value) {
        if (MONO_DB::table($this->table)->where("name", $name)->get() == array())

            return MONO_DB::table($this->table)->insert(["name" => $name, "value" => MONO_JSON::Encode($value)]);

        else
            return MONO_DB::table($this->table)->update(["name" => $name, "value" => MONO_JSON::Encode($value)]);
    }

    public function Get($name) {
        return MONO_JSON::Decode(MONO_DB::table($this->table)->where("name", $name)->value("value"));
    }

    public function UnSet($name) {
        return MONO_DB::table($this->table)->where("name", $name)->delete();
    }

    public function Destroy() {
        return MONO_DB::drop($this->table);
    }
}