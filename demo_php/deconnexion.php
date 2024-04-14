<?php
session_start();
session_destroy();
header('Location:/demo_php/index.php');
exit;

?>