<?php
// index.php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/lib/db.php';
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/auth.php';

// redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

$page = $_GET['p'] ?? 'dashboard';
$valid = ['dashboard','kasir','produk','riwayat','laporan','promo','reservasi','menu_signature','pengguna','pengaturan','logout','export'];
if (!in_array($page, $valid)) $page = 'dashboard';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Domo Coffe Chasier</title>
  <!-- Tailwind CDN -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body data-theme="light" class="min-h-screen antialiased">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-72 bg-card border-r p-6 hidden md:block">
      <div class="mb-6">
        <div class="text-2xl font-bold">Domo <span class="text-accent">Coffe</span> Chasier</div>
        <div class="text-sm text-muted">Point of Sale</div>
      </div>
      <nav class="space-y-1">
        <?php
        function navItem($p,$label,$icon){
          global $page;
          $active = $page === $p ? 'bg-active' : 'hover:bg-active/50';
          echo "<a href=\"?p={$p}\" class=\"flex items-center gap-3 px-3 py-2 rounded-md {$active}\"><i class='bx {$icon} text-lg'></i><span>{$label}</span></a>";
        }
        navItem('dashboard','Dashboard','bx-home');
        navItem('kasir','Kasir','bx-cart');
        navItem('produk','Produk','bx-package');
        navItem('riwayat','Riwayat Transaksi','bx-receipt');
        navItem('laporan','Laporan','bx-line-chart');
        navItem('reservasi','Reservasi','bx-calendar');
        navItem('menu_signature','Menu Signature','bx-coffee');
        ?>
        <div class="mt-6 mb-2 text-xs uppercase text-muted">Pengaturan & Administrasi</div>
        <?php navItem('pengguna','Pengguna / Akses','bx-user'); navItem('pengaturan','Pengaturan / Sistem','bx-cog'); ?>
        <a href="logout.php" class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-active/50"><i class='bx bx-log-out'></i><span>Logout</span></a>
      </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <header class="flex items-center justify-between p-4 border-b bg-card">
        <div class="flex items-center gap-3">
          <button id="sidebarToggle" class="md:hidden p-2 rounded-md bg-gray-100"><i class='bx bx-menu'></i></button>
          <h1 class="text-lg font-semibold uppercase"><?php echo htmlspecialchars($page); ?></h1>
        </div>
        <div class="flex items-center gap-4">
          <button id="themeToggle" class="p-2 rounded-md theme-toggle" title="Toggle theme"><i id="themeIcon" class="bx bx-moon"></i></button>
          <div class="text-sm"><?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'Kasir'); ?></div>
        </div>
      </header>

      <!-- Content -->
      <main class="p-6 overflow-auto">
        <?php include __DIR__ . "/pages/{$page}.php"; ?>
      </main>
    </div>
  </div>

  <script src="assets/js/theme.js"></script>
  <script src="assets/js/app.js"></script>
  <script>
    // responsive sidebar toggle
    document.getElementById('sidebarToggle').addEventListener('click', function(){
      const s = document.getElementById('sidebar');
      s.classList.toggle('hidden');
    });
  </script>
</body>
</html>
