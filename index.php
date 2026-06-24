<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$search = "";
$posts_per_page = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) { $page = 1; }

$offset = ($page - 1) * $posts_per_page;

if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search = $_GET['search'];
    $like = '%' . $search . '%';

    $sql = "SELECT * FROM posts
            WHERE title LIKE ? OR content LIKE ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $like, $like, $posts_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $count_sql = "SELECT COUNT(*) AS total FROM posts WHERE title LIKE ? OR content LIKE ?";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("ss", $like, $like);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();

} else {

    $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $posts_per_page, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $count_sql = "SELECT COUNT(*) AS total FROM posts";
    $count_result = $conn->query($count_sql);
}

$total_posts = $count_result->fetch_assoc()['total'];
$total_pages = max(1, ceil($total_posts / $posts_per_page));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: #050816;
            color: white;
        }

        .container {
            width: 95%;
            max-width: 1300px;
            margin: auto;
        }

        /* ---------- Navbar ---------- */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px;
            margin-top: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.03);
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            font-size: 34px;
        }

        .logo h1 {
            font-size: 32px;
            background: linear-gradient(90deg, #8b5cf6, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo p {
            color: #cbd5e1;
            margin-top: 4px;
            font-size: 14px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            color: #e2e8f0;
        }

        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 12px 22px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s ease;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* ---------- Top area ---------- */
        .top-area {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            margin-top: 30px;
            gap: 25px;
            flex-wrap: wrap;
        }

        .create-btn {
            background: linear-gradient(135deg, #8b5cf6, #2563eb);
            color: white;
            padding: 15px 25px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            align-self: flex-start;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.35);
        }

        .welcome-card {
            flex: 1;
            min-width: 320px;
            background: linear-gradient(135deg, #172554, #4c1d95);
            padding: 35px;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .welcome-card h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #cbd5e1;
            font-size: 15px;
        }

        /* ---------- Search ---------- */
        .search-box {
            margin-top: 25px;
        }

        .search-box form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .input-wrap {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .input-wrap .icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-box input {
            width: 100%;
            padding: 15px 15px 15px 44px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            background: #1e293b;
            color: white;
            font-size: 15px;
        }

        .search-box input::placeholder {
            color: #94a3b8;
        }

        .search-box button {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border: none;
            color: white;
            padding: 15px 28px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.15s ease;
        }

        .search-box button:hover {
            opacity: 0.9;
        }

        /* ---------- Section title ---------- */
        .section-title {
            margin-top: 40px;
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .section-title h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
        }

        .total-posts {
            display: inline-block;
            background: #312e81;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 14px;
        }

        /* ---------- Post card ---------- */
        .post-card {
            background: linear-gradient(90deg, #0f172a, #172554, #0f172a);
            margin-top: 20px;
            padding: 22px 25px;
            border-radius: 18px;
            border-left: 4px solid #8b5cf6;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .post-icon {
            min-width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .post-body {
            flex: 1;
            min-width: 0;
        }

        .post-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            flex-wrap: wrap;
        }

        .post-text h3 {
            margin-bottom: 8px;
            color: white;
            font-size: 18px;
        }

        .post-text p {
            color: #cbd5e1;
            line-height: 1.6;
            font-size: 14.5px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .post-card small {
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .edit-btn,
        .delete-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            transition: opacity 0.15s ease;
        }

        .edit-btn {
            background: #2563eb;
            color: white;
        }

        .delete-btn {
            background: #ef4444;
            color: white;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            opacity: 0.88;
        }

        .no-posts {
            margin-top: 30px;
            text-align: center;
            color: #94a3b8;
            padding: 40px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
            border-radius: 16px;
        }

        /* ---------- Pagination ---------- */
        .pagination {
            text-align: center;
            margin: 40px 0;
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pagination a {
            display: inline-block;
            min-width: 44px;
            padding: 12px 16px;
            text-decoration: none;
            border-radius: 10px;
            background: #1e293b;
            color: white;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: background 0.15s ease;
        }

        .pagination a:hover {
            background: #334155;
        }

        .pagination a.active {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            border-color: transparent;
        }

        footer {
            text-align: center;
            color: #94a3b8;
            padding: 20px;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .welcome-card h1 { font-size: 26px; }
            .top-area { flex-direction: column; }
            .post-top { flex-direction: column; }
            .actions { width: 100%; }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="navbar">

            <div class="logo">
                <span class="logo-icon">🪶</span>
                <div>
                    <h1>Blog Management System</h1>
                    <p>Create. Manage. Inspire. ✨</p>
                </div>
            </div>

            <div class="nav-right">
                <div class="user-chip">
                    <span class="avatar-circle">👤</span>
                    Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </div>

                <a href="logout.php"
                    class="logout-btn"
                    onclick="return confirm('Logout?')">
                    ⏻ Logout
                </a>
            </div>

        </div>

        <div class="top-area">

            <a href="create.php" class="create-btn">
                + Create Post
            </a>

            <div class="welcome-card">
                <h1>👋 Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Manage and organize your blog posts efficiently.</p>
            </div>

        </div>

        <div class="search-box">

            <form method="GET">

                <div class="input-wrap">
                    <span class="icon">🔍</span>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search posts by title or content..."
                        value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <button type="submit">
                    🔍 Search
                </button>

            </form>

        </div>

        <div class="section-title">
            <h2>📚 All Posts</h2>

            <div class="total-posts">
                Total Posts: <?php echo (int)$total_posts; ?>
            </div>
        </div>

        <?php if ($total_posts > 0): ?>
            <?php while ($row = $result->fetch_assoc()) { ?>

                <div class="post-card">

                    <div class="post-icon">📄</div>

                    <div class="post-body">
                        <div class="post-top">
                            <div class="post-text">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p><?php echo htmlspecialchars($row['content']); ?></p>
                                <small>📅 <?php echo htmlspecialchars($row['created_at']); ?></small>
                            </div>

                            <div class="actions">
                                <a class="edit-btn"
                                    href="edit.php?id=<?php echo (int)$row['id']; ?>">
                                    ✏ Edit
                                </a>

                                <a class="delete-btn"
                                    href="delete.php?id=<?php echo (int)$row['id']; ?>"
                                    onclick="return confirm('Delete this post?')">
                                    🗑 Delete
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            <?php } ?>
        <?php else: ?>
            <div class="no-posts">No posts found.</div>
        <?php endif; ?>

        <div class="pagination">

            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>

                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                    class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>

            <?php } ?>

        </div>

        <footer>
            © 2026 Blog Management System | Developed by Krishna Kanth
        </footer>

    </div>
</body>

</html>