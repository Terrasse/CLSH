<?php
session_start();
require_once ('Controleur.php');
$controle = new Controleur();
$controle -> analyseURL();
?>
