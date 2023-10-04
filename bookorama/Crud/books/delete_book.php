<?php
// Sertakan file koneksi database
require_once('../lib/db_login.php');

// Include header.php
include('../header.php');

// Periksa apakah parameter 'id' diberikan
if (isset($_GET['id'])) {
    // Bersihkan masukan ISBN
    $isbn = mysqli_real_escape_string($db, $_GET['id']);

    // Query untuk mengambil informasi buku berdasarkan ISBN
    $query = "SELECT books.*, categories.name AS category_name FROM books
              INNER JOIN categories ON books.categoryid = categories.categoryid
              WHERE isbn = '$isbn'";
    $result = $db->query($query);

    if (!$result) {
        die('Tidak dapat mengambil data dari database: <br/>' . $db->error);
    }

    if ($result->num_rows === 1) {
        // Ambil data buku yang ingin dihapus
        $row = $result->fetch_object();
        $title = $row->title;

        // Periksa apakah form dikirimkan untuk menghapus buku
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Query untuk menghapus buku berdasarkan ISBN
            $deleteQuery = "DELETE FROM books WHERE isbn = '$isbn'";

            if ($db->query($deleteQuery) === TRUE) {
                // Jika penghapusan berhasil, arahkan kembali ke halaman view_books
                header("Location: view_books.php");
                exit();
            } else {
                echo '<div class="alert alert-danger">Error: ' . $db->error . '</div>';
            }
        }
    } else {
        echo '<div class="alert alert-warning">Buku dengan ISBN tersebut tidak ditemukan.</div>';
    }
} else {
    echo '<div class="alert alert-danger">ISBN buku tidak diberikan.</div>';
}
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>Konfirmasi Hapus Buku</h2>
        </div>
        <div class="card-body">
            <p>Anda akan menghapus buku dengan judul berikut:</p>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="max-width: 100px;">ISBN</th>
                        <th style="max-width: 300px;">Title</th>
                        <th style="max-width: 100px;">Category</th>
                        <th style="max-width: 200px;">Author</th>
                        <th style="max-width: 100px;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menampilkan data buku yang ingin dihapus
                    echo '<tr>';
                    echo '<td style="max-width: 100px;">' . $row->isbn . '</td>';
                    echo '<td style="max-width: 300px;">' . $row->title . '</td>';
                    echo '<td style="max-width: 100px;">' . $row->category_name . '</td>';
                    echo '<td style="max-width: 200px;">' . $row->author . '</td>';
                    echo '<td style="max-width: 100px;">$' . $row->price . '</td>';
                    echo '</tr>';
                    ?>
                </tbody>
            </table>
            <p>Apakah Anda yakin ingin menghapus buku ini?</p>
            <form method="POST">
                <button type="submit" class="btn btn-danger">Hapus</button>
                <a class="btn btn-secondary" href="view_books.php">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php
// Include footer.php
include('../footer.php');
?>
