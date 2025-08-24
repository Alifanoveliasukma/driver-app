let isDragging = false;
let offsetX = 0;

// berangkat
function startSlide(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlide(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlide(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   // endpoint POST /utama/berangkat
  const nextUrl   = container.dataset.redirect; // halaman tiba muat
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("OutLoadDate");
  const outLoadDate = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    body: JSON.stringify({ orderId, OutLoadDate: outLoadDate }),
    })
    .then(async (res) => {
    const ct = res.headers.get("content-type") || "";
    const isJson = ct.includes("application/json");
    const data = isJson ? await res.json() : null;

    if (res.ok && isJson && data?.success) {
        window.location.href = data.nextUrl;        // pindah ke halaman tiba muat
    } else if (res.status === 419) {
        alert("Sesi kedaluwarsa (419). Refresh halaman lalu coba lagi.");
        resetSlider();
    } else {
        alert((isJson && data?.message) || `Gagal konfirmasi (HTTP ${res.status}).`);
        resetSlider();
    }
    })
    .catch(() => {
    alert("Kesalahan jaringan.");
    resetSlider();
    })
    .finally(() => {
    btn.style.pointerEvents = "";
    });

  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

// tiba muat

function startSlidetiba(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlidetiba(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlidetiba(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   
  const nextUrl   = container.dataset.redirect; 
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("LoadDateStart");
  const loadDateStart = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ orderId, LoadDateStart: loadDateStart }),
    })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await res.json() : {};
        if (res.ok && (data.success ?? true)) {
          window.location.href = nextUrl; // pindah ke halaman tiba muat
        } else {
          alert(data.message || "Gagal konfirmasi.");
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });
  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

// selesai muat
function startSlideSelesaiMuat(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlideSelesaiMuat(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlideSelesaiMuat(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   
  const nextUrl   = container.dataset.redirect; 
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("LoadDate");
  const loadDate = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ orderId, LoadDate: loadDate }),
    })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await res.json() : {};
        if (res.ok && (data.success ?? true)) {
          window.location.href = nextUrl; // pindah ke halaman tiba muat
        } else {
          alert(data.message || "Gagal konfirmasi.");
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });
  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

// keluar muat
function startSlideKeluarMuat(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlideKeluarMuat(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlideKeluarMuat(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   
  const nextUrl   = container.dataset.redirect; 
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("UnloadDateStart");
  const unloadDateStart = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ orderId, UnloadDateStart: unloadDateStart }),
    })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await res.json() : {};
        if (res.ok && (data.success ?? true)) {
          window.location.href = nextUrl; // pindah ke halaman tiba muat
        } else {
          alert(data.message || "Gagal konfirmasi.");
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });
  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

// Tiba Tujuan
function startSlideTibaTujuan(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlideTibaTujuan(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlideTibaTujuan(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   
  const nextUrl   = container.dataset.redirect; 
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("OutUnloadDate");
  const outUnloadDate = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ orderId, OutUnloadDate: outUnloadDate }),
    })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await res.json() : {};
        if (res.ok && (data.success ?? true)) {
          window.location.href = nextUrl; // pindah ke halaman tiba muat
        } else {
          alert(data.message || "Gagal konfirmasi.");
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });
  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

// Mulai Bongkar
function startSlideMulaiBongkar(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlideMulaiBongkar(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlideMulaiBongkar(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   
  const nextUrl   = container.dataset.redirect; 
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("UnloadDate");
  const unloadDate = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ orderId, UnloadDate: unloadDate }),
    })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await res.json() : {};
        if (res.ok && (data.success ?? true)) {
          window.location.href = nextUrl; // pindah ke halaman tiba muat
        } else {
          alert(data.message || "Gagal konfirmasi.");
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });
  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}

// Keluar Bongkar
function startSlideKeluarBongkar(e) {
    isDragging = true;
    offsetX = e.clientX || (e.touches && e.touches[0].clientX);
    document.addEventListener("mousemove", onSlide);
    document.addEventListener("mouseup", stopSlide);
    document.addEventListener("touchmove", onSlide);
    document.addEventListener("touchend", stopSlide);
}

function onSlideKeluarBongkar(e) {
    if (!isDragging) return;

    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    let clientX = e.clientX || (e.touches && e.touches[0].clientX);
    let moveX = clientX - offsetX;
    moveX = Math.max(
        0,
        Math.min(moveX, container.clientWidth - btn.clientWidth)
    );
    btn.style.left = moveX + "px";

    if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        btn.style.background = "#198754";
        btn.innerHTML =
            '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
    } else {
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }
}

function stopSlideKeluarBongkar(e) {
  isDragging = false;

  const btn       = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl   = container.dataset.action;   
  const nextUrl   = container.dataset.redirect; 
  const orderId   = container.dataset.orderid;

  const hiddenEl   = document.getElementById("UnloadStd");
  const unloadStd = hiddenEl ? hiddenEl.value : null;

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit
    btn.style.pointerEvents = "none";

    fetch(postUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({ orderId, UnloadStd: unloadStd }),
    })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const data = ct.includes("application/json") ? await res.json() : {};
        if (res.ok && (data.success ?? true)) {
          window.location.href = nextUrl; // pindah ke halaman tiba muat
        } else {
          alert(data.message || "Gagal konfirmasi.");
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });
  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
  }
}


function initRealtimeDateTime() {
  function updateDateTime() {
    let now = new Date();

    const bulan = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    let tanggal = String(now.getDate()).padStart(2,"0") + " " + bulan[now.getMonth()] + " " + now.getFullYear();
    let jam     = String(now.getHours()).padStart(2,"0") + ":" + String(now.getMinutes()).padStart(2,"0");

    // LOAD
    let tanggalElLoad = document.getElementById("tanggalKeluar");
    let jamElLoad     = document.getElementById("jamKeluar");
    let hiddenElLoad  = document.getElementById("OutLoadDate");

    if (tanggalElLoad) tanggalElLoad.innerText = tanggal;
    if (jamElLoad)     jamElLoad.innerText     = jam;

    // UNLOAD
    let tanggalElUnload = document.getElementById("tanggalKeluarUnload");
    let jamElUnload     = document.getElementById("jamKeluarUnload");
    let hiddenElUnload  = document.getElementById("LoadDateStart");

    if (tanggalElUnload) tanggalElUnload.innerText = tanggal;
    if (jamElUnload)     jamElUnload.innerText     = jam;


    // WAIT FOR LOAD
    let tanggalSelesaiMuat = document.getElementById("tanggalSelesaiMuat");
    let jamSelesaiMuat     = document.getElementById("jamSelesaiMuat");
    let hiddenSelesaiMuat  = document.getElementById("LoadDate");

    if (tanggalSelesaiMuat) tanggalSelesaiMuat.innerText = tanggal;
    if (jamSelesaiMuat)     jamSelesaiMuat.innerText     = jam;

    // WAIT UNLOAD
    let tanggalKeluarMuat = document.getElementById("tanggalKeluarMuat");
    let jamKeluarMuat     = document.getElementById("jamKeluarMuat");
    let hiddenKeluarMuat  = document.getElementById("UnloadDateStart");

    if (tanggalKeluarMuat) tanggalKeluarMuat.innerText = tanggal;
    if (jamKeluarMuat)     jamKeluarMuat.innerText     = jam;

    // FINISHED
    let tanggalTibaTujuan = document.getElementById("tanggalTibaTujuan");
    let jamTibaTujuan     = document.getElementById("jamTibaTujuan");
    let hiddenTibaTujuan  = document.getElementById("UnloadDateStart");

    if (tanggalTibaTujuan) tanggalTibaTujuan.innerText = tanggal;
    if (jamTibaTujuan)     jamTibaTujuan.innerText     = jam;

    // WAIT UNLOAD
    let tanggalMulaiBongkar = document.getElementById("tanggalMulaiBongkar");
    let jamMulaiBongkar     = document.getElementById("jamMulaiBongkar");
    let hiddenMulaiBongkar  = document.getElementById("UnloadDate");

    if (tanggalMulaiBongkar) tanggalMulaiBongkar.innerText = tanggal;
    if (jamMulaiBongkar)     jamMulaiBongkar.innerText     = jam;

    // EXECUTED
    let tanggalKeluarBongkar = document.getElementById("tanggalKeluarBongkar");
    let jamKeluarBongkar     = document.getElementById("jamKeluarBongkar");
    let hiddenKeluarBongkar  = document.getElementById("UnloadStd");

    if (tanggalKeluarBongkar) tanggalKeluarBongkar.innerText = tanggal;
    if (jamKeluarBongkar)     jamKeluarBongkar.innerText     = jam;

    // format SQL datetime
    let yyyy = now.getFullYear();
    let mm   = String(now.getMonth() + 1).padStart(2, "0");
    let dd   = String(now.getDate()).padStart(2, "0");
    let HH   = String(now.getHours()).padStart(2, "0");
    let ii   = String(now.getMinutes()).padStart(2, "0");
    let ss   = String(now.getSeconds()).padStart(2, "0");
    let formatted = `${yyyy}-${mm}-${dd} ${HH}:${ii}:${ss}`;

    if (hiddenElLoad)   hiddenElLoad.value   = formatted;
    if (hiddenSelesaiMuat) hiddenSelesaiMuat.value = formatted;
  }

  setInterval(updateDateTime, 1000);
  updateDateTime();
}

// function initRealtimeDateTime() {
//     function updateDateTime() {
//         let now = new Date();

//         const bulan = [
//             "Jan",
//             "Feb",
//             "Mar",
//             "Apr",
//             "May",
//             "Jun",
//             "Jul",
//             "Aug",
//             "Sep",
//             "Oct",
//             "Nov",
//             "Dec",
//         ];
//         let tanggal =
//             String(now.getDate()).padStart(2, "0") +
//             " " +
//             bulan[now.getMonth()] +
//             " " +
//             now.getFullYear();
//         let jam =
//             String(now.getHours()).padStart(2, "0") +
//             ":" +
//             String(now.getMinutes()).padStart(2, "0");

//         let tanggalEl = document.getElementById("tanggalKeluar");
//         let jamEl = document.getElementById("jamKeluar");
//         let hiddenEl = document.getElementById("OutLoadDate");

//         if (tanggalEl) tanggalEl.innerText = tanggal;
//         if (jamEl) jamEl.innerText = jam;

//         let yyyy = now.getFullYear();
//         let mm = String(now.getMonth() + 1).padStart(2, "0");
//         let dd = String(now.getDate()).padStart(2, "0");
//         let HH = String(now.getHours()).padStart(2, "0");
//         let ii = String(now.getMinutes()).padStart(2, "0");
//         let ss = String(now.getSeconds()).padStart(2, "0");
//         let formatted = `${yyyy}-${mm}-${dd} ${HH}:${ii}:${ss}`;

//         if (hiddenEl) hiddenEl.value = formatted;
//     }

//     setInterval(updateDateTime, 1000);
//     updateDateTime();
// }

document.addEventListener("DOMContentLoaded", initRealtimeDateTime);
