<?php
require_once('../lib/db_login.php');

$isbn = $_GET['isbn'];
$query = "SELECT b.isbn, b.author, b.title, b.price, c.name AS category_name 
          FROM books b
          INNER JOIN categories AS c ON b.categoryid = c.categoryid
          WHERE b.isbn='" . $isbn . "'";
$result = $db->query($query);
if (!$result) {
    die("Could not query the database: <br />" . $db->error);
} else {
    while ($row = $result->fetch_object()) {
        $isbn = $row->isbn;
        $author = $row->author;
        $title = $row->title;
        $price = $row->price;
        $category = $row->category_name;
    }
}

// Inisialisasi pesan notifikasi
$notification = '';

if (!isset($_POST['submit'])) {
} else {
    $valid = TRUE;
    $review = test_input($_POST['review']);
    if ($review == '') {
        $error_rev = 'Please write the review';
        $valid = FALSE;
    }

    if ($valid) {
        $insert = "INSERT INTO book_reviews (isbn, review) VALUES ('" . $isbn . "', '" . $review . "')";
        $insert_result = $db->query($insert);
        if (!$insert_result) {
            die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $insert);
        } else {
            // Set notifikasi jika berhasil menambahkan review
            $notification = 'Review added successfully';
        }
    }
}

// Handle review deletion
if (isset($_GET['delete_review'])) {
    $review_id = $_GET['delete_review'];
    // Perlu menambahkan tanda petik pada $review_id
    $delete_query = "DELETE FROM book_reviews WHERE isbn='" . $isbn . "' AND review='" . $review_id . "'";
    $delete_result = $db->query($delete_query);
    if (!$delete_result) {
        die("Could not delete the review: <br />" . $db->error);
    } else {
        // Set notifikasi jika berhasil menghapus review
        $notification = 'Review deleted successfully';
    }
}
?>

<?php include('../header.php') ?>

<br>
<div class="card mt-4">
    <div class="card-header">Detail Book</div>
    <div class="card-body">
        <!-- Tampilkan notifikasi -->
        <?php if (!empty($notification)): ?>
            <div class="alert alert-success"><?= $notification ?></div>
        <?php endif; ?>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?isbn=' . $isbn ?>" method="post" autocomplete="on">
            <!-- Form untuk menampilkan detail buku -->
            <div class="form-group mt-2">
                <label for="isbn">ISBN:</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?= $isbn; ?>" disabled>
                <div class="error">
                    <?php if (isset($error_isbn)) echo $error_isbn ?>
                </div>
            </div>
            <div class="form-group mt-2">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $author; ?>" disabled>
                <div class="error">
                    <?php if (isset($error_author)) echo $error_author ?>
                </div>
            </div>
            <div class="form-group mt-2">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $title; ?>" disabled>
                <div class="error">
                    <?php if (isset($error_title)) echo $error_title ?>
                </div>
            </div>
            <div class="form-group mt-2">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= $price; ?>" disabled>
                <div class="error">
                    <?php if (isset($error_price)) echo $error_price ?>
                </div>
            </div>
            <div class="form-group mt-2">
                <label for="category">Category:</label>
                <select name="category" id="category" class="form-control" disabled>
                    <option value="" selected disabled>--Select a Category--</option>
                    <?php
                    $query = 'SELECT name FROM categories';
                    $result = $db->query($query);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $categoryName = $row['name'];
                            $isSelected = ($category == $categoryName) ? 'selected' : '';

                            echo "<option value=\"$categoryName\" $isSelected>$categoryName</option>";
                        }

                        $result->free_result();
                    } else {
                        echo 'Error:' . $db->error;
                    }
                    ?>
                </select>
                <div class="error">
                    <?php if (isset($error_category)) echo $error_category ?>
                </div>
            </div>
            <br>
            <h5>Reviews <span class="badge badge-secondary">New</span></h5>
            <table class="table table-striped">
                <tr>
                    <th style="width: 20%">No.</th>
                    <th style="width: 50%">Review</th>
                    <th style="width: 15%"></th>
                </tr>
                <?php
                // Eksekusi query untuk mendapatkan review
                $getReviews = "SELECT review FROM book_reviews WHERE isbn='" . $isbn . "'";
                $reviews = $db->query($getReviews);
                if (!$reviews) {
                    die('Could not query the database: <br/>' . $db->error . '<br>Query:' . $getReviews);
                }

                $i = 1;
                while ($row = $reviews->fetch_object()) {
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $row->review . '</td>';
                    echo '<td class="action-column"><div class="action-buttons"><a href="detail_books.php?isbn=' . $isbn . '&delete_review=' . urlencode($row->review) . '" class="btn btn-danger">Delete Review</a></div></td>';
                    echo '</tr>';
                    $i++;
                }

                // Bebaskan hasil query
                $reviews->free_result();
                ?>
            </table>
        </form>
        
        <!-- Form untuk menambah review -->
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?isbn=' . $isbn ?>" method="post" autocomplete="on">
            <div class="form-group">
                <label for="review">Add Review:</label>
                <textarea class="form-control" id="review" name="review" rows="3"></textarea>
                <div class="error">
                    <?php if (isset($error_rev)) echo $error_rev ?>
                </div>
            </div>
            <div class = mt-2>
                <button type="submit" name="submit" class="btn btn-primary">+ Add Review</button>
                <a class="btn btn-secondary" href="view_books.php">Kembali ke Tabel Buku</a>
            </div>
        </form>
    </div>
</div>

<?php include('../footer.php') ?>
<?php
$db->close();
?>
