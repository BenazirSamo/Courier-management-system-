<?php
include("../includes/agent-auth.php");
include("../config/db.php");

// Admin check
if ($_SESSION['role'] != 1) header("Location: ../public/login.php");

// Get customer ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id>0){
    mysqli_query($conn,"DELETE FROM customers WHERE id=$id");
}

header("Location: customers.php");
exit();
?>
