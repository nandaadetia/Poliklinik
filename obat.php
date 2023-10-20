<?php
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $ktgr_obat = $_POST['ktgr_obat'];


    if (!isset($_GET['id'])) {
        $query = "INSERT INTO obat (nama, ktgr_obat) VALUES ('$nama', '$ktgr_obat')";
    } else {
        $query = "UPDATE `obat` SET `nama` = '$nama', `ktgr_obat` = '$ktgr_obat' WHERE id='" . $_GET['id'] . "'";
    }
    $result = mysqli_query($mysqli, $query);
}

if (isset($_GET['aksi'])) {
    $query = "DELETE FROM `obat` WHERE id='" . $_GET['id'] . "'";
    $result = mysqli_query($mysqli, $query);
}
?>

<div class="container">
    <div class="form-obat">
        <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php
            $nama = '';
            $ktgr_obat = '';
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT * FROM obat WHERE id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $nama = $row['nama'];
                    $ktgr_obat = $row['ktgr_obat'];
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
                    <label for="ktgr_obat" class="fw-bold">
                        Kategori Obat
                    </label>
                    <input type="text" class="form-control" name="ktgr_obat" id="ktgr_obat" placeholder="Kategori Obat" value="<?php echo $ktgr_obat ?>">
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
                    <th scope="col">Kategori Obat</th>
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
                        <td><?php echo $data['ktgr_obat'] ?></td>
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