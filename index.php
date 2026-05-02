<?php
// Include database connection
require_once 'config/db.php';

// Fetch latest 3 important announcements/events
$announcements_query = "SELECT * FROM news 
                        WHERE category IN ('announcements', 'events') 
                        ORDER BY date_posted DESC 
                        LIMIT 3";
$announcements_result = mysqli_query($conn, $announcements_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Website - Home</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Link to CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
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

    <!-- HERO SLIDER SECTION -->
    <section class="hero-slider" id="heroSlider">
        
        <!-- SLIDE 1: The Main Enrollment Announcement -->
        <div class="slide active" style="background-image: url('assets/img/mainslide1.jpg');">
            <div class="hero-content">
                <p class="hero-subtitle">Welcome to St. Uriel Academy of Taguig City, Inc.</p>
                <h1 class="hero-headline">Enrollment for School Year <br><span>2025–2026</span> is Now Ongoing</h1>
                <p class="hero-description">
                    Join a community dedicated to academic excellence and character building. 
                    Secure your child's future today by registering for the upcoming academic year.
                </p>
                <div class="btn-group">
                    <a href="admission.html#requirements" class="btn btn-primary">Enroll Now</a>
                    <a href="https://web.facebook.com/share/p/18QFwguVby/" target="_blank" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>

        <!-- SLIDE 2: Campus Life Highlight -->
        <div class="slide" style="background-image: url('assets/img/sslg.jpg');">
            <div class="hero-content">
                <p class="hero-subtitle">Campus Life</p>
                <h1 class="hero-headline">A School of <span>Opportunities</span></h1>
                <p class="hero-description">
                    Explore our vibrant campus life filled with extracurricular activities, clubs, and events that foster growth and camaraderie among students.
                </p>
                <div class="btn-group">
                    <a href="about.html" class="btn btn-primary">Virtual Tour</a>
                    <a href="about.html" class="btn btn-secondary">View Gallery</a>
                </div>
            </div>
        </div>

        <!-- SLIDE 3: Academic Achievement -->
        <div class="slide" style="background-image: url('assets/img/slide3img.jpg');">
            <div class="hero-content">
                <p class="hero-subtitle">Academic Excellence</p>
                <h1 class="hero-headline">Shaping the <span>Leaders</span> of Tomorrow</h1>
                <p class="hero-description">
                    Our curriculum is designed to challenge students and prepare them for global success.
                </p>
                <div class="btn-group">
                    <a href="academics.html" class="btn btn-primary">Our Curriculum</a>
                    <a href="contact.html" class="btn btn-secondary">Contact Us</a>
                </div>
            </div>
        </div>

        <!-- Navigation Dots -->
        <div class="slider-dots">
            <div class="dot active" onclick="currentSlide(1)"></div>
            <div class="dot" onclick="currentSlide(2)"></div>
            <div class="dot" onclick="currentSlide(3)"></div>
        </div>

    </section>

<!-- ========================================
   LATEST ANNOUNCEMENTS SECTION
   ======================================== -->
<section class="announcements-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title-main">Latest Announcements</h2>
            <p class="section-subtitle-main">Stay informed about important school events and updates</p>
        </div>
        
        <div class="announcements-grid">
            <?php 
            if (mysqli_num_rows($announcements_result) > 0):
                while ($announcement = mysqli_fetch_assoc($announcements_result)): 
                    // Determine color based on category
                    $color = '#11998e';
                    if ($announcement['category'] == 'events') {
                        $color = '#7b1fa2';
                    }
            ?>
            <div class="announcement-card">
                <!-- Image Section -->
                <div class="announcement-image">
                    <?php if (!empty($announcement['image']) && file_exists('uploads/' . $announcement['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($announcement['image']); ?>" 
                             alt="<?php echo htmlspecialchars($announcement['title']); ?>">
                    <?php else: ?>
                        <div class="announcement-placeholder" style="background: linear-gradient(135deg, <?php echo $color; ?> 0%, <?php echo $color; ?>dd 100%);">
                            <i class="fas <?php echo $announcement['category'] == 'events' ? 'fa-calendar-alt' : 'fa-bullhorn'; ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Category Badge -->
                    <span class="announcement-badge" style="background: <?php echo $color; ?>;">
                        <?php echo ucfirst($announcement['category']); ?>
                    </span>
                </div>

                <!-- Content Section -->
                <div class="announcement-content">
                    <h3 class="announcement-title">
                        <?php echo htmlspecialchars($announcement['title']); ?>
                    </h3>
                    <p class="announcement-summary">
                        <?php echo htmlspecialchars(substr($announcement['summary'], 0, 140)) . '...'; ?>
                    </p>
                    
                    <div class="announcement-footer">
                        <div class="announcement-date">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo date('M d, Y', strtotime($announcement['date_posted'])); ?></span>
                        </div>
                        <a href="news_detail.php?id=<?php echo $announcement['id']; ?>" 
                           class="announcement-link" 
                           style="color: <?php echo $color; ?>;">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <div class="no-announcements">
                <i class="fas fa-info-circle"></i>
                <p>No announcements available at the moment.</p>
                <a href="news.php" class="btn-view-all">Browse All News</a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="view-all-section">
            <a href="news.php" class="btn-view-all-news">
                <span>View All News</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- ANNOUNCEMENTS SECTION STYLES -->
<style>
    /* Main Section */
    .announcements-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
        overflow: hidden;
    }

    .announcements-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.5) 35px, rgba(255,255,255,.5) 70px);
        pointer-events: none;
    }

    .announcements-section .container {
        position: relative;
        z-index: 1;
    }

    /* Section Header */
    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-title-main {
        font-size: 42px;
        color: #1a1a1a;
        margin-bottom: 15px;
        font-weight: 800;
        position: relative;
        display: inline-block;
    }

    .section-title-main::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 2px;
    }

    .section-subtitle-main {
        color: #666;
        font-size: 18px;
        margin-top: 20px;
    }

    /* Announcements Grid */
    .announcements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 35px;
        margin-bottom: 50px;
    }

    /* Announcement Card */
    .announcement-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
    }

    .announcement-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    /* Image Section */
    .announcement-image {
        position: relative;
        width: 100%;
        height: 240px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .announcement-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .announcement-card:hover .announcement-image img {
        transform: scale(1.1);
    }

    /* Placeholder for no image */
    .announcement-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .announcement-placeholder i {
        font-size: 80px;
        opacity: 0.9;
    }

    /* Category Badge */
    .announcement-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        color: white;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 20px;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
    }

    /* Content Section */
    .announcement-content {
        padding: 25px;
    }

    .announcement-title {
        font-size: 22px;
        color: #1a1a1a;
        margin-bottom: 15px;
        font-weight: 700;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.3s;
    }

    .announcement-card:hover .announcement-title {
        color: #11998e;
    }

    .announcement-summary {
        color: #555;
        line-height: 1.7;
        margin-bottom: 20px;
        font-size: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Footer */
    .announcement-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 2px solid #f0f0f0;
    }

    .announcement-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #888;
        font-size: 14px;
        font-weight: 500;
    }

    .announcement-date i {
        font-size: 16px;
    }

    .announcement-link {
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: gap 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .announcement-link:hover {
        gap: 12px;
    }

    .announcement-link i {
        font-size: 12px;
    }

    /* No Announcements State */
    .no-announcements {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .no-announcements i {
        font-size: 80px;
        color: #d1fae5;
        margin-bottom: 25px;
    }

    .no-announcements p {
        font-size: 18px;
        color: #666;
        margin-bottom: 30px;
    }

    /* View All Section */
    .view-all-section {
        text-align: center;
        margin-top: 50px;
    }

    .btn-view-all-news {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 40px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.3);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-view-all-news:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(17, 153, 142, 0.4);
        gap: 18px;
    }

    .btn-view-all-news i {
        font-size: 14px;
        transition: transform 0.3s;
    }

    .btn-view-all-news:hover i {
        transform: translateX(5px);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .announcements-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
    }

    @media (max-width: 768px) {
        .announcements-section {
            padding: 60px 0;
        }

        .section-title-main {
            font-size: 32px;
        }

        .section-subtitle-main {
            font-size: 16px;
        }

        .announcements-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .announcement-image {
            height: 200px;
        }

        .announcement-content {
            padding: 20px;
        }

        .announcement-title {
            font-size: 20px;
        }

        .announcement-summary {
            font-size: 14px;
        }

        .announcement-footer {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .btn-view-all-news {
            padding: 14px 30px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .section-title-main {
            font-size: 28px;
        }

        .announcement-image {
            height: 180px;
        }

        .announcement-badge {
            font-size: 11px;
            padding: 6px 12px;
        }
    }
</style>

    <!-- ABOUT SECTION -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <h2 class="section-title">About St. Uriel Academy</h2>
                <p class="about-text">
                    St. Uriel Academy of Taguig City, Inc. has been a beacon of academic excellence and moral integrity for over two decades. 
                    We are committed to nurturing students with a holistic education that combines academic rigor with character development. 
                    Our dedicated faculty and state-of-the-art facilities provide an environment where students can thrive and reach their full potential.
                </p>
                <a href="about.html#history" class="btn btn-primary">Learn More About Us</a>
            </div>
        </div>
    </section>

    <!-- SENIOR HIGH STRANDS SECTION -->
    <section class="strands-section">
        <div class="container">
            <h2 class="section-title">Senior High School Strands</h2>
            <p class="section-subtitle">Choose your path to success</p>
            
            <div class="strands-grid">
                <!-- HUMSS Strand -->
                <div class="strand-card">
                    <div class="strand-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>HUMSS</h3>
                    <p>Humanities and Social Sciences. Prepare for careers in law, education, social work, and public service.</p>
                    <a href="#" class="btn-strand">Learn More</a>
                </div>

                <!-- ABM Strand -->
                <div class="strand-card">
                    <div class="strand-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>ABM</h3>
                    <p>Accountancy, Business, and Management. Develop skills for entrepreneurship and business leadership.</p>
                    <a href="#" class="btn-strand">Learn More</a>
                </div>

                <!-- GAS Strand -->
                <div class="strand-card">
                    <div class="strand-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>GAS</h3>
                    <p>General Academic Strand. Explore various disciplines and prepare for diverse college courses.</p>
                    <a href="#" class="btn-strand">Learn More</a>
                </div>

                <!-- ICT Strand -->
                <div class="strand-card">
                    <div class="strand-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>ICT</h3>
                    <p>Information and Communications Technology. Master digital skills for the tech-driven future.</p>
                    <a href="#" class="btn-strand">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 St. Uriel Academy of Taguig City, Inc. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Link to JavaScript File -->
    <script src="script.js"></script>
</body>
</html>