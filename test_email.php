<?php
// Test if email is working
$to = "rafaelmortz@gmail.com"; // Change to your email
$subject = "Test Email from SUATC";
$message = "This is a test email to verify email functionality is working.";
$headers = "From: noreply@suatc.edu.ph\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Email sent successfully! Check your inbox.";
} else {
    echo "❌ Email failed. Check your PHP mail configuration.";
}
?>