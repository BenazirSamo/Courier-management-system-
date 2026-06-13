<?php
session_start();
include '../config/db.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {

    // Trim inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = (int)$_POST['role'];

    // ===== VALIDATION =====

    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    }
    elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $error = "Username can only contain letters, numbers, and underscores.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    }
    elseif (!preg_match("/[A-Z]/", $password)) {
        $error = "Password must contain at least one uppercase letter.";
    }
    elseif (!preg_match("/[a-z]/", $password)) {
        $error = "Password must contain at least one lowercase letter.";
    }
    elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password must contain at least one number.";
    }
    elseif (!in_array($role, [2,3])) {
        $error = "Invalid role selected.";
    }

    else {

        // ===== CHECK EXISTING USER (SECURE) =====
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or Email already exists!";
        } else {

            // ⚠️ Plain password (NOT SECURE)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $email, $password, $role);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Courier System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ===== Same CSS as login page ===== */
body {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.login-card {
    width: 100%;
    max-width: 450px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

.card-body {
    padding: 30px 25px;
}

.form-control {
    border-radius: 8px;
    background: #fff;
    color: #000;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ddd;
}

.form-label {
    font-weight: 500;
}

.btn-theme {
    background: #ff7a00;
    color: #fff;
    font-weight: 600;
    border-radius: 30px;
    padding: 10px;
    transition: 0.3s;
    border: none;
}

.btn-theme:hover {
    background: #e56e00;
}

.alert {
    font-size: 14px;
    padding: 10px 15px;
    border-radius: 8px;
}

.back-home {
    text-align: center;
    margin-top: 15px;
}

.back-home a {
    color: #555;
    text-decoration: none;
    font-size: 14px;
}

.back-home a:hover {
    text-decoration: underline;
}

@media (max-width: 576px) {
    .login-card {
        margin: 15px;
    }
}
</style>
</head>
<body>

<div class="login-card">
    <!-- Compact Logo Header -->
    <div style="background: #141414; color: white; padding: 20px;">
        <div class="d-flex align-items-center justify-content-center" style="gap: 15px;">
            <img src="../assets/images/new logo.png" alt="Logo" style="height: 50px;">
            <div class="text-start">
                <h4 style="margin: 0; font-size: 1.4rem;">Courier Management System</h4>
                <small style="opacity: 0.8;">Create New Account</small>
            </div>
        </div>
    </div> <!-- Header div close here -->

    <div class="card-body"> <!-- Card-body should be separate from header -->
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if(!empty($success)): ?>
            <div class="alert alert-success text-center"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="">Select Role</option>
                    <option value="2">User</option>
                    <option value="3">Agent</option>
                </select>
            </div>

            <button type="submit" name="register" class="btn btn-theme w-100 mt-2">Register</button>
        </form>

        <div class="back-home mt-3">
            <a href="login.php">← Already have an account? Login here</a>
        </div>
    </div>
</div>

</body>
</html>