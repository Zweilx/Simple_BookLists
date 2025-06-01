<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'perpustakaan';

$conn = new mysqli($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $conn->real_escape_string($_POST['ISBN']);
    $title = $conn->real_escape_string($_POST['Book_Title']);
    $author = $conn->real_escape_string($_POST['Book_Author']);
    $year = intval($_POST['Year_Of_Publication']);
    $publisher = $conn->real_escape_string($_POST['Publisher']);
    $img_s = $conn->real_escape_string($_POST['Image_URL_S']);
    $img_m = $conn->real_escape_string($_POST['Image_URL_M']);
    $img_l = $conn->real_escape_string($_POST['Image_URL_L']);

    $conn->query("INSERT INTO books (ISBN, Book_Title, Book_Author, Year_Of_Publication, Publisher, Image_URL_S, Image_URL_M, Image_URL_L) VALUES ('$isbn', '$title', '$author', $year, '$publisher', '$img_s', '$img_m', '$img_l')");
    header("Location: books.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Tambah Buku</h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="ISBN" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="Book_Title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="Book_Author" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="number" name="Year_Of_Publication" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <input type="text" name="Publisher" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL S</label>
            <input type="text" name="Image_URL_S" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL M</label>
            <input type="text" name="Image_URL_M" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Image URL L</label>
            <input type="text" name="Image_URL_L" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="books.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>