<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'perpustakaan';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $isbn = $conn->real_escape_string($_GET['delete']);
    if (!empty($isbn)) {
        $delete = $conn->query("DELETE FROM books WHERE ISBN='$isbn'");
        if (!$delete) {
            die("Gagal menghapus buku: " . $conn->error);
        }
        header("Location: books.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>ISBN buku tidak valid.</div>";
    }
}

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
if ($limit <= 0) $limit = 10;

$query = "";
if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM books WHERE Book_Title LIKE '%$query%' OR Book_Author LIKE '%$query%' LIMIT $limit";
} else {
    $sql = "SELECT * FROM books LIMIT $limit";
}
$result = $conn->query($sql);

$all_columns = [
    'ISBN' => 'ISBN',
    'Book_Title' => 'Judul',
    'Book_Author' => 'Penulis',
    'Year_Of_Publication' => 'Tahun',
    'Publisher' => 'Penerbit'
];

$selected_columns = isset($_GET['cols']) ? $_GET['cols'] : array_keys($all_columns);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-4">
        <div class="container-fluid w-100">
            <div class="row w-100 align-items-center">
                <div class="col-4 d-flex align-items-center">
                    <a class="navbar-brand fw-bold" style="font-size:1.5rem;" href="index.php">Perpustakaan</a>
                </div>
                <div class="col-4 d-flex justify-content-center">
                    <div class="alert alert-info d-flex align-items-center gap-2 mb-0 py-1 px-3" role="alert" style="font-size:1rem;">
                        Dataset ini diambil dari 
                        <a href="https://www.kaggle.com/datasets/arashnic/book-recommendation-dataset?resource=download"
                           class="btn btn-primary btn-sm ms-2"
                           target="_blank"
                           rel="noopener noreferrer"
                           style="color:#fff;">
                            kaggle
                        </a>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <a href="index.php" class="btn btn-outline-primary">Beranda</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<div class="container mt-5">
    <h1 class="mb-4">Daftar Buku</h1>
    <a href="add_book.php" class="btn btn-success mb-3">Tambah Buku</a>
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <form action="books.php" method="GET" class="mb-2">
                <?php foreach ($all_columns as $col => $label): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="cols[]" value="<?php echo $col; ?>"
                            id="col_<?php echo $col; ?>" <?php if (in_array($col, $selected_columns)) echo 'checked'; ?>>
                        <label class="form-check-label" for="col_<?php echo $col; ?>"><?php echo $label; ?></label>
                    </div>
                <?php endforeach; ?>
                <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
                <button type="submit" class="btn btn-sm btn-primary ms-2">Terapkan</button>
            </form>
            <form action="books.php" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="query" placeholder="Cari buku..." value="<?php echo htmlspecialchars($query); ?>">
                    <?php foreach ($selected_columns as $col): ?>
                        <input type="hidden" name="cols[]" value="<?php echo $col; ?>">
                    <?php endforeach; ?>
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>
    <form method="GET" class="mb-3 d-flex align-items-center" action="books.php">
        <label for="limit" class="me-2 mb-0">Tampilkan</label>
        <input
            type="number"
            min="1"
            max="1000"
            name="limit"
            id="limit"
            class="form-control form-control-sm w-auto me-2"
            value="<?php echo isset($_GET['limit']) ? intval($_GET['limit']) : 10; ?>"
            style="width: 80px;"
            onchange="this.form.submit()"
        >
        <span>data</span>
        <input type="hidden" name="query" value="<?php echo htmlspecialchars($query); ?>">
        <?php foreach ($selected_columns as $col): ?>
            <input type="hidden" name="cols[]" value="<?php echo $col; ?>">
        <?php endforeach; ?>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <?php foreach ($selected_columns as $col): ?>
                    <th><?php echo $all_columns[$col]; ?></th>
                <?php endforeach; ?>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <?php foreach ($selected_columns as $col): ?>
                    <td><?php echo htmlspecialchars($row[$col]); ?></td>
                <?php endforeach; ?>
                <td>
                    <a href="edit_book.php?isbn=<?php echo urlencode($row['ISBN']); ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="books.php?delete=<?php echo urlencode($row['ISBN']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>