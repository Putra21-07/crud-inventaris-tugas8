<?php
session_start();
require_once 'Database.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("DELETE FROM produk WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        $_SESSION['flash_message'] = "Produk berhasil dihapus!";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal menghapus produk.";
        $_SESSION['flash_type'] = "error";
    }
}

header("Location: index.php");
exit;
?>