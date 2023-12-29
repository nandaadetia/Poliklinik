<?php
if (isset($_POST['simpan'])) {

    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    if (!isset($_GET['id'])) {
        $query = "INSERT INTO dokter (nama, alamat, no_hp) VALUES ('$nama', '$alamat', '$no_hp')";
    } else {
        $query = "UPDATE `dokter` SET `nama` = '$nama', `alamat` = '$alamat', `no_hp` = '$no_hp' WHERE id='" . $_GET['id'] . "'";
    }
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        header("Location: http://localhost/poliklinik/index.php?page=dokter");
        exit;
    }
}

if (isset($_GET['aksi'])) {
    $query = "DELETE FROM `dokter` WHERE id='" . $_GET['id'] . "'";
    $result = mysqli_query($mysqli, $query);
    if ($result) {
        header("Location: http://localhost/poliklinik/index.php?page=dokter");
        exit;
    }
}
?>

<div class="container">
    <div class="form-dokter">
        <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php
            $nama = '';
            $alamat = '';
            $no_hp = '';
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT * FROM dokter WHERE id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $nama = $row['nama'];
                    $alamat = $row['alamat'];
                    $no_hp = $row['no_hp'];
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
                    <label for="alamat" class="fw-bold">
                        Alamat
                    </label>
                    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" value="<?php echo $alamat ?>">
                </div>
                <div class="form-input mt-3">
                    <label for="no_hp" class="fw-bold">
                        No HP
                    </label>
                    <input type="text" class="form-control" name="no_hp" id="no_hp" placeholder="No HP" value="<?php echo $no_hp ?>">
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
                    <th scope="col">Alamat</th>
                    <th scope="col">No HP</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($mysqli, "SELECT * FROM dokter");
                $no = 1;
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama'] ?></td>
                        <td><?php echo $data['alamat'] ?></td>
                        <td><?php echo $data['no_hp'] ?></td>
                        <td>
                            <a class="btn btn-success rounded-pill px-3" href="index.php?page=dokter&id=<?php echo $data['id'] ?>">Ubah</a>
                            <a class="btn btn-danger rounded-pill px-3" href="index.php?page=dokter&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>