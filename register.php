<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {

        $sql = "INSERT INTO users (username, password)
            VALUES ('$username', '$password')";

        $conn->query($sql);

        $message = "Registration Successful!";
    } catch (mysqli_sql_exception $e) {

        $message = "Username already exists. Try another username.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>

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

        .register-box {
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
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: .3s;
        }

        button:hover {
            background: #15803d;
            transform: scale(1.02);
        }

        .msg {
            text-align: center;
            color: #fbbf24;
            margin-top: 15px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #93c5fd;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="register-box">

        <h2>📝 User Registration</h2>

        <form method="POST">

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Register</button>

        </form>

        <p class="msg"><?php echo $message; ?></p>

        <div class="login-link">
            Already have an account?
            <a href="login.php">Login</a>
        </div>

    </div>

</body>

</html>