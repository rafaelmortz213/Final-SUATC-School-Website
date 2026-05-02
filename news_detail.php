<?php
// Include database connection
$db_path = __DIR__ . '/config/db.php';
if (!file_exists($db_path)) {
    die("ERROR: config/db.php not found.");
}
require_once $db_path;

// Verify connection
if (!isset($conn) || !$conn) {
    die("ERROR: Database connection failed.");
}

// Get news ID from URL
$news_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($news_id == 0) {
    header('Location: news.php');
    exit;
}

// Fetch news details
$query = "SELECT * FROM news WHERE id = $news_id LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: news.php');
    exit;
}

$news = mysqli_fetch_assoc($result);

// Fetch related news (same category, exclude current)
$related_query = "SELECT * FROM news WHERE category = '{$news['category']}' AND id != $news_id ORDER BY date_posted DESC LIMIT 3";
$related_result = mysqli_query($conn, $related_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?> - SUATC News</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .news-detail-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .news-detail-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        
        .news-detail-image {
            width: 100%;
            height: 400px;
            overflow: hidden;
            background: #f5f5f5;
        }
        
        .news-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .news-detail-content {
            padding: 40px;
        }
        
        .news-detail-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .news-detail-category {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .category-announcements { background: #e3f2fd; color: #1976d2; }
        .category-events { background: #f3e5f5; color: #7b1fa2; }
        .category-achievements { background: #fff3e0; color: #f57c00; }
        .category-programs { background: #e8f5e9; color: #2e7d32; }
        
        .news-detail-date {
            color: #666;
            font-size: 14px;
        }
        
        .news-detail-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .news-detail-body {
            font-size: 16px;
            line-height: 1.8;
            color: #444;
            margin-top: 30px;
        }
        
        .back-to-news {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #11998e;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 30px;
            transition: gap 0.3s;
        }
        
        .back-to-news:hover {
            gap: 12px;
        }
        
        .related-news {
            margin-top: 60px;
        }
        
        .related-news h3 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #333;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        
        .related-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .related-card:hover {
            transform: translateY(-5px);
        }
        
        .related-card-image {
            width: 100%;
            height: 180px;
            background: #f5f5f5;
            overflow: hidden;
        }
        
        .related-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .related-card-content {
            padding: 20px;
        }
        
        .related-card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .related-card-date {
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- NAVIGATION BAR (Copy from news.php) -->
    <nav class="navbar">
        <div class="container">
            <a href="index.html" class="logo">
                <img src="assets/img/titleheader.png" alt="SUATC" style="height: 80px; vertical-align: middle; margin-left: -40px;">
            </a>
            <ul class="nav-menu">
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="news.php" class="nav-link">News</a></li>
                <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </nav>

    <div class="news-detail-container">
        <a href="news.php" class="back-to-news">
            <i class="fas fa-arrow-left"></i> Back to News
        </a>
        
        <div class="news-detail-card">
            <?php if (!empty($news['image']) && file_exists('uploads/' . $news['image'])): ?>
                <div class="news-detail-image">
                    <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                </div>
            <?php endif; ?>
            
            <div class="news-detail-content">
                <div class="news-detail-meta">
                    <span class="news-detail-category category-<?php echo $news['category']; ?>">
                        <?php echo ucfirst($news['category']); ?>
                    </span>
                    <span class="news-detail-date">
                        <i class="fas fa-calendar"></i> <?php echo date('F d, Y', strtotime($news['date_posted'])); ?>
                    </span>
                </div>
                
                <h1 class="news-detail-title"><?php echo htmlspecialchars($news['title']); ?></h1>
                
                <div class="news-detail-body">
                    <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                </div>
            </div>
        </div>
        
        <?php if (mysqli_num_rows($related_result) > 0): ?>
        <div class="related-news">
            <h3>Related News</h3>
            <div class="related-grid">
                <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                <a href="news_detail.php?id=<?php echo $related['id']; ?>" class="related-card">
                    <div class="related-card-image">
                        <?php if (!empty($related['image']) && file_exists('uploads/' . $related['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($related['image']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                        <?php endif; ?>
                    </div>
                    <div class="related-card-content">
                        <h4 class="related-card-title"><?php echo htmlspecialchars($related['title']); ?></h4>
                        <p class="related-card-date">
                            <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($related['date_posted'])); ?>
                        </p>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 St. Uriel Academy of Taguig City, Inc. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>