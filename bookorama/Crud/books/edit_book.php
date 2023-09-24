<?php
// Sertakan file koneksi database
require_once('../lib/db_login.php');

// Sertakan header
include('../header.php');

// Periksa apakah parameter 'id' diberikan
if (isset($_GET['id'])) {
    // Bersihkan masukan ISBN
    $isbn = mysqli_real_escape_string($db, $_GET['id']);

    // Query untuk mengambil informasi buku berdasarkan ISBN
    $query = "SELECT * FROM books WHERE isbn = '$isbn'";
    $result = $db->query($query);

    if (!$result) {
        die('Tidak dapat mengambil data dari database: <br/>' . $db->error);
    }

    if ($result->num_rows === 1) {
        // Ambil data buku
        $row = $result->fetch_object();
        $isbn = $row->isbn;
        $title = $row->title;
        $category_id = $row->categoryid;
        $author = $row->author;
        $price = $row->price;

        // Periksa apakah form telah dikirimkan untuk menyimpan perubahan
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Ambil dan bersihkan data form
            $newTitle = mysqli_real_escape_string($db, $_POST['title']);
            $newCategoryId = mysqli_real_escape_string($db, $_POST['category_id']);
            $newAuthor = mysqli_real_escape_string($db, $_POST['author']);
            $newPrice = mysqli_real_escape_string($db, $_POST['price']);

            // Query untuk melakukan update data buku
            $updateQuery = "UPDATE books SET 
                            title = '$newTitle', 
                            categoryid = '$newCategoryId', 
                            author = '$newAuthor', 
                            price = '$newPrice' 
                            WHERE isbn = '$isbn'";

            if ($db->query($updateQuery) === TRUE) {
                echo '<div class="alert alert-success">Data buku berhasil diperbarui.</div>';
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

<div class="card mt-5">
    <div class="card-header">Edit Buku</div>
    <div class="card-body">
        <form method="POST" action="edit_book.php?id=<?php echo $isbn; ?>">
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" name="isbn" value="<?php echo $isbn; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Judul</label>
                <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select class="form-select" name="category_id" required>
                    <?php
                    // Query untuk mengambil daftar kategori
                    $category_query = 'SELECT categoryid, name FROM categories';
                    $category_result = $db->query($category_query);

                    if ($category_result) {
                        while ($category_row = $category_result->fetch_assoc()) {
                            $selected = ($category_row['categoryid'] == $category_id) ? 'selected' : '';
                            echo '<option value="' . $category_row['categoryid'] . '" ' . $selected . '>' . $category_row['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Pengarang</label>
                <input type="text" class="form-control" name="author" value="<?php echo $author; ?>" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga</label>
                <input type="text" class="form-control" name="price" value="<?php echo $price; ?>" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a class="btn btn-secondary" href="view_books.php">Kembali ke Tabel Buku</a>
            </div>

        </form>
    </div>
</div>

<?php
// Sertakan footer
include('../footer.php');
?>


