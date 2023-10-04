<?php
// Include header and database connection
include('../header.php');
require_once('../lib/db_login.php');

// Initialize variables
$categoryFilter = [];
$minPrice = '';
$maxPrice = '';
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve filter values from the form
    $categoryFilter = isset($_POST['category']) ? $_POST['category'] : [];
    $minPrice = $_POST['min_price'];
    $maxPrice = $_POST['max_price'];

    // Build the SQL query based on filter values
    $query = 'SELECT 
                books.isbn, 
                books.title, 
                categories.name AS category_name, 
                books.author, 
                books.price 
              FROM books 
              INNER JOIN categories ON books.categoryid = categories.categoryid
              WHERE 1 ';

    if (!empty($categoryFilter)) {
        // Handle multiple selected categories
        $categoryFilterString = implode("', '", $categoryFilter);
        $query .= "AND categories.name IN ('$categoryFilterString') ";
    }

    if (!empty($minPrice)) {
        $query .= "AND books.price >= $minPrice ";
    }

    if (!empty($maxPrice)) {
        $query .= "AND books.price <= $maxPrice ";
    }

    $query .= 'ORDER BY books.isbn';

    // Execute the query
    $result = $db->query($query);
    if (!$result) {
        die('Could not query the database: <br/>' . $db->error . '<br>Query:' . $query);
    }
} else {
    // The form has not been submitted yet
    $result = null;
}
?>

<div class="card mt-5">
    <div class="card-header">Filter Books</div>
    <div class="card-body">
        <form method="POST" action="filter_books.php">
            <div class="form-group">
                <label>Category:</label>
                <?php
                $categoryQuery = 'SELECT * FROM categories';
                $categoryResult = $db->query($categoryQuery);
                if ($categoryResult) {
                    while ($category = $categoryResult->fetch_assoc()) {
                        $checked = in_array($category['name'], $categoryFilter) ? 'checked' : '';
                        echo '<div class="form-check">';
                        echo '<input type="checkbox" class="form-check-input" name="category[]" value="' . $category['name'] . '" ' . $checked . '>';
                        echo '<label class="form-check-label">' . $category['name'] . '</label>';
                        echo '</div>';
                    }
                    $categoryResult->free();
                }
                ?>
            </div>
            <div class="form-group">
                <label for="min_price">Minimum Price:</label>
                <input type="number" class="form-control" name="min_price" value="<?php echo $minPrice; ?>">
            </div>
            <div class="form-group">
                <label for="max_price">Maximum Price:</label>
                <input type="number" class="form-control" name="max_price" value="<?php echo $maxPrice; ?>">
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a class="btn btn-secondary" href="view_books.php">Kembali ke Tabel Buku</a>
            </div>
        </form>

        <?php
        if ($result) {
            if ($result->num_rows > 0) {
                echo '<table class="table table-striped">
                        <tr>
                            <th style="max-width: 100px;">ISBN</th>
                            <th style="max-width: 300px;">Title</th>
                            <th style="max-width: 100px;">Category</th>
                            <th style="max-width: 200px;">Author</th>
                            <th style="max-width: 100px;">Price</th>
                        </tr>';

                while ($row = $result->fetch_object()) {
                    echo '<tr>';
                    echo '<td style="max-width: 100px;">' . $row->isbn . '</td>';
                    echo '<td style="max-width: 300px;">' . $row->title . '</td>';
                    echo '<td style="max-width: 100px;">' . $row->category_name . '</td>';
                    echo '<td style="max-width: 200px;">' . $row->author . '</td>';
                    echo '<td style="max-width: 100px;">$' . $row->price . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>No books found matching your filter criteria.</p>';
            }

            $result->free();
        }
        ?>

    </div>
</div>

<?php
// Include footer and close database connection
include('../footer.php');
$db->close();
?>

