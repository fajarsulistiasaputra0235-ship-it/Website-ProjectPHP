<?php
$data = db_load();
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_settings'])) {
  $data['settings']['currency'] = $_POST['currency'];
  db_save($data); echo '<div class="p-3 bg-green-50 text-green-700 rounded mb-4">Pengaturan disimpan.</div>';
}
?>
<div class="card p-4 rounded shadow">
  <h3 class="font-semibold mb-3">Pengaturan / Sistem</h3>
  <form method="post" class="grid grid-cols-1 md:grid-cols-3 gap-2">
    <label class="block"><span class="text-sm text-muted">Currency</span><input name="currency" value="<?php echo htmlspecialchars($data['settings']['currency'] ?? 'IDR'); ?>" class="p-2 border rounded w-full"></label>
    <div></div>
    <button name="save_settings" class="px-4 py-2 bg-accent text-white rounded">Simpan</button>
  </form>
</div>
