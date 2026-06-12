function switchTab(tabId) {
  // 1. Sembunyikan semua konten section kanan
  const contents = document.querySelectorAll(".tab-content");
  contents.forEach((content) => {
    content.classList.add("hidden");
  });

  // 2. Tampilkan konten section yang dipilih
  document.getElementById(`content-${tabId}`).classList.remove("hidden");

  // 3. Reset semua gaya tombol sidebar menjadi tidak aktif
  const buttons = document.querySelectorAll("#sidebar-menu button");
  buttons.forEach((btn) => {
    btn.classList.remove("bg-blue-50", "text-blue-600", "font-bold");
    btn.classList.add("text-slate-500", "font-medium");
  });

  // 4. Berikan gaya aktif pada tombol sidebar yang sedang dipilih
  const activeBtn = document.getElementById(`tab-${tabId}`);
  activeBtn.classList.remove("text-slate-500", "font-medium");
  activeBtn.classList.add("bg-blue-50", "text-blue-600", "font-bold");
}
