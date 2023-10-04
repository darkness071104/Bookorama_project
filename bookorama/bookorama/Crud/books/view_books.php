<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: ../login.php');
}
?>
<?php include('../header.php'); ?>
<?php require_once('../lib/db_login.php'); ?>

<ul class="nav nav-tabs mt-1">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="view_books.php">Data Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="span_view_books.php">Span Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="recap_book.php">Show Graphics</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../order_data.php">Data Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="../show_cart.php">Shopping Cart</a>
  </li>
</ul>

<div class="card">
    <div class="card-header">Books Data</div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col mb-4">
                <div>
                    <a class="btn btn-outline-primary" href="add_book.php">+ Add Book</a>
                    <a class="btn btn-primary" href="filter_books.php">Filter</a>
                </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4 text-right">
                <form method="GET" action="search_books.php">
                    <div class="input-group">
                        <input type="text" class="form-control" name="query" placeholder="Search by Title, Author, ISBN, etc." required>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped">
            <tr>
                <th style="max-width: 100px;">ISBN</th>
                <th style="max-width: 300px;">Title</th>
                <th style="max-width: 100px;">Category</th>
                <th style="max-width: 200px;">Author</th>
                <th style="max-width: 100px;">Price</th>
                <th>Action</th>
            </tr>
            <?php
            // TODO: Tuliskan dan eksekusi query untuk menampilkan data buku di sini
            $query = 'SELECT 
                            books.isbn, 
                            books.title, 
                            categories.name AS category_name, 
                            books.author, 
                            books.price 
                        FROM books 
                        INNER JOIN categories ON books.categoryid = categories.categoryid
                        ORDER BY books.isbn
                    ';
            $result = $db->query($query);
            if (!$result) {
                die('Could not query the database: <br/>' . $db->error . '<br>Query:' . $query);
            }

            // Fetch and display the results
            $i = 1;
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td style="max-width: 100px;">' . $row->isbn . '</td>';
                echo '<td style="max-width: 300px;"><a href="detail_books.php?isbn=' . $row->isbn . '">' . $row->title . '</a></td>';
                echo '<td style="max-width: 100px;">' . $row->category_name . '</td>';
                echo '<td style="max-width: 200px;">' . $row->author . '</td>';
                echo '<td style="max-width: 100px;">$' . $row->price . '</td>';
                echo '<td>
                            <a class="btn btn-warning btn-sm" href="edit_book.php?id=' . $row->isbn . '">Edit</a>
                            <a class="btn btn-danger btn-sm" href="delete_book.php?id=' . $row->isbn . '">Delete</a>
                            <a class="btn btn-success btn-sm" href="../show_cart.php?isbn=' . $row->isbn . '">+ Add to Cart</a>
                      </td>';
                echo '</tr>';
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = ' . $result->num_rows;

            $result->free();
            $db->close();
            ?>
        </table>
    </div>
</div>
<?php include('../footer.php'); ?>
