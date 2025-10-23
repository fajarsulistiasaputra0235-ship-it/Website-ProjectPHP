<?php
require_once __DIR__ . '/../lib/helpers.php';
$data = db_load();
$format = $_GET['format'] ?? 'csv';
$type = $_GET['type'] ?? 'transactions';
$rows = $type === 'transactions' ? $data['transactions'] : [];

if ($format === 'csv') {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename=export_'.$type.'_'.date('Ymd_His').'.csv');
  $out = fopen('php://output','w');
  fputcsv($out, ['id','date','total','items']);
  foreach($rows as $r){
    $items=[];
    foreach($r['items'] as $it) $items[] = $it['name'].' x'.$it['qty'];
    fputcsv($out, [$r['id'],$r['date'],$r['total'],implode('; ',$items)]);
  }
  fclose($out); exit;
}

if ($format === 'pdf') {
  // server-side PDF: dompdf recommended. If not installed, fallback to printable HTML
  if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
    $dompdf = new Dompdf\Dompdf();
    $html = '<h2>Laporan Transaksi</h2><table border="1" cellpadding="6" cellspacing="0" width="100%"><thead><tr><th>ID</th><th>Tanggal</th><th>Total</th><th>Items</th></tr></thead><tbody>';
    foreach($rows as $r){
      $items=[]; foreach($r['items'] as $it) $items[] = htmlspecialchars($it['name']).' x'.$it['qty'];
      $html .= '<tr><td>'.$r['id'].'</td><td>'.$r['date'].'</td><td>'.rupiah($r['total']).'</td><td>'.implode(', ',$items).'</td></tr>';
    }
    $html .= '</tbody></table>';
    $dompdf->loadHtml($html); $dompdf->setPaper('A4','portrait'); $dompdf->render(); $dompdf->stream('laporan_'.$type.'_'.date('Ymd_His').'.pdf', ['Attachment'=>1]);
    exit;
  } else {
    header('Content-Type: text/html; charset=utf-8');
    echo '<html><head><meta charset="utf-8"><title>Laporan</title><style>body{font-family:Arial;padding:20px}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ddd;padding:8px}</style></head><body>';
    echo '<h2>Laporan Transaksi</h2><button onclick="window.print()">Print / Save as PDF</button>';
    echo '<table><thead><tr><th>ID</th><th>Tanggal</th><th>Total</th><th>Items</th></tr></thead><tbody>';
    foreach($rows as $r){
      $items=[]; foreach($r['items'] as $it) $items[] = htmlspecialchars($it['name']).' x'.$it['qty'];
      echo '<tr><td>'.$r['id'].'</td><td>'.$r['date'].'</td><td>'.rupiah($r['total']).'</td><td>'.implode(', ',$items).'</td></tr>';
    }
    echo '</tbody></table></body></html>'; exit;
  }
}
