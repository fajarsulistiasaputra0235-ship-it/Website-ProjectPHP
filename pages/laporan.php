<?php
$data = db_load();

// Ambil filter periode
$period = $_GET['period'] ?? 'daily'; // default harian

// Filter transaksi
$filteredTx = $data['transactions'] ?? [];

// Hitung total pendapatan
$totalSales = array_reduce($filteredTx, fn($c,$t)=>$c+($t['total']??0),0);

// Pendapatan per produk
$byProduct = [];
foreach($filteredTx as $t) {
    foreach($t['items'] as $it){
        if(!isset($byProduct[$it['id']])) $byProduct[$it['id']] = ['name'=>$it['name'],'qty'=>0,'revenue'=>0];
        $byProduct[$it['id']]['qty'] += $it['qty'];
        $byProduct[$it['id']]['revenue'] += $it['qty'] * $it['price'];
    }
}
?>

<div class="card p-4 rounded shadow mb-6">
    <h3 class="font-semibold mb-3">Laporan Pendapatan</h3>

    <!-- Filter periode -->
    <form method="get" class="flex flex-col md:flex-row gap-2 mb-4">
        <input type="hidden" name="p" value="laporan">
        <select name="period" class="p-2 border rounded">
            <option value="daily" <?php echo $period=='daily'?'selected':''; ?>>Harian</option>
            <option value="weekly" <?php echo $period=='weekly'?'selected':''; ?>>Mingguan</option>
            <option value="monthly" <?php echo $period=='monthly'?'selected':''; ?>>Bulanan</option>
            <option value="yearly" <?php echo $period=='yearly'?'selected':''; ?>>Tahunan</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-accent text-white rounded">Filter</button>
    </form>

    <p class="mb-4">Total pendapatan: <strong><?php echo rupiah($totalSales); ?></strong></p>

    <!-- Tabel per produk -->
    <div class="overflow-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-sm text-muted border-b">
                    <th class="py-2 px-2">Tanggal</th>
                    <th class="py-2 px-2">Produk</th>
                    <th class="py-2 px-2">Qty Terjual</th>
                    <th class="py-2 px-2">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($filteredTx as $t): ?>
                    <?php foreach($t['items'] as $it): ?>
                        <tr class="border-t">
                            <td class="py-2 px-2"><?php echo htmlspecialchars($t['date']); ?></td>
                            <td class="py-2 px-2"><?php echo htmlspecialchars($it['name']); ?></td>
                            <td class="py-2 px-2"><?php echo $it['qty']; ?></td>
                            <td class="py-2 px-2"><?php echo rupiah($it['qty'] * $it['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
