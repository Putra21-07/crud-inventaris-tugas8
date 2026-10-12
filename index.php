<?php
session_start();
require_once 'Database.php';

$db = Database::getInstance()->getConnection();

// Query dengan JOIN 2 tabel (kategori dan supplier)
$query = "SELECT produk.*, kategori.nama_kategori, supplier.nama_supplier 
          FROM produk 
          JOIN kategori ON produk.kategori_id = kategori.id 
          JOIN supplier ON produk.supplier_id = supplier.id";
$stmt = $db->prepare($query);
$stmt->execute();
$produks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inventaris - List Produk</title>
    <link rel="stylesheet" href="style.css"> <!-- UI Rapi / CSS -->
</head>
<body>
    <div class="container">
        <h2>Daftar Inventaris Produk</h2>

        <!-- Flash Message Sukses/Gagal -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert <?= $_SESSION['flash_type']; ?>">
                <?= htmlspecialchars($_SESSION['flash_message']); ?>
            </div>
            <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
        <?php endif; ?>

        <a href="create.php" class="btn btn-primary">+ Tambah Produk</a>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($produks as $row): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <!-- Output HTML pakai htmlspecialchars() -->
                    <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?= htmlspecialchars($row['nama_supplier']); ?></td>
                    <td><?= htmlspecialchars($row['stok']); ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>