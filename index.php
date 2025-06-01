<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'perpustakaan';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "";
if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM books WHERE Book_Title LIKE '%$query%' OR Book_Author LIKE '%$query%'";
} else {
    $sql = "SELECT * FROM books";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .big-header {
            font-size: 2rem;
            font-weight: bold;
            padding: 2rem 0 1rem 0;
        }
        .book-cover {
            height: 250px;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-4">
            <div class="container-fluid w-100">
                <div class="row w-100 align-items-center">
                    <div class="col-4 d-flex align-items-center">
                        <a class="navbar-brand fw-bold" style="font-size:1.5rem;" href="#">Perpustakaan</a>
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
                        <a href="books.php" class="btn btn-outline-primary">Kelola Buku</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
        <h1 class="text-center mb-4 big-header">Perpustakaan</h1>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="index.php" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="query" placeholder="Cari buku..." value="<?php echo htmlspecialchars($query); ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card h-100 p-0" style="background:#f8f9fa;">
                            <img 
                                src="<?php echo htmlspecialchars($row['Image_URL_L']); ?>" 
                                class="card-img-top"
                                style="height:100%; min-height:250px; aspect-ratio:2/3; object-fit:contain; background:#f8f9fa;"
                                alt="Cover"
                            >
                            <div class="card-body">
                                <h5 class="card-title mb-2" style="font-size:1.1rem;"><?php echo htmlspecialchars($row['Book_Title']); ?></h5>
                                <p class="mb-1"><strong>ISBN:</strong> <?php echo htmlspecialchars($row['ISBN']); ?></p>
                                <p class="mb-1"><strong>Penulis:</strong> <?php echo htmlspecialchars($row['Book_Author']); ?></p>
                                <p class="mb-1"><strong>Tahun:</strong> <?php echo htmlspecialchars($row['Year_Of_Publication']); ?></p>
                                <p class="mb-1"><strong>Penerbit:</strong> <?php echo htmlspecialchars($row['Publisher']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Tidak ada buku ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>