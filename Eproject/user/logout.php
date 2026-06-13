<?php
session_start();

// saari session values clear
session_unset();

// session destroy
session_destroy();

// login page par bhejo
header("Location: ../public/login.php");
exit();