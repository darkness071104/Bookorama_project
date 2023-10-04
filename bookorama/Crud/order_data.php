<?php
session_start();

include('./header.php');
require_once('./lib/db_login.php');
$sum_qty = 0; // Inisialisasi total item di shopping cart
$sum_price = 0; // Inisialisasi total price di shopping cart
$order_number = 0; // Inisialisasi nomor order

?>

<ul class="nav nav-tabs mt-1">
  <li class="nav-item">
    <a class="nav-link" href="./books/view_books.php">Data Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./books/span_view_books.php">Span Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  href="./books/recap_book.php">Show Graphics</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="order_data.php">Data Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="show_cart.php">Shopping Cart</a>
  </li>
</ul>

<div class="card">
    <div class="card-header">Order Books</div>
    <div class="card-body">
        <br>
        <form method="POST">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <button type="submit">Filter</button>
        </form>
        <br>

        <?php
        // Mengambil data dari tabel orders
        $query = "SELECT o.*, b.title, b.isbn, b.author, b.price, oi.quantity
                  FROM orders o
                  JOIN order_items oi ON o.orderid = oi.orderid
                  JOIN books b ON oi.isbn = b.isbn";
        $result = $db->query($query);
        if (!$result) {
            die("Could not query the database: <br>" . $db->error . "<br>Query: " . $query);
        }

        if ($result->num_rows == 0) {
            echo '<p>No order!</p>';
        } else {
            echo '<table class="table table-striped">';
            echo '<tr>';
            echo '<th>No Order</th>';
            echo '<th>Date</th>';
            echo '<th>Detail Item</th>';
            echo '<th>Qty</th>';
            echo '<th>Price</th>';
            echo '<th>Total</th>';
            echo '</tr>';

            while ($row = $result->fetch_object()) {
                $order_date = $row->date;
                $order_number++;
                echo '<tr>';
                echo '<td>' . $order_number . '</td>';
                echo '<td>' . $order_date . '</td>';
                echo '<td>';
                echo '<p>ISBN: ' . $row->isbn . '</p>';
                echo '<p>Title: ' . $row->title . '</p>';
                echo '<p>Author: ' . $row->author . '</p>';
                echo '</td>';
                echo '<td>' . $row->quantity . '</td>';
                $price = $row->price;
                echo '<td>$ ' . $price . '</td>';
                $total_price = $row->quantity * $price;
                echo '<td>$ ' . $total_price . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }
        ?>
    </div>
</div>
