<?php
$number = 1;
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitterd');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library | Digiboard</title>
    
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="assets/vendor/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="assets/vendor/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" id="primaryColor" href="assets/css/blue-color.css">
    <link rel="stylesheet" id="rtlStyle" href="#">
</head>
<body>
<h1>Book</h1>
<form method="GET" class="d-flex justify-content-beetween align">
    <input type="text" class="form-control" id="search" placeholder="Search for..." name="find" required />
    <button class="btn btn-sm btn-primary"><i class="fa-solid fa-search"></i></button>
</form>
<div class="table table-responsive my-4">
    <table width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
            </tr>
        </thead>
        <tbody><?php foreach($data as $book) : ?>
            <tr>
                <th><?= $number++ ?></th>
                <th><?= $book->getTitle() ?></th>
                <th><?= $book->getAuthor() ?></th>
                <th><?= $book->getYear() ?></th>
                
            </tr>     
    </tbody>
    <?php endforeach ?>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
