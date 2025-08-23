let isDragging = false;
let offsetX = 0;

function startSlide(e) {
  isDragging = true;
  offsetX = e.clientX;
  document.addEventListener('mousemove', onSlide);
  document.addEventListener('mouseup', stopSlide);
}

function onSlide(e) {
  if (!isDragging) return;

  const btn = document.querySelector('.slide-button');
  const container = document.querySelector('.slide-track');
  let moveX = e.clientX - offsetX;
  moveX = Math.max(0, Math.min(moveX, container.clientWidth - btn.clientWidth));
  btn.style.left = moveX + 'px';

  if (moveX >= container.clientWidth - btn.clientWidth - 5) {
    btn.style.background = '#198754';
    btn.innerHTML = '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
  } else {
    btn.style.background = '#ffffff';
    btn.innerHTML = '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

function stopSlide(e) {
  isDragging = false;
  const btn = document.querySelector('.slide-button');
  const container = document.querySelector('.slide-track');
  const targetUrl = container.dataset.redirect;
  const hiddenEl = document.getElementById('OutLoadDate');
      let outLoadDate = hiddenEl ? hiddenEl.value : null;

      if (parseInt(btn.style.left) >= container.clientWidth - btn.clientWidth - 5) {
          alert('✅ Konfirmasi Berangkat!');

          fetch(targetUrl, {
              method: 'PATCH',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({
                  OutLoadDate: outLoadDate,
                  orderId: 1382751
              })
          })
          .then(res => res.json())
          .then(data => {
              console.log('Update sukses:', data);
              window.location.href = "{{ route('utama.konfirmasi-tiba-muat')}}"
          })
          .catch(err => console.error(err));

      } else {
          btn.style.left = '0px';
          btn.style.background = '#ffffff';
          btn.innerHTML = '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
      }

      document.removeEventListener('mousemove', onSlide);
      document.removeEventListener('mouseup', stopSlide);
  }


function initRealtimeDateTime() {
    function updateDateTime() {
        let now = new Date();

        const bulan = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        let tanggal = String(now.getDate()).padStart(2, '0') + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();

        let jam = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

        let tanggalEl = document.getElementById('tanggalKeluar');
        let jamEl = document.getElementById('jamKeluar');
        let hiddenEl = document.getElementById('OutLoadDate');

        if (tanggalEl) tanggalEl.innerText = tanggal;
        if (jamEl) jamEl.innerText = jam;

        let yyyy = now.getFullYear();
        let mm = String(now.getMonth() + 1).padStart(2, '0');
        let dd = String(now.getDate()).padStart(2, '0');
        let HH = String(now.getHours()).padStart(2, '0');
        let ii = String(now.getMinutes()).padStart(2, '0');
        let ss = String(now.getSeconds()).padStart(2, '0');
        let formatted = `${yyyy}-${mm}-${dd} ${HH}:${ii}:${ss}`;

        if (hiddenEl) hiddenEl.value = formatted;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
}

document.addEventListener("DOMContentLoaded", initRealtimeDateTime);
