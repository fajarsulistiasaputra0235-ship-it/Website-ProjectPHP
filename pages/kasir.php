<?php
$data = db_load();
$products = $data['products'];

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['checkout'])) {
    $cart = json_decode($_POST['cart'], true) ?: [];
    $total = 0;
    foreach($cart as $c) $total += $c['qty'] * $c['price'];

    // Ambil metode pembayaran
    $payment_method = $_POST['payment_method'] ?? 'Tunai';

    $tx = [
        'id' => db_next_id($data['transactions']),
        'items' => $cart,
        'total' => $total,
        'payment_method' => $payment_method,
        'date' => date('Y-m-d H:i:s')
    ];

    // reduce stock
    foreach($cart as $ci) {
        foreach($data['products'] as $k => $p) {
            if($p['id'] == $ci['id']) {
                $data['products'][$k]['stock'] = max(0, $p['stock'] - $ci['qty']);
            }
        }
    }

    $data['transactions'][] = $tx;
    db_save($data);

    echo '<div class="p-3 bg-green-50 text-green-700 rounded mb-4">Transaksi berhasil. ID: '.$tx['id'].' | Pembayaran: '.htmlspecialchars($payment_method).'</div>';
}
?>

<div class="grid md:grid-cols-2 gap-4">
    <div>
        <div class="card p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Produk</h3>
            <div class="space-y-3 max-h-96 overflow-auto">
                <?php foreach($products as $p): ?>
                <div class="flex items-center justify-between p-3 border rounded">
                    <div class="flex items-center space-x-3">
                        <?php if(!empty($p['image'])): ?>
                            <img src="<?php echo htmlspecialchars($p['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($p['name']); ?>" 
                                 class="w-12 h-12 object-cover object-center rounded">
                        <?php else: ?>
                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">No Image</div>
                        <?php endif; ?>
                        <div>
                            <div class="font-medium"><?php echo htmlspecialchars($p['name']); ?></div>
                            <div class="text-sm text-muted"><?php echo rupiah($p['price']); ?> • Stok: <?php echo $p['stock']; ?></div>
                        </div>
                    </div>
                    <button class="px-3 py-1 bg-accent text-white rounded add-to-cart" 
                            data-id="<?php echo $p['id']; ?>" 
                            data-name="<?php echo htmlspecialchars($p['name']); ?>" 
                            data-price="<?php echo $p['price']; ?>">Tambah</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div>
        <div class="card p-4 rounded shadow">
            <h3 class="font-semibold mb-3">Keranjang</h3>
            <form method="post" id="checkoutForm">
                <input type="hidden" name="cart" id="cartInput">
                <div id="cartList" class="space-y-2"></div>

                <!-- Pilihan metode pembayaran -->
                <div class="mt-3">
                    <label class="font-medium mb-1 block">Metode Pembayaran:</label>
                    <select name="payment_method" class="w-full p-2 border rounded">
                        <option value="Tunai">Tunai</option>
                        <option value="OVO">OVO</option>
                        <option value="Gopay">Gopay</option>
                        <option value="Dana">Dana</option>
                    </select>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <div class="text-lg font-bold">Total: <span id="cartTotal">Rp 0</span></div>
                    <div>
                        <button type="button" id="clearCart" class="px-3 py-1 border rounded mr-2">Bersihkan</button>
                        <button type="submit" name="checkout" class="px-4 py-2 bg-accent text-white rounded">Checkout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
