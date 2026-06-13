<?php
session_start();

function protect($role_required)
{
    if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
        header("Location: ../public/login.php");
        exit();
    }

    // role check
    if ((int)$_SESSION['role'] !== (int)$role_required) {

        // redirect according to role
        if ($_SESSION['role'] == 1) {
            header("Location: ../admin/dashboard.php");
        } elseif ($_SESSION['role'] == 2) {
            header("Location: ../user/dashboard.php");
        } elseif ($_SESSION['role'] == 3) {
            header("Location: ../agent/dashboard.php");
        }

        exit();
    }
}
?>