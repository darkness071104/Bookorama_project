<?php include('../header.php') ?>
<div class="card mt-5">
    <div class="card-header">Books Data</div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <a class="btn btn-outline-primary" href="add_book.php">+ Add Book </a>
            </div>
            <div class="col-md-4">
                <form method="GET" action="search_books.php">
                    <div class="input-group">
                        <input type="text" class="form-control" name="query" placeholder="Search by Title, Author, ISBN, etc." required>
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET" action="filter_books.php">
                    <div class="input-group">
                        <select class="form-select" name="category_id" aria-label="Filter by Category">
                            <option value="">All Categories</option>
                            <?php
                            // Include our login information
                            require_once('../lib/db_login.php');

                            // Query untuk mengambil daftar kategori dari basis data
                            $category_query = 'SELECT categoryid, name FROM categories';
                            $category_result = $db->query($category_query);
                            if (!$category_result) {
                                die('Could not query the database: <br/>' . $db->error);
                            }

                            while ($category_row = $category_result->fetch_assoc()) {
                                echo '<option value="' . $category_row['categoryid'] . '">' . $category_row['name'] . '</option>';
                            }

                            $category_result->free();
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
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
            // TODO 1: Tuliskan dan eksekusi query
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
                echo '<td style="max-width: 300px;">' . $row->title . '</td>';
                echo '<td style="max-width: 100px;">' . $row->category_name . '</td>';
                echo '<td style="max-width: 200px;">' . $row->author . '</td>';
                echo '<td style="max-width: 100px;">$' . $row->price . '</td>';
                echo '<td>
                            <a class="btn btn-warning btn-sm" href="edit_book.php?id=' . $row->isbn . '">Edit</a>
                            <a class="btn btn-danger btn-sm" href="delete_book.php?id=' . $row->isbn . '">Delete</a>
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
<?php include('../footer.php') ?>