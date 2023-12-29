<?php
if (isset($_GET['id'])) {
    $id_periksa = $_GET['id'];

    $ambil_data_periksa = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id = '$id_periksa'");
    $data_periksa = mysqli_fetch_assoc($ambil_data_periksa);

    $drugs_query = "SELECT o.nama, dp.jumlah_obat, o.harga, dp.id
                    FROM detail_periksa dp
                    JOIN obat o ON dp.id_obat = o.id
                    WHERE dp.id_periksa = '$id_periksa'";
    $drugs_result = mysqli_query($mysqli, $drugs_query);

    // Menghitung total harga obat
    $total_harga_obat = 0;
    while ($drug_data = mysqli_fetch_assoc($drugs_result)) {
        $harga_obat = $drug_data['jumlah_obat'] * $drug_data['harga'];
        $total_harga_obat += $harga_obat;
    }

    // Biaya periksa, including total harga obat and jasa dokter
    $biaya_periksa = $total_harga_obat + 150000;
}
?>
<a href="index.php?page=detail_periksa&id=<?php echo $id_periksa ?>" class="btn btn-primary mb-3">
    < Back
</a>
<div class="container">
    <h2 class="mt-4 mb-4">POLIKLINIK</h2>
    <h4>(Invoice)</h4>
    <div class="">
        <table id="combinedTable" class="table table-responsive table-striped table-hover">
            <thead class="thead-dark" style="background-color: #f8f9fa;">
                <tr>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal Periksa</th>
                    <th>Catatan</th>
                    <th>Jasa Dokter</th>
                    <th>Total Harga Obat</th>
                    <th>Biaya Periksa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($mysqli, "SELECT pr.*,d.nama as 'nama_dokter', p.nama as 'nama_pasien' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) WHERE pr.id='$id_periksa' ORDER BY pr.tgl_periksa DESC");
                while ($data = mysqli_fetch_array($result)) {
                ?>
                    <tr>
                        <td><?php echo $data['nama_pasien'] ?></td>
                        <td><?php echo $data['nama_dokter'] ?></td>
                        <td><?php echo $data['tgl_periksa'] ?></td>
                        <td><?php echo $data['catatan'] ?></td>
                        <td>Rp.150.000</td>
                        <td><?php echo "Rp." . number_format($total_harga_obat,2,",",".") ?></td>
                        <td class="fw-bolder"><?php echo "Rp." . number_format($biaya_periksa,2,",",".") ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        var tablesToExport = $('#combinedTable');
        var buttons = [
            {
                extend: 'collection',
                text: 'Export',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            }
        ];

        tablesToExport.DataTable({
            dom: 'Bfrtip',
            buttons: buttons,
            searching: false,
            paging: false
        });
    });
</script>
