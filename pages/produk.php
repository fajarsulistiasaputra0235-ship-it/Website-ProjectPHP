<?php
$data = db_load();
$products = $data['products'] ?? [];

// ➕ Tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
  $name  = trim($_POST['name']);
  $price = (int)$_POST['price'];
  $stock = (int)$_POST['stock'];
  $image = $_POST['image'] ?: 'placeholder.png';

  $data['products'][] = [
    'id'    => db_next_id($data['products'] ?? []),
    'name'  => $name,
    'price' => $price,
    'stock' => $stock,
    'image' => $image
  ];
  db_save($data);
  echo '<div class="p-3 bg-green-50 text-green-700 rounded mb-4">Produk ditambahkan.</div>';
}

// 🗑️ Hapus produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
  $id = (int)$_POST['delete_product'];
  $data['products'] = array_values(array_filter($data['products'], fn($p) => $p['id'] !== $id));
  db_save($data);
  echo '<div class="p-3 bg-red-50 text-red-700 rounded mb-4">Produk dihapus.</div>';
}

// ✏️ Edit produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
  $id    = (int)$_POST['id'];
  $name  = trim($_POST['name']);
  $price = (int)$_POST['price'];
  $stock = (int)$_POST['stock'];
  $image = $_POST['image'] ?: 'placeholder.png';

  foreach ($data['products'] as &$p) {
    if ($p['id'] === $id) {
      $p['name']  = $name;
      $p['price'] = $price;
      $p['stock'] = $stock;
      $p['image'] = $image;
      break;
    }
  }
  db_save($data);
  echo '<div class="p-3 bg-blue-50 text-blue-700 rounded mb-4">Produk diperbarui.</div>';
}
?>

<div class="card p-4 rounded shadow bg-white">
  <h3 class="font-semibold mb-3 text-lg">🛒 Produk</h3>

  <!-- ➕ Form Tambah Produk -->
  <form method="post" class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-4">
    <input name="name" placeholder="Nama produk" class="p-2 border rounded" required>
    <input name="price" type="number" placeholder="Harga" class="p-2 border rounded" required>
    <input name="stock" type="number" placeholder="Stok" class="p-2 border rounded" required>
    <input name="image" type="text" placeholder="URL gambar (opsional)" class="p-2 border rounded">
    <button name="add_product" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Tambah</button>
  </form>

  <!-- 🧾 Tabel Produk -->
  <div class="overflow-auto">
    <table class="w-full text-left border">
      <thead class="bg-gray-100 text-gray-700 text-sm">
        <tr>
          <th class="p-2">Gambar</th>
          <th class="p-2">Nama</th>
          <th class="p-2">Harga</th>
          <th class="p-2">Stok</th>
          <th class="p-2 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)): ?>
          <tr><td colspan="5" class="p-3 text-center text-gray-500 italic">Tidak ada produk.</td></tr>
        <?php else: ?>
          <?php foreach ($products as $p): ?>
            <tr class="border-t hover:bg-gray-50 transition">
              <td class="p-2"><img src="<?php echo htmlspecialchars($p['image']); ?>" class="w-12 h-12 object-cover rounded"></td>
              <td><?php echo htmlspecialchars($p['name']); ?></td>
              <td><?php echo rupiah($p['price']); ?></td>
              <td><?php echo $p['stock']; ?></td>
              <td class="text-right space-x-1">
                <!-- ✏️ Tombol Edit -->
                <button type="button" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($p)); ?>)" class="px-2 py-1 bg-blue-500 text-white rounded text-sm">Edit</button>

                <!-- 🗑️ Tombol Hapus -->
                <form method="post" style="display:inline;" onsubmit="return confirm('Hapus produk <?php echo htmlspecialchars($p['name']); ?>?')">
                  <button name="delete_product" value="<?php echo $p['id']; ?>" class="px-2 py-1 bg-red-500 text-white rounded text-sm">Hapus</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- 🪄 Modal Edit Produk -->
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden">
  <div class="bg-white p-6 rounded shadow-lg w-96">
    <h3 class="text-lg font-semibold mb-3">Edit Produk</h3>
    <form method="post">
      <input type="hidden" name="id" id="edit_id">
      <div class="space-y-2">
        <input name="name" id="edit_name" placeholder="Nama produk" class="p-2 border rounded w-full" required>
        <input name="price" id="edit_price" type="number" placeholder="Harga" class="p-2 border rounded w-full" required>
        <input name="stock" id="edit_stock" type="number" placeholder="Stok" class="p-2 border rounded w-full" required>
        <input name="image" id="edit_image" type="text" placeholder="URL gambar" class="p-2 border rounded w-full">
      </div>
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
        <button name="edit_product" class="px-4 py-2 bg-blue-500 text-white rounded">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEditModal(p) {
  document.getElementById('edit_id').value = p.id;
  document.getElementById('edit_name').value = p.name;
  document.getElementById('edit_price').value = p.price;
  document.getElementById('edit_stock').value = p.stock;
  document.getElementById('edit_image').value = p.image;
  document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
  document.getElementById('editModal').classList.add('hidden');
}
</script>
