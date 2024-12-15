<?php
if (!defined('SECURE_ACCESS')) {
   die('Direct access not permitted');
}

if(isset($_SESSION['is_login']) == false){
    header("location: /login");
}

?>
    <title>VISITOR | HONEY TREE LIBRARY</title>
    <link rel="stylesheet" href="/views/style.css">

    <header class="header">
        <div class="logo">
        <img src="views/img/pooh.png" alt="d" class="logo-img"> Honey Tree Library
        </div>
        <nav class="nav">
            <a href="#">Home</a>
            <a href="#">Koleksi Buku</a>
            <a href="#">Kategori Buku</a>
            <a href="#">Acara</a>
            <a href="#">Kontak</a>
        </nav>
    </header>
    
    <div class = "main-content">
    <h2 align=center>INI HALAMAN VISITOR</h2>
    </div>

    <footer class="footer">
        <p>© 2024 Perpustakaan Winnie the Pooh | Hundred Acre Wood</p>
    </footer>
</body>
</html>
