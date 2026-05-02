<?php
// Use this function when adding news
function sendNewsNotifications($conn, $title, $summary, $category, $news_id) {
    // Fetch all subscribers
    $subscribers_query = "SELECT email FROM subscribers";
    $subscribers_result = mysqli_query($conn, $subscribers_query);
    
    if (mysqli_num_rows($subscribers_result) == 0) {
        return false; // No subscribers
    }
    
    $subject = "New " . ucfirst($category) . ": " . $title;
    
    $message = "Hello from St. Uriel Academy of Taguig City!\n\n";
    $message .= "A new " . $category . " has been posted:\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Title: " . $title . "\n\n";
    $message .= "Summary:\n" . $summary . "\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Read the full article here:\n";
    $message .= "http://localhost/suatcweb/news_detail.php?id=" . $news_id . "\n\n";
    $message .= "Or visit our news page:\n";
    $message .= "http://localhost/suatcweb/news.php\n\n";
    $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "Thank you for staying connected with SUATC!\n\n";
    $message .= "Best regards,\n";
    $message .= "St. Uriel Academy Administration\n";
    $message .= "Website: http://localhost/suatcweb\n";
    
    // Email headers
    $headers = "From: SUATC News <noreply@suatc.edu.ph>\r\n";
    $headers .= "Reply-To: admin@suatc.edu.ph\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    $sent_count = 0;
    
    while ($subscriber = mysqli_fetch_assoc($subscribers_result)) {
        if (mail($subscriber['email'], $subject, $message, $headers)) {
            $sent_count++;
        }
    }
    
    return $sent_count;
}
?>