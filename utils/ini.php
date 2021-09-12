<?php

class MONO_ini {
    private $filePath = "";
    private $file;
    private $ini;
    private $curIni;
    private $multi = false;
    private $rewrite = false;

    public function __construct($file, $rewrite = false)
    {
        $this->Open($file, $rewrite);
    }

    public function Open($file, $rewrite = false) {
        $this->filePath = $file;
        $this->rewrite = $rewrite;

        $this->curIni = parse_ini_file($this->filePath, true);
        $this->multi = $this->IsMultiArr($this->curIni);
        $this->ConvertBool();

        $this->ini = array();
        $this->file = new MONO_File($this->filePath, $rewrite);
    }

    // Convert bool string in ini to bool
    private function ConvertBool() {
        if ($this->multi) {
            foreach ($this->curIni as $group => $arr) {
                foreach ($this->curIni as $key => $value) {
                    if ($value == "1") $this->curIni[$key] = true;
                    if ($value == "0") $this->curIni[$key] = false;
                }
            }
        }
        else {
            foreach ($this->curIni as $key => $value) {
                if ($value == "1") $this->curIni[$key] = true;
                if ($value == "0") $this->curIni[$key] = false;
            }
        }
    }

    // Get ini file to user
    public function GetIni() {
        return $this->curIni;
    }

    // GEt data from ini
    public function Get($name) { // fix
        return $this->ini[$name];
    }

    // Return multi value to user
    public function IsMulti() {
        return $this->multi;
    }

    // Check ini is multi-arr or single arr
    private function IsMultiArr($arr) {
        $multi = false;
        foreach ($arr as $value) {
            if (is_array($value)) $multi = true;
            break;
        }
        return $multi;
    }

    // Write data to ini arr
    public function Write($arr) {
        $multi = $this->IsMultiArr($arr);

        if (($this->multi && $multi) || (!$this->multi && !$multi)) {
            foreach ($arr as $name => $value) {
                $this->ini[$name] = $value;
            }
        }
        else {
            return "Array parametr error.";
        }
    }

    // Close file
    public function Close() {
        $write_data = "";
        if ($this->multi) {
            foreach ($this->ini as $group => $arr) {
                $write_data .= "[" . $group . "]\n";
                foreach ($arr as $key => $value) {
                    if (gettype($value) == "boolean") {
                        if ($value == true) $write_data .= $key . " = true;\n";
                        else $write_data .= $key . " = false;\n";
                    }
                    else $write_data .= $key . " = '" . $value . "';\n";
                }
                $write_data .= "\n";
            }
        }
        else {
            foreach ($this->ini as $key => $value) {
                if (gettype($value) == "boolean") {
                    if ($value == true) $write_data .= $key . " = true;\n";
                    else $write_data .= $key . " = false;\n";
                }
                else $write_data .= $key . " = '" . $value . "';\n";
            }
        }

        $this->file->Write($write_data);
        $this->file->Close();
    }

    public function Reset() {
        $this->Open($this->filePath, $this->rewrite);
    }
}