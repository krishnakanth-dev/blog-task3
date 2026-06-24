<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$search = "";

if (isset($_GET['search'])) {

    $search = $_GET['search'];

    $sql = "SELECT * FROM posts
            WHERE title LIKE '%$search%'
            OR content LIKE '%$search%'
            ORDER BY created_at DESC";
} else {

    $sql = "SELECT * FROM posts
            ORDER BY created_at DESC";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b, #334155);
            margin: 0;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            width: 85%;
            margin: 30px auto;
        }

        .btn {
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
        }

        .create-btn {
            background: #16a34a;
        }

        .edit-btn {
            background: #2563eb;
        }

        .delete-btn {
            background: #dc2626;
        }

        .logout-btn {
            background: #6b7280;
        }

        .post-card {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            padding: 25px;
            margin-top: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            transition: 0.3s;
        }

        .post-card:hover {
            transform: translateY(-6px);
        }

        .search-box {
            margin-top: 20px;
        }

        .search-box input {
            padding: 12px;
            width: 320px;
            border: none;
            border-radius: 10px;
            outline: none;
        }

        .search-box input:focus {
            box-shadow: 0 0 15px #3b82f6;
        }

        .search-box button {
            padding: 12px 18px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .search-box button:hover {
            background: #1d4ed8;
        }

        small {
            color: gray;
        }

        h1 {
            color: white;
            margin-bottom: 5px;
        }

        h2 {
            color: white;
        }

        h3 {
            color: #60a5fa;
        }

        small {
            color: #cbd5e1;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h2>Blog Management System</h2>

        <div>
            Welcome,
            <?php echo $_SESSION['username']; ?>

            <a class="btn logout-btn"
                href="logout.php"
                onclick="return confirm('Are you sure you want to logout?')">
                Logout
            </a>
        </div>
    </div>

    <div class="container">

        <a class="btn create-btn"
            href="create.php">
            + Create Post
        </a>

        <div class="search-box">
            <form method="GET">

                <input
                    type="text"
                    name="search"
                    placeholder="Search posts"
                    value="<?php echo $search; ?>">

                <button type="submit">
                    Search
                </button>

            </form>
        </div>
        <div style="color:white; margin-top:30px;">
            <h1>👋 Welcome Back, <?php echo $_SESSION['username']; ?></h1>
            <p>Manage and organize your blog posts efficiently.</p>
        </div>

        <h2>📚 All Posts</h2>

        <p style="color:#cbd5e1;">
            Total Posts: <?php echo $result->num_rows; ?>
        </p>


        <?php while ($row = $result->fetch_assoc()) { ?>

            <div class="post-card">

                <h3>
                    <?php echo $row['title']; ?>
                </h3>

                <p>
                    <?php echo $row['content']; ?>
                </p>

                <a class="btn edit-btn"
                    href="edit.php?id=<?php echo $row['id']; ?>">
                    Edit
                </a>

                <a class="btn delete-btn"
                    href="delete.php?id=<?php echo $row['id']; ?>"
                    onclick="return confirm('Are you sure?')">
                    Delete
                </a>

                <br><br>

                <small>
                    Posted on:
                    <?php echo $row['created_at']; ?>
                </small>

            </div>

        <?php } ?>

    </div>

</body>

</html>