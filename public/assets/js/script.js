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

  if (parseInt(btn.style.left) >= container.clientWidth - btn.clientWidth - 5) {
    alert('✅ Konfirmasi Berangkat!');
    window.location.href = targetUrl; 
  } else {
    btn.style.left = '0px';
    btn.style.background = '#ffffff';
    btn.innerHTML = '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }

  document.removeEventListener('mousemove', onSlide);
  document.removeEventListener('mouseup', stopSlide);
}
