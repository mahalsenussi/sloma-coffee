<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db_connect.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that username.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLOMA COFFEE - Login - تسجيل الدخول</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --sloma-green: #2E7D32;
            --sloma-green-dark: #1B5E20;
            --sloma-green-light: #E8F5E9;
        }
        
        body {
            background-color: var(--sloma-green-light) !important;
            font-family: "Segoe UI", "Cairo", "Helvetica", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: var(--sloma-green) !important;
            border-color: var(--sloma-green) !important;
        }
        
        .btn-primary:hover {
            background-color: var(--sloma-green-dark) !important;
            border-color: var(--sloma-green-dark) !important;
        }
        
        .header-hero {
            background: linear-gradient(
                rgba(46, 125, 50, 0.7),
                rgba(46, 125, 50, 0.7)
            ),
            url('img/web3.jpg') center/cover no-repeat;
        }
        
        .login-card {
            background: white;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
            max-width: 400px;
            width: 90%;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .login-logo img {
            height: 80px;
            border-radius: 50%;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #E0E0E0;
        }
        
        .form-control:focus {
            border-color: var(--sloma-green);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }
    </style>
</head>
<body>
    <div class="header-hero w-100" style="height: 120px; display: flex; flex-direction: column; align-items: center; justify-content: center; position: absolute; top: 0;">
        <img src="img/sloma.jpg" alt="SLOMA COFFEE" style="height: 60px; border-radius: 50%;">
        <h4 class="text-white" style="font-weight: bold;">SLOMA COFFEE</h4>
    </div>

    <div class="login-card" style="position: relative; z-index: 1; margin-top: 140px;">
        <div class="login-logo">
            <p style="color: var(--sloma-green); font-weight: bold; margin-bottom: 5px;">Login - تسجيل الدخول</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username - اسم المستخدم</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password">Password - كلمة المرور</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login - دخول</button>
        </form>
        
        <div style="text-align: center; margin-top: 15px;">
            <a href="index.php" style="color: var(--sloma-green);">Back to Home - العودة للرئيسية</a>
        </div>
    </div>
</body>
</html>