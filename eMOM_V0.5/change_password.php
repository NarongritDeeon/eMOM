<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, password_changed_at = NOW(), must_change_password = 0 WHERE id = ?");
        $stmt->execute([$hashed_password, $_SESSION['user_id']]);
        $success = "เปลี่ยนรหัสผ่านสำเร็จ";
        header("refresh:7; url=index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรุณาเปลี่ยนรหัสผ่าน - e-MOM</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>กรุณาเปลี่ยนรหัสผ่าน</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form action="change_password.php" method="post">
            <input type="password" name="new_password" placeholder="รหัสผ่านใหม่" required>
            <input type="password" name="confirm_password" placeholder="ยืนยันรหัสผ่านใหม่" required>
            <button type="submit">เปลี่ยนรหัสผ่าน</button>
        </form>
        <a class="forgot-password" href="index.php">กลับไปที่หน้าเข้าสู่ระบบ</a>
    </div>
</body>
</html>
