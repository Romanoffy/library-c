<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['is_login'])) {
    header("Location: /login");
    exit();
}

// Debug information
error_log("Loading borrow.php");
error_log("Session data: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Form Peminjaman Buku</h2>
        
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

        <form action="/borrow" method="POST">
            <div class="mb-3">
                <label for="book" class="form-label">Pilih Buku</label>
                <select class="form-select" name="book_id" id="book" required>
                    <option value="">Pilih buku yang akan dipinjam</option>
                    <?php
                    global $pdo;
                    $sql = "SELECT * FROM books WHERE status = 'available'";
                    $stmt = $pdo->query($sql);
                    while($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $book['id'] . "'>" . htmlspecialchars($book['title']) . " - " . htmlspecialchars($book['author']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="borrow_date" class="form-label">Tanggal Peminjaman</label>
                <input type="date" class="form-control" id="borrow_date" name="borrow_date" required 
                       min="<?= date('Y-m-d', strtotime('-30 days')) ?>" 
                       max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                       value="<?= date('Y-m-d') ?>">
                <small class="text-muted">Anda dapat memilih tanggal 30 hari ke belakang atau 30 hari ke depan</small>
            </div>

            <div class="mb-3">
                <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                <input type="date" class="form-control" id="return_date" name="return_date" required
                       min="<?= date('Y-m-d', strtotime('-30 days')) ?>"
                       max="<?= date('Y-m-d', strtotime('+3 months')) ?>">
                <small class="text-muted">Anda dapat memilih tanggal 30 hari ke belakang (untuk testing denda) atau maksimal 3 bulan ke depan</small>
            </div>

            <button type="submit" class="btn btn-primary">Pinjam Buku</button>
            <a href="/book" class="btn btn-secondary">Kembali</a>
        </form>
        <div class="container mt-5">
        <h2>Pengembalian Buku</h2>
        
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

        <?php if(isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning">
                <?= $_SESSION['warning']; ?>
                <?php unset($_SESSION['warning']); ?>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <h3>Daftar Buku yang Sedang Dipinjam</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Batas Pengembalian</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    global $pdo;
                    $user_id = $_SESSION['user_id'];
                    $sql = "SELECT b.title, br.id as borrowing_id, br.borrow_date, br.return_date, br.status,
                           DATEDIFF(CURRENT_DATE, br.return_date) as days_late
                           FROM borrowings br 
                           JOIN books b ON br.book_id = b.id 
                           WHERE br.user_id = ? AND br.status = 'borrowed'
                           ORDER BY br.borrow_date DESC";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$user_id]);
                    
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $is_late = strtotime($row['return_date']) < strtotime('today');
                        $days_late = max(0, $row['days_late']);
                        $denda = $days_late * 1000; // Rp 1.000 per hari
                        $status_class = $is_late ? 'text-danger' : 'text-success';
                        $status_text = $is_late ? 'Terlambat ' . $days_late . ' hari' : 'Masih dalam masa peminjaman';
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($row['borrow_date'])) . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($row['return_date'])) . "</td>";
                        echo "<td class='{$status_class}'>" . $status_text . "</td>";
                        echo "<td>";
                        if ($denda > 0) {
                            echo "<span class='text-danger'>Rp " . number_format($denda, 0, ',', '.') . "</span>";
                        } else {
                            echo "<span class='text-success'>-</span>";
                        }
                        echo "</td>";
                        echo "<td>
                                <form action='/return' method='POST' style='display:inline;'>
                                    <input type='hidden' name='borrowing_id' value='" . $row['borrowing_id'] . "'>
                                    <button type='submit' class='btn btn-warning btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin mengembalikan buku ini?" . 
                                    ($denda > 0 ? "\\nAnda akan dikenakan denda sebesar Rp " . number_format($denda, 0, ',', '.') : "") . "\")'>
                                        Kembalikan
                                    </button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

      
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi dan pengaturan tanggal
        const borrowDate = document.getElementById('borrow_date');
        const returnDate = document.getElementById('return_date');

        function updateReturnDateConstraints() {
            const selectedBorrowDate = new Date(borrowDate.value);
            
            // Set minimal tanggal pengembalian (30 hari sebelum peminjaman untuk testing)
            const minReturn = new Date(selectedBorrowDate);
            minReturn.setDate(minReturn.getDate() - 30);
            
            // Set maksimal tanggal pengembalian (3 bulan setelah peminjaman)
            const maxReturn = new Date(selectedBorrowDate);
            maxReturn.setMonth(maxReturn.getMonth() + 3);

            returnDate.min = minReturn.toISOString().split('T')[0];
            returnDate.max = maxReturn.toISOString().split('T')[0];

            // Reset tanggal pengembalian jika di luar range yang valid
            if (new Date(returnDate.value) < minReturn || new Date(returnDate.value) > maxReturn) {
                returnDate.value = '';
            }
        }

        // Update constraint saat tanggal peminjaman berubah
        borrowDate.addEventListener('change', updateReturnDateConstraints);

        // Set constraint awal
        updateReturnDateConstraints();

        // Validasi form sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const borrowDateVal = new Date(borrowDate.value);
            const returnDateVal = new Date(returnDate.value);

            if (!borrowDate.value || !returnDate.value) {
                e.preventDefault();
                alert('Silakan pilih tanggal peminjaman dan pengembalian');
                return;
            }

            const maxDate = new Date(borrowDateVal);
            maxDate.setMonth(maxDate.getMonth() + 3);

            if (returnDateVal > maxDate) {
                e.preventDefault();
                alert('Maksimal peminjaman adalah 3 bulan');
                return;
            }
        });
    </script>
</body>
</html> 