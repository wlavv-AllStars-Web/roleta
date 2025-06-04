<?php
$password = 'Allstarsgroup351';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>