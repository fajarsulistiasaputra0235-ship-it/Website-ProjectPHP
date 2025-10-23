<?php
$data = db_load();
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_user'])) {
  $username = trim($_POST['username']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'] ?? 'staff';
  $data['users'][] = ['id'=>db_next_id($data['users']),'username'=>$username,'password'=>$password,'role'=>$role];
  db_save($data); 
  echo '<div class="p-3 bg-green-50 text-green-700 rounded mb-4">Pengguna ditambahkan.</div>';
}
?>
<div class="card p-4 rounded shadow">
  <h3 class="font-semibold mb-3">Pengguna / Akses</h3>

  <!-- Form tambah pengguna -->
  <form method="post" class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-4">
    <input name="username" placeholder="Username" class="p-2 border rounded" required>
    <input name="password" type="password" placeholder="Password" class="p-2 border rounded" required>
    <select name="role" class="p-2 border rounded">
      <option value="admin">Admin</option>
      <option value="staff">Staff</option>
    </select>
    <button name="add_user" class="px-4 py-2 bg-accent text-white rounded">Tambah</button>
  </form>

  <!-- Tabel pengguna -->
  <div class="overflow-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-100 text-sm text-gray-600 border-b">
          <th class="py-2 px-3 text-center">ID</th>
          <th class="py-2 px-3">Username</th>
          <th class="py-2 px-3 text-center">Role</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data['users'] as $u): ?>
        <tr class="border-t hover:bg-gray-50">
          <td class="py-2 px-3 text-center"><?php echo $u['id']; ?></td>
          <td class="py-2 px-3"><?php echo htmlspecialchars($u['username']); ?></td>
          <td class="py-2 px-3 text-center"><?php echo htmlspecialchars($u['role']); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
