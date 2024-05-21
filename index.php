<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'src/classes/Main.php';

$main = new Main();
$main->run();

