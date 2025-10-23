<?php
// login.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/auth.php';

if (isset($_SESSION['user'])) {
  header('Location: index.php?p=dashboard'); exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $u = $_POST['username']; $p = $_POST['password'];
  // simple built-in kasir credential + also allow db users via auth_login
  if ($u === 'kasir' && $p === '1234') {
    $_SESSION['user'] = ['username'=>'kasir','role'=>'staff'];
    header('Location: index.php?p=dashboard'); exit;
  } elseif (auth_login($u,$p)) {
    header('Location: index.php?p=dashboard'); exit;
  } else {
    $error = 'Username atau password salah.';
  }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Domo Coffe Chasier</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-soft">
  <div class="w-full max-w-md">
    <div class="card p-6 rounded-lg shadow">
      <h2 class="text-2xl font-semibold mb-4">Login - Domo Coffe Chasier</h2>
      <?php if($error): ?><div class="mb-4 p-3 bg-red-50 text-red-700 rounded"><?php echo $error; ?></div><?php endif; ?>
      <form method="post" class="space-y-4">
        <input name="username" placeholder="username" class="w-full p-3 border rounded" required>
        <input name="password" type="password" placeholder="password" class="w-full p-3 border rounded" required>
        <div class="flex justify-between items-center">
          <div class="text-sm text-muted">Default: <strong>kasir</strong> / <strong>1234</strong></div>
          <button name="login" class="px-4 py-2 bg-accent text-white rounded">Login</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
