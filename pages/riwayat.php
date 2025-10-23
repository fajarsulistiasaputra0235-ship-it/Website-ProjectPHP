<?php
$data = db_load();
$txs = array_reverse($data['transactions']);
?>
<div class="card p-4 rounded shadow">
  <div class="flex justify-between items-center mb-3">
    <h3 class="font-semibold">Riwayat Transaksi</h3>
    <div class="space-x-2">
      <a class="px-3 py-1 border rounded" href="?p=export&type=transactions&format=csv">Export CSV</a>
      <a class="px-3 py-1 border rounded" href="?p=export&type=transactions&format=pdf">Export PDF</a>
    </div>
  </div>
  <div class="overflow-auto">
    <table class="w-full text-left">
      <thead>
        <tr class="text-sm text-muted">
          <th>ID</th>
          <th>Tanggal</th>
          <th>Total</th>
          <th>Items</th>
          <th>Pembayaran</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($txs as $t): ?>
        <tr class="border-t align-top">
          <td class="py-2"><?php echo $t['id']; ?></td>
          <td><?php echo $t['date']; ?></td>
          <td><?php echo rupiah($t['total']); ?></td>
          <td>
            <?php foreach($t['items'] as $it) echo htmlspecialchars($it['name']).' x'.$it['qty'].'<br>'; ?>
          </td>
          <td>
            <?php echo htmlspecialchars($t['payment_method'] ?? '-'); ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
