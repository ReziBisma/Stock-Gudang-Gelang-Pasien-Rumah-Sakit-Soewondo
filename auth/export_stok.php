<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../tcpdf/tcpdf.php';

/* ======================
   AUTH CHECK
====================== */
if (
    !isset($_SESSION['login']) ||
    !in_array($_SESSION['role'], ['admin','operator'])
){
    die("Akses ditolak");
}

/* ======================
   FILTER QUERY
====================== */
$where = [];

if(!empty($_GET['tgl_awal']) && !empty($_GET['tgl_akhir'])){
    $awal = $_GET['tgl_awal'];
    $akhir = $_GET['tgl_akhir'];
    $where[] = "s.tanggal BETWEEN '$awal' AND '$akhir'";
}

if(!empty($_GET['barang'])){
    $barang = (int)$_GET['barang'];
    $where[] = "s.barang_id = $barang";
}

$whereSQL = $where ? "WHERE ".implode(" AND ", $where) : "";

/* ======================
   QUERY DATA
====================== */
$query = mysqli_query($conn,"
    SELECT s.*, b.nama_barang
    FROM stok s
    JOIN barang b ON s.barang_id=b.id
    $whereSQL
    ORDER BY s.tanggal ASC
");

/* ======================
   CREATE PDF
====================== */
$pdf = new TCPDF();
$pdf->AddPage();

$pdf->SetFont('helvetica','B',14);
$pdf->Cell(0,10,'LAPORAN STOK GELANG',0,1,'C');

$pdf->SetFont('helvetica','',10);
$pdf->Ln(3);

/* ======================
   TABLE HEADER
====================== */
$html = '
<table border="1" cellpadding="4">
<tr style="background-color:#eeeeee;">
<th width="60">Tanggal</th>
<th width="110">Barang</th>
<th width="60">Awal</th>
<th width="60">Masuk</th>
<th width="60">Keluar</th>
<th width="60">Akhir</th>
</tr>';

while($d=mysqli_fetch_assoc($query)){
    $html .= "
    <tr>
        <td>{$d['tanggal']}</td>
        <td>{$d['nama_barang']}</td>
        <td align='center'>{$d['stok_awal']}</td>
        <td align='center'>{$d['masuk']}</td>
        <td align='center'>{$d['keluar']}</td>
        <td align='center'><b>{$d['stok_akhir']}</b></td>
    </tr>";
}

$html .= "</table>";

$pdf->writeHTML($html,true,false,true,false,'');

/* ======================
   OUTPUT PDF
====================== */
$pdf->Output('laporan_stok.pdf','I');
exit;
