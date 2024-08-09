<?php
// user_list.php
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// เรียกใช้งานไฟล์ db.php สำหรับการเชื่อมต่อฐานข้อมูล
require_once 'includes/db.php';

// ดึงข้อมูลรายชื่อผู้ใช้งานจากฐานข้อมูล
$stmt = $pdo->query('SELECT id, citizen_id, prefix, first_name, last_name, email, phone_number, department, role FROM users');
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายชื่อผู้ใช้งานระบบ</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/user_list.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    
    <main class="main-content">
        <h1>รายชื่อผู้ใช้งานระบบ</h1>
        <div class="search-container">
            <input type="text" id="search" placeholder="ค้นหาผู้ใช้งาน">
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>รหัสบัตรประชาชน</th>
                    <th>คำนำหน้า</th>
                    <th>ชื่อจริง</th>
                    <th>นามสกุล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>แผนก</th>
                    <th>บทบาท</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['citizen_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['prefix']); ?></td>
                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($user['department']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div id="pagination" class="pagination-controls"></div>
        <div class="total-count"> จำนวนผู้ใช้งานทั้งหมด: <?php echo count($users) ; ?> ราย </div>
    </main>
    <script src="js/user_list.js"></script>
</body>
</html>
