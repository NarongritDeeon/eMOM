<?php
// add_user.php

// เรียกใช้งานไฟล์ db.php สำหรับการเชื่อมต่อฐานข้อมูล
require_once 'includes/db.php';

// เรียกใช้งานไฟล์ header.php และ sidebar.php
require_once 'header.php';
require_once 'sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $citizen_id = $_POST['citizen_id'];
    $prefix = $_POST['prefix'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $department = $_POST['department'];
    $role = $_POST['role'];
    $password = password_hash('12345', PASSWORD_DEFAULT); // ตั้งค่ารหัสผ่านเริ่มต้น

    // ตรวจสอบว่ารหัสบัตรประชาชนมีอยู่ในระบบหรือไม่
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE citizen_id = ?');
    $stmt->execute([$citizen_id]);
    if ($stmt->fetchColumn() > 0) {
        $error = 'รหัสบัตรประชาชนนี้มีอยู่ในระบบแล้ว';
    } else {
        // ตรวจสอบอีเมลที่มีอยู่ในระบบ
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'อีเมลนี้มีอยู่ในระบบแล้ว';
        } else {
            // เพิ่มข้อมูลลงในฐานข้อมูล
            $stmt = $pdo->prepare('INSERT INTO users (citizen_id, prefix, first_name, last_name, email, phone_number, department, role, password, registered_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
            if ($stmt->execute([$citizen_id, $prefix, $first_name, $last_name, $email, $phone_number, $department, $role, $password])) {
                $success = 'ลงทะเบียนผู้ใช้งานสำเร็จ';
                // ล้างข้อมูลฟอร์ม
                $citizen_id = $prefix = $first_name = $last_name = $email = $phone_number = $department = $role = '';
            } else {
                $error = 'เกิดข้อผิดพลาดในการลงทะเบียน';
            }
        }
    }
} else {
    // ถ้าเป็นการเข้าถึงหน้าแรก ให้ตั้งค่าตัวแปรเป็นค่าว่าง
    $citizen_id = $prefix = $first_name = $last_name = $email = $phone_number = $department = $role = '';
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/add_user.css">
    <title>เพิ่มผู้ใช้งานระบบ</title>
</head>
<body>
    <main class="main-content">
        <div class="container">
            <h1>เพิ่มผู้ใช้งานระบบ</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form action="add_user.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="citizen_id">รหัสบัตรประชาชน:</label>
                        <input type="text" name="citizen_id" id="citizen_id" maxlength="13" pattern="\d{13}" value="<?php echo htmlspecialchars($citizen_id); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="prefix">คำนำหน้า:</label>
                        <select name="prefix" id="prefix" required>
                            <option value="นาย" <?php echo $prefix === 'นาย' ? 'selected' : ''; ?>>นาย</option>
                            <option value="นาง" <?php echo $prefix === 'นาง' ? 'selected' : ''; ?>>นาง</option>
                            <option value="นางสาว" <?php echo $prefix === 'นางสาว' ? 'selected' : ''; ?>>นางสาว</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">ชื่อจริง:</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">นามสกุล:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">อีเมล:</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">เบอร์โทรศัพท์:</label>
                        <input type="text" name="phone_number" id="phone_number" maxlength="10" pattern="\d{9,10}" value="<?php echo htmlspecialchars($phone_number); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="department">แผนก:</label>
                        <select name="department" id="department" required>
                            <option value="GOV Project Department" <?php echo $department === 'GOV Project Department' ? 'selected' : ''; ?>>GOV Project Department</option>
                            <option value="Warehouse & Logistic" <?php echo $department === 'Warehouse & Logistic' ? 'selected' : ''; ?>>Warehouse & Logistic</option>
                            <option value="Sale Department" <?php echo $department === 'Sale Department' ? 'selected' : ''; ?>>Sale Department</option>
                            <option value="Purchase Department" <?php echo $department === 'Purchase Department' ? 'selected' : ''; ?>>Purchase Department</option>
                            <option value="Team CMG" <?php echo $department === 'Team CMG' ? 'selected' : ''; ?>>Team CMG</option>
                            <option value="Software & Development" <?php echo $department === 'Software & Development' ? 'selected' : ''; ?>>Software & Development</option>
                            <option value="Human Resource Department" <?php echo $department === 'Human Resource Department' ? 'selected' : ''; ?>>Human Resource Department</option>
                            <option value="Accounting Department" <?php echo $department === 'Accounting Department' ? 'selected' : ''; ?>>Accounting Department</option>
                            <option value="Service Department" <?php echo $department === 'Service Department' ? 'selected' : ''; ?>>Service Department</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="role">บทบาท:</label>
                        <select name="role" id="role" required>
                            <option value="ผู้ดูแลระบบ" <?php echo $role === 'ผู้ดูแลระบบ' ? 'selected' : ''; ?>>ผู้ดูแลระบบ</option>
                            <option value="ผู้ใช้งาน" <?php echo $role === 'ผู้ใช้งาน' || $role === '' ? 'selected' : ''; ?>>ผู้ใช้งาน</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <button type="submit">ลงทะเบียน</button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
