<?php
session_start();
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['username'] = $username;

            header("Location: index.php");
            exit();
        } else {
            $message = "Wrong Password!";
        }
    } else {
        $message = "User Not Found!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 400px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 10px #3b82f6;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: .3s;
        }

        button:hover {
            background: #1d4ed8;
            transform: scale(1.02);
        }

        .msg {
            text-align: center;
            color: #fbbf24;
            margin-top: 15px;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #93c5fd;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-box">

        <h2>🔐 Login</h2>

        <form method="POST">

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>

        </form>

        <p class="msg"><?php echo $message; ?></p>

        <div class="register-link">
            Don't have an account?
            <a href="register.php">Register</a>
        </div>

    </div>

</body>

</html>