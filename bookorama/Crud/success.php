<?php include('./header.php') ?>
<?php

session_start();

// TODO 2: Hapus session
if(isset($_SESSION['cart'])){
    unset($_SESSION['cart']);
}
?>

<div class="card mt-4">
    <div class="card-header">Success</div>
    <div class="card-body">
        <h1 class="text-success">Success</h1>
        <p>Terima kasih telah berbelanja di toko kami.</p>
        <p><a href="./books/view_books.php" class="btn btn-primary">Kembali ke halaman utama</a></p>
    </div>
</div>

<?php include('./footer.php') ?>