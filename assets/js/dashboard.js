// Fungsi utama perpindahan Tab Menu Utama Sidebar
function switchTab(tabId) {
  document.querySelectorAll(".tab-content").forEach((tab) => {
    tab.classList.add("hidden");
  });
  const targetTab = document.getElementById("tab-" + tabId);
  if (targetTab) targetTab.classList.remove("hidden");

  document.querySelectorAll(".nav-btn").forEach((btn) => {
    btn.classList.remove(
      "bg-blue-600",
      "text-white",
      "shadow-sm",
      "shadow-blue-200",
    );
    btn.classList.add(
      "text-slate-600",
      "hover:bg-slate-50",
      "hover:text-slate-900",
    );
  });

  const activeBtn = document.getElementById("menu-" + tabId);
  if (activeBtn) {
    activeBtn.classList.remove(
      "text-slate-600",
      "hover:bg-slate-50",
      "hover:text-slate-900",
    );
    activeBtn.classList.add(
      "bg-blue-600",
      "text-white",
      "shadow-sm",
      "shadow-blue-200",
    );
  }

  const titleMap = {
    dashboard: "Dashboard Penjual",
    produk: "Katalog Produk Anda",
    pengiriman: "Manajemen Pengiriman",
    pesan: "Ruang Chat Pelanggan",
    finance: "Laporan Pendapatan Toko",
  };
  if (titleMap[tabId]) {
    document.getElementById("page-title").innerText = titleMap[tabId];
  }
}

// Fungsi perpindahan Sub-Tab internal 4 status pengiriman di dalam Tab Pengiriman
function switchSubTab(subTabId) {
  document.querySelectorAll(".sub-tab-content").forEach((content) => {
    content.classList.add("hidden");
  });
  document.getElementById("sub-tab-" + subTabId).classList.remove("hidden");

  document.querySelectorAll(".sub-nav-btn").forEach((btn) => {
    btn.classList.remove("border-blue-600", "text-blue-600", "font-bold");
    btn.classList.add("border-transparent", "text-slate-500", "font-medium");
  });

  const activeSubBtn = document.getElementById("sub-btn-" + subTabId);
  if (activeSubBtn) {
    activeSubBtn.classList.remove(
      "border-transparent",
      "text-slate-500",
      "font-medium",
    );
    activeSubBtn.classList.add("border-blue-600", "text-blue-600", "font-bold");
  }
}

// FUNGSI MODAL KUSTOM HAPUS (TENGAH LAYAR)
const deleteModal = document.getElementById("delete-confirm-modal");
const deleteCancelBtn = document.getElementById("delete-cancel-btn");
const deleteExecuteBtn = document.getElementById("delete-execute-btn");

function openDeleteModal(deleteUrl, productName) {
  if (deleteModal && deleteExecuteBtn) {
    // Set link tombol hapus tujuan ke file delete.php?id=...
    deleteExecuteBtn.setAttribute("href", deleteUrl);
    // Ubah teks deskripsi agar informatif menyebutkan nama produknya
    deleteModal.querySelector("p").innerHTML =
      `Apakah Anda yakin ingin menghapus <strong>"${productName}"</strong> secara permanen dari katalog Anda? Semua berkas foto produk terkait juga akan terhapus fisik.`;

    // Munculkan modal dengan transisi CSS halus
    deleteModal.classList.remove("opacity-0", "pointer-events-none");
    deleteModal.querySelector(".scale-95").classList.remove("scale-95");
  }
}

// Tutup modal jika menekan batal
if (deleteCancelBtn) {
  deleteCancelBtn.addEventListener("click", () => {
    deleteModal.classList.add("opacity-0", "pointer-events-none");
    deleteModal.querySelector(".transform").classList.add("scale-95");
  });
}

// MANAJEMEN TIMER TOAST 5 DETIK & URL CLEANER
window.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const activeTab = urlParams.get("tab");

  if (activeTab) {
    switchTab(activeTab);
  } else {
    switchTab("dashboard");
  }

  // Atur Timer Penghilang Toast Otomatis (5000 ms = 5 Detik)
  const successToast = document.getElementById("toast-success");
  if (successToast) {
    setTimeout(() => {
      // Beri animasi fade out sebelum dihapus dari struktur DOM HTML
      successToast.classList.add("opacity-0");
      setTimeout(() => {
        successToast.remove();
      }, 500); // Tunggu transisi fade out selesai
    }, 5000);
  }

  // Bersihkan query string parameter (?tab=...&status=...) dari browser agar clean saat di-refresh manual oleh penjual
  if (urlParams.has("status")) {
    const cleanUrl =
      window.location.protocol +
      "//" +
      window.location.host +
      window.location.pathname +
      (activeTab ? "?tab=" + activeTab : "");
    window.history.replaceState({ path: cleanUrl }, "", cleanUrl);
  }
});
