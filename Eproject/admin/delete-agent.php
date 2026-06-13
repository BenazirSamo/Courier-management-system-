<?php
include("../includes/agent-auth.php");
include("../config/db.php");

// Only admin
if ($_SESSION['role'] != 1) {
    header("Location: ../public/login.php");
    exit();
}

// Get agent ID from URL
$agent_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($agent_id > 0){
    mysqli_query($conn, "DELETE FROM users WHERE id = (
        SELECT user_id FROM agents WHERE id = $agent_id
    )");

    // Redirect back to manage agents
    header("Location: manage-agent.php");
    exit();
} else {
    die("Invalid Agent ID");
}
?>
