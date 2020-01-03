<?php
session_start();
$_SESSION = array();
session_destroy();
setcookie('id');
unset($_COOKIE['id']);
setcookie('pseudo');
unset($_COOKIE['pseudo']);

/*
setcookie('id', NULL, -1);
setcookie('pseudo', NULL, -1);
*/
header("Location: index.php")



?>