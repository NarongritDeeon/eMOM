<?php
session_start();
require 'includes/db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $citizen_id = $_POST['citizen_id'];
    $email = $_POST['email'];

    // ตรวจสอบรหัสบัตรประชาชนและอีเมล
    $stmt = $pdo->prepare("SELECT * FROM users WHERE citizen_id = ? AND email = ?");
    $stmt->execute([$citizen_id, $email]);
    $user = $stmt->fetch();

    if ($user) {
        // ตั้งรหัสผ่านใหม่เป็น 12345
        $new_password = password_hash('12345', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, must_change_password = 1 WHERE id = ?");
        $stmt->execute([$new_password, $user['id']]);
        $success = "รหัสผ่านของคุณถูกเปลี่ยนเป็น 12345 กรุณาเข้าสู่ระบบและเปลี่ยนรหัสผ่าน";
    } else {
        $error = "ข้อมูลไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน - e-MOM</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>ลืมรหัสผ่าน</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="forgot_password.php" method="post">
            <input type="text" name="citizen_id" placeholder="รหัสบัตรประชาชน" required>
            <input type="email" name="email" placeholder="อีเมล" required>
            <button type="submit">รีเซ็ตรหัสผ่าน</button>
        </form>
        <a class="forgot-password" href="index.php">กลับไปที่หน้าเข้าสู่ระบบ</a>
    </div>
</body>
</html>
