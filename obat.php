<?php
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $ket_obat = $_POST['ket_obat'];
    $harga = $_POST['harga'];

    if (!isset($_GET['id'])) {
        $query = "INSERT INTO obat (nama, ket_obat, harga) VALUES ('$nama', '$ket_obat', '$harga')";
    } else {
        $query = "UPDATE `obat` SET `nama` = '$nama', `ket_obat` = '$ket_obat',  `harga` = '$harga' WHERE id='" . $_GET['id'] . "'";
    }
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        header("Location: http://localhost/poliklinik/index.php?page=obat");
        exit;
    }
}

if (isset($_GET['aksi'])) {
    $query = "DELETE FROM `obat` WHERE id='" . $_GET['id'] . "'";
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        header("Location: http://localhost/poliklinik/index.php?page=obat");
        exit;
    }
}
?>

<div class="container">
    <div class="form-obat">
        <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php
            $nama = '';
            $ket_obat = '';
            $harga = '';
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT * FROM obat WHERE id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $nama = $row['nama'];
                    $ket_obat = $row['ket_obat'];
                    $harga = $row['harga'];
                }
            ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
            }
            ?>
            <div class="col mt-3">
                <div class="form-input mt-3">
                    <label for="nama" class="fw-bold">
                        Nama
                    </label>
                    <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?php echo $nama ?>">
                </div>
                <div class="form-input mt-3">
                    <label for="ket_obat" class="fw-bold">
                        Keterangan Obat
                    </label>
                    <input type="text" class="form-control" name="ket_obat" id="ket_obat" placeholder="Keterangan Obat" value="<?php echo $ket_obat ?>">
                </div>
                <div class="form-input mt-3">
                    <label for="harga" class="fw-bold">
                        Harga Obat
                    </label>
                    <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga Obat" value="<?php echo $harga ?>">
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-3 mt-3" name="simpan">Simpan</button>
            </div>
        </form>
        <br>
        <hr>
    </div>

    <div class="table-dokter mt-3">
        <table class="table table-responsive">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Keterangan Obat</th>
                    <th scope="col">Harga</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($mysqli, "SELECT * FROM obat");
                $no = 1;
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama'] ?></td>
                        <td><?php echo $data['ket_obat'] ?></td>
                        <td><?php echo $data['harga'] ?></td>
                        <td>
                            <a class="btn btn-success rounded-pill px-3" href="index.php?page=obat&id=<?php echo $data['id'] ?>">Ubah</a>
                            <a class="btn btn-danger rounded-pill px-3" href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>