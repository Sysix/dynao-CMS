<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
session_start();

$page = ($_GET["page"] == '') ? 'step1' : $_GET["page"];

include "layout/index.php";

?>