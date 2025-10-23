<?php
$data = db_load();

// Statistik ringkas
$totalProducts = count($data['products']);
$totalTx = count($data['transactions']);
$totalSales = array_reduce($data['transactions'], fn($c,$t)=>$c+($t['total']??0),0);

// Siapkan data penjualan harian untuk grafik
$dailySales = [];
foreach($data['transactions'] as $t){
    $dayKey = (new DateTime($t['date']))->format('Y-m-d');
    $dailySales[$dayKey] = ($dailySales[$dayKey] ?? 0) + $t['total'];
}
ksort($dailySales);
?>
<!-- Ringkasan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
  <div class="card p-4 rounded shadow hover:shadow-lg transition">
    <h3 class="text-sm text-muted">Total Produk</h3>
    <div class="text-2xl font-bold"><?php echo $totalProducts; ?></div>
  </div>
  <div class="card p-4 rounded shadow hover:shadow-lg transition">
    <h3 class="text-sm text-muted">Total Transaksi</h3>
    <div class="text-2xl font-bold"><?php echo $totalTx; ?></div>
  </div>
  <div class="card p-4 rounded shadow hover:shadow-lg transition">
    <h3 class="text-sm text-muted">Pendapatan</h3>
    <div class="text-2xl font-bold"><?php echo rupiah($totalSales); ?></div>
  </div>
</div>

<!-- Grafik Penjualan Harian (compact) -->
<div class="card p-4 rounded shadow mb-4">
  <h3 class="font-semibold mb-3">Grafik Penjualan Harian</h3>
  <div class="overflow-x-auto">
    <div style="max-width:100%; height:250px;">
      <canvas id="chartSales" style="width:100%; height:100%;"></canvas>
    </div>
  </div>
</div>

<!-- Produk Teratas -->
<div class="card p-4 rounded shadow">
  <h3 class="font-semibold mb-3">Produk Teratas</h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
    <?php foreach(array_slice($data['products'],0,6) as $p): ?>
      <div class="p-3 border rounded hover:shadow transition">
        <div class="font-medium"><?php echo htmlspecialchars($p['name']); ?></div>
        <div class="text-sm text-muted"><?php echo rupiah($p['price']); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartSales');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($dailySales)); ?>,
        datasets: [{
            label: 'Pendapatan Harian',
            data: <?php echo json_encode(array_values($dailySales)); ?>,
            borderColor: 'rgba(59,130,246,1)',
            backgroundColor: 'rgba(59,130,246,0.2)',
            fill: true,
            tension: 0.3,
            pointRadius: 3,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // agar tinggi div 250px dipertahankan
        plugins: { legend: { display: true }, tooltip: { mode: 'index', intersect: false } },
        scales: {
            x: { title: { display: true, text: 'Tanggal' } },
            y: { title: { display: true, text: 'Pendapatan (Rp)' }, beginAtZero: true }
        }
    }
});
</script>
