<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_email = "admin@example.com";
    $subject = "New Application Submission";
    $message = "A new application has been submitted. Please find the attached Excel file.";
    
    if (isset($_FILES["file"])) {
        $file_tmp = $_FILES["file"]["tmp_name"];
        $file_name = $_FILES["file"]["name"];

        $boundary = md5(uniqid(time()));
        $headers = "From: no-reply@yourwebsite.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "$message\r\n";
        
        $file = file_get_contents($file_tmp);
        $file_encoded = chunk_split(base64_encode($file));
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= "$file_encoded\r\n";
        $body .= "--$boundary--";

        if (mail($admin_email, $subject, $body, $headers)) {
            echo "Application submitted successfully.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "No file received.";
    }
} else {
    echo "Invalid request.";
}
?>
