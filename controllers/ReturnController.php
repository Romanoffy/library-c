<?php
if(!defined('SECURE_ACCESS')) die('Direct access not permitted');

require_once 'Controller.php';
require_once 'config/database.php';

class ReturnController extends Controller
{
    const DENDA_PER_HARI = 1000; // Rp 1.000 per hari keterlambatan

    public static function index()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }
        
        return self::view('views/return.php');
    }

    public static function processReturn()
    {
        if(!isset($_SESSION['is_login'])) {
            $_SESSION['error'] = "Silakan login terlebih dahulu";
            header("Location: /login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /return");
            exit();
        }

        $borrowing_id = $_POST['borrowing_id'] ?? null;

        if (!$borrowing_id) {
            $_SESSION['error'] = "ID peminjaman tidak valid";
            header("Location: /return");
            exit();
        }

        try {
            global $pdo;
            
            // Ambil data peminjaman
            $stmt = $pdo->prepare("
                SELECT b.*, br.return_date, br.book_id 
                FROM borrowings br
                JOIN books b ON b.id = br.book_id
                WHERE br.id = ? AND br.status = 'borrowed'
            ");
            $stmt->execute([$borrowing_id]);
            $borrowing = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$borrowing) {
                $_SESSION['error'] = "Data peminjaman tidak ditemukan";
                header("Location: /return");
                exit();
            }

            // Hitung denda jika terlambat
            $return_date = new DateTime($borrowing['return_date']);
            $today = new DateTime();
            $denda = 0;

            if ($today > $return_date) {
                $diff = $today->diff($return_date);
                $denda = $diff->days * self::DENDA_PER_HARI;
            }

            // Mulai transaksi
            $pdo->beginTransaction();

            // Update status peminjaman
            $stmt = $pdo->prepare("
                UPDATE borrowings 
                SET status = 'returned', 
                    actual_return_date = CURRENT_DATE,
                    late_fee = ?
                WHERE id = ?
            ");
            $stmt->execute([$denda, $borrowing_id]);

            // Update status buku menjadi available
            $stmt = $pdo->prepare("UPDATE books SET status = 'available' WHERE id = ?");
            $stmt->execute([$borrowing['book_id']]);

            $pdo->commit();

            if ($denda > 0) {
                $_SESSION['warning'] = "Buku berhasil dikembalikan. Anda dikenakan denda keterlambatan sebesar Rp " . number_format($denda, 0, ',', '.');
            } else {
                $_SESSION['success'] = "Buku berhasil dikembalikan";
            }
            
            header("Location: /return");
            exit();

        } catch (PDOException $e) {
            if(isset($pdo)) $pdo->rollBack();
            error_log("Error in ReturnController::processReturn - " . $e->getMessage());
            $_SESSION['error'] = "Terjadi kesalahan saat memproses pengembalian";
            header("Location: /return");
            exit();
        }
    }
}

// Routing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ReturnController::processReturn();
} else {
    ReturnController::index();
} 