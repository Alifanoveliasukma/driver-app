let isDragging = false;
let offsetX = 0;

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
    const btn = document.querySelector(".slide-button");
    const container = document.querySelector(".slide-track");
    const targetUrl = container.dataset.redirect;

    const orderId = container.dataset.orderid;

    const now = new Date();
    const formatted =
        now.getFullYear() +
        "-" +
        String(now.getMonth() + 1).padStart(2, "0") +
        "-" +
        String(now.getDate()).padStart(2, "0") +
        " " +
        String(now.getHours()).padStart(2, "0") +
        ":" +
        String(now.getMinutes()).padStart(2, "0") +
        ":" +
        String(now.getSeconds()).padStart(2, "0");

    console.log(targetUrl, formatted, "TEST TES");
    if (
        parseInt(btn.style.left) >=
        container.clientWidth - btn.clientWidth - 5
    ) {
        alert("✅ Konfirmasi Berangkat!");

        fetch(targetUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                OutLoadDate: formatted,
                orderId: orderId,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                console.log("Update sukses:", data);
                if (data.success) {
                    alert("Sukses Konfirmasi");
                    // window.location.href = "{{ route('utama.konfirmasi-tiba-muat') }}";
                } else {
                    alert("Gagal konfirmasi!");
                }
            })
            .catch((err) => console.error(err));
    } else {
        btn.style.left = "0px";
        btn.style.background = "#ffffff";
        btn.innerHTML =
            '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
    }

    document.removeEventListener("mousemove", onSlide);
    document.removeEventListener("mouseup", stopSlide);
    document.removeEventListener("touchmove", onSlide);
    document.removeEventListener("touchend", stopSlide);
}
