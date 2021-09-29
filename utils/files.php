<?php



class MONO_File {
    private $path;
    private $file;
    private $write;
    private $mode = "a+";

    public function __construct($file = false, $rewrite = false, $wrChmod = false)
    {
        if ($file) $this->Open($file, $rewrite, $wrChmod);
    }

    // Open
    public function Open($file, $rewrite = false, $wrChmod = false) {
        // Set open mode
        if ($rewrite) $this->mode = "w+";

        $this->path = $file;


        // Check - files is writable.
        if (file_exists($this->path) == is_writable($this->path))
            $this->file = fopen($this->path, $this->mode);

        else {

            // set writable.
            if ($wrChmod) {
                $this->Chmod(0775);
                $this->file = fopen($this->path, $this->mode);
            }
            else {
                $this->mode = "r";
                $this->file = fopen($this->path, $this->mode);
            }

        }
    }

    public function Read() {
        fseek($this->file, 0);
        return stream_get_contents($this->file);
    }

    public function Write($str) {
        if ($this->mode != "r")
            $this->write .= $str;

        else
            MONO_Error::CoreError("File is not writable. Only read.");
    }

    // Reset write changes
    public function Reset() {
        $this->write = "";
    }

    public function Close() {
        if ($this->mode != "r")
            fwrite($this->file, $this->write);

        fclose($this->file);
    }

    public function Chmod($code) {
        if (is_int($code)) {
            $code = (string)$code;
            $len = strlen($code);
            if ($len < 4) {
                for ($i = 0; $i < (4 - $len); $i++) {
                    $code = "0" . $code;
                }
            }



            try {
                chmod($this->path, $code);
            }
            catch(Exception $e) {
                MONO_Error::CoreError("Error in chmod function.", array("Check chmod code."));
            }
        }
    }
}