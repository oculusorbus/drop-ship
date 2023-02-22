<?php
session_start();
$status = $_GET["status"];
$_SESSION['userData']['3d'] = $status;
?>