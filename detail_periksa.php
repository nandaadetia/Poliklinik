<?php
if (isset($_POST['simpan_detail_obat'])) {
    $id_periksa = $_POST['id_periksa'];
    $id_obat = $_POST['obat'];
    $jumlah_obat = (isset($_POST['jumlah_obat']) && is_numeric($_POST['jumlah_obat'])) ? $_POST['jumlah_obat'] : 1;

    if (!isset($_GET['id_obat'])){
        $query = "INSERT INTO detail_periksa (id_periksa, id_obat, jumlah_obat) VALUES ('$id_periksa', '$id_obat', '$jumlah_obat')";
    }else {
        $id_obat_edit = $_GET['id_obat'];  // Added this line to retrieve the id_obat for editing
        $query = "UPDATE `detail_periksa` SET `id_periksa`='$id_periksa', `id_obat`='$id_obat', `jumlah_obat`='$jumlah_obat' WHERE id='$id_obat_edit'";
    }
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        // Update biaya_periksa after inserting drug data
        $drugs_query = "SELECT o.nama, dp.jumlah_obat, o.harga
                        FROM detail_periksa dp
                        JOIN obat o ON dp.id_obat = o.id
                        WHERE dp.id_periksa = '$id_periksa'";
        $drugs_result = mysqli_query($mysqli, $drugs_query);

        $total_harga_obat = 0;

        while ($drug_data = mysqli_fetch_assoc($drugs_result)) {
            $harga_obat = $drug_data['jumlah_obat'] * $drug_data['harga'];
            $total_harga_obat += $harga_obat;
        }

        $biaya_periksa = $total_harga_obat + 150000;

        $update_query = "UPDATE `periksa` SET `biaya_periksa` = '$biaya_periksa' WHERE id='$id_periksa'";
        $update_result = mysqli_query($mysqli, $update_query);

        if ($update_result) {
        } else {
            echo "Terjadi kesalahan saat mengupdate biaya periksa: " . mysqli_error($mysqli);
        }
    } else {
        echo "Terjadi kesalahan saat menambahkan data obat: " . mysqli_error($mysqli);
    }
}

if (isset($_GET['id'])) {
    $id_periksa = $_GET['id'];

    $ambil_data_periksa = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id = '$id_periksa'");
    $data_periksa = mysqli_fetch_assoc($ambil_data_periksa);

    $drugs_query = "SELECT o.nama, dp.jumlah_obat, o.harga, dp.id
                    FROM detail_periksa dp
                    JOIN obat o ON dp.id_obat = o.id
                    WHERE dp.id_periksa = '$id_periksa'";
    $drugs_result = mysqli_query($mysqli, $drugs_query);
}

//delete obat
if ((isset($_GET['aksi']) && isset($_GET['id_obat']))) {
    $id_obat_to_delete = $_GET['id_obat'];

    $deleted_drug_query = "SELECT o.harga, dp.jumlah_obat
                           FROM detail_periksa dp
                           JOIN obat o ON dp.id_obat = o.id
                           WHERE dp.id = '$id_obat_to_delete'";
    $deleted_drug_result = mysqli_query($mysqli, $deleted_drug_query);
    $deleted_drug_data = mysqli_fetch_assoc($deleted_drug_result);
    if(!empty($deleted_drug_data)){
        $harga_obat_deleted = $deleted_drug_data['harga'];
        $jumlah_obat_deleted = $deleted_drug_data['jumlah_obat'];
    }

    $query_biaya_periksa = mysqli_query($mysqli, "SELECT biaya_periksa FROM periksa WHERE id='$id_periksa'");
    $biaya_periksa = mysqli_fetch_array($query_biaya_periksa)['biaya_periksa'];
    $total_harga_obat = $harga_obat_deleted * $jumlah_obat_deleted;
    $biaya_periksa_hapus = $biaya_periksa - $total_harga_obat;
    
    mysqli_query($mysqli, "UPDATE `periksa` SET `biaya_periksa` = '$biaya_periksa_hapus' WHERE id='$id_periksa'");
    $hapus = mysqli_query($mysqli, "DELETE FROM `detail_periksa` WHERE id='$id_obat_to_delete'");

    if ($hapus) {
        ?>
        <script>
            Swal.fire({
                timer: 1,
                showConfirmButton: false
            }).then((result) => {
                    window.location.href = 'index.php?page=detail_periksa&id=<?php echo $id_periksa; ?>';
            });
        </script>
        <?php
    }
    
}
?>

<div class="container">
    <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <?php
        if (isset($_GET['id_obat'])) {
            $id_obat = $_GET['id_obat'];
            $query2 = "SELECT *
                        FROM detail_periksa WHERE id = '$id_obat'";
            $dataedit = mysqli_query($mysqli, $query2);
            $dataedit = mysqli_fetch_assoc($dataedit);
            if(!empty($dataedit)){
                $id_periksa2 = $dataedit['id_periksa'];
                $id_obat2 = $dataedit['id_obat'];
                $jumlah_obat2 = $dataedit['jumlah_obat'];
            }
        }
        ?>
        <input type="hidden" name="id_periksa" value="<?php echo $id_periksa; ?>">
        <div class="form-input mt-3">
            <label for="obat" class="fw-bold">Obat</label>
            <select class="form-control" name="obat" required>
                <option value="" <?php echo (isset($_GET['id_obat'])) ? '' : 'selected'; ?>>Pilih Obat</option>
                <?php
                $obat = mysqli_query($mysqli, "SELECT * FROM obat");
                while ($data_obat = mysqli_fetch_array($obat)) {
                ?>
                    <option value="<?php echo (isset($_GET['id_obat'])) ? $dataedit['id_obat'] : $data_obat['id']; ?>" <?php echo (isset($_GET['id_obat']) && $dataedit['id_obat'] == $data_obat['id']) ? 'selected' : ''; ?>>
                        <?php echo $data_obat['nama']; ?>
                    </option>
                <?php
                }
                ?>
            </select>
        </div>

        <div class="form-input mt-3">
            <label for="jumlah_obat" class="fw-bold">Jumlah Obat</label>
            <input type="number" class="form-control" name="jumlah_obat" id="jumlah_obat" placeholder="Jumlah Obat" value="<?php echo (isset($_GET['id_obat'])) ? $jumlah_obat2 : "" ?>">
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-3 mt-3" name="simpan_detail_obat">Tambah Obat</button>
    </form>

    <div class="table-periksa mt-3">
        <table class="table table-responsive">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pasien</th>
                    <th scope="col">Dokter</th>
                    <th scope="col">Tanggal Periksa</th>
                    <th scope="col">Catatan</th>
                    <th scope="col">Total Biaya</th>
                    <th class="d-flex justify-content-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($mysqli, "SELECT pr.*,d.nama as 'nama_dokter', p.nama as 'nama_pasien' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) WHERE pr.id='$id_periksa' ORDER BY pr.tgl_periksa DESC");
                $no = 1;
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $data['nama_pasien'] ?></td>
                        <td><?php echo $data['nama_dokter'] ?></td>
                        <td><?php echo $data['tgl_periksa'] ?></td>
                        <td><?php echo $data['catatan'] ?></td>
                        <td class="fw-bolder"><?php echo 'Rp.' . number_format($data['biaya_periksa'],2,",",".") ?></td>
                        <td class="d-flex justify-content-center gap-1">
                            <a class="btn btn-warning rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>">Edit</a>
                            <a class="btn btn-success rounded-pill px-3" href="index.php?page=export&id=<?php echo $data['id'] ?>">Export</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <strong>Obat yang Diberikan:</strong><br>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th>Jumlah Obat</th>
                    <th>Harga</th>
                    <th class="d-flex justify-content-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_harga_obat = 0;
                $biaya_periksa = $total_harga_obat + 150000;

                while ($drug_data = mysqli_fetch_assoc($drugs_result)) {
                    $harga_obat = $drug_data['jumlah_obat'] * $drug_data['harga'];
                    $total_harga_obat += $harga_obat;
                ?>
                    <tr>
                        <td><?php echo $drug_data['nama']; ?></td>
                        <td><?php echo $drug_data['jumlah_obat']; ?></td>
                        <td><?php echo $drug_data['jumlah_obat'] . " x " . $drug_data['harga'] . " = " . "Rp." . number_format($harga_obat,2,",","."); ?></td>
                        <td class="d-flex justify-content-center gap-1">
                            <a href="index.php?page=detail_periksa&id=<?= $id_periksa ?>&id_obat=<?php echo $drug_data['id'] ?>" class="btn btn-warning rounded-pill px-3">Edit</a>
                            <a href="index.php?page=detail_periksa&id=<?= $id_periksa ?>&id_obat=<?php echo $drug_data['id'] ?>&aksi=hapus" class="btn btn-danger rounded-pill px-3">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot class="">
                <tr>
                    <th colspan="3" class="text-center">Total Harga Obat + Biaya Dokter</th>
                    <th class="text-center"><?php echo "Rp." . number_format($total_harga_obat,2,",",".") . " + " . "Rp.150.000" . " = " . "Rp." . number_format(($total_harga_obat + 150000),2,",",".") ?> </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
