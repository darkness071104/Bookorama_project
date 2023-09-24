<?php
// Sertakan file koneksi database
require_once('../lib/db_login.php');

// Sertakan header
include('../header.php');

// Periksa jika filter kategori diberikan
if (isset($_GET['category_id']) && $_GET['category_id'] !== "") {
    // Bersihkan masukan filter kategori untuk mencegah serangan SQL injection
    $category_id = mysqli_real_escape_string($db, $_GET['category_id']);

    // Bangun query SQL untuk penyaringan berdasarkan kategori
    $query = "SELECT 
                books.isbn, 
                books.title, 
                categories.name AS category_name, 
                books.author, 
                books.price 
              FROM books 
              INNER JOIN categories ON books.categoryid = categories.categoryid
              WHERE categories.categoryid = '$category_id'
              ORDER BY books.isbn";

    $result = $db->query($query);

    if (!$result) {
        die('Tidak dapat mengambil data dari database: <br/>' . $db->error);
    }
} else {
    // Jika filter kategori tidak diberikan, tampilkan semua data buku
    $query = "SELECT 
                books.isbn, 
                books.title, 
                categories.name AS category_name, 
                books.author, 
                books.price 
              FROM books 
              INNER JOIN categories ON books.categoryid = categories.categoryid
              ORDER BY books.isbn";

    $result = $db->query($query);
}

// Tampilkan hasil filter
?>

<div class="card mt-5">
    <div class="card-header">Filter Results</div>
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
        <div class="mt-3">
            <a class="btn btn-secondary" href="view_books.php">Kembali ke Tabel Buku</a>
        </div>
    </div>
</div>

<?php
// Sertakan footer
include('../footer.php');
?>
