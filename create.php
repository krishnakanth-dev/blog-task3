<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "INSERT INTO posts(title, content)
            VALUES('$title', '$content')";

    if ($conn->query($sql) === TRUE) {
        $message = "Post Created Successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Post</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(135deg,#0f172a,#1e293b,#334155);
            color: white;
        }

        .container {
            width: 50%;
            margin: 60px auto;
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            outline: none;
        }

        input:focus, textarea:focus {
            box-shadow: 0 0 10px #3b82f6;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #22c55e;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: #16a34a;
            transform: scale(1.03);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #93c5fd;
            text-decoration: none;
        }

        .msg {
            text-align: center;
            margin-bottom: 10px;
            color: #fbbf24;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Create New Post</h2>

    <p class="msg"><?php echo $message; ?></p>

    <form method="POST">

        <label>Title</label>
        <input type="text" name="title" required>

        <label>Content</label>
        <textarea name="content" rows="6" required></textarea>

        <button type="submit">Create Post</button>

    </form>

    <a class="back" href="index.php">← Back to Dashboard</a>

</div>

</body>
</html>