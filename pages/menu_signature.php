<?php
$data = db_load();
$menu = $data['menu_signature'] ?? [];

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu'])) {
  $menu[] = [
    'id' => db_next_id($menu),
    'name' => trim($_POST['name']),
    'price' => (int) $_POST['price'],
    'desc' => trim($_POST['desc']),
  ];
  $data['menu_signature'] = $menu;
  db_save($data);
  echo '<div class="p-3 bg-green-50 text-green-700 rounded mb-4">✅ Menu signature ditambahkan.</div>';
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_menu'])) {
  $id = (int) $_POST['id'];
  $menu = array_values(array_filter($menu, fn($m) => $m['id'] != $id));
  $data['menu_signature'] = $menu;
  db_save($data);
  echo '<div class="p-3 bg-red-50 text-red-700 rounded mb-4">🗑️ Menu signature dihapus.</div>';
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_menu'])) {
  foreach ($menu as &$m) {
    if ($m['id'] == $_POST['id']) {
      $m['name'] = trim($_POST['name']);
      $m['price'] = (int) $_POST['price'];
      $m['desc'] = trim($_POST['desc']);
    }
  }
  unset($m);
  $data['menu_signature'] = $menu;
  db_save($data);
  echo '<div class="p-3 bg-blue-50 text-blue-700 rounded mb-4">✏️ Menu signature diperbarui.</div>';
}
$menu = $data['menu_signature'] ?? [];
?>

<div class="card p-4 rounded shadow bg-white">
  <h3 class="font-semibold mb-3 text-lg">🍽️ Menu Signature</h3>

  <!-- Form Tambah -->
  <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
    <input name="name" placeholder="Nama menu" class="p-2 border rounded focus:ring focus:ring-accent/30" required>
    <input name="price" type="number" placeholder="Harga" class="p-2 border rounded focus:ring focus:ring-accent/30" required>
    <input name="desc" placeholder="Deskripsi" class="p-2 border rounded focus:ring focus:ring-accent/30">
    <button name="add_menu" class="px-4 py-2 bg-accent text-white rounded hover:bg-accent/80 transition">Tambah</button>
  </form>

  <!-- List Menu -->
  <?php if (empty($menu)): ?>
    <div class="text-sm text-muted italic">Belum ada menu signature.</div>
  <?php endif; ?>

  <?php foreach ($menu as $m): ?>
    <div class="p-3 border rounded mb-3 bg-card hover:bg-gray-50 transition" x-data="{ edit:false }">
      <!-- Mode Tampilan -->
      <div x-show="!edit" class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
          <div class="font-medium text-gray-800">
            <?php echo htmlspecialchars($m['name']); ?> — 
            <span class="text-accent"><?php echo rupiah($m['price']); ?></span>
          </div>
          <div class="text-sm text-muted"><?php echo htmlspecialchars($m['desc']); ?></div>
        </div>
        <div class="mt-2 md:mt-0 flex gap-2">
          <button type="button" @click="edit=true" class="px-3 py-1.5 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 active:scale-95 transition">Edit</button>
          <form method="post" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
            <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
            <button name="delete_menu" class="px-3 py-1.5 bg-red-500 text-white text-sm rounded hover:bg-red-600 active:scale-95 transition">Hapus</button>
          </form>
        </div>
      </div>

      <!-- Mode Edit -->
      <form method="post" x-show="edit" x-transition class="mt-3 grid grid-cols-1 md:grid-cols-5 gap-2 items-center">
        <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
        <input name="name" value="<?php echo htmlspecialchars($m['name']); ?>" class="p-2 border rounded focus:ring focus:ring-blue-200" required>
        <input name="price" type="number" value="<?php echo htmlspecialchars($m['price']); ?>" class="p-2 border rounded focus:ring focus:ring-blue-200" required>
        <input name="desc" value="<?php echo htmlspecialchars($m['desc']); ?>" class="p-2 border rounded focus:ring focus:ring-blue-200">
        <div class="flex gap-2 justify-end">
          <button name="update_menu" class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 active:scale-95 transition">Simpan</button>
          <button type="button" @click="edit=false" class="px-3 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 active:scale-95 transition">Batal</button>
        </div>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<!-- AlpineJS untuk toggle edit -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
