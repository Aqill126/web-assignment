<?php
session_start();
require_once 'config.php';

$msg = "";
$msg_type = "";

// ==========================================
// KAWALAN SESSION TIMEOUT (1 MINIT = 60 SAAT)
// ==========================================
if (isset($_SESSION['username'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
        // Sesi tamat tempoh jika tiada aktiviti melebihi 60 saat
        session_unset();
        session_destroy();
        header("Location: homepage.php?msg=session_expired");
        exit();
    }
    $_SESSION['last_activity'] = time(); // Kemas kini masa aktiviti terakhir
}

// Papar mesej jika sesi tamat
if (isset($_GET['msg']) && $_GET['msg'] == 'session_expired') {
    $msg = "Sesi anda telah tamat (Tiada aktiviti dalam 1 minit). Sila log masuk semula.";
    $msg_type = "error";
}

// Proses Log Keluar
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: homepage.php");
    exit();
}

// Hantar ke halaman bersesuaian jika sudah log masuk
if (isset($_SESSION['username']) && !isset($_GET['msg'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: index.php");
        exit();
    }
}

// ==========================================
// LOGIK PROSES LOGIN
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    $login_user = $conn->real_escape_string($_POST['username']);
    $login_pass = $_POST['password'];
    $login_role = $_POST['role'];

    if ($login_role == 'admin') {
        $admin_query = "SELECT * FROM admins WHERE admin_username = '$login_user' AND admin_password = '$login_pass'";
        $admin_result = $conn->query($admin_query);

        if ($admin_result && $admin_result->num_rows > 0) {
            $admin_data = $admin_result->fetch_assoc();
            $_SESSION['role'] = 'admin';
            $_SESSION['username'] = $admin_data['admin_username'];
            $_SESSION['last_activity'] = time(); // Set masa mula sesi
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $msg = "Ralat: ID Pentadbir atau Kata Laluan Admin salah!";
            $msg_type = "error";
        }
    } else {
        $user_query = "SELECT * FROM users WHERE username = '$login_user' AND password = '$login_pass'";
        $user_result = $conn->query($user_query);

        if ($user_result && $user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
            $_SESSION['role'] = 'user';
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['full_name'] = $user_data['first_name'] . " " . $user_data['last_name'];
            $_SESSION['last_activity'] = time(); // Set masa mula sesi
            header("Location: index.php");
            exit();
        } else {
            $msg = "Ralat: Username atau Kata Laluan Pengguna salah!";
            $msg_type = "error";
        }
    }
}

// ==========================================
// LOGIK PROSES SIGN UP (DAFTAR USER)
// ==========================================
if (isset($_POST['action']) && $_POST['action'] == 'signup') {
    $fname = $conn->real_escape_string($_POST['firstname']);
    $lname = $conn->real_escape_string($_POST['lastname']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $address = $conn->real_escape_string($_POST['address']);
    $state = $conn->real_escape_string($_POST['state']); // Ambil data negeri terpilih
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    
    $edu_array = isset($_POST['education']) ? $_POST['education'] : [];
    $education = implode(", ", $edu_array); 
    
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $re_password = $_POST['repassword'];

    if ($password !== $re_password) {
        $msg = "Ralat: Kata laluan tidak sepadan!";
        $msg_type = "error";
    } else {
        $check_query = "SELECT id FROM users WHERE username='$username' OR email_address='$email'";
        $result = $conn->query($check_query);

        if ($result && $result->num_rows > 0) {
            $msg = "Ralat: Username atau Email telah wujud!";
            $msg_type = "error";
        } else {
            $sql_insert = "INSERT INTO users (first_name, last_name, gender, address, state, phone_number, email_address, educational_background, username, password) 
                           VALUES ('$fname', '$lname', '$gender', '$address', '$state', '$phone', '$email', '$education', '$username', '$password')";
            
            if ($conn->query($sql_insert) === TRUE) {
                $msg = "Pendaftaran berjaya! Sila log masuk menggunakan borang di bawah.";
                $msg_type = "success";
            } else {
                $msg = "Ralat sistem: " . $conn->error;
                $msg_type = "error";
            }
        }
    }
}

// Dapatkan senarai negeri dari database secara dinamik
$states_result = $conn->query("SELECT state_name FROM states ORDER BY state_name ASC");
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Aqill Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f8fafc; color: #0f172a; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .welcome-header { text-align: center; margin-bottom: 30px; }
        .welcome-header h1 { color: #1e293b; font-size: 2.5rem; margin-bottom: 10px; }
        .container { background: white; padding: 35px; border-radius: 8px; width: 100%; max-width: 650px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
        h2 { color: #1e293b; border-bottom: 2px solid #0284c7; padding-bottom: 8px; margin-bottom: 20px; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { padding: 10px 5px; vertical-align: top; border: none; }
        label { font-weight: 600; font-size: 14px; }
        input[type="text"], input[type="password"], input[type="email"], textarea, select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px; background: white; }
        .radio-group, .checkbox-group { display: flex; gap: 15px; align-items: center; padding-top: 5px; }
        .btn-container { display: flex; gap: 10px; margin-top: 10px; }
        .btn { padding: 11px 25px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; transition: opacity 0.2s; }
        .btn-submit { background-color: #10b981; color: white; }
        .btn-clear { background-color: #f59e0b; color: white; }
        .btn-cancel { background-color: #ef4444; color: white; }
        .btn-public { background-color: #0284c7; color: white; width: 100%; text-align: center; text-decoration: none; display: block; margin-top: 15px; padding: 12px; border-radius: 4px; font-weight: bold; }
        .btn:hover, .btn-public:hover { opacity: 0.9; }
        .alert-box { padding: 15px; margin-bottom: 25px; border-radius: 6px; font-weight: 600; width: 100%; max-width: 650px; text-align: center; }
        .alert-success { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .alert-error { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
        .switch-link { text-align: center; margin-top: 15px; font-size: 14px; display: flex; justify-content: space-between; }
        .switch-link a { color: #0284c7; text-decoration: none; font-weight: bold; }
        .form-section { display: none; }
        .form-section.active { display: block; }
    </style>
</head>
<body>

    <div class="welcome-header">
        <h1>AQILL SYSTEM PORTAL</h1>
        <p>Sila Log Masuk atau Daftar Akaun untuk Mengakses Portfolio Rasmi</p>
    </div>

    <?php if ($msg != ""): ?>
        <div class="alert-box alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="container">
        <div id="login-section" class="form-section active">
            <h2>User & Admin Login</h2>
            <form action="homepage.php" method="POST">
                <input type="hidden" name="action" value="login">
                <table>
                    <tr>
                        <td style="width: 25%;"><label>Peranan (Role):</label></td>
                        <td>
                            <select name="role" required>
                                <option value="user">User Biasa</option>
                                <option value="admin">Admin</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>ID / Username:</label></td>
                        <td><input type="text" name="username" required placeholder="Masukkan Username / Admin ID"></td>
                    </tr>
                    <tr>
                        <td><label>Password:</label></td>
                        <td><input type="password" name="password" required placeholder="Masukkan kata laluan"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="btn-container">
                                <button type="submit" class="btn btn-submit">Submit</button>
                                <button type="reset" class="btn btn-clear">Clear</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
            <div class="switch-link">
                <a href="#" onclick="alert('Fungsi set semula kata laluan belum diaktifkan.')">Forgot Password?</a>
                <span>Belum ada akaun? <a href="#" onclick="toggleForm('signup-section')">Daftar (Sign Up)</a></span>
            </div>
            <hr style="margin-top:20px; border:0; border-top:1px solid #e2e8f0;">
            <a href="index.php" class="btn-public">Masuk Sebagai Pelawat Umum (Tanpa Login)</a>
        </div>

        <div id="signup-section" class="form-section">
            <h2>Sign Up / Registration Form</h2>
            <form action="homepage.php" method="POST">
                <input type="hidden" name="action" value="signup">
                <table>
                    <tr>
                        <td style="width: 25%;"><label>First Name:</label></td>
                        <td><input type="text" name="firstname" required></td>
                    </tr>
                    <tr>
                        <td><label>Last Name:</label></td>
                        <td><input type="text" name="lastname" required></td>
                    </tr>
                    <tr>
                        <td><label>Gender:</label></td>
                        <td>
                            <div class="radio-group">
                                <label><input type="radio" name="gender" value="Male" required> Male</label>
                                <label><input type="radio" name="gender" value="Female"> Female</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Address:</label></td>
                        <td><textarea name="address" rows="2" required></textarea></td>
                    </tr>
                    <tr>
                        <td><label>State (Negeri):</label></td>
                        <td>
                            <select name="state" required>
                                <option value="">-- Pilih Negeri --</option>
                                <?php if ($states_result && $states_result->num_rows > 0): ?>
                                    <?php while($s_row = $states_result->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($s_row['state_name']); ?>">
                                            <?php echo htmlspecialchars($s_row['state_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <option value="Perlis">Perlis (Database fallback)</option>
                                    <option value="Kedah">Kedah</option>
                                <?php endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Phone Number:</label></td>
                        <td><input type="text" name="phone" required></td>
                    </tr>
                    <tr>
                        <td><label>Email Address:</label></td>
                        <td><input type="email" name="email" required></td>
                    </tr>
                    <tr>
                        <td><label>Educational:</label></td>
                        <td>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="education[]" value="SPM"> SPM</label>
                                <label><input type="checkbox" name="education[]" value="Diploma"> Diploma</label>
                                <label><input type="checkbox" name="education[]" value="Degree"> Degree</label>
                                <label><input type="checkbox" name="education[]" value="Master"> Master</label>
                                <label><input type="checkbox" name="education[]" value="PhD"> PhD</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><label>User Name:</label></td>
                        <td><input type="text" name="username" required></td>
                    </tr>
                    <tr>
                        <td><label>Password:</label></td>
                        <td><input type="password" name="password" required></td>
                    </tr>
                    <tr>
                        <td><label>Re-Password:</label></td>
                        <td><input type="password" name="repassword" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="btn-container">
                                <button type="submit" class="btn btn-submit">Submit</button>
                                <button type="reset" class="btn btn-clear">Clear</button>
                                <button type="button" class="btn btn-cancel" onclick="toggleForm('login-section')">Cancel</button>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <script>
        function toggleForm(sectionId) {
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }
    </script>
</body>
</html>