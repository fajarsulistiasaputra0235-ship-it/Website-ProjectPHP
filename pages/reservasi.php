<?php
$data = db_load();

// Tambah reservasi
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_reserve'])) {
  $data['reservations'][] = [
      'id'=>db_next_id($data['reservations'] ?? []),
      'name'=>$_POST['name'],
      'date'=>$_POST['date'],
      'people'=>$_POST['people']
  ];
  db_save($data); 
  echo '<div class="p-3 bg-green-50 text-green-700 rounded mb-4">Reservasi disimpan.</div>';
}

// Hapus reservasi
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['delete_reserve'])) {
    $deleteId = (int)$_POST['delete_reserve'];
    foreach($data['reservations'] as $k => $r) {
        if($r['id'] === $deleteId) {
            unset($data['reservations'][$k]);
            $data['reservations'] = array_values($data['reservations']); // reset index
            db_save($data);
            echo '<div class="p-3 bg-red-50 text-red-700 rounded mb-4">Reservasi ID '.$deleteId.' dihapus.</div>';
            break;
        }
    }
}

$res = $data['reservations'] ?? [];
?>
<div class="card p-4 rounded shadow">
  <h3 class="font-semibold mb-3">Reservasi</h3>

  <!-- Form tambah reservasi -->
  <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
    <input name="name" placeholder="Nama" class="p-2 border rounded" required>
    <input name="date" type="datetime-local" class="p-2 border rounded" required>
    <input name="people" type="number" class="p-2 border rounded" placeholder="Jumlah orang" required>
    <button name="add_reserve" class="px-4 py-2 bg-accent text-white rounded">Simpan</button>
  </form>

  <!-- Daftar reservasi -->
  <?php foreach($res as $r): ?>
    <div class="p-3 border rounded mb-2 flex justify-between items-center">
      <div>
        <div class="font-medium"><?php echo htmlspecialchars($r['name']); ?> — <?php echo htmlspecialchars($r['people']); ?> orang</div>
        <div class="text-sm text-muted"><?php echo htmlspecialchars($r['date']); ?></div>
      </div>
      <form method="post" style="display:inline;" onsubmit="return confirm('Hapus reservasi <?php echo htmlspecialchars($r['name']); ?>?')">
        <button name="delete_reserve" value="<?php echo $r['id']; ?>" class="px-2 py-1 bg-red-500 text-white rounded text-sm">Hapus</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
