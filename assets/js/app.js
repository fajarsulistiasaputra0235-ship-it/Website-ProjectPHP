// assets/js/app.js - cart interactions
(function () {
  function formatRp(n) {
    return "Rp " + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }
  const cart = [];
  function render() {
    const el = document.getElementById("cartList");
    if (!el) return;
    el.innerHTML = "";
    let total = 0;
    cart.forEach((it, idx) => {
      total += it.qty * it.price;
      const div = document.createElement("div");
      div.className = "flex items-center justify-between p-2 border rounded";
      div.innerHTML =
        '<div><div class="font-medium">' +
        it.name +
        '</div><div class="text-sm text-muted">' +
        formatRp(it.price) +
        '</div></div><div class="flex items-center gap-2"><input type="number" min="1" value="' +
        it.qty +
        '" data-idx="' +
        idx +
        '" class="w-16 p-1 border qty"><button class="px-2 py-1 border remove" data-idx="' +
        idx +
        '">Hapus</button></div>';
      el.appendChild(div);
    });
    const totalEl = document.getElementById("cartTotal");
    if (totalEl) totalEl.textContent = formatRp(total);
    const cartInput = document.getElementById("cartInput");
    if (cartInput) cartInput.value = JSON.stringify(cart);
  }

  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-to-cart")) {
      const btn = e.target;
      const id = parseInt(btn.dataset.id);
      const name = btn.dataset.name;
      const price = parseInt(btn.dataset.price);
      const found = cart.find((c) => c.id === id);
      if (found) found.qty += 1;
      else cart.push({ id, name, price, qty: 1 });
      render();
    }
    if (e.target.classList.contains("remove")) {
      const idx = parseInt(e.target.dataset.idx);
      cart.splice(idx, 1);
      render();
    }
  });

  document.addEventListener("change", function (e) {
    if (e.target.classList.contains("qty")) {
      const idx = parseInt(e.target.dataset.idx);
      const v = parseInt(e.target.value) || 1;
      cart[idx].qty = v;
      render();
    }
  });

  const clearBtn = document.getElementById("clearCart");
  if (clearBtn)
    clearBtn.addEventListener("click", function () {
      cart.length = 0;
      render();
    });
  const form = document.getElementById("checkoutForm");
  if (form)
    form.addEventListener("submit", function (e) {
      if (cart.length === 0) {
        alert("Keranjang kosong");
        e.preventDefault();
      }
    });

  render();
})();
