<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
session_start();

$page = (isset($_GET["page"]) == '') ? 'step1' : $_GET["page"];

include "layout/index.php";

?>