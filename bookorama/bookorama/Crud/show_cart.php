<?php
session_start();
error_reporting(0);

$id = $_GET['isbn']; // Mengambil ISBN dari URL
if ($id != '') {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Periksa apakah item sudah ada dalam keranjang
    if (isset($_SESSION['cart'][$id])) {
        // Item sudah ada, hapus item sebelum menambahkannya lagi
        unset($_SESSION['cart'][$id]);
    }

    // Tambahkan item ke keranjang dengan jumlah 1
    $_SESSION['cart'][$id] = 1;
}
?>

<?php include('./header.php') ?>

<?php
if (isset($_POST['submit'])) {
    require_once('./lib/db_login.php');
    $sum_qty = 0;
    $sum_price = 0;

    if (is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Mulai transaksi
        $db->begin_transaction();

        try {
            // Masukkan data pesanan ke dalam tabel "orders"
            // Anda perlu menyesuaikan "customerid" sesuai dengan cara Anda mengidentifikasi pelanggan
            $customerid = $_SESSION['customerid']; // Misalnya, Anda menyimpan customerid di sesi
            $date = date("Y-m-d");
            $insert_order_query = "INSERT INTO orders (customerid, amount, date) VALUES ('$customerid', 0, '$date')";
            $insert_order_result = $db->query($insert_order_query);

            if (!$insert_order_result) {
                throw new Exception("Could not query the database for inserting order.");
            }

            $orderid = $db->insert_id; // Ambil ID pesanan yang baru saja dibuat

            foreach ($_SESSION['cart'] as $isbn => $qty) {
                // Lakukan query untuk memasukkan data ke dalam tabel order_items
                $query = "INSERT INTO order_items (orderid, isbn, quantity) VALUES ('$orderid', '$isbn', '$qty');";
                $result = $db->query($query);

                if (!$result) {
                    throw new Exception("Could not query the database for inserting order items.");
                }

                // Hitung jumlah total item dan harga total
                $sum_qty += $qty;
                $query = "SELECT price FROM books WHERE isbn = '$isbn'";
                $result = $db->query($query);
                $row = $result->fetch_assoc();
                $sum_price += $row['price'] * $qty;
            }

            // Perbarui jumlah total pesanan
            $update_order_query = "UPDATE orders SET amount = '$sum_price' WHERE orderid = '$orderid'";
            $update_order_result = $db->query($update_order_query);

            if (!$update_order_result) {
                throw new Exception("Could not update order amount.");
            }

            // Commit transaksi
            $db->commit();

            // Setelah selesai memasukkan semua data, Anda dapat mengosongkan session cart.
            unset($_SESSION['cart']);

            // Arahkan pengguna ke halaman "success.php"
            header('Location: success.php');
            exit();
        } catch (Exception $e) {
            // Jika terjadi kesalahan, rollback transaksi
            $db->rollback();

            // Tampilkan pesan kesalahan
            echo "An error occurred: " . $e->getMessage();
        } finally {
            // Tutup koneksi database
            $db->close();
        }
    }
}
?>

<div class="card">
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link"  href="./books/view_books.php">Data Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./books/span_view_books.php">Span Table</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="./books/recap_book.php">Show Graphics</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="order_data.php">Data Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="show_cart.php">Shopping Cart</a>
  </li>
</ul>
    <div class="card-header">Keranjang</div>
    <div class="card-body">
        <br>
        <table class="table table-striped">
            <tr>
                <th>ISBN</th>
                <th>Author</th>
                <th>Title</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Price * Qty</th>
            </tr>
            <?php
            require_once('./lib/db_login.php');
            $sum_qty = 0;
            $sum_price = 0;

            if (is_array($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $id => $qty) {
                    // TODO 1: Tuliskan dan eksekusi query
                    $query = "SELECT * FROM books WHERE isbn='$id'";
                    $result = $db->query($query);
                    if(!$result){
                        die("Could not query the database: <br />".$db->error."<br>Query: ".$query);
                    }

                    while ($row = $result->fetch_object()) {
                        echo '<tr>';
                        echo '<td>' . $row->isbn . '</td>';
                        echo '<td>' . $row->author . '</td>';
                        echo '<td>' . $row->title . '</td>';
                        echo '<td>$' . $row->price . '</td>';
                        echo '<td>' . $qty . '</td>';
                        echo '<td>$' . $row->price * $qty . '</td>';
                        echo '</tr>';

                        $sum_qty = $sum_qty + $qty;
                        $sum_price = $sum_price + ($row->price * $qty);
                    }
                }
            }
            
            if (empty($_SESSION['cart'])) {
                echo '<tr><td colspan="6" align="center">There is no item in shopping cart</td></tr>';
            }
            ?>
        </table>
        Total items = <?php echo $sum_qty ?><br><br>
        <div class="d-flex justify-content-between">
            <div>
                <a class="btn btn-primary" href="./books/view_books.php">Continue Shopping</a>
                <a class="btn btn-danger" href="delete_cart.php">Empty Cart</a>
            </div>
            <div>
                <form method="POST">
                    <button class="btn btn-success" name="submit" type="submit">Checkout</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('./footer.php') ?>
