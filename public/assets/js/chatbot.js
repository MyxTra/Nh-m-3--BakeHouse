/* BakeHouse chatbot: streaming UI (SSE over fetch), safe markdown + links + tables, live qty/total, cart sync. */
(function () {
  'use strict';

  var root = document.getElementById('bhChat');
  var fab = document.getElementById('bhChatToggle');
  if (!root || !fab) { return; }

  var PLACEHOLDER_SVG = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(
    '<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80">' +
    '<rect width="80" height="80" rx="10" fill="#f3ece0"/>' +
    '<circle cx="40" cy="34" r="12" fill="#d8c7ae"/>' +
    '<path d="M18 64c4-11 14-16 22-16s18 5 22 16" fill="#d8c7ae"/>' +
    '</svg>'
  );

  function getBasePath() {
    var path = window.location.pathname || '/';
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
    var raw = String(p).trim();
    if (/^(?:https?:)?\/\//i.test(raw) || raw.indexOf('data:') === 0) { return raw; }
    if (BASE_PATH && raw.indexOf(BASE_PATH + '/') === 0) { return raw; }
    var clean = raw.charAt(0) === '/' ? raw : '/' + raw;
    if (clean === '/contact' || clean === '/orders' || clean === '/cart' || clean === '/checkout' || clean === '/login' || clean.indexOf('/products/') === 0) {
      if (clean.indexOf('/public/index.php') !== 0) {
        clean = '/public/index.php' + clean;
      }
    }
    return BASE_PATH + clean;
  }

  function sanitizeHref(rawHref) {
    var h = String(rawHref || '').trim();
    if (!h || /^(?:javascript|data|vbscript):/i.test(h)) { return null; }
    if (/^(?:https?:)?\/\//i.test(h)) { return h; }
    return url(h);
  }

  var BH = window.BH || {};
  var STORE_KEY = 'bh_chat_v1';
  var MAX_HISTORY = 10;
  var HINT = '';
  var SUGGESTIONS = [
    { icon: 'bi-cake2', text: 'Mình muốn làm bánh bông lan, ngân sách dưới 300k' },
    { icon: 'bi-cookie', text: 'Mới tập làm cookie thì cần mua gì?' },
    { icon: 'bi-truck', text: 'Phí ship và các hình thức thanh toán?' },
    { icon: 'bi-receipt', text: 'Đơn hàng của mình đến đâu rồi?' }
  ];

  var scroller = document.getElementById('bhChatScroll');
  var list = document.getElementById('bhChatList');
  var jump = document.getElementById('bhChatJump');
  var form = document.getElementById('bhChatForm');
  var input = document.getElementById('bhChatInput');
  var sendBtn = document.getElementById('bhChatSend');
  var statusText = root.querySelector('[data-chat-status]');
  var hintEl = root.querySelector('.bh-chat__hint');
  var expandBtn = root.querySelector('[data-chat-expand]');

  var state = { open: false, expanded: false, messages: [] };
  var busy = false;
  var follow = true;
  var controller = null;
  var coarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
  var mobileMq = window.matchMedia ? window.matchMedia('(max-width: 575.98px)') : { matches: false };
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function el(tag, cls, html) {
    var n = document.createElement(tag);
    if (cls) { n.className = cls; }
    if (html !== undefined) { n.innerHTML = html; }
    return n;
  }

  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function formatVnd(amount) {
    var n = Math.max(0, Math.round(Number(amount) || 0));
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' ₫';
  }

  function parseUnitPrice(p) {
    if (p && typeof p.unit_price === 'number' && !isNaN(p.unit_price)) { return Math.max(0, p.unit_price); }
    if (p && typeof p.price_raw === 'number' && !isNaN(p.price_raw)) { return Math.max(0, p.price_raw); }
    var raw = String((p && p.price) || '').trim();
    if (!raw) { return 0; }
    var m = raw.match(/[\d.,]+/);
    if (!m) { return 0; }
    var digits = m[0].replace(/[^\d]/g, '');
    return parseInt(digits, 10) || 0;
  }

  function notify(msg, type) {
    var text = String(msg || '').trim();
    if (!text) { return; }
    if (typeof BH.toast === 'function') {
      BH.toast(text, type || 'success');
      return;
    }
    var toastBox = root.querySelector('.bh-chat__toast');
    if (!toastBox) {
      toastBox = el('div', 'bh-chat__toast');
      toastBox.setAttribute('role', 'status');
      toastBox.setAttribute('aria-live', 'polite');
      root.appendChild(toastBox);
    }
    toastBox.textContent = text;
    toastBox.className = 'bh-chat__toast is-show is-' + (type || 'success');
    clearTimeout(toastBox._t);
    toastBox._t = setTimeout(function () {
      toastBox.classList.remove('is-show');
    }, 2400);
  }

  function save() {
    try {
      sessionStorage.setItem(STORE_KEY, JSON.stringify({
        open: state.open,
        expanded: state.expanded,
        messages: state.messages.slice(-40)
      }));
    } catch (e) { /* storage unavailable */ }
  }

  function load() {
    try {
      var raw = sessionStorage.getItem(STORE_KEY);
      if (!raw) { return; }
      var data = JSON.parse(raw);
      if (data && Array.isArray(data.messages)) {
        state.open = !!data.open;
        state.expanded = !!data.expanded;
        state.messages = data.messages.filter(function (m) {
          return m && (m.role === 'user' || m.role === 'assistant') && typeof m.content === 'string';
        });
      }
    } catch (e) { /* ignore corrupt state */ }
  }

  function inline(text) {
    var tokens = [];
    var raw = String(text);

    raw = raw.replace(/\[([^\]\n]+)\]\(([^)\s]+)\)/g, function (match, label, href) {
      var safeUrl = sanitizeHref(href);
      if (!safeUrl) { return match; }
      var idx = tokens.length;
      var isExternal = /^(?:https?:)?\/\//i.test(safeUrl);
      var targetAttr = isExternal ? ' target="_blank" rel="noopener noreferrer"' : '';
      tokens.push('<a href="' + esc(safeUrl) + '"' + targetAttr + '>' + esc(label) + '</a>');
      return '@@BHTOK' + idx + '@@';
    });

    raw = raw.replace(/(^|[\s(])(\/(?:contact|orders|cart|checkout|login|register|products\/[^\s).,!?]+))/g, function (match, prefix, pathPart) {
      var idx = tokens.length;
      tokens.push('<a href="' + esc(url(pathPart)) + '">' + esc(pathPart) + '</a>');
      return prefix + '@@BHTOK' + idx + '@@';
    });

    raw = raw.replace(/(^|[\s(])(đăng nhập)(?=[\s).,!?]|$)/gi, function (match, prefix, word) {
      var idx = tokens.length;
      tokens.push('<a href="' + esc(url('/login')) + '">' + esc(word) + '</a>');
      return prefix + '@@BHTOK' + idx + '@@';
    });

    var s = esc(raw);
    s = s.replace(/`([^`\n]+)`/g, '<code>$1</code>');
    s = s.replace(/\*\*([^*\n]+)\*\*/g, '<strong>$1</strong>');
    s = s.replace(/(^|[\s(])\*([^*\s][^*\n]*?)\*(?=[\s).,!?:;]|$)/g, '$1<em>$2</em>');

    s = s.replace(/@@BHTOK(\d+)@@/g, function (_, idxStr) {
      var i = parseInt(idxStr, 10);
      return tokens[i] !== undefined ? tokens[i] : '';
    });
    return s;
  }

  function splitTableRow(line) {
    var trimmed = line.trim().replace(/^\|/, '').replace(/\|$/, '');
    return trimmed.split('|').map(function (c) { return c.trim(); });
  }

  function isTableSep(line) {
    var cells = splitTableRow(line);
    if (!cells.length) { return false; }
    return cells.every(function (c) { return /^:?-{2,}:?$/.test(c); });
  }

  function renderTableBlock(rows) {
    var headers = splitTableRow(rows[0]);
    var bodyRows = rows.slice(2).map(splitTableRow);
    var html = '<div class="bh-table-wrap"><table><thead><tr>';
    headers.forEach(function (h) { html += '<th>' + inline(h) + '</th>'; });
    html += '</tr></thead><tbody>';
    bodyRows.forEach(function (r) {
      html += '<tr>';
      for (var i = 0; i < headers.length; i++) {
        html += '<td>' + inline(r[i] || '') + '</td>';
      }
      html += '</tr>';
    });
    html += '</tbody></table></div>';
    return html;
  }

  function markdown(src) {
    var lines = String(src).replace(/\r/g, '').split('\n');
    var out = [];
    var para = [];
    var listType = null;
    var items = [];
    var i = 0;

    function flushPara() {
      if (para.length) { out.push('<p>' + para.map(inline).join('<br>') + '</p>'); para = []; }
    }
    function flushList() {
      if (listType) {
        out.push('<' + listType + '>' + items.map(function (it) { return '<li>' + inline(it) + '</li>'; }).join('') + '</' + listType + '>');
        listType = null; items = [];
      }
    }

    while (i < lines.length) {
      var line = lines[i];

      if (line.indexOf('|') !== -1 && i + 1 < lines.length && lines[i + 1].indexOf('|') !== -1 && isTableSep(lines[i + 1])) {
        flushPara();
        flushList();
        var tblLines = [line, lines[i + 1]];
        i += 2;
        while (i < lines.length && lines[i].indexOf('|') !== -1 && !/^\s*$/.test(lines[i])) {
          tblLines.push(lines[i]);
          i++;
        }
        out.push(renderTableBlock(tblLines));
        continue;
      }

      var bullet = line.match(/^\s*(?:[-*•])\s+(.*)$/);
      var number = line.match(/^\s*\d+[.)]\s+(.*)$/);
      var heading = line.match(/^\s*#{1,6}\s+(.*)$/);
      if (bullet || number) {
        flushPara();
        var type = bullet ? 'ul' : 'ol';
        if (listType && listType !== type) { flushList(); }
        listType = type;
        items.push((bullet || number)[1]);
      } else if (/^\s*$/.test(line)) {
        flushPara(); flushList();
      } else if (heading) {
        flushPara(); flushList();
        out.push('<p><strong>' + inline(heading[1]) + '</strong></p>');
      } else {
        flushList();
        para.push(line);
      }
      i++;
    }
    flushPara(); flushList();
    return out.join('');
  }

  function atBottom() {
    return scroller.scrollHeight - scroller.scrollTop - scroller.clientHeight < 72;
  }

  function scrollToBottom(smooth) {
    follow = true;
    jump.hidden = true;
    scroller.scrollTo({ top: scroller.scrollHeight, behavior: smooth && !reduceMotion ? 'smooth' : 'auto' });
  }

  function afterGrow() {
    if (follow) { scroller.scrollTop = scroller.scrollHeight; } else if (busy) { jump.hidden = false; }
  }

  scroller.addEventListener('scroll', function () {
    follow = atBottom();
    if (follow) { jump.hidden = true; }
  }, { passive: true });
  jump.addEventListener('click', function () { scrollToBottom(true); });

  function clearWelcome() {
    var w = list.querySelector('.bh-welcome');
    if (w) { w.remove(); }
  }

  function renderWelcome() {
    var w = el('div', 'bh-welcome');
    w.innerHTML = '<div class="bh-welcome__logo" aria-hidden="true"><i class="bi bi-stars"></i></div>' +
      '<h3 class="bh-welcome__title">Xin chào! 👋</h3>' +
      '<p class="bh-welcome__sub">Mình là trợ lý BakeHouse. Hãy cho mình biết món bạn muốn làm, mình sẽ gợi ý nguyên liệu và dụng cụ có sẵn trong shop.</p>';
    var chips = el('div', 'bh-chips');
    SUGGESTIONS.forEach(function (s) {
      var b = el('button', 'bh-chip');
      b.type = 'button';
      b.innerHTML = '<i class="bi ' + s.icon + '" aria-hidden="true"></i><span></span>';
      b.querySelector('span').textContent = s.text;
      b.addEventListener('click', function () { send(s.text); });
      chips.appendChild(b);
    });
    w.appendChild(chips);
    list.appendChild(w);
  }

  function addUser(text) {
    clearWelcome();
    var row = el('div', 'bh-msg bh-msg--user');
    var bubble = el('div', 'bh-msg__bubble');
    bubble.textContent = text;
    row.appendChild(bubble);
    list.appendChild(row);
    return row;
  }

  function createAssistant() {
    clearWelcome();
    var row = el('div', 'bh-msg bh-msg--bot');
    row.innerHTML = '<div class="bh-msg__avatar" aria-hidden="true"><i class="bi bi-stars"></i></div>' +
      '<div class="bh-msg__col"><div class="bh-msg__status"></div><div class="bh-msg__text"></div></div>';
    list.appendChild(row);
    return {
      row: row,
      col: row.querySelector('.bh-msg__col'),
      status: row.querySelector('.bh-msg__status'),
      text: row.querySelector('.bh-msg__text')
    };
  }

  function setStatus(view, message) {
    if (!message) { view.status.innerHTML = ''; return; }
    view.status.innerHTML = '<span class="bh-dots" aria-hidden="true"><i></i><i></i><i></i></span><span class="bh-shimmer"></span>';
    view.status.querySelector('.bh-shimmer').textContent = message;
  }

  function isCartPage() {
    return /\/cart(?:\/|$|\?)/i.test(window.location.pathname || '') ||
      !!document.querySelector('[data-cart-page], #cartPage, .bh-cart-page, form[action*="/cart/update"]');
  }

  function refreshCartDomInPlace() {
    if (!isCartPage()) { return; }
    fetch(window.location.href, {
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function (r) {
      return r.ok ? r.text() : '';
    }).then(function (html) {
      if (!html) { return; }
      var parser = new DOMParser();
      var doc = parser.parseFromString(html, 'text/html');
      var selectors = ['[data-cart-content]', '#cartContent', '.bh-cart-content', 'main .container', 'main'];
      for (var i = 0; i < selectors.length; i++) {
        var nextEl = doc.querySelector(selectors[i]);
        var curEl = document.querySelector(selectors[i]);
        if (nextEl && curEl) {
          curEl.innerHTML = nextEl.innerHTML;
          document.dispatchEvent(new CustomEvent('bh:cart:refreshed'));
          break;
        }
      }
    }).catch(function () { /* giữ nguyên giao diện nếu fetch phụ lỗi */ });
  }

  function postCart(productId, quantity) {
    var qty = Math.max(1, parseInt(quantity, 10) || 1);
    var body = new FormData();
    body.append('product_id', productId);
    body.append('quantity', qty);
    return fetch(url('/public/index.php/api/cart/add'), {
      method: 'POST',
      body: body,
      credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': BH.csrf || '', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    }).then(function (res) {
      return res.json().catch(function () { return { ok: false, message: 'Có lỗi xảy ra.' }; })
        .then(function (json) { json.status = res.status; return json; });
    });
  }

  function resetAddButton(btn) {
    btn.disabled = false;
    btn.classList.remove('is-done');
    btn.innerHTML = '<i class="bi bi-cart-plus"></i><span class="bh-add__label">Thêm</span>';
  }

  function flashAdded(btn, qty) {
    btn.classList.add('is-done');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-lg"></i><span class="bh-add__label">+' + qty + '</span>';
    clearTimeout(btn._flashTimer);
    btn._flashTimer = setTimeout(function () {
      resetAddButton(btn);
    }, 900);
  }

  function addOne(item, opts) {
    var silentToast = !!(opts && opts.silentToast);
    var btn = item.btn;
    var productId = item.id;
    var qty = item.getQty ? item.getQty() : 1;
    if (btn.dataset.adding === '1') { return Promise.resolve(false); }
    btn.dataset.adding = '1';
    btn.disabled = true;
    if (item.syncStepper) { item.syncStepper(); }
    btn.innerHTML = '<span class="bh-spin"></span>';

    return postCart(productId, qty).then(function (res) {
      btn.dataset.adding = '0';
      if (res.status === 401) {
        resetAddButton(btn);
        if (item.syncStepper) { item.syncStepper(); }
        window.location.href = res.login_url ? url(res.login_url) : url('/public/index.php/login');
        return false;
      }
      if (!res.ok) {
        resetAddButton(btn);
        if (item.syncStepper) { item.syncStepper(); }
        notify(res.message || 'Không thể thêm vào giỏ hàng.', 'danger');
        return false;
      }
      flashAdded(btn, qty);
      if (item.syncStepper) { item.syncStepper(); }
      if (typeof BH.updateCartBadge === 'function' && typeof res.count !== 'undefined') {
        BH.updateCartBadge(res.count);
      }
      document.dispatchEvent(new CustomEvent('bh:cart:added', { detail: { productId: productId, quantity: qty, response: res } }));
      if (!silentToast) {
        notify(res.message || ('Đã thêm ' + qty + ' ' + (item.name || 'sản phẩm') + ' vào giỏ hàng.'), 'success');
      }
      if (isCartPage()) {
        refreshCartDomInPlace();
      }
      return true;
    }).catch(function () {
      btn.dataset.adding = '0';
      resetAddButton(btn);
      if (item.syncStepper) { item.syncStepper(); }
      notify('Không thể kết nối máy chủ.', 'danger');
      return false;
    });
  }

  function resolveProductImage(p) {
    var raw = (p && (p.image_url || p.image_path || p.image)) || '';
    raw = String(raw).trim();
    if (!raw) { return PLACEHOLDER_SVG; }
    if (/^(?:https?:)?\/\//i.test(raw) || raw.indexOf('data:') === 0) { return raw; }
    if (BASE_PATH && raw.indexOf(BASE_PATH + '/') === 0) { return raw; }
    return url(raw);
  }

  function inferScaleFromRecentUser() {
    if (!state.messages || !state.messages.length) { return 1; }
    var lastUser = '';
    for (var k = state.messages.length - 1; k >= 0; k--) {
      if (state.messages[k].role === 'user') {
        lastUser = state.messages[k].content || '';
        break;
      }
    }
    var mQty = lastUser.match(/\b(\d{1,2})\s*(?:người|ng|suất|phần|chiếc|cái|bánh|kg)\b/i);
    if (!mQty) { return 1; }
    var val = parseInt(mQty[1], 10);
    return (val > 1 && val <= 50) ? val : 1;
  }

  function renderProducts(view, products, total) {
    if (!products || !products.length) { return; }
    var wrap = el('div', 'bh-products');
    var items = [];
    var sumTotalEl = null;
    var hasKnownUnitPrices = false;
    var inferredScale = inferScaleFromRecentUser();

    function recalcBundleTotal() {
      if (!sumTotalEl) { return; }
      var sum = 0;
      items.forEach(function (it) {
        sum += it.unitPrice * it.getQty();
      });
      if (hasKnownUnitPrices && sum > 0) {
        sumTotalEl.textContent = formatVnd(sum);
      } else if (total) {
        sumTotalEl.textContent = total;
      }
    }

    products.forEach(function (p) {
      var maxQty = Math.max(1, parseInt(p.stock_quantity, 10) || 99);
      var rawQty = parseInt(p.quantity, 10) || 1;
      if (rawQty === 1 && inferredScale > 1) {
        rawQty = inferredScale;
      }
      var qty = Math.min(maxQty, Math.max(1, rawQty));
      var unitPrice = parseUnitPrice(p);
      if (unitPrice > 0) { hasKnownUnitPrices = true; }

      var card = el('div', 'bh-product');
      card.innerHTML =
        '<img class="bh-product__img" alt="" loading="lazy">' +
        '<div class="bh-product__info">' +
          '<a class="bh-product__name"></a>' +
          '<div class="bh-product__price"></div>' +
          '<div class="bh-product__sub"></div>' +
        '</div>' +
        '<div class="bh-product__actions">' +
          '<div class="bh-qty" role="group" aria-label="Số lượng">' +
            '<button type="button" class="bh-qty__btn bh-qty--minus" aria-label="Giảm số lượng">−</button>' +
            '<span class="bh-qty__val"></span>' +
            '<button type="button" class="bh-qty__btn bh-qty--plus" aria-label="Tăng số lượng">+</button>' +
          '</div>' +
          '<button type="button" class="bh-add"><i class="bi bi-cart-plus"></i><span class="bh-add__label">Thêm</span></button>' +
        '</div>';

      var img = card.querySelector('img');
      img.alt = '';
      img.src = resolveProductImage(p);
      img.addEventListener('error', function () {
        if (img.src !== PLACEHOLDER_SVG) {
          img.src = PLACEHOLDER_SVG;
        }
      });

      var link = card.querySelector('a');
      link.textContent = p.name;
      link.href = url('/public/index.php/products/' + encodeURIComponent(p.slug));

      var price = card.querySelector('.bh-product__price');
      price.textContent = p.price;
      if (p.unit) {
        var unit = el('span');
        unit.textContent = ' / ' + p.unit;
        price.appendChild(unit);
      }

      var subEl = card.querySelector('.bh-product__sub');
      var minusBtn = card.querySelector('.bh-qty--minus');
      var plusBtn = card.querySelector('.bh-qty--plus');
      var qtyEl = card.querySelector('.bh-qty__val');
      var btn = card.querySelector('.bh-add');

      function updateCardSubtotal() {
        if (unitPrice > 0) {
          subEl.textContent = 'Tạm tính (' + qty + '): ' + formatVnd(unitPrice * qty);
        } else {
          subEl.textContent = 'Số lượng: ' + qty;
        }
      }

      function syncStepper() {
        var locked = btn.dataset.adding === '1';
        qtyEl.textContent = String(qty);
        minusBtn.disabled = locked || qty <= 1;
        plusBtn.disabled = locked || qty >= maxQty;
        updateCardSubtotal();
        recalcBundleTotal();
      }

      syncStepper();

      minusBtn.addEventListener('click', function () {
        if (qty > 1) {
          qty--;
          syncStepper();
        }
      });

      plusBtn.addEventListener('click', function () {
        if (qty < maxQty) {
          qty++;
          syncStepper();
        }
      });

      var entry = {
        btn: btn,
        id: p.id,
        name: p.name,
        unitPrice: unitPrice,
        getQty: function () { return qty; },
        syncStepper: syncStepper
      };

      btn.setAttribute('aria-label', 'Thêm ' + p.name + ' vào giỏ hàng');
      btn.addEventListener('click', function () { addOne(entry); });
      items.push(entry);
      wrap.appendChild(card);
    });

    if (products.length > 1) {
      var sum = el('div', 'bh-products__sum');
      var label = el('span');
      label.innerHTML = products.length + ' sản phẩm · <strong></strong>';
      sumTotalEl = label.querySelector('strong');
      recalcBundleTotal();

      var all = el('button', 'bh-addall', '<i class="bi bi-bag-plus"></i> Thêm tất cả');
      all.type = 'button';
      all.addEventListener('click', function () {
        all.disabled = true;
        var added = 0;
        items.reduce(function (chain, item) {
          return chain.then(function () {
            return addOne(item, { silentToast: true }).then(function (ok) { if (ok) { added++; } });
          });
        }, Promise.resolve()).then(function () {
          all.disabled = false;
          if (added) {
            notify('Đã thêm ' + added + ' sản phẩm vào giỏ hàng.', 'success');
          } else {
            notify('Không thể thêm sản phẩm vào giỏ hàng.', 'danger');
          }
        });
      });
      sum.appendChild(label);
      sum.appendChild(all);
      wrap.appendChild(sum);
    }
    view.col.appendChild(wrap);
  }

  function renderStoredAssistant(m) {
    var view = createAssistant();
    view.text.innerHTML = markdown(m.content);
    renderProducts(view, m.products, m.total);
  }

  function renderAll() {
    list.innerHTML = '';
    if (!state.messages.length) { renderWelcome(); return; }
    state.messages.forEach(function (m) {
      if (m.role === 'user') { addUser(m.content); } else { renderStoredAssistant(m); }
    });
    list.querySelectorAll('.bh-msg').forEach(function (n) { n.style.animation = 'none'; });
  }

  function parseEvent(block) {
    var event = 'message';
    var data = [];
    block.split('\n').forEach(function (line) {
      if (line.indexOf('event:') === 0) { event = line.slice(6).trim(); }
      else if (line.indexOf('data:') === 0) { data.push(line.slice(5).replace(/^ /, '')); }
    });
    if (!data.length) { return null; }
    try { return { event: event, data: JSON.parse(data.join('\n')) }; } catch (e) { return null; }
  }

  function requestBody(history) {
    var body = new FormData();
    body.append('messages', JSON.stringify(history));
    return body;
  }

  function headers(accept) {
    return { 'X-CSRF-TOKEN': BH.csrf || '', 'X-Requested-With': 'XMLHttpRequest', 'Accept': accept };
  }

  function streamChat(history, onEvent, signal) {
    if (!window.fetch || !window.ReadableStream || !window.TextDecoder) { return Promise.reject({ fallback: true }); }
    return fetch(url('/public/index.php/api/chatbot/stream'), {
      method: 'POST', body: requestBody(history), headers: headers('text/event-stream'), credentials: 'same-origin', signal: signal
    }).then(function (res) {
      var type = res.headers.get('Content-Type') || '';
      if (!res.ok || type.indexOf('text/event-stream') === -1) {
        return res.json().catch(function () { return {}; }).then(function (json) {
          throw { http: res.status, message: json.message };
        });
      }
      if (!res.body || !res.body.getReader) { throw { fallback: true }; }
      var reader = res.body.getReader();
      var decoder = new TextDecoder('utf-8');
      var buffer = '';
      function pump() {
        return reader.read().then(function (chunk) {
          if (chunk.done) { return; }
          buffer += decoder.decode(chunk.value, { stream: true });
          var parts = buffer.split('\n\n');
          buffer = parts.pop();
          parts.forEach(function (part) {
            var evt = parseEvent(part);
            if (evt) { onEvent(evt.event, evt.data); }
          });
          return pump();
        });
      }
      return pump();
    });
  }

  function plainChat(history, signal) {
    return fetch(url('/public/index.php/api/chatbot/chat'), {
      method: 'POST', body: requestBody(history), headers: headers('application/json'), credentials: 'same-origin', signal: signal
    }).then(function (res) {
      return res.json().catch(function () { return {}; }).then(function (json) {
        if (!res.ok || !json.ok) { throw { http: res.status, message: json.message }; }
        return json;
      });
    });
  }

  function Typer(view) {
    this.view = view;
    this.target = '';
    this.shown = 0;
    this.raf = 0;
    this.onDrained = null;
  }
  Typer.prototype.push = function (text) { this.target += text; this.kick(); };
  Typer.prototype.reset = function () { this.target = ''; this.shown = 0; this.paint(); };
  Typer.prototype.replace = function (text) {
    if (text.indexOf(this.target.slice(0, this.shown)) !== 0) { this.shown = text.length; }
    this.target = text;
    this.kick();
  };
  Typer.prototype.kick = function () {
    var self = this;
    if (self.raf) { return; }
    self.raf = requestAnimationFrame(function tick() {
      self.raf = 0;
      var backlog = self.target.length - self.shown;
      if (backlog > 0) {
        var step = reduceMotion ? backlog : Math.max(1, Math.ceil(backlog / 9));
        var next = self.shown + step;
        var code = self.target.charCodeAt(next - 1);
        if (code >= 0xD800 && code <= 0xDBFF) { next++; }
        self.shown = Math.min(next, self.target.length);
        self.paint();
      }
      if (self.target.length > self.shown) { self.raf = requestAnimationFrame(tick); }
      else if (self.onDrained) { var cb = self.onDrained; self.onDrained = null; cb(); }
    });
  };
  Typer.prototype.paint = function () {
    var text = this.target.slice(0, this.shown);
    this.view.text.innerHTML = text ? markdown(text) : '';
    afterGrow();
  };
  Typer.prototype.finish = function (cb) {
    if (this.target.length <= this.shown && !this.raf) { cb(); return; }
    this.onDrained = cb;
    this.kick();
  };
  Typer.prototype.stop = function (skipPaint) {
    if (this.raf) { cancelAnimationFrame(this.raf); this.raf = 0; }
    this.shown = this.target.length;
    if (!skipPaint) { this.paint(); }
  };

  function setBusy(on) {
    busy = on;
    root.classList.toggle('is-busy', on);
    list.setAttribute('aria-busy', on ? 'true' : 'false');
    statusText.textContent = on ? 'Đang trả lời…' : 'Trực tuyến';
    sendBtn.setAttribute('data-state', on ? 'stop' : 'send');
    sendBtn.setAttribute('aria-label', on ? 'Dừng trả lời' : 'Gửi câu hỏi');
    refreshSend();
  }

  function historyForServer() {
    return state.messages.slice(-MAX_HISTORY).map(function (m) {
      var item = { role: m.role, content: m.content };
      if (m.sig) { item.sig = m.sig; }
      return item;
    });
  }

  function showError(view, message, retryText) {
    view.row.classList.remove('is-streaming');
    setStatus(view, '');
    view.text.innerHTML = '';
    var box = el('div', 'bh-msg__error', '<i class="bi bi-exclamation-circle" aria-hidden="true"></i><span></span>');
    box.querySelector('span').textContent = message;
    if (retryText) {
      var retry = el('button', 'bh-msg__retry', 'Thử lại');
      retry.type = 'button';
      retry.addEventListener('click', function () {
        view.row.remove();
        state.messages.pop();
        if (list.lastElementChild && list.lastElementChild.classList.contains('bh-msg--user')) {
          list.lastElementChild.remove();
        }
        send(retryText);
      });
      box.appendChild(retry);
    }
    view.col.appendChild(box);
    afterGrow();
  }

  function send(text) {
    text = String(text || '').trim();
    if (!text || busy) { return; }

    state.messages.push({ role: 'user', content: text });
    addUser(text);
    input.value = '';
    autosize();
    scrollToBottom(false);
    save();

    var view = createAssistant();
    view.row.classList.add('is-streaming');
    setStatus(view, 'Đang suy nghĩ…');
    var typer = new Typer(view);
    var started = false;
    var finalPayload = null;
    var failed = false;
    controller = window.AbortController ? new AbortController() : null;
    setBusy(true);
    scrollToBottom(false);

    function onEvent(event, data) {
      if (event === 'status') { setStatus(view, data.text); }
      else if (event === 'delta') {
        if (!started) { started = true; setStatus(view, ''); }
        typer.push(data.text);
      }
      else if (event === 'reset') { started = false; typer.reset(); setStatus(view, 'Đang suy nghĩ…'); }
      else if (event === 'final') { finalPayload = data; }
      else if (event === 'error') { failed = true; showError(view, data.message || 'Có lỗi xảy ra.', text); }
    }

    function complete() {
      setBusy(false);
      controller = null;
      view.row.classList.remove('is-streaming');
      setStatus(view, '');
      if (failed || !finalPayload) { return; }
      var p = finalPayload;
      view.text.innerHTML = markdown(p.message);
      renderProducts(view, p.products, p.total);
      state.messages.push({ role: 'assistant', content: p.message, sig: p.sig, products: p.products, total: p.total });
      save();
      afterGrow();
      if (p.products && p.products.length && follow) { scrollToBottom(true); }
    }

    function finishFlow() {
      if (failed) { setBusy(false); controller = null; return; }
      if (finalPayload) {
        typer.replace(finalPayload.message);
        typer.finish(complete);
      } else {
        typer.stop();
        if (typer.target) {
          state.messages.push({ role: 'assistant', content: typer.target });
          save();
        } else {
          showError(view, 'Kết nối bị gián đoạn. Bạn thử lại nhé.', text);
        }
        setBusy(false);
        controller = null;
        view.row.classList.remove('is-streaming');
      }
    }

    function onFail(err) {
      if (err && err.name === 'AbortError') {
        view.row.classList.remove('is-streaming');
        setStatus(view, '');
        if (typer.target) {
          typer.stop();
          state.messages.push({ role: 'assistant', content: typer.target });
        } else {
          typer.stop(true);
          view.row.remove();
          state.messages.pop();
          if (list.lastElementChild && list.lastElementChild.classList.contains('bh-msg--user')) {
            list.lastElementChild.remove();
          }
          input.value = text;
          autosize();
        }
        save();
        setBusy(false);
        controller = null;
        return;
      }
      typer.stop();
      var msg = (err && err.message) || 'Không thể kết nối máy chủ. Bạn thử lại nhé.';
      showError(view, msg, err && err.http === 429 ? null : text);
      setBusy(false);
      controller = null;
    }

    var signal = controller ? controller.signal : undefined;
    streamChat(historyForServer(), onEvent, signal).then(finishFlow).catch(function (err) {
      if (err && err.fallback) {
        plainChat(historyForServer(), signal).then(function (json) {
          finalPayload = json;
          started = true;
          setStatus(view, '');
          typer.push(json.message);
          finishFlow();
        }).catch(onFail);
        return;
      }
      onFail(err);
    });
  }

  function autosize() {
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 128) + 'px';
    var len = input.value.length;
    if (hintEl) {
      hintEl.textContent = len >= 420 ? len + '/500 ký tự' : HINT;
      hintEl.hidden = len < 420;
    }
  }

  function refreshSend() {
    sendBtn.disabled = !busy && input.value.trim() === '';
  }

  input.addEventListener('input', function () { autosize(); refreshSend(); });
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey && !e.isComposing && e.keyCode !== 229 && !coarse) {
      e.preventDefault();
      if (!busy) { send(input.value); }
    }
  });
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (busy) { if (controller) { controller.abort(); } return; }
    send(input.value);
  });

  function syncViewport() {
    if (!mobileMq.matches || !window.visualViewport) { root.style.removeProperty('--bh-chat-vh'); root.style.top = ''; return; }
    var vv = window.visualViewport;
    root.style.setProperty('--bh-chat-vh', vv.height + 'px');
    root.style.top = vv.offsetTop + 'px';
    if (follow) { scroller.scrollTop = scroller.scrollHeight; }
  }

  function setOpen(open, opts) {
    state.open = open;
    root.classList.toggle('is-open', open);
    fab.setAttribute('aria-expanded', open ? 'true' : 'false');
    fab.setAttribute('aria-label', open ? 'Đóng trợ lý BakeHouse' : 'Mở trợ lý BakeHouse');
    document.documentElement.classList.toggle('bh-chat-lock', open && mobileMq.matches);
    if (open) {
      root.removeAttribute('inert');
      fab.classList.add('is-seen');
      try { sessionStorage.setItem('bh_chat_seen', '1'); } catch (e) {}
      syncViewport();
      if (state.messages.length) { scrollToBottom(false); } else { scroller.scrollTop = 0; }
      if (!(opts && opts.silent) && !coarse) { setTimeout(function () { input.focus(); }, 60); }
    } else {
      var hadFocus = root.contains(document.activeElement);
      root.setAttribute('inert', '');
      if (hadFocus) { fab.focus(); }
    }
    if (!(opts && opts.silent)) { save(); }
  }

  fab.addEventListener('click', function () { setOpen(!state.open); });
  var closeBtn = root.querySelector('[data-chat-close]');
  if (closeBtn) { closeBtn.addEventListener('click', function () { setOpen(false); }); }
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape' || !state.open) { return; }
    if (document.querySelector('.modal.show, .offcanvas.show, .dropdown-menu.show')) { return; }
    setOpen(false);
  });

  if (expandBtn) {
    expandBtn.addEventListener('click', function () {
      state.expanded = !state.expanded;
      root.classList.toggle('is-expanded', state.expanded);
      expandBtn.setAttribute('aria-pressed', state.expanded ? 'true' : 'false');
      expandBtn.innerHTML = '<i class="bi ' + (state.expanded ? 'bi-arrows-angle-contract' : 'bi-arrows-angle-expand') + '"></i>';
      expandBtn.setAttribute('aria-label', state.expanded ? 'Thu nhỏ cửa sổ chat' : 'Mở rộng cửa sổ chat');
      save();
    });
  }

  var newBtn = root.querySelector('[data-chat-new]');
  if (newBtn) {
    newBtn.addEventListener('click', function () {
      if (controller) { controller.abort(); }
      state.messages = [];
      save();
      renderAll();
      setBusy(false);
      input.focus();
    });
  }

  if (window.visualViewport) {
    window.visualViewport.addEventListener('resize', syncViewport);
    window.visualViewport.addEventListener('scroll', syncViewport);
  }
  if (mobileMq.addEventListener) {
    mobileMq.addEventListener('change', function () {
      document.documentElement.classList.toggle('bh-chat-lock', state.open && mobileMq.matches);
      syncViewport();
    });
  }

  load();
  try { if (sessionStorage.getItem('bh_chat_seen')) { fab.classList.add('is-seen'); } } catch (e) {}
  root.classList.toggle('is-expanded', state.expanded);
  if (state.expanded && expandBtn) {
    expandBtn.setAttribute('aria-pressed', 'true');
    expandBtn.innerHTML = '<i class="bi bi-arrows-angle-contract"></i>';
  }
  renderAll();
  setBusy(false);
  setOpen(state.open, { silent: true });
  if (state.messages.length) { scroller.scrollTop = scroller.scrollHeight; }
})();