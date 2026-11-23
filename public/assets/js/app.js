(function () {
  'use strict';


  function getBasePath() {
    var path = window.location.pathname || '/';
    var frontControllerMatch = path.match(/^(.*?\/public\/index\.php)(?:\/|$|\?)/i);
    if (frontControllerMatch) {
      return frontControllerMatch[1].replace(/\/+$/, '');
    }


    var cutMatch = path.match(/^(.*?)(?:\/(?:admin|products|cart|checkout|orders|notifications|login|register|account|api|public)(?:\/|$|\?))/i);
    var base = cutMatch ? cutMatch[1] : path;
    if (!/\.php$/i.test(base) && !base.endsWith('/')) {
      var parts = base.split('/').filter(Boolean);
      base = parts.length ? '/' + parts[0] : '';
    } else if (/\.php$/i.test(base)) {
      base = base.replace(/\/[^/]*\.php$/i, '');
    }
    return base === '/' ? '' : base.replace(/\/+$/, '');
  }


  var BASE_PATH = getBasePath();


  function url(p) {
    if (!p) { return BASE_PATH || '/'; }
    if (/^(?:https?:)?\/\//i.test(p)) { return p; }
    var clean = p.charAt(0) === '/' ? p : '/' + p;
    if (BASE_PATH && (clean === BASE_PATH || clean.indexOf(BASE_PATH + '/') === 0)) {
      return clean;
    }
    clean = clean.replace(/^\/(?:public\/)?index\.php(?=\/|$)/i, '') || '/';
    if (clean === '/') {
      return (BASE_PATH || '') + '/';
    }
    return BASE_PATH + clean;
  }


  var csrfMeta = document.querySelector('meta[name="csrf-token"]');
  var csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';


  // ---------- Toasts ----------
  function toast(message, type) {
    var container = document.getElementById('bhToasts');
    if (!container || !window.bootstrap) { return; }
    var el = document.createElement('div');
    el.className = 'toast align-items-center border-0 text-bg-' + (type || 'success');
    el.setAttribute('role', 'alert');
    el.innerHTML = '<div class="d-flex"><div class="toast-body"></div>' +
      '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button></div>';
    el.querySelector('.toast-body').textContent = message;
    container.appendChild(el);
    el.addEventListener('hidden.bs.toast', function () { el.remove(); });
    new bootstrap.Toast(el, { delay: 4500 }).show();
  }
  window.BH = { toast: toast, csrf: csrfToken, url: url };


  document.querySelectorAll('[data-flash]').forEach(function (node) {
    toast(node.getAttribute('data-message'), node.getAttribute('data-flash'));
  });


  if (window.sessionStorage) {
    var authNotice = sessionStorage.getItem('bh_auth_notice');
    if (authNotice) {
      sessionStorage.removeItem('bh_auth_notice');
      toast(authNotice, 'warning');
    }
  }


  // ---------- fetch helper ----------
  function post(endpoint, data) {
    var body = new FormData();
    Object.keys(data).forEach(function (k) { body.append(k, data[k]); });
    return fetch(url(endpoint), {
      method: 'POST',
      body: body,
      headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      credentials: 'same-origin'
    }).then(function (res) {
      return res.json().catch(function () { return { ok: false, message: 'Có lỗi xảy ra.' }; })
        .then(function (json) { json.status = res.status; return json; });
    });
  }


  function updateCartBadge(count) {
    document.querySelectorAll('.bh-cart-badge').forEach(function (b) {
      b.textContent = count;
      b.style.display = count > 0 ? 'flex' : 'none';
    });
  }


  window.BH.updateCartBadge = updateCartBadge;


  // ---------- Add to cart ----------
  document.addEventListener('click', function (event) {
    var btn = event.target.closest('[data-add-to-cart]');
    if (!btn) { return; }
    event.preventDefault();
    var qtyInput = btn.getAttribute('data-qty-input') ? document.querySelector(btn.getAttribute('data-qty-input')) : null;
    if (!qtyInput) {
      // Thẻ sản phẩm: ô số lượng nằm cạnh nút trong cùng thẻ (một sản phẩm có thể xuất hiện ở nhiều mục nên không dùng id).
      var card = btn.closest('.bh-card');
      qtyInput = card ? card.querySelector('[data-card-qty]') : null;
    }
    var quantity = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;
    if (qtyInput) {
      var maxQty = parseInt(qtyInput.getAttribute('max') || '0', 10);
      quantity = Math.max(1, maxQty > 0 ? Math.min(quantity, maxQty) : quantity);
      qtyInput.value = quantity;
    }
    btn.disabled = true;
    post('/api/cart/add', { product_id: btn.getAttribute('data-add-to-cart'), quantity: quantity }).then(function (res) {
      btn.disabled = false;
      if (res.status === 401) {
        if (res.message && window.sessionStorage) {
          sessionStorage.setItem('bh_auth_notice', res.message);
        }
        window.location.href = res.login_url ? url(res.login_url) : url('/login');
        return;
      }
      toast(res.message, res.ok ? 'success' : 'danger');
      if (typeof res.count !== 'undefined') { updateCartBadge(res.count); }
    }).catch(function () { btn.disabled = false; toast('Không thể kết nối máy chủ.', 'danger'); });
  });


  // ---------- Cart page ----------
  function applyCartResponse(res) {
    toast(res.message, res.ok ? 'success' : 'danger');
    if (typeof res.count !== 'undefined') { updateCartBadge(res.count); }
    if (!res.ok) { return false; }
    document.querySelectorAll('[data-cart-subtotal]').forEach(function (n) { n.textContent = res.subtotal; });
    var fee = parseInt(document.body.getAttribute('data-shipping-fee') || '0', 10);
    document.querySelectorAll('[data-cart-total]').forEach(function (n) {
      n.textContent = new Intl.NumberFormat('vi-VN').format(res.subtotal_raw + (res.count > 0 ? fee : 0)) + ' ₫';
    });
    return true;
  }


  document.querySelectorAll('[data-cart-qty]').forEach(function (input) {
    var row = input.closest('[data-cart-row]');
    var productId = row ? row.getAttribute('data-cart-row') : null;
    if (!row || !productId) { return; }
    function send(value) {
      post('/api/cart/update', { product_id: productId, quantity: value }).then(function (res) {
        if (!applyCartResponse(res)) { window.location.reload(); return; }
        if (value < 1 || !res.lines || !res.lines[productId]) {
          row.remove();
          if (res.count === 0) { window.location.reload(); }
          return;
        }
        input.value = res.lines[productId].quantity;
        var lineTotal = row.querySelector('[data-line-total]');
        if (lineTotal) { lineTotal.textContent = res.lines[productId].line_total; }
      });
    }
    input.addEventListener('change', function () { send(parseInt(input.value, 10) || 1); });
    row.querySelectorAll('[data-qty-step]').forEach(function (b) {
      b.addEventListener('click', function () {
        send((parseInt(input.value, 10) || 1) + parseInt(b.getAttribute('data-qty-step'), 10));
      });
    });
  });


  document.querySelectorAll('[data-cart-remove]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var row = btn.closest('[data-cart-row]');
      var productId = row ? row.getAttribute('data-cart-row') : null;
      if (!row || !productId) { return; }
      post('/api/cart/remove', { product_id: productId }).then(function (res) {
        if (applyCartResponse(res)) { row.remove(); if (res.count === 0) { window.location.reload(); } }
      });
    });
  });


  // ---------- Quantity stepper (product detail) ----------
  document.querySelectorAll('.bh-qty').forEach(function (box) {
    var input = box.querySelector('input');
    if (!input) { return; }
    box.querySelectorAll('[data-step]').forEach(function (b) {
      b.addEventListener('click', function () {
        var max = parseInt(input.getAttribute('max') || '999', 10);
        var next = (parseInt(input.value, 10) || 1) + parseInt(b.getAttribute('data-step'), 10);
        input.value = Math.min(Math.max(1, next), max);
      });
    });
  });


  // ---------- Product gallery ----------
  document.querySelectorAll('[data-gallery-thumb]').forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      var main = document.getElementById('bhGalleryMain');
      if (main) { main.src = thumb.getAttribute('data-gallery-thumb'); }
      document.querySelectorAll('[data-gallery-thumb]').forEach(function (t) { t.classList.remove('is-active'); });
      thumb.classList.add('is-active');
    });
  });


  // ---------- Notifications: mark all read when the bell dropdown opens ----------
  var notifBtn = document.getElementById('bhNotifBtn');
  if (notifBtn) {
    notifBtn.addEventListener('shown.bs.dropdown', function () {
      var badge = document.getElementById('bhNotifBadge');
      if (!badge || badge.style.display === 'none') { return; }
      post('/notifications/mark-read', {}).then(function (res) {
        if (!res.ok) { return; }
        badge.style.display = 'none';
        badge.textContent = '0';
        document.querySelectorAll('.bh-notif-menu__item.is-unread').forEach(function (el) { el.classList.remove('is-unread'); });
      });
    });
  }


  // ---------- Toggle hidden items (e.g. "Hiển thị tất cả" on the homepage category grid) ----------
  document.querySelectorAll('[data-toggle-categories]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var grid = document.getElementById(btn.getAttribute('aria-controls'));
      if (!grid) { return; }
      var expanded = btn.getAttribute('aria-expanded') === 'true';
      grid.querySelectorAll('.bh-cat-extra').forEach(function (el) { el.classList.toggle('d-none', expanded); });
      btn.setAttribute('aria-expanded', String(!expanded));
      btn.textContent = expanded ? 'Hiển thị tất cả' : 'Thu gọn';
    });
  });


  // ---------- Price range label ----------
  var range = document.getElementById('bhPriceRange');
  if (range) {
    var out = document.getElementById('bhPriceOutput');
    if (out) {
      var update = function () { out.textContent = new Intl.NumberFormat('vi-VN').format(range.value) + ' ₫'; };
      range.addEventListener('input', update);
      update();
    }
  }


  // ---------- Show/hide password ----------
  document.querySelectorAll('input[type="password"]').forEach(function (input) {
    var wrap = document.createElement('div');
    wrap.className = 'bh-password-field';
    input.parentNode.insertBefore(wrap, input);
    wrap.appendChild(input);
    input.classList.add('bh-password-field__input');


    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'bh-password-field__toggle';
    btn.setAttribute('aria-label', 'Hiện mật khẩu');
    btn.innerHTML = '<i class="bi bi-eye"></i>';
    btn.addEventListener('click', function () {
      var shown = input.type === 'text';
      input.type = shown ? 'password' : 'text';
      btn.setAttribute('aria-label', shown ? 'Hiện mật khẩu' : 'Ẩn mật khẩu');
      btn.innerHTML = shown ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
    wrap.appendChild(btn);
  });


  // ---------- Auto-submit selects ----------
  document.querySelectorAll('[data-autosubmit]').forEach(function (el) {
    el.addEventListener('change', function () { if (el.form) { el.form.submit(); } });
  });


  // ---------- Confirm modal ----------
  var confirmModalEl = document.getElementById('bhConfirmModal');
  if (confirmModalEl && window.bootstrap) {
    var confirmModal = new bootstrap.Modal(confirmModalEl);
    var pendingForm = null;
    document.addEventListener('submit', function (event) {
      var form = event.target;
      if (!form.hasAttribute('data-confirm') || form.dataset.confirmed === '1') { return; }
      event.preventDefault();
      pendingForm = form;
      var msgEl = confirmModalEl.querySelector('[data-confirm-message]');
      if (msgEl) { msgEl.textContent = form.getAttribute('data-confirm'); }
      confirmModal.show();
    });
    var okBtn = confirmModalEl.querySelector('[data-confirm-ok]');
    if (okBtn) {
      okBtn.addEventListener('click', function () {
        if (pendingForm) {
          pendingForm.dataset.confirmed = '1';
          confirmModal.hide();
          HTMLFormElement.prototype.submit.call(pendingForm);
        }
      });
    }
  }


  // ---------- Image preview (admin) ----------
  document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
    var target = document.querySelector(input.getAttribute('data-preview'));
    input.addEventListener('change', function () {
      if (!target) { return; }
      target.querySelectorAll('img[data-object-url]').forEach(function (oldImg) {
        URL.revokeObjectURL(oldImg.getAttribute('data-object-url'));
      });
      target.innerHTML = '';
      Array.prototype.forEach.call(input.files, function (file) {
        var url = URL.createObjectURL(file);
        var img = document.createElement('img');
        img.src = url;
        img.setAttribute('data-object-url', url);
        img.alt = 'Xem trước';
        img.className = 'bh-thumb me-2 mb-2';
        img.style.width = '84px';
        img.style.height = '84px';
        target.appendChild(img);
      });
    });
  });
})();

