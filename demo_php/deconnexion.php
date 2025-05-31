<?php
session_start();
session_destroy();
header('Location: index.php'); // Remove /demo_php/ path
exit;
?>