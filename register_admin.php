<?php
require_once 'config.php';

// Tetapkan maklumat login yang anda mahukan
$admin_user = 'admin'; 
$admin_pass = 'admin123'; // Sila tukar ikut kesukaan anda

// Menghasilkan password hash yang serasi dengan password_verify()
$hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);

// Periksa jika admin sudah wujud
$check = $conn->query("SELECT * FROM admins WHERE admin_username = '$admin_user'");

if ($check->num_rows > 0) {
    echo "Akaun admin ini sudah sedia wujud!";
} else {
    $sql = "INSERT INTO admins (admin_username, admin_password) VALUES ('$admin_user', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        echo "Akaun Admin Berjaya Dicipta!<br>";
        echo "Username: <b>$admin_user</b><br>";
        echo "Password: <b>$admin_pass</b>";
    } else {
        echo "Ralat: " . $conn->error;
    }
}
?>