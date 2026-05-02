<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db_path = dirname(__DIR__) . '/config/db.php';
if (!file_exists($db_path)) {
    die("ERROR: config/db.php not found at: " . $db_path);
}
require_once $db_path;

require_once $db_path;

// Include email notification function
function sendNewsNotifications($conn, $title, $summary, $category, $news_id) {
    $subscribers_query = "SELECT email FROM subscribers";
    $subscribers_result = mysqli_query($conn, $subscribers_query);
    
    if (mysqli_num_rows($subscribers_result) == 0) {
        return 0;
    }
    
    $subject = "🔔 New " . ucfirst($category) . " from SUATC";
    
    $message = "Hello from St. Uriel Academy of Taguig City!\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $message .= "📢 NEW " . strtoupper($category) . "\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "📌 " . $title . "\n\n";
    $message .= $summary . "\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "🔗 Read More:\n";
    $message .= "http://localhost/suatcweb/news_detail.php?id=" . $news_id . "\n\n";
    $message .= "Or visit: http://localhost/suatcweb/news.php\n\n";
    $message .= "Best regards,\nSUATC Administration";
    
    $headers = "From: SUATC News <noreply@suatc.edu.ph>\r\n";
    $headers .= "Reply-To: admin@suatc.edu.ph\r\n";
    
    $sent_count = 0;
    while ($subscriber = mysqli_fetch_assoc($subscribers_result)) {
        if (@mail($subscriber['email'], $subject, $message, $headers)) {
            $sent_count++;
        }
    }
    
    return $sent_count;
}

if (!isset($conn) || !$conn) {
    die("ERROR: Database connection not established. Check config/db.php");
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $date_posted = mysqli_real_escape_string($conn, $_POST['date_posted']);
    
    $image_name = null;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $image_name = time() . '_' . basename($filename);
            $upload_path = '../uploads/' . $image_name;
            
            if (!file_exists('../uploads')) {
                mkdir('../uploads', 0755, true);
            }
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $error = 'Failed to upload image';
                $image_name = null;
            }
        } else {
            $error = 'Invalid file type. Only JPG, JPEG, PNG, and GIF allowed.';
        }
    }
    
    if (empty($error)) {
        $query = "INSERT INTO news (title, content, summary, image, category, date_posted) 
                  VALUES ('$title', '$content', '$summary', " . 
                  ($image_name ? "'$image_name'" : "NULL") . ", '$category', '$date_posted')";
        
        if (mysqli_query($conn, $query)) {
    $news_id = mysqli_insert_id($conn);
    
    // Send email notifications
    $sent_count = sendNewsNotifications($conn, $title, $summary, $category, $news_id);
    
    if ($sent_count > 0) {
        $success = "News added successfully! Email sent to $sent_count subscriber(s).";
    } else {
        $success = 'News added successfully! (No active subscribers)';
    }
    
    $_POST = array();
} else {
    $error = 'Error adding news: ' . mysqli_error($conn);
}
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News - SUATC Admin</title>
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
            flex: 1;
        }

        .school-logo-header {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            background: white;
            padding: 5px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.2);
            flex-shrink: 0;
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
            margin-right: 10px;
        }
        
        .btn {
            padding: 11px 22px;
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
        
        .btn-secondary {
            background: white;
            color: #11998e;
        }

        .btn-secondary:hover {
            background: #f0fdf4;
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 45px;
            border-top: 4px solid #38ef7d;
        }
        
        .form-group {
            margin-bottom: 28px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #11998e;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group label i {
            font-size: 16px;
        }
        
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #11998e;
            box-shadow: 0 0 0 4px rgba(17, 153, 142, 0.1);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.6;
        }
        
        .file-upload {
            border: 2px dashed #d1fae5;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            background: #f0fdf4;
            cursor: pointer;
        }
        
        .file-upload:hover {
            border-color: #38ef7d;
            background: #dcfce7;
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            cursor: pointer;
            color: #11998e;
            font-weight: 600;
        }

        .file-upload i {
            font-size: 52px;
            color: #6ee7b7;
            display: block;
            margin-bottom: 15px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
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
        
        .form-hint {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .form-card {
                padding: 30px 20px;
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
                <h1><i class="fas fa-plus-circle"></i> Add New News</h1>
            </div>
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </header>
    
    <div class="container">
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title"><i class="fas fa-heading"></i> News Title *</label>
                    <input type="text" id="title" name="title" required 
                           placeholder="Enter news title">
                </div>
                
                <div class="form-group">
                    <label for="summary"><i class="fas fa-align-left"></i> Summary *</label>
                    <textarea id="summary" name="summary" required 
                              placeholder="Brief summary for preview (recommended 150-200 characters)"></textarea>
                    <div class="form-hint">This will be shown in the news card preview</div>
                </div>
                
                <div class="form-group">
                    <label for="content"><i class="fas fa-file-alt"></i> Full Content *</label>
                    <textarea id="content" name="content" required 
                              placeholder="Full news article content" 
                              style="min-height: 200px;"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="category"><i class="fas fa-tag"></i> Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="announcements">Announcements</option>
                        <option value="events">Events</option>
                        <option value="achievements">Student Achievements</option>
                        <option value="programs">School Programs</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date_posted"><i class="fas fa-calendar"></i> Date Posted *</label>
                    <input type="date" id="date_posted" name="date_posted" required 
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-image"></i> News Image (Optional)</label>
                    <div class="file-upload" onclick="document.getElementById('image').click()">
                        <input type="file" id="image" name="image" accept="image/*">
                        <label for="image" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file-name">Click to upload image</span>
                            <div class="form-hint">JPG, JPEG, PNG, or GIF (Max 5MB)</div>
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Publish News
                </button>
            </form>
        </div>
    </div>
    
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('file-name').textContent = fileName;
            }
        });
    </script>
</body>
</html>