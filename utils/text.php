<?php

class MONO_Text {
    public static function Safe($str) {
        return addslashes(nl2br($str));
    }
}