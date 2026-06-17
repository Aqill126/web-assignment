<?php
session_start();
require_once 'config.php';

// KESELAMATAN: Pastikan hanya pengguna yang mempunyai role 'admin' boleh masuk!
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: homepage.php");
    exit();
}

// Dapatkan data semua user biasa terdaftar daripada aqill_db
$sql_users = "SELECT id, first_name, last_name, gender, email_address, phone_number, educational_background, created_at FROM users ORDER BY created_at DESC";
$result_users = $conn->query($sql_users);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Aqill DB Control</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f1f5f9; padding: 30px; color: #1e293b; }
        .header { background: #1e293b; color: white; padding: 20px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 22px; }
        .logout-btn { background: #ef4444; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 14px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h2 { margin-bottom: 20px; font-size: 18px; color: #0284c7; border-bottom: 2px solid #cbd5e1; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { border: 1px solid #cbd5e1; padding: 12px; text-align: left; }
        th { background: #334155; color: white; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { background: #3b82f6; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>ADMIN CONTROL CENTRE - DATABASE: <span style="color:#38bdf8;">aqill_db</span></h1>
        <div>
            <span style="margin-right:15px;">Log masuk sebagai: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="homepage.php?logout=true" class="logout-btn">Log Keluar</a>
        </div>
    </div>

    <div class="card">
        <h2>Senarai Pengguna Terdaftar dalam Sistem (Tabel `users`)</h2>
        
        <?php if ($result_users && $result_users->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Penuh</th>
                        <th>Jantina</th>
                        <th>E-mel</th>
                        <th>No. Telefon</th>
                        <th>Latar Belakang Akademik</th>
                        <th>Tarikh Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['email_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                            <td><span class="badge"><?php echo htmlspecialchars($row['educational_background']); ?></span></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color:#64748b;">Tiada rekod pengguna biasa yang berdaftar buat masa ini.</p>
        <?php endif; ?>
    </div>

</body>
</html>