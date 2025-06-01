<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'perpustakaan';

$conn = new mysqli($host, $username, $password, $dbname);

$isbn = isset($_GET['isbn']) ? $conn->real_escape_string($_GET['isbn']) : '';
if (empty($isbn)) {
    die("ISBN tidak valid.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['Book_Title']);
    $author = $conn->real_escape_string($_POST['Book_Author']);
    $year = intval($_POST['Year_Of_Publication']);
    $publisher = $conn->real_escape_string($_POST['Publisher']);

    $update = $conn->query("UPDATE books SET Book_Title='$title', Book_Author='$author', Year_Of_Publication=$year, Publisher='$publisher' WHERE ISBN='$isbn'");
    if (!$update) {
        die("Gagal mengupdate buku: " . $conn->error);
    }
    header("Location: books.php");
    exit;
}

$result = $conn->query("SELECT * FROM books WHERE ISBN='$isbn'");
if (!$result || $result->num_rows == 0) {
    die("Buku tidak ditemukan.");
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Buku</h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input type="text" name="ISBN" class="form-control" value="<?php echo htmlspecialchars($row['ISBN']); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="Book_Title" class="form-control" value="<?php echo htmlspecialchars($row['Book_Title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="Book_Author" class="form-control" value="<?php echo htmlspecialchars($row['Book_Author']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input type="number" name="Year_Of_Publication" class="form-control" value="<?php echo htmlspecialchars($row['Year_Of_Publication']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <input type="text" name="Publisher" class="form-control" value="<?php echo htmlspecialchars($row['Publisher']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="books.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>