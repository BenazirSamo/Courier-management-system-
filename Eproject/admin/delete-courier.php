<?php
include("../includes/agent-auth.php");
include("../config/db.php");

if(!in_array($_SESSION['role'], [1,3])){
    header("Location: ../public/login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id>0){
    mysqli_query($conn,"DELETE FROM couriers WHERE id=$id");
}

header("Location: manage-courier.php");
exit();
?>
