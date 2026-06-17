<?php
session_start();

// ========================================================
// KAWALAN SESSION TIMEOUT (1 MINIT = 60 SAAT) BAGI USER LOG MASUK
// ========================================================
if (isset($_SESSION['username'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
        session_unset();
        session_destroy();
        header("Location: homepage.php?msg=session_expired");
        exit();
    }
    $_SESSION['last_activity'] = time(); // Reset masa setiap kali ada aktiviti/refresh
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aqill Official Portfolio & System</title>
    <style>
        /* 1. Global & Reset Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f8fafc; color: #1e293b; line-height: 1.6; padding-bottom: 60px; }
        a { text-decoration: none; color: #0284c7; }
        a:hover { text-decoration: underline; }

        /* 2. Top Navigation Bar & Logo */
        .navbar { background-color: #1e293b; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .logo a { color: #38bdf8; font-size: 24px; font-weight: bold; text-decoration: none; }
        .nav-links { display: flex; gap: 20px; list-style: none; }
        .nav-links a { color: #cbd5e1; font-weight: 500; font-size: 14px; }
        .nav-links a:hover { color: white; }
        .user-status { font-size: 13px; background: #334155; padding: 5px 12px; border-radius: 20px; }
        .logout-btn { background-color: #ef4444; color: white !important; padding: 4px 10px; border-radius: 4px; font-size: 12px; }

        /* 3. Layout Main Container */
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        
        /* 4. Section Card Layouts */
        .section-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; scroll-margin-top: 80px; }
        h2 { color: #0f172a; font-size: 22px; margin-bottom: 15px; border-bottom: 3px solid #0284c7; padding-bottom: 5px; display: inline-block; }
        p { margin-bottom: 15px; text-align: justify; }

        /* Gaya Khas untuk Profil & Biodata (Flexbox) */
        .bio-container { display: flex; gap: 25px; align-items: center; margin-top: 15px; }
        .profile-img { width: 150px; height: auto; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.12); border: 3px solid #0284c7; flex-shrink: 0; }
        .bio-text { flex-grow: 1; }

        /* Responsif untuk skrin kecil (gambar naik ke atas teks) */
        @media (max-width: 600px) {
            .bio-container { flex-direction: column; text-align: center; }
            .profile-img { margin-bottom: 15px; }
        }

        /* 5. Social Media & Links Stylings */
        .media-grid, .links-grid { display: flex; gap: 15px; margin-top: 15px; flex-wrap: wrap; }
        .link-item { background: #f1f5f9; padding: 12px 20px; border-radius: 6px; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 10px; border: 1px solid #e2e8f0; }
        .link-item:hover { background: #e2e8f0; }

        /* 6. Strict Table Implementation (Resume & Time Table) */
        table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 14px; background: white; }
        th, td { border: 1px solid #cbd5e1; padding: 12px; text-align: left; vertical-align: top; }
        th { background-color: #334155; color: white; font-weight: 600; }
        tr:nth-child(even) { background-color: #f8fafc; }
        
        /* Nested Lists Styling for Resume */
        .nested-list { margin-left: 20px; padding-top: 5px; }
        .nested-list li { margin-bottom: 4px; }

        /* 7. Gallery Display */
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-top: 15px; }
        .gallery-item { background: #e2e8f0; height: 160px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b; border: 2px dashed #cbd5e1; position: relative; overflow: hidden; }
        .gallery-item span { position: absolute; bottom: 10px; font-size: 12px; background: rgba(0,0,0,0.6); color: white; padding: 2px 8px; border-radius: 4px; }

        /* 8. Download Buttons */
        .download-btn { background-color: #10b981; color: white; padding: 10px 20px; border-radius: 4px; font-weight: bold; display: inline-flex; gap: 10px; margin-right: 10px; font-size: 14px; border: none; cursor: pointer; }
        .download-btn:hover { opacity: 0.9; text-decoration: none; }

        /* 9. Footer Disclaimer & Copyright */
        .footer { background-color: #1e293b; color: #94a3b8; text-align: center; padding: 20px; font-size: 13px; margin-top: 50px; border-top: 4px solid #0284c7; }
        .footer a { color: #38bdf8; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <a href="index.php">🌐 AQILL PORTAL</a>
        </div>
        <ul class="nav-links">
            <li><a href="#biography">Biography</a></li>
            <li><a href="#resume">Resume</a></li>
            <li><a href="#timetable">Time Table</a></li>
            <li><a href="#galleries">Galleries</a></li>
            <li><a href="#download">Download</a></li>
            <li><a href="#links">Links</a></li>
        </ul>
        <div class="user-status">
            <?php if (isset($_SESSION['username'])): ?>
                Pengguna: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> 
                <a href="homepage.php?logout=true" class="logout-btn" style="margin-left: 8px;">Log Keluar</a>
            <?php else: ?>
                Pelawat Awam | <a href="homepage.php" style="color:#38bdf8; font-weight:bold;">Log Masuk / Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">

     <div id="biography" class="section-card">
            <h2>Personal Biography</h2>
            <div class="bio-container">
                <img src="pic/pic1.png" alt="Aqill Profile" class="profile-img" onerror="this.src='pic/pic1.jpg';">
                
                <div class="bio-text">
                    <p>Selamat datang ke portfolio sistem saya. Nama saya Muhammad Aqill. Saya merupakan seorang pelajar dalam bidang Sains Komputer dan Teknologi Maklumat. Saya mempunyai minat yang mendalam dalam pembangunan sistem web bersepadu menggunakan PHP, pengurusan pangkalan data MySQL, dan reka bentuk antaramuka pengguna (UI/UX) berasaskan web standard yang responsif.</p>
                    <p>Visi saya adalah untuk membina solusi perisian yang mempermudahkan tugasan harian komuniti di samping mengekalkan kualiti pengurusan data yang selamat dan cekap.</p>
                </div>
            </div>
        </div>

        <div class="section-card">
            <h2>Social Media Links</h2>
            <p>Ikuti perkembangan dan hubungi saya menerusi platform media sosial rasmi di bawah:</p>
            <div class="media-grid">
                <a href="https://facebook.com" target="_blank" class="link-item">📘 Facebook</a>
                <a href="https://instagram.com" target="_blank" class="link-item">📸 Instagram</a>
                <a href="https://twitter.com" target="_blank" class="link-item">🐦 Twitter / X</a>
                <a href="https://youtube.com" target="_blank" class="link-item">🎥 YouTube</a>
            </div>
        </div>

        <div id="resume" class="section-card">
            <h2>Resume (Job Application)</h2>
            <p>Berikut dipaparkan ringkasan resume satu muka surat bagi permohonan jawatan:</p>
            
            <table>
                <tr>
                    <th style="width: 30%;">Kategori ringkasan</th>
                    <th>Butiran Pengalaman & Pendidikan</th>
                </tr>
                <tr>
                    <td><strong>Objektif Karier</strong></td>
                    <td>Menyumbang kepakaran pengaturcaraan web dalam persekitaran kerja profesional bagi menaik taraf kecekapan operasi sistem pangkalan data agensi.</td>
                </tr>
                <tr>
                    <td><strong>Latar Belakang Pendidikan</strong></td>
                    <td>
                        <ol class="nested-list">
                            <li><strong>Ijazah Sarjana Muda Sains Komputer</strong> - UiTM (2024 - Kini)</li>
                            <li><strong>Diploma Sains Komputer</strong> - UiTM (2021 - 2024)</li>
                            <li><strong>Sijil Pelajaran Malaysia (SPM)</strong> - Aliran Sains (2020)</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td><strong>Kemahiran Teknikal</strong></td>
                    <td>
                        <ul class="nested-list">
                            <li>Bahasa Atur Cara: PHP, HTML5, CSS3, JavaScript, SQL</li>
                            <li>Pangkalan Data: MySQL / MariaDB via phpMyAdmin</li>
                            <li>Alatan Kerja: XAMPP, VS Code, Git & GitHub</li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>

        <div id="timetable" class="section-card">
            <h2>Time Table (Jadual Waktu Kursus)</h2>
            <p>Klik pada <strong>Kod Kursus</strong> di dalam jadual untuk melompat terus melihat maklumat terperinci kursus di bahagian bawah tabel:</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Hari / Masa</th>
                        <th>08:00 AM - 10:00 AM</th>
                        <th>10:00 AM - 12:00 PM</th>
                        <th>12:00 PM - 02:00 PM</th>
                        <th>02:00 PM - 04:00 PM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Isnin</strong></td>
                        <td><a href="#course-csc510">CSC510</a></td>
                        <td>REHAT</td>
                        <td><a href="#course-itt530">ITT530</a></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td><strong>Rabu</strong></td>
                        <td>-</td>
                        <td><a href="#course-csc510">CSC510</a></td>
                        <td>REHAT</td>
                        <td><a href="#course-itt545">ITT545</a></td>
                    </tr>
                </tbody>
            </table>

            <br>
            <h3>Butiran Terperinci Kursus (Course Details)</h3>
            
            <div id="course-csc510" style="background:#f8fafc; padding:15px; margin-top:10px; border-left:4px solid #3b82f6;">
                <strong>CSC510 - Web Systems and Technologies</strong><br>
                Lecturer: Dr. Ahmad Bin Ali<br>
                Syllabus: Mempelajari PHP server-side scripting, pematuhan HTML/CSS, kawalan sesi kuki, pautan CRUD pangkalan data.
            </div>
            
            <div id="course-itt530" style="background:#f8fafc; padding:15px; margin-top:10px; border-left:4px solid #3b82f6;">
                <strong>ITT530 - Advanced Computer Networks</strong><br>
                Lecturer: Puan Siti Aminah<br>
                Syllabus: Pengurusan topologi rangkaian, konfigurasi router, subnetting IP, struktur komunikasi selamat klien-pelayan.
            </div>

            <div id="course-itt545" style="background:#f8fafc; padding:15px; margin-top:10px; border-left:4px solid #3b82f6;">
                <strong>ITT545 - Database Management Systems</strong><br>
                Lecturer: Encik Khairul Anwar<br>
                Syllabus: Reka bentuk skema ERD, proses normalisasi data (1NF, 2NF, 3NF), pemantapan query SQL serta indexes optimisasi.
            </div>
        </div>

      <!-- 7. GALLERIES SECTION (Koleksi Gambar Projek & Kampus) -->
        <div id="galleries" class="section-card">
            <h2>Galleries Collection</h2>
            <p>Berikut merupakan ruang paparan dokumentasi multimedia sepanjang aktiviti projek sistem:</p>
            <div class="gallery-grid">
                
                <!-- Kotak 1: Reka Bentuk UI -->
                <div class="gallery-item" style="border: none; background: #cbd5e1;">
                    <img src="https://picsum.photos/300/200?random=1" alt="Reka Bentuk UI" style="width:100%; height:100%; object-fit:cover;">
                    <span>Reka Bentuk UI (Projek 1)</span>
                </div>
                
                <!-- Kotak 2: Skema SQL -->
                <div class="gallery-item" style="border: none; background: #cbd5e1;">
                    <img src="https://picsum.photos/300/200?random=2" alt="Skema SQL" style="width:100%; height:100%; object-fit:cover;">
                    <span>Skema SQL Database</span>
                </div>
                
                <!-- Kotak 3: Sistem Walkthrough -->
                <div class="gallery-item" style="border: none; background: #cbd5e1;">
                    <img src="https://picsum.photos/300/200?random=3" alt="Sistem Walkthrough" style="width:100%; height:100%; object-fit:cover;">
                    <span>📹 Video Demo Walkthrough</span>
                </div>
                
                <!-- Kotak 4: Kampus UiTM -->
                <div class="gallery-item" style="border: none; background: #cbd5e1;">
                    <img src="https://picsum.photos/300/200?random=4" alt="Kampus UiTM" style="width:100%; height:100%; object-fit:cover;">
                    <span>Kampus UiTM</span>
                </div>

            </div>
        </div>
        <div id="download" class="section-card">
            <h2>Download Resources</h2>
            <p>Anda boleh memuat turun dokumen sokongan rasmi berformat PDF di bawah:</p>
            <a href="downloads/Resume_Aqill.pdf" download class="download-btn">📄 Download Resume (PDF)</a>
            <a href="downloads/Timetable_Aqill.pdf" download class="download-btn" style="background-color: #3b82f6;">📅 Download Time Table (PDF)</a>
        </div>

        <div id="links" class="section-card">
            <h2>External Academic Links</h2>
            <p>Pautan rujukan pantas ke portal akademik utama universiti:</p>
            <div class="links-grid">
                <a href="https://uitm.edu.my" target="_blank" class="link-item">🏫 UiTM Official Website</a>
                <a href="https://fskm.uitm.edu.my" target="_blank" class="link-item">💻 FSKM UiTM Website</a>
                <a href="https://istudent.uitm.edu.my" target="_blank" class="link-item">🔑 i-Student Portal</a>
            </div>
        </div>

    </div>

    <footer class="footer">
        <p>&copy; 2026 Aqill Portal System. All Rights Reserved.</p>
        <p style="font-size:11px; margin-top:5px; color:#64748b;">
            <strong>Disclaimer:</strong> Segala data kandungan yang dipaparkan di portal portfolio ini adalah bagi tujuan penilaian akademik modul pembangunan aplikasi sahaja. Data yang dimasukkan adalah selamat di bawah kendalian lokaliti <a href="homepage.php">aqill_db</a>.
        </p>
    </footer>

</body>
</html>