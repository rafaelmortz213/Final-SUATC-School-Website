<?php
require_once 'config/db.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Invalid email address';
    } else {
        // Check if already subscribed
        $check = mysqli_query($conn, "SELECT * FROM subscribers WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $response['message'] = 'You are already subscribed!';
        } else {
            // Insert new subscriber
            $insert = mysqli_query($conn, "INSERT INTO subscribers (email) VALUES ('$email')");
            if ($insert) {
                $response['success'] = true;
                $response['message'] = 'Thank you for subscribing!';
                
                // Send welcome email (optional - requires mail configuration)
                mail($email, "Welcome to SUATC News", "Thank you for subscribing to our newsletter!");
            } else {
                $response['message'] = 'Error subscribing. Please try again.';
            }
        }
    }
}

echo json_encode($response);
?>