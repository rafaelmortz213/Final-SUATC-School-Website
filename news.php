<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
$db_path = __DIR__ . '/config/db.php';
if (!file_exists($db_path)) {
    die("ERROR: config/db.php not found. Please check your file structure.");
}
require_once $db_path;

// Verify connection
if (!isset($conn) || !$conn) {
    die("ERROR: Database connection failed. Check config/db.php for errors.");
}

// Fetch all news for the main grid
$news_query = "SELECT * FROM news ORDER BY date_posted DESC";
$news_result = mysqli_query($conn, $news_query);

if (!$news_result) {
    die('Query Error: ' . mysqli_error($conn));
}

// Fetch featured news (latest 3 for slider)
$featured_query = "SELECT * FROM news ORDER BY date_posted DESC LIMIT 3";
$featured_result = mysqli_query($conn, $featured_query);
$featured_news = [];
while ($row = mysqli_fetch_assoc($featured_result)) {
    $featured_news[] = $row;
}

// Fetch recent news for sidebar (latest 4)
$recent_query = "SELECT * FROM news ORDER BY date_posted DESC LIMIT 4";
$recent_result = mysqli_query($conn, $recent_query);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School News and Announcements</title>
    <link rel="stylesheet" href="assets/css/nslider.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    
    <!-- NAVIGATION BAR -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="logo">
                <img src="assets/img/titleheader.png" alt="St. Uriel Academy of Taguig City, Inc. Header" style="height: 80px; vertical-align: middle; margin-left: -40px;">
            </a>

            <div class="menu-toggle" id="mobile-menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>

            <ul class="nav-menu">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                
                <li class="nav-item dropdown">
                    <a href="about.html" class="nav-link">About <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="about.html#history">History</a></li>
                        <li><a href="about.html#mission">Mission and Vision</a></li>
                        <li><a href="about.html#hymn">School Hymn</a></li>
                        <li><a href="about.html#faculty">Faculty</a></li>
                        <li><a href="about.html#organizations">Student Organizations</a></li>
                        <li><a href="about.html#team">Team Members</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="admission.html" class="nav-link">Admission <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="admission.html#requirements">Admission Requirements</a></li>
                        <li><a href="admission.html#enrollment">Enrollment Process</a></li>
                        <li><a href="admission.html#tuition">Tuition and Fees</a></li>
                        <li><a href="admission.html#scholarships">Scholarships</a></li>
                        <li><a href="admission.html#faqs">FAQs</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a href="academics.html" class="nav-link">Academics <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="academics.html#senior-high">Senior High School Tracks</a></li>
                        <li><a href="academics.html#junior-high">Junior High School</a></li>
                        <li><a href="academics.html#elementary">Elementary</a></li>
                        <li><a href="academics.html#nursery">Nursery or Kindergarten</a></li>
                        <li><a href="academics.html#calendar">Academic Calendar</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a href="news.php" class="nav-link">News</a></li>
                <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </nav>


    <!-- ========================================
       PAGE HEADER
       ======================================== -->
    <section class="news-header">
        <div class="container">
            <h1 class="page-title">School News and Announcements</h1>
            <p class="page-subtitle">
                Stay updated with the latest announcements, school events, student achievements, 
                and important updates from our academic community.
            </p>
        </div>
    </section>

    <!-- ========================================
       FEATURED NEWS SLIDER
       ======================================== -->
    <section class="featured-slider">
        <div class="container">
            <div class="slider-wrapper">
                <div class="slider-container">
                    <?php 
                    $slide_count = 0;
                    foreach ($featured_news as $featured): 
                        $slide_count++;
                        $active_class = ($slide_count == 1) ? 'active' : '';
                        
                        // Determine icon based on category
                        $icon = 'fa-bullhorn';
                        $icon_label = 'News';
                        switch(strtolower($featured['category'])) {
                            case 'announcements':
                                $icon = 'fa-bullhorn';
                                $icon_label = 'Announcement';
                                break;
                            case 'achievements':
                                $icon = 'fa-trophy';
                                $icon_label = 'Achievement';
                                break;
                            case 'events':
                                $icon = 'fa-users';
                                $icon_label = 'Event';
                                break;
                            case 'programs':
                                $icon = 'fa-laptop-code';
                                $icon_label = 'Program';
                                break;
                        }
                    ?>
                    <!-- Slide <?php echo $slide_count; ?> -->
                    <div class="slide <?php echo $active_class; ?>">
                        <div class="slide-image">
                            <?php if (!empty($featured['image']) && file_exists('uploads/' . $featured['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($featured['image']); ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                    <span><?php echo $icon_label; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="slide-content">
                            <span class="slide-category"><?php echo ucfirst(htmlspecialchars($featured['category'])); ?></span>
                            <h2 class="slide-title"><?php echo htmlspecialchars($featured['title']); ?></h2>
                            <p class="slide-summary">
                                <?php echo htmlspecialchars($featured['summary']); ?>
                            </p>
                            <div class="slide-meta">
                                <span class="slide-date">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('F d, Y', strtotime($featured['date_posted'])); ?>
                                </span>
                            </div>
                            <a href="news_detail.php?id=<?php echo $featured['id']; ?>" class="btn-slide">Read More</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>


            </div>
        </div>
    </section>

    <!-- ========================================
       NEWS FILTER AND SEARCH
       ======================================== -->
    <section class="news-filter-section">
        <div class="container">
            <div class="filter-container">
                <!-- Category Filters -->
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All News</button>
                    <button class="filter-btn" data-filter="announcements">Announcements</button>
                    <button class="filter-btn" data-filter="events">Events</button>
                    <button class="filter-btn" data-filter="achievements">Student Achievements</button>
                    <button class="filter-btn" data-filter="programs">School Programs</button>
                </div>

                <!-- Search Bar -->
                <div class="search-container">
                    <input type="text" id="searchNews" placeholder="Search news articles...">
                    <button class="search-btn"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================
       LATEST NEWS GRID
       ======================================== -->
    <section class="news-grid-section">
        <div class="container">
            <div class="news-layout">
                <!-- Main News Grid -->
                <div class="news-grid">
                    <?php 
                    // Reset result pointer
                    mysqli_data_seek($news_result, 0);
                    
                    while ($news = mysqli_fetch_assoc($news_result)): 
                        // Determine icon based on category
                        $icon = 'fa-bullhorn';
                        $icon_label = 'News';
                        switch(strtolower($news['category'])) {
                            case 'announcements':
                                $icon = 'fa-bullhorn';
                                $icon_label = 'Announcement';
                                break;
                            case 'achievements':
                                $icon = 'fa-medal';
                                $icon_label = 'Achievement';
                                break;
                            case 'events':
                                $icon = 'fa-running';
                                $icon_label = 'Event';
                                break;
                            case 'programs':
                                $icon = 'fa-laptop-code';
                                $icon_label = 'Program';
                                break;
                        }
                    ?>
                    <!-- News Card -->
                    <div class="news-card" data-category="<?php echo htmlspecialchars($news['category']); ?>">
                        <div class="card-image">
                            <?php if (!empty($news['image']) && file_exists('uploads/' . $news['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="image-placeholder">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                    <span><?php echo $icon_label; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <span class="card-category"><?php echo ucfirst(htmlspecialchars($news['category'])); ?></span>
                            <h3 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h3>
                            <p class="card-summary">
                                <?php echo htmlspecialchars($news['summary']); ?>
                            </p>
                            <div class="card-meta">
                                <span class="card-date">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('F d, Y', strtotime($news['date_posted'])); ?>
                                </span>
                            </div>
                            <a href="news_detail.php?id=<?php echo $news['id']; ?>" class="card-link">Read More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Sidebar -->
                <aside class="news-sidebar">
                    <!-- Recent News -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Recent News</h4>
                        <ul class="recent-news-list">
                            <?php while ($recent = mysqli_fetch_assoc($recent_result)): ?>
                            <li>
                                <a href="news_detail.php?id=<?php echo $recent['id']; ?>">
                                    <span class="recent-date"><?php echo date('F d, Y', strtotime($recent['date_posted'])); ?></span>
                                    <span class="recent-title"><?php echo htmlspecialchars($recent['title']); ?></span>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>



                    <<!-- Subscribe Widget -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title">Subscribe to Updates</h4>
                        <p class="widget-description">Get the latest news delivered to your inbox.</p>
                        <form class="subscribe-form" id="subscribeForm">
                            <input type="email" name="email" id="subscribeEmail" placeholder="Your email address" required>
                            <button type="submit">Subscribe</button>
                        </form>
                        <div id="subscribeMessage" style="margin-top: 10px; font-size: 13px;"></div>
                    </div>

                    <script>
                    document.getElementById('subscribeForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        const email = document.getElementById('subscribeEmail').value;
                        const messageDiv = document.getElementById('subscribeMessage');
                        
                        fetch('subscribe.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                            body: 'email=' + encodeURIComponent(email)
                        })
                        .then(response => response.json())
                        .then(data => {
                            messageDiv.style.color = data.success ? 'green' : 'red';
                            messageDiv.textContent = data.message;
                            if (data.success) {
                                document.getElementById('subscribeForm').reset();
                            }
                        });
                    });
                    </script>
                </aside>
            </div>
        </div>
    </section>


<div style="text-align: center; padding: 20px; background: #f5f5f5; margin-top: 40px;">
    <a href="admin/login.php" style="color: #667eea; text-decoration: none; font-size: 14px;">
        <i class="fas fa-lock"></i> Admin Panel
    </a>
</div>

        <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 St. Uriel Academy of Taguig City, Inc. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- ========================================
       JAVASCRIPT
       ======================================== -->
    <script src="script.js"></script>
</body>
</html>
<?php
// Close database connection
mysqli_close($conn);
?>