<?php
// Sertakan file koneksi database
require_once('../lib/db_login.php');

// Sertakan header
include('../header.php');

// Periksa jika ada permintaan pencarian yang dikirimkan
if (isset($_GET['query'])) {
    // Bersihkan masukan pencarian untuk mencegah serangan SQL injection
    $searchQuery = mysqli_real_escape_string($db, $_GET['query']);

    // Bangun query SQL untuk pencarian
    $query = "SELECT 
                books.isbn, 
                books.title, 
                categories.name AS category_name, 
                books.author, 
                books.price 
              FROM books 
              INNER JOIN categories ON books.categoryid = categories.categoryid
              WHERE books.title LIKE '%$searchQuery%' 
                OR books.author LIKE '%$searchQuery%'
                OR books.isbn LIKE '%$searchQuery%'
              ORDER BY books.isbn";

    $result = $db->query($query);

    if (!$result) {
        die('Tidak dapat mengambil data dari database: <br/>' . $db->error);
    }
} else {
    // Jika tidak ada masukan pencarian yang diberikan, tampilkan pesan
    echo '<div class="alert alert-info">Silakan masukkan kueri pencarian.</div>';
}

// Tampilkan hasil pencarian
?>

<div class="card mt-5">
    <div class="card-header">Search Result</div>
    <div class="card-body">
        <table class="table table-striped">
            <tr>
                <th style="max-width: 100px;">ISBN</th>
                <th style="max-width: 300px;">Judul</th>
                <th style="max-width: 100px;">Kategori</th>
                <th style="max-width: 200px;">Pengarang</th>
                <th style="max-width: 100px;">Harga</th>
                <th>Aksi</th>
            </tr>
            <?php
            if (isset($result) && $result->num_rows > 0) {
                while ($row = $result->fetch_object()) {
                    echo '<tr>';
                    echo '<td style="max-width: 100px;">' . $row->isbn . '</td>';
                    echo '<td style="max-width: 300px;">' . $row->title . '</td>';
                    echo '<td style="max-width: 100px;">' . $row->category_name . '</td>';
                    echo '<td style="max-width: 200px;">' . $row->author . '</td>';
                    echo '<td style="max-width: 100px;">$' . $row->price . '</td>';
                    echo '<td>
                                <a class="btn btn-warning btn-sm" href="edit_book.php?id=' . $row->isbn . '">Edit</a>
                                <a class="btn btn-danger btn-sm" href="delete_book.php?id=' . $row->isbn . '">Hapus</a>
                          </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">Tidak ada hasil ditemukan.</td></tr>';
            }
            ?>
        </table>

        <!-- Tombol Kembali ke Halaman View Books -->
        <div class="mt-3">
            <a class="btn btn-secondary" href="view_books.php">Kembali ke Tabel Buku</a>
        </div>
    </div>
</div>

<?php
// Sertakan footer
include('../footer.php');
?>