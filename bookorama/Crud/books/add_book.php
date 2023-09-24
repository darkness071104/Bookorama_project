<?php
// Sertakan file koneksi database
require_once('../lib/db_login.php');

// Sertakan header
include('../header.php');

// Inisialisasi pesan error
$errorMessages = array(
    'isbn' => '',
    'author' => '',
    'title' => '',
    'price' => '',
    'category' => ''
);

// Inisialisasi data buku (kosong)
$bookData = array(
    'isbn' => '',
    'author' => '',
    'title' => '',
    'price' => '',
    'category' => ''
);

// Periksa apakah form telah dikirimkan
if (isset($_POST['submit'])) {
    $isValid = true;

    // Simpan data yang sudah dimasukkan oleh pengguna ke dalam variabel
    $bookData['isbn'] = mysqli_real_escape_string($db, $_POST['isbn']);
    $bookData['author'] = mysqli_real_escape_string($db, $_POST['author']);
    $bookData['title'] = mysqli_real_escape_string($db, $_POST['title']);
    $bookData['price'] = mysqli_real_escape_string($db, $_POST['price']);
    $bookData['category'] = $_POST['category'] ?? '';

    // Validasi ISBN
    if (empty($bookData['isbn'])) {
        $errorMessages['isbn'] = 'ISBN is required';
        $isValid = false;
    } else {
        // Periksa apakah ISBN sudah ada di database
        $query_check_isbn = "SELECT isbn FROM books WHERE isbn = '{$bookData['isbn']}'";
        $result_check_isbn = $db->query($query_check_isbn);

        if ($result_check_isbn->num_rows > 0) {
            $errorMessages['isbn'] = 'ISBN already exists';
            $isValid = false;
        }
    }

    // Validasi Author
    if (empty($bookData['author'])) {
        $errorMessages['author'] = 'Author is required';
        $isValid = false;
    }

    // Validasi Title
    if (empty($bookData['title'])) {
        $errorMessages['title'] = 'Title is required';
        $isValid = false;
    }

    // Validasi Price
    if (empty($bookData['price'])) {
        $errorMessages['price'] = 'Price is required';
        $isValid = false;
    }

    // Validasi Category
    if (empty($bookData['category'])) {
        $errorMessages['category'] = 'Category is required';
        $isValid = false;
    }

    // Jika data valid, tambahkan buku baru ke database
    if ($isValid) {
        $query = "INSERT INTO books (isbn, title, categoryid, author, price) 
                  VALUES ('{$bookData['isbn']}', '{$bookData['title']}', (SELECT categoryid FROM categories WHERE name = '{$bookData['category']}'), '{$bookData['author']}', '{$bookData['price']}')";

        $result = $db->query($query);
        if (!$result) {
            die('Could not query the database: <br/>' . $db->error . '<br>Query:' . $query);
        } else {
            $db->close();
            header('Location: view_books.php');
        }
    }
}
?>

<div class="card mt-5">
    <div class="card-header">Tambah Buku Baru</div>
    <div class="card-body">
        <form action="add_book.php" method="POST" autocomplete="on">
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $bookData['isbn'] ?>">
                <?php if (!empty($errorMessages['isbn'])) : ?>
                    <div class="alert alert-danger"><?= $errorMessages['isbn'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $bookData['title'] ?>">
                <?php if (!empty($errorMessages['title'])) : ?>
                    <div class="alert alert-danger"><?= $errorMessages['title'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select name="category" id="category" class="form-control">
                    <option value="" selected disabled>--Select a Category--</option>
                    <?php
                    // Query untuk mengambil daftar kategori
                    $category_query = 'SELECT name FROM categories';
                    $category_result = $db->query($category_query);

                    if ($category_result) {
                        while ($category_row = $category_result->fetch_assoc()) {
                            $categoryName = $category_row['name'];
                            $selected = ($categoryName == $bookData['category']) ? 'selected' : '';
                            echo "<option value=\"$categoryName\" $selected>$categoryName</option>";
                        }
                    }
                    ?>
                </select>
                <?php if (!empty($errorMessages['category'])) : ?>
                    <div class="alert alert-danger"><?= $errorMessages['category'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $bookData['author'] ?>">
                <?php if (!empty($errorMessages['author'])) : ?>
                    <div class="alert alert-danger"><?= $errorMessages['author'] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= $bookData['price'] ?>">
                <?php if (!empty($errorMessages['price'])) : ?>
                    <div class="alert alert-danger"><?= $errorMessages['price'] ?></div>
                <?php endif; ?>
            </div>
            <br>
            <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>
            <a href="view_books.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php
// Sertakan footer
include('../footer.php');
?>
