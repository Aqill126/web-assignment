<?php
// Tetapan konfigurasi database
$db_host = 'localhost';
$db_user = 'root';      // Ganti dengan username database anda jika berbeza
$db_pass = '';          // Ganti dengan password database anda jika berbeza
$db_name = 'aqill_db';  // Nama database telah ditukar ke aqill_db

// Membuat sambungan
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Semak sambungan
if ($conn->connect_error) {
    die("Sambungan ke database gagal: " . $conn->connect_error);
}
?>