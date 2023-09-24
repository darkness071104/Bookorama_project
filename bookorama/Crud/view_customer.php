<?php include('./header.php') ?>
<div class="card mt-5">
    <div class="card-header">Customers Data</div>
    <div class="card-body">
        <a href="add_customer.php" class="btn btn-primary mb-4">+ Add Customer Data</a>
        <br>
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Action</th>
            </tr>
            <?php
            // TODO 1: Buat koneksi dengan database

            // TODO 2: Tulis dan eksekusi query ke database

            // TODO 3: Parsing data yang diterima dari database ke halaman HTML/PHP

            // TODO 4: Lakukan dealokasi variabel $result

            // TODO 5: Tutup koneksi dengan database
            ?>
    </div>
</div>
<?php include('./footer.php') ?>