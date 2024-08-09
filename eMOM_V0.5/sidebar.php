<?php
// sidebar.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// เรียกใช้งานไฟล์ db.php สำหรับการเชื่อมต่อฐานข้อมูล
require_once 'includes/db.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// ดึงข้อมูลบทบาทของผู้ใช้จากฐานข้อมูล
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$user_role = $user['role'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">
    <script src="js/sidebar.js" defer></script>
    <title>Sidebar</title>
</head>

<body>
    <aside class="sidebar">
        <ul>
            <li><a href="main.php">หน้าหลัก</a></li>
            <li class="meetings">
                <a href="javascript:void(0)">การประชุม <span class="arrow">▼</span></a>
                <ul class="meetings-menu">
                    <li><a href="main.php">เพิ่มข้อมูลห้องประชุม</a></li>
                    <li><a href="main.php">ห้องประชุม (รอดำเนินการ)</a></li>
                    <li><a href="main.php">ห้องประชุม (เสร็จสิ้น)</a></li>
                </ul>
            </li>
            <li><a href="main.php">รายงาน</a></li>
            <?php if ($user_role === 'ผู้ดูแลระบบ') : ?>
                <li class="settings">
                    <a href="javascript:void(0)">ตั้งค่า <span class="arrow">▼</span></a>
                    <ul class="settings-menu">
                        <li><a href="add_user.php">เพิ่มผู้ใช้งานระบบ</a></li>
                        <li><a href="user_list.php">รายชื่อผู้ใช้งานระบบ</a></li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </aside>

    <main class="main-content">
        <!-- เนื้อหาหลักของเว็บไซต์จะถูกวางไว้ที่นี่ -->
    </main>
</body>

</html>
