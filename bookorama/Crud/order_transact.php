<!--File     : view_customer.php
Deskripsi  : menampilkan data customers
Nama Anggota Kelompok 9:
1) Fauzan Ramadhan Putra (24060121140140)
2) Muhammad Haikal Ali (24060121130064)
3) Oktaviana Sadama Nur Azizah	(24060121130060)
4) Della Shanda Anggrivani	(24060121120024)
5) Habibah Mylah Dalilah	(24060121120028)
-->
<?php include('header.php') ?>
<br>
<div class="card">
<div class="card-header">Customers Data</div>
<div class="card-body">
<br>
<p>Contoh SQL Transaction</p>

<?php
// Include our login information
require_once('./lib/db_login.php');

// Start transaction
$db->autocommit(FALSE);
$db->begin_transaction();
$query_ok = TRUE;

// Cek query
$customerid = 1;
$amount = 300;
$date = '2022-06-01';
$orderid = 1004;
$books = array('0-672-31697-8' => 1, 
    '0-672-31769-9' => 2, 
    '0-672-31509-2' => 3);

$query1 = "INSERT INTO orders (orderid, customerid, amount, date) VALUES (?, ?, ?, ?)";
$stmt1 = $db->prepare($query1);
$stmt1->bind_param("iids", $orderid, $customerid, $amount, $date);
if (!$stmt1->execute()) {
    $query_ok = FALSE;
    die("Could not query the database: <br />" . $stmt1->error);
}

$query2 = "INSERT INTO order_items (orderid, isbn, quantity) VALUES (?, ?, ?)";
$stmt2 = $db->prepare($query2);
$stmt2->bind_param("isi", $orderid, $isbn, $qty);

foreach ($books as $isbn => $qty) {
    if (!$stmt2->execute()) {
        $query_ok = FALSE;
        die("Could not query the database: <br />" . $stmt2->error);
    }
}

// Commit the transaction
if ($query_ok) {
    $db->commit();
    echo "Eksekusi berhasil!!!";
} else {
    $db->rollback();
    echo "Eksekusi Gagal!!!";
}

// Close connection
$stmt1->close();
$stmt2->close();
$db->close();
?>
</div>
</div>
<?php include('footer.php') ?>
