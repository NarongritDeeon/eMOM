<?php
// header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT citizen_id, prefix, first_name, last_name, email, phone_number, department, role FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // ผู้ใช้ไม่มีอยู่ในระบบ
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eMOM</title>
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="main.php">e-MOM</a>
            </div>
            <div class="user-info">
                <span id="user-name" onclick="showProfilePopup()"><?php echo htmlspecialchars($user['prefix'] . ' ' . $user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['department']) . ')'; ?></span>
                <a href="#" onclick="showLogoutPopup()">ออกจากระบบ</a>
            </div>
        </div>
    </header>

    <!-- POP UP สำหรับออกจากระบบ -->
    <div id="logout-popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeLogoutPopup()">&times;</span>
            <h2>ยืนยันการออกจากระบบ</h2>
            <div class="popup-buttons">
                <button onclick="logout()">ใช่</button>
                <button onclick="closeLogoutPopup()">ไม่</button>
            </div>
        </div>
    </div>

    <!-- POP UP สำหรับแก้ไขข้อมูลส่วนตัว -->
    <div id="profile-popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeProfilePopup()">&times;</span>
            <h2>แก้ไขข้อมูลส่วนตัว</h2>
            <form id="profile-form" action="edit_profile.php" method="POST">
                <div class="form-group">
                    <label for="prefix">คำนำหน้า:</label>
                    <select name="prefix" id="prefix">
                        <option value="นาย" <?php echo $user['prefix'] == 'นาย' ? 'selected' : ''; ?>>นาย</option>
                        <option value="นาง" <?php echo $user['prefix'] == 'นาง' ? 'selected' : ''; ?>>นาง</option>
                        <option value="นางสาว" <?php echo $user['prefix'] == 'นางสาว' ? 'selected' : ''; ?>>นางสาว</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="first_name">ชื่อจริง:</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">นามสกุล:</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">เบอร์โทรศัพท์:</label>
                    <input type="text" name="phone_number" id="phone_number" maxlength="10" pattern="\d{9,10}" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                </div>
                <div class="form-group">
                    <label for="department">แผนก:</label>
                    <input type="text" id="department" value="<?php echo htmlspecialchars($user['department']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="citizen_id">รหัสบัตรประชาชน:</label>
                    <input type="text" id="citizen_id" value="<?php echo htmlspecialchars($user['citizen_id']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                <button type="submit">บันทึก</button>
            </form>
        </div>
    </div>
    
    <!-- POP UP สำหรับแจ้งเตือนการบันทึกข้อมูลสำเร็จ -->
    <div id="success-popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closeSuccessPopup()">&times;</span>
            <h2>เปลี่ยนแปลงข้อมูลสำเร็จ</h2>
            <div class="popup-buttons">
                <button onclick="closeSuccessPopup()">ปิด</button>
            </div>
        </div>
    </div>


    <script src="js/header.js"></script>
</body>
</html>
