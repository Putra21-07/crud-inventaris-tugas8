<?php
session_start();
require_once 'Database.php';
$db = Database::getInstance()->getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data produk berdasarkan ID (Prepared Statement)
$stmt = $db->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: index.php");
    exit;
}

$kategori = $db->query("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
$supplier = $db->query("SELECT * FROM supplier")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $supplier_id = $_POST['supplier_id'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $update = $db->prepare("UPDATE produk SET nama_produk = ?, kategori_id = ?, supplier_id = ?, stok = ?, harga = ? WHERE id = ?");
    
    if ($update->execute([$nama, $kategori_id, $supplier_id, $stok, $harga, $id])) {
        $_SESSION['flash_message'] = "Produk berhasil diperbarui!";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal memperbarui produk.";
        $_SESSION['flash_type'] = "error";
    }
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Produk</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Produk:</label>
                <!-- Form Pre-filled & htmlspecialchars -->
                <input type="text" name="nama_produk" value="<?= htmlspecialchars($product['nama_produk']); ?>" required>
            </div>
            <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori_id" required>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['id']; ?>" <?= ($kat['id'] == $product['kategori_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <?php foreach ($supplier as $sup): ?>
                        <option value="<?= $sup['id']; ?>" <?= ($sup['id'] == $product['supplier_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($sup['nama_supplier']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" value="<?= htmlspecialchars($product['stok']); ?>" required>
            </div>
            <div class="form-group">
                <label>Harga:</label>
                <input type="number" step="0.01" name="harga" value="<?= htmlspecialchars($product['harga']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>