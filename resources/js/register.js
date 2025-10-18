// resources/js/register.js
// Separated JS to handle SweetAlert2 errors/success on the register page.
// Loads jQuery and SweetAlert2 via CDN if not already available.

function loadScript(src) {
  return new Promise(function (resolve, reject) {
    var s = document.createElement('script');
    s.src = src;
    s.async = true;
    s.onload = resolve;
    s.onerror = reject;
    document.head.appendChild(s);
  });
}

function parseJsonAttr(el, name) {
  try {
    var raw = el.getAttribute(name);
    if (!raw || raw === 'null') return null;
    return JSON.parse(raw);
  } catch (e) {
    return null;
  }
}

async function initRegisterAlerts() {
  var container = document.getElementById('register-data');
  if (!container) return; // only run on register page

  // Ensure jQuery and SweetAlert2 available
  if (!window.jQuery) {
    await loadScript('https://code.jquery.com/jquery-3.7.1.min.js');
  }
  if (!window.Swal) {
    await loadScript('https://cdn.jsdelivr.net/npm/sweetalert2@11');
  }

  var $ = window.jQuery;
  var Swal = window.Swal;

  $(function () {
    var errors = parseJsonAttr(container, 'data-errors') || [];
    var successMsg = parseJsonAttr(container, 'data-success');
    var errorMsg = parseJsonAttr(container, 'data-error');

    if (errors && errors.length) {
      Swal.fire({
        icon: 'error',
        title: 'Please fix the following',
        html:
          '<div class="text-left space-y-1">' +
          errors.map(function (e) {
            return '<div>• ' + e + '</div>';
          }).join('') +
          '</div>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#0ea5e9',
        width: 520,
      });
    } else if (successMsg) {
      Swal.fire({
        icon: 'success',
        title: 'Saved',
        text: successMsg,
        timer: 1800,
        showConfirmButton: false,
      });
    } else if (errorMsg) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMsg,
        confirmButtonText: 'OK',
        confirmButtonColor: '#0ea5e9',
      });
    }
  });
}

// Kick off after DOM ready (safe even if loaded late by Vite)
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initRegisterAlerts);
} else {
  initRegisterAlerts();
}