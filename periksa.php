<?php
if (isset($_POST['simpan'])) {

    $id_pasien = $_POST['pasien'];
    $id_dokter = $_POST['dokter'];
    $id_obat = $_POST['obat'];
    $tgl_periksa = $_POST['tgl_periksa'];
    $catatan = $_POST['catatan'];
    
    if (!isset($_GET['id'])) {
        $query = "INSERT INTO periksa (id_pasien, id_dokter, id_obat,  tgl_periksa, catatan) VALUES ('$id_pasien', '$id_dokter', '$id_obat', '$tgl_periksa', '$catatan')";
    } else {
        $query = "UPDATE `periksa` SET `id_pasien` = '$id_pasien', `id_dokter` = '$id_dokter', `id_obat` = '$id_obat', `tgl_periksa` = '$tgl_periksa', `catatan` = '$catatan' WHERE id='" . $_GET['id'] . "'";
    }
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        header("Location: http://localhost/Bengkel%20Koding/poliklinik/index.php?page=periksa");
        exit;
    } else {
        echo "Terjadi kesalahan saat menambahkan data periksa: " . mysqli_error($mysqli);
    }
}

if (isset($_GET['aksi'])) {
    $query = "DELETE FROM `periksa` WHERE id='" . $_GET['id'] . "'";
    $result = mysqli_query($mysqli, $query);
    header("Location: http://localhost/Bengkel%20Koding/poliklinik/index.php?page=periksa");
}
?>

<div class="container">
    <div class="form-periksa">
        <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php
            $id_pasien2 = '';
            $id_dokter2 = '';
            $id_obat2 = '';
            if (isset($_GET['id'])) {
                $id_periksa = $_GET['id'];

                // Query ke database untuk mendapatkan data periksa berdasarkan ID
                $ambil_data_periksa = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id = '$id_periksa'");
                $data_periksa = mysqli_fetch_assoc($ambil_data_periksa);

                // Selanjutnya, Anda dapat menambahkan kode ini di setiap input untuk mengisi nilai-nilai form
                $id_pasien2 = $data_periksa['id_pasien'];
                $id_dokter2 = $data_periksa['id_dokter'];
                $id_obat2 = $data_periksa['id_obat'];
                $tgl_periksa2 = $data_periksa['tgl_periksa'];
                $catatan2 = $data_periksa['catatan'];
            ?>
                <input type="hidden" name="id" value="<?php echo $id_periksa ?>">
            <?php
            }
            ?>
            <div class="col mt-3">
                <div class="form-input mt-3">
                    <label for="pasien" class="fw-bold">Pasien</label>
                    <select class="form-control" name="pasien">
                        <option value="" disabled selected>Pilih Pasien</option>
                        <?php
                        $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
                        while ($data = mysqli_fetch_array($pasien)) {
                            
                                $selected = ($data['id'] == $id_pasien2) ? 'selected="selected"' : '';
                            ?>
                                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                        <?php
                            
                        }
                        ?>
                    </select>
                </div>
                <div class="form-input mt-3">
                    <label for="dokter" class="fw-bold">Dokter</label>
                    <select class="form-control" name="dokter">
                        <option value="" disabled selected>Pilih Dokter</option>
                        <?php
                        $dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
                        while ($data = mysqli_fetch_array($dokter)) {
                                $selected = ($data['id'] == $id_dokter2) ? 'selected="selected"' : '';
                            ?>
                                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                        <?php
                        }
                        ?>
                    </select>
                <div class="col mt-3">
                    <div class="form-input mt-3">
                    <label for="obat" class="fw-bold">Obat</label>
                    <select class="form-control" name="obat">
                        <option value="" disabled selected>Pilih Obat</option>
                        <?php
                        $obat = mysqli_query($mysqli, "SELECT * FROM obat");
                        while ($data = mysqli_fetch_array($obat)) {
                            
                                $selected = ($data['id'] == $id_obat2) ? 'selected="selected"' : '';
                            ?>
                                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                        <?php     
                        }
                    ?>
                    </select>
                    </div>
                </div>
                <div class="form-input mt-3">
                    <label for="tgl_periksa" class="fw-bold">
                        Tanggal Periksa
                    </label>
                    <input type="datetime-local" class="form-control" name="tgl_periksa" id="tgl_periksa" placeholder="Tanggal Periksa" value="<?php echo isset($tgl_periksa2) ? $tgl_periksa2 : ''; ?>">
                </div>
                <div class="form-input mt-3">
                    <label for="catatan" class="fw-bold">
                        Catatan
                    </label>
                    <input type="text" class="form-control" name="catatan" id="catatan" placeholder="Catatan" value="<?php echo isset($catatan2) ? $catatan2 : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-3 mt-3" name="simpan">Simpan</button>
            </div>
        </form>
        <br>
        <hr>
    </div>
</div>

<div class="table-periksa mt-3">
    <table class="table table-responsive">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Pasien</th>
                <th scope="col">Dokter</th>
                <th scope="col">Obat</th>
                <th scope="col">Tanggal Diperiksa</th>
                <th scope="col">Catatan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($mysqli, "SELECT pr.*,d.nama as 'nama_dokter', p.nama as 'nama_pasien', o.nama as 'nama_obat' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) LEFT JOIN obat o ON (pr.id_obat=o.id) ORDER BY pr.tgl_periksa DESC");
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
            ?>
                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $data['nama_pasien'] ?></td>
                    <td><?php echo $data['nama_dokter'] ?></td>
                    <td><?php echo $data['nama_obat'] ?></td>
                    <td><?php echo $data['tgl_periksa'] ?></td>
                    <td><?php echo $data['catatan'] ?></td>
                    <td>
                        <a class="btn btn-success rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>">
                            Ubah</a>
                        <a class="btn btn-danger rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
</div>