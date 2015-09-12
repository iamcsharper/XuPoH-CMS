<?php
require "engine/Core/Core.php";
$core = new Core(new EngineSettings());

header('Content-Type: text/html; charset=utf-8');

try {
	$core->start();
} catch(Exception $e) {
	Debugger::printException($e);
}