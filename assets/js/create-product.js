// ==========================================
// 1. Auto-generate Slug Real-time
// ==========================================
const namaProduk = document.getElementById("nama_produk");
const slugInput = document.getElementById("slug");

if (namaProduk && slugInput) {
  namaProduk.addEventListener("input", function () {
    let text = this.value;
    let slug = text
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, "") // Hapus karakter spesial
      .replace(/\s+/g, "-") // Ganti spasi dengan -
      .replace(/-+/g, "-"); // Hindari multi dash '--'
    slugInput.value = slug;
  });
}

// ==========================================
// 2. CSS Border Controller Dinamis (Checkbox & Radio Card)
// ==========================================
function updateCardStyles() {
  document
    .querySelectorAll('label input[type="checkbox"], label input[type="radio"]')
    .forEach((input) => {
      const parentLabel = input.closest("label");
      if (!parentLabel) return;

      if (input.checked) {
        parentLabel.classList.remove("border-slate-200");
        parentLabel.classList.add("border-blue-600", "bg-blue-50/30");
      } else {
        parentLabel.classList.remove("border-blue-600", "bg-blue-50/30");
        parentLabel.classList.add("border-slate-200");
      }
    });
}

// Jalankan event listener saat status check berubah
document.querySelectorAll("label input").forEach((input) => {
  input.addEventListener("change", updateCardStyles);
});

// Inisialisasi awal agar pilihan 'checked' bawaan langsung terdesain biru saat halaman dimuat
updateCardStyles();

// ==========================================
// 3. Multi-Image Preview & Sinkronisasi Form Input
// ==========================================
const gambarInput = document.getElementById("gambar-input");
const previewContainer = document.getElementById("preview-container");
let selectedFiles = []; // Menyimpan referensi file untuk sinkronisasi input

if (gambarInput && previewContainer) {
  gambarInput.addEventListener("change", function (e) {
    const files = Array.from(e.target.files);

    files.forEach((file) => {
      if (!file.type.startsWith("image/")) return; // Hanya izinkan file gambar

      selectedFiles.push(file);

      const reader = new FileReader();
      reader.onload = function (event) {
        const card = document.createElement("div");
        card.className =
          "relative group border border-slate-200 rounded-xl overflow-hidden aspect-square bg-slate-100 shadow-sm animate-fade-in";

        card.innerHTML = `
                    <img src="${event.target.result}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                        <span class="text-xs text-white bg-slate-900/80 px-2 py-1 rounded-md max-w-[85%] truncate">${file.name}</span>
                    </div>
                    <button type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 shadow hover:bg-red-600 transition focus:outline-none close-btn">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;

        // Tambahkan fungsi hapus pratinjau dan sinkronisasi ulang file input
        card.querySelector(".close-btn").addEventListener("click", function () {
          const index = selectedFiles.indexOf(file);
          if (index > -1) {
            selectedFiles.splice(index, 1); // Hapus dari array tracking
          }
          card.remove(); // Hapus element HTML preview
          syncInputFiles(); // Sinkronisasikan ke input file HTML
        });

        previewContainer.appendChild(card);
      };
      reader.readAsDataURL(file);
    });

    // Jalankan sinkronisasi setelah file baru ditambahkan
    syncInputFiles();
  });
}

// Fungsi vital: Memasukkan kembali array selectedFiles ke dalam FileList element HTML input
function syncInputFiles() {
  if (!gambarInput) return;
  const dataTransfer = new DataTransfer();
  selectedFiles.forEach((file) => {
    dataTransfer.items.add(file);
  });
  gambarInput.files = dataTransfer.files;
}

// ==========================================
// 4. Deteksi Refresh & Pop-up Produk Sukses
// ==========================================
window.addEventListener("DOMContentLoaded", () => {
  // Membaca parameter URL browser
  const urlParams = new URLSearchParams(window.location.search);

  // Jika mendeteksi parameter '?status=success' pasca-refresh oleh PHP
  if (urlParams.get("status") === "success") {
    // Tampilkan pop-up pesan berhasil unggah
    alert("Produk berhasil diunggah!");

    // Bersihkan parameter '?status=success' dari URL browser tanpa me-refresh ulang halaman
    // agar pop-up tidak muncul berkali-kali saat user menekan F5 manual.
    const cleanUrl =
      window.location.protocol +
      "//" +
      window.location.host +
      window.location.pathname;
    window.history.replaceState({ path: cleanUrl }, "", cleanUrl);
  }
});
