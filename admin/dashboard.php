<?php
session_start();

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection with absolute path
$db_path = dirname(__DIR__) . '/config/db.php';
if (!file_exists($db_path)) {
    die("ERROR: config/db.php not found at: " . $db_path);
}
require_once $db_path;

// Verify connection
if (!isset($conn) || !$conn) {
    die("ERROR: Database connection not established. Check config/db.php");
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image name before deleting
    $img_query = "SELECT image FROM news WHERE id = $id";
    $img_result = mysqli_query($conn, $img_query);
    $img_data = mysqli_fetch_assoc($img_result);
    
    // Delete from database
    $delete_query = "DELETE FROM news WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        // Delete image file if exists
        if (!empty($img_data['image']) && file_exists('../uploads/' . $img_data['image'])) {
            unlink('../uploads/' . $img_data['image']);
        }
        $success = 'News deleted successfully!';
    } else {
        $error = 'Error deleting news';
    }
}

// Fetch all news
$query = "SELECT * FROM news ORDER BY date_posted DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0fdf4;
        }
        
        .dashboard-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 20px 40px;
        box-shadow: 0 4px 20px rgba(17, 153, 142, 0.2);
        }

        .header-content {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
        }

        .header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .school-logo-header {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        padding: 5px;
        box-shadow: 0 3px 15px rgba(0,0,0,0.2);
    }

    .school-logo-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
        
        .dashboard-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

        .dashboard-header h1 i {
            margin-right: 12px;
        }

        .welcome-text {
        font-size: 13px;
        opacity: 0.95;
        margin-top: 5px;
    }

        .welcome-text i {
            margin-right: 5px;
        }
        
        .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

        .header-title {
        flex: 1;
    }
        
        .btn {
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        white-space: nowrap;
    }
        
        .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
        
        .btn-primary {
        background: white;
        color: #11998e;
    }

        .btn-primary:hover {
        background: #f0fdf4;
    }

        .btn-secondary {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1.5px solid rgba(255,255,255,0.3);
        }

        .btn-secondary:hover {
        background: rgba(255,255,255,0.25);
    }
        
        .btn-danger {
        background: #ef4444;
        color: white;
    }

        .btn-danger:hover {
        background: #dc2626;
    }
        
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #38ef7d;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.15);
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 13px;
            margin-bottom: 15px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-card h3 i {
            color: #11998e;
            font-size: 18px;
        }
        
        .stat-card .stat-number {
            font-size: 42px;
            font-weight: 700;
            color: #11998e;
            line-height: 1;
        }
        
        .news-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .table-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-bottom: 2px solid #38ef7d;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h2 {
            color: #11998e;
            font-size: 22px;
            font-weight: 700;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f9fafb;
        }
        
        th {
            padding: 16px 20px;
            text-align: left;
            color: #11998e;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 18px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover {
            background: #f0fdf4;
        }

        tr:last-child td {
            border-bottom: none;
        }
        
        .news-title {
            font-weight: 600;
            color: #333;
            max-width: 350px;
        }
        
        .news-category {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .category-announcements { background: #dbeafe; color: #1e40af; }
        .category-events { background: #fae8ff; color: #86198f; }
        .category-achievements { background: #fed7aa; color: #c2410c; }
        .category-programs { background: #d1fae5; color: #065f46; }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        
        .btn-small:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }
        
        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fecaca;
        }
        
        .alert {
            padding: 16px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .alert i {
            font-size: 18px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .no-news {
            text-align: center;
            padding: 80px 40px;
            color: #999;
        }

        .no-news i {
            font-size: 80px;
            color: #d1fae5;
            margin-bottom: 25px;
        }

        .no-news p {
            font-size: 16px;
            margin-bottom: 25px;
            color: #666;
        }

        @media (max-width: 992px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

            .header-actions {
                width: 100%;
                flex-wrap: wrap;
            }

            .btn {
                flex: 1;
                justify-content: center;
                min-width: 140px;
            }

            .table-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px 10px;
            }
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <div class="school-logo-header">
                <img src="../assets/img/logo.png" alt="SUATC Logo">
            </div>
            <div class="header-title">
                <h1>Admin Dashboard</h1>
                <div class="welcome-text">
                    <i class="fas fa-user-circle"></i> Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="../news.php" class="btn btn-secondary" target="_blank">
                <i class="fas fa-eye"></i> View Site
            </a>
            <a href="add_news.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add News
            </a>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</header>
    
    <div class="container">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total News</h3>
                <div class="stat-number"><?php echo mysqli_num_rows($result); ?></div>
            </div>
            <div class="stat-card">
                <h3>Published Today</h3>
                <div class="stat-number">
                    <?php 
                    $today_query = "SELECT COUNT(*) as count FROM news WHERE DATE(date_posted) = CURDATE()";
                    $today_result = mysqli_query($conn, $today_query);
                    $today_data = mysqli_fetch_assoc($today_result);
                    echo $today_data['count'];
                    ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>This Month</h3>
                <div class="stat-number">
                    <?php 
                    $month_query = "SELECT COUNT(*) as count FROM news WHERE MONTH(date_posted) = MONTH(CURDATE())";
                    $month_result = mysqli_query($conn, $month_query);
                    $month_data = mysqli_fetch_assoc($month_result);
                    echo $month_data['count'];
                    ?>
                </div>
            </div>
        </div>
        
        <div class="news-table">
            <div class="table-header">
                <h2>All News Articles</h2>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($news = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $news['id']; ?></td>
                            <td class="news-title"><?php echo htmlspecialchars($news['title']); ?></td>
                            <td>
                                <span class="news-category category-<?php echo $news['category']; ?>">
                                    <?php echo ucfirst($news['category']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($news['date_posted'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?delete=<?php echo $news['id']; ?>" 
                                       class="btn-small btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this news?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-news">
                    <i class="fas fa-newspaper" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                    <p>No news articles found. Click "Add News" to create your first article.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>