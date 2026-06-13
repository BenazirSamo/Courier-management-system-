<?php
session_start();

include '../config/db.php';
$error = "";
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password =$_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = (int)$user['role'];

        if ($_SESSION['role'] === 1) {
            header("Location: ../admin/index.php");
            exit();
        } elseif ($_SESSION['role'] === 2) {
            header("Location: ../user/dashboard.php");
            exit();
        } elseif ($_SESSION['role'] === 3) {
            header("Location: ../agent/dashboard.php");
            exit();
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Courier System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><style>
/* ----- Body ----- */
body {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* ----- Card ----- */
.login-card {
    width: 100%;
    max-width: 420px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

/* ----- Card Header ----- */
.login-header {
    background: #ff7a00;
    color: #fff;
    padding: 20px;
    text-align: center;
}

.login-header h4 {
    margin: 0;
    font-weight: 700;
}

.login-header small {
    font-weight: 400;
}

/* ----- Form ----- */
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

/* ----- Button ----- */
.btn-theme {
    background: #ff7a00;
    color: #fff;
    font-weight: 600;
    border-radius: 30px;
    transition: 0.3s;
    border: none;
}

.btn-theme:hover {
    background: #e56e00;
}

/* ----- Error Message ----- */
.alert {
    font-size: 14px;
    padding: 10px 15px;
    border-radius: 8px;
}

/* ----- Back/Home Link ----- */
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

/* ----- Responsive ----- */
@media (max-width: 576px) {
    .login-card {
        margin: 15px;
    }
}/* ----- Logo with Text Header ----- */
.logo-header {
    background: #ff7a00;
    color: #fff;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    border-bottom: 3px solid #e56e00;
}

.logo-header img {
    height: 45px;
    width: auto;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.logo-header .title {
    text-align: left;
    border-left: 2px solid rgba(255,255,255,0.3);
    padding-left: 15px;
}

.logo-header h4 {
    margin: 0;
    font-weight: 700;
    font-size: 1.5rem;
}

.logo-header small {
    font-weight: 400;
    opacity: 0.9;
    font-size: 0.9rem;
}
</style>

<body class="bg-light">
<div class="login-card">
    <div class="text-center py-4" style="background: #141414; color: white;">
        <div class="d-inline-flex align-items-center gap-3">
            <img src="../assets/images/new logo.png" alt="Logo" style="height: 50px;">
            <div class="text-start">
                <h4 class="mb-1" style="font-size: 1.4rem;">Courier Management System</h4>
            </div>
        </div>
    </div>
    <div class="card-body">
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger text-center">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-theme w-100 mt-2">Login</button>
        </form>
    </div>
</div>
</body>
</html>