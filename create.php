<?php
session_start();
require_once 'Database.php';
$db = Database::getInstance()->getConnection();

// Ambil data untuk dropdown kategori & supplier
$kategori = $db->query("SELECT * FROM kategori")->fetchAll(PDO::FETCH_ASSOC);
$supplier = $db->query("SELECT * FROM supplier")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_produk'];
    $kategori_id = $_POST['kategori_id'];
    $supplier_id = $_POST['supplier_id'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // SEMUA query input pakai prepared statements
    $stmt = $db->prepare("INSERT INTO produk (nama_produk, kategori_id, supplier_id, stok, harga) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$nama, $kategori_id, $supplier_id, $stok, $harga])) {
        $_SESSION['flash_message'] = "Produk berhasil ditambahkan!";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Gagal menambahkan produk.";
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
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Produk Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Produk:</label>
                <input type="text" name="nama_produk" required>
            </div>
            <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori_id" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['id']; ?>"><?= htmlspecialchars($kat['nama_kategori']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Supplier:</label>
                <select name="supplier_id" required>
                    <option value="">Pilih Supplier</option>
                    <?php foreach ($supplier as $sup): ?>
                        <option value="<?= $sup['id']; ?>"><?= htmlspecialchars($sup['nama_supplier']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" required>
            </div>
            <div class="form-group">
                <label>Harga:</label>
                <input type="number" step="0.01" name="harga" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>