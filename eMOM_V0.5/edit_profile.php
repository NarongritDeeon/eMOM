<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];
$prefix = $_POST['prefix'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone_number = $_POST['phone_number'];

// ตรวจสอบข้อมูลที่ส่งมาจากฟอร์มก่อนการอัปเดต
if (empty($prefix) || empty($first_name) || empty($last_name) || empty($phone_number)) {
    echo 'กรุณากรอกข้อมูลให้ครบถ้วน';
    exit;
}

// อัปเดตข้อมูลในฐานข้อมูล
$stmt = $pdo->prepare('UPDATE users SET prefix = ?, first_name = ?, last_name = ?, phone_number = ?, profile_updated_at = NOW() WHERE id = ?');
$stmt->execute([$prefix, $first_name, $last_name, $phone_number, $user_id]);

echo 'Success';
?>
