<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = $conn->query(
    "SELECT * FROM posts WHERE id=$id"
);

$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE posts
            SET title='$title',
                content='$content'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {

        header("Location: index.php");
        exit();

    } else {

        echo "Error: " . $conn->error;

    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Post</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
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
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            outline: none;
            box-sizing: border-box;
        }

        input:focus,
        textarea:focus {
            box-shadow: 0 0 12px #3b82f6;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: #1d4ed8;
            transform: scale(1.03);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #93c5fd;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Edit Post</h2>

    <form method="POST">

        <label>Title</label>

        <input
            type="text"
            name="title"
            value="<?php echo $post['title']; ?>"
            required>

        <label>Content</label>

        <textarea
            name="content"
            rows="6"
            required><?php echo $post['content']; ?></textarea>

        <button type="submit">
            Update Post
        </button>

    </form>

    <a class="back" href="index.php">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>