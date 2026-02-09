<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../tcpdf/tcpdf.php';

/* ======================
   AUTH
====================== */
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak");
}

/* ======================
   FORMAT
====================== */
$format = $_GET['format'] ?? 'pdf';

/* ======================
   QUERY
====================== */
$query = mysqli_query($conn, "
    SELECT id, nama_barang
    FROM barang
    ORDER BY nama_barang ASC
");

if (!$query) {
    die("Query gagal");
}

/* ======================
   CSV EXPORT
====================== */
if ($format === 'csv') {

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=barang.csv');

    $output = fopen('php://output', 'w');

    // HEADER CSV (tanpa ID)
    fputcsv($output, ['Nama Barang']);

    while ($row = mysqli_fetch_assoc($query)) {
        fputcsv($output, [$row['nama_barang']]);
    }

    fclose($output);
    exit;
}


/* ======================
   PDF EXPORT (DEFAULT)
====================== */
$pdf = new TCPDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'LAPORAN DATA BARANG', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 6, 'Tanggal Cetak: ' . date('d-m-Y H:i'), 0, 1, 'C');
$pdf->Ln(6);

$html = '
<table border="1" cellpadding="6">
<tr style="background-color:#eeeeee;">
    <th width="60" align="center"><b>No</b></th>
    <th width="460"><b>Nama Barang</b></th>
</tr>
';

$no = 1;
mysqli_data_seek($query, 0);

while ($d = mysqli_fetch_assoc($query)) {
    $html .= "
    <tr>
        <td align='center'>{$no}</td>
        <td>{$d['nama_barang']}</td>
    </tr>";
    $no++;
}

$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('laporan_barang.pdf', 'I');
exit;

