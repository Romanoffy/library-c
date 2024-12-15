<?php
if(!isset($_SESSION['is_login'])) {
    header("Location: /login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Daftar Buku Perpustakaan</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <form method="GET" class="d-flex justify-content-beetween align">
    <input type="text" class="form-control" id="search" placeholder="Search for..." name="find" required />
    <button class="btn btn-sm btn-primary"><i class="fa-solid fa-search"></i></button>
</form>
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $pdo;
                    // Tangkap input pencarian
                    $search = isset($_GET['find']) ? $_GET['find'] : '';

                    // Modifikasi query SQL
                    $sql = "SELECT b.*, 
                           br.borrow_date, 
                           br.return_date,
                           br.status as borrow_status,
                           u.username as borrower
                           FROM books b
                           LEFT JOIN (
                               SELECT * FROM borrowings 
                               WHERE status = 'borrowed'
                           ) br ON b.id = br.book_id
                           LEFT JOIN users u ON br.user_id = u.id
                           WHERE b.title LIKE :search
                           ORDER BY b.title";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['search' => '%' . $search . '%']);
                    while($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($book['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($book['author']) . "</td>";
                        echo "<td>" . htmlspecialchars($book['year']) . "</td>";
                        
                        // Status peminjaman
                        echo "<td>";
                        if ($book['borrow_status'] == 'borrowed') {
                            echo "<span class='text-danger'>Dipinjam</span><br>";
                            echo "<small>Peminjam: " . htmlspecialchars($book['borrower']) . "<br>";
                            echo "Tanggal Pinjam: " . date('d/m/Y', strtotime($book['borrow_date'])) . "<br>";
                            echo "Tanggal Kembali: " . date('d/m/Y', strtotime($book['return_date'])) . "</small>";
                        } else {
                            echo "<span class='text-success'>Tersedia</span>";
                        }
                        echo "</td>";
                        
                        // Tombol aksi
                        echo "<td>";
                        if ($book['borrow_status'] != 'borrowed') {
                            echo "<a href='/borrow' class='btn btn-primary btn-sm'>Pinjam</a>";
                        } else {
                            if ($book['borrower'] == $_SESSION['username']) {
                                echo "<a href='/borrow' class='btn btn-warning btn-sm'>Kembalikan</a>";
                            } else {
                                echo "<button class='btn btn-secondary btn-sm' disabled>Tidak Tersedia</button>";
                            }
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="/membership" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
