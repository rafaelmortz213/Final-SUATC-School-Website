<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$db_path = dirname(__DIR__) . '/config/db.php';
if (!file_exists($db_path)) {
    die("ERROR: config/db.php not found");
}
require_once $db_path;

if (!isset($conn) || !$conn) {
    die("ERROR: Database connection failed");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM admin_users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SUATC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Background with school image */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(17, 153, 142, 0.95) 0%, rgba(56, 239, 125, 0.9) 100%),
                        url('../assets/img/school-bg.jpg') center/cover;
            z-index: -2;
        }
        
        /* Overlay pattern */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.03) 35px, rgba(255,255,255,.03) 70px);
            z-index: -1;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 45px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .school-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
            border: 3px solid #11998e;
        }
        
        .school-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .login-header h1 {
            color: #11998e;
            margin-bottom: 8px;
            font-size: 28px;
            font-weight: 700;
        }
        
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        
        .form-group label i {
            margin-right: 8px;
            color: #11998e;
            width: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #11998e;
            box-shadow: 0 0 0 4px rgba(17, 153, 142, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }
        
        .error-message {
            background: #fef2f2;
            color: #991b1b;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
            border-left: 4px solid #ef4444;
            font-size: 14px;
            animation: shake 0.4s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .back-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }
        
        .back-link a {
            color: #11998e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .back-link a:hover {
            gap: 12px;
            color: #0d7a6f;
        }

        .credentials-info {
            background: #f0fdf4;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 13px;
            border-left: 4px solid #38ef7d;
            line-height: 1.6;
        }

        .credentials-info strong {
            color: #11998e;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .credentials-info code {
            background: #dcfce7;
            padding: 3px 8px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            color: #0d7a6f;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="school-logo">
                <img src="../assets/img/logo.png" alt="SUATC Logo">
            </div>
            <h1>Admin Login</h1>
            <p>St. Uriel Academy of Taguig City</p>
        </div>

        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" id="username" name="username" required autofocus placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login to Dashboard
            </button>
        </form>
        
        <div class="back-link">
            <a href="../news.php">
                <i class="fas fa-arrow-left"></i> Back to Website
            </a>
        </div>
    </div>
</body>
</html>