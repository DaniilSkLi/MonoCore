<?php

require_once __DIR__ . "/core.php";

MONO_Hooks::add_action("head", function() { echo 0; });
MONO_Hooks::add_action("head", function() { echo 1; });
MONO_Hooks::add_action("head", function() { echo 2; });

MONO_Hooks::do_action("head");