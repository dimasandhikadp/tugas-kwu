<?php
  include '../config/koneksi.php';
  session_start();
?>

<!DOCTYPE html>
<html lang="id">
  <?php
    include '../includes/head.php'
  ?>
  <body class="bg-gray-50 text-gray-800 font-sans">
    
   <?php
    include '../includes/header-secondary.php'
  ?>

    <main class="max-w-7xl w-full mx-auto px-6 py-10 flex-grow flex flex-col md:flex-row gap-8">
      <!-- SIDEBAR -->
      <aside class="w-full md:w-64 shrink-0">
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm sticky top-24">
          <ul class="space-y-1" id="sidebar-menu">
            <li>
              <button onclick="switchTab('pemberitahuan-privasi')" id="tab-pemberitahuan-privasi" class="w-full text-left block px-4 py-2.5 text-sm font-medium rounded-xl text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors cursor-pointer">Pemberitahuan Privasi</button>
            </li>
            <li>
              <button onclick="switchTab('kebijakan-privasi')" id="tab-kebijakan-privasi" class="w-full text-left block px-4 py-2.5 text-sm font-bold rounded-xl bg-blue-50 text-blue-600 cursor-pointer">Kebijakan Privasi</button>
            </li>
            <li>
              <button onclick="switchTab('syarat-ketentuan')" id="tab-syarat-ketentuan" class="w-full text-left block px-4 py-2.5 text-sm font-medium rounded-xl text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors cursor-pointer">Syarat & Ketentuan</button>
            </li>
            <li>
              <button onclick="switchTab('faq')" id="tab-faq" class="w-full text-left block px-4 py-2.5 text-sm font-medium rounded-xl text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors cursor-pointer">FAQ</button>
            </li>
          </ul>
        </div>
      </aside>

      <!-- KONTEN UTAMA -->
      <section class="flex-grow bg-white border border-slate-100 rounded-2xl p-6 md:p-10 shadow-sm min-h-[500px]">
        
        <!-- SECTION 2: PEMBERITAHUAN PRIVASI -->
        <div id="content-pemberitahuan-privasi" class="tab-content hidden space-y-6">
          <h2 class="text-2xl font-bold text-blue-950 border-b border-slate-100 pb-4">Pemberitahuan Privasi</h2>
          <p class="text-sm md:text-base text-slate-500 leading-relaxed">
            Pemberitahuan singkat ini dirancang untuk memberi tahu Anda secara transparan mengenai pemrosesan data kilat saat Anda menggunakan aplikasi atau situs web kami.
          </p>
          <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 text-xs text-slate-600 space-y-2">
            <p>• Kami menghormati hak privasi digital Anda berdasarkan regulasi perlindungan data yang berlaku di Indonesia.</p>
            <p>• Data pelacakan cookie digunakan semata-mata untuk menyimpan preferensi keranjang belanja ikan Anda agar tidak hilang saat halaman dimuat ulang.</p>
          </div>
        </div>

        <!-- SECTION 3: KEBIJAKAN PRIVASI (DEFAULT ACTIVE) -->
        <div id="content-kebijakan-privasi" class="tab-content space-y-6">
          <h2 class="text-2xl font-bold text-blue-950 border-b border-slate-100 pb-4">Kebijakan Privasi</h2>
          
          <div class="space-y-6">
            <div>
              <h3 class="text-xs font-bold text-blue-600 tracking-wide uppercase mb-2">PENGANTAR</h3>
              <p class="text-sm md:text-base text-slate-500 leading-relaxed pl-1">
                Selamat datang di Segar. Kami berkomitmen penuh untuk menjaga kepercayaan Anda sebagai pengguna dengan melindungi informasi pribadi yang Anda berikan saat bertransaksi produk hasil laut segar di website kami.
              </p>
            </div>

            <div>
              <h3 class="text-xs font-bold text-blue-600 tracking-wide uppercase mb-2">RINGKASAN</h3>
              <p class="text-sm md:text-base text-slate-500 leading-relaxed pl-1">
                Kebijakan ini merangkum jenis data yang kami kumpulkan, cara memproses pesanan pengiriman dari nelayan, serta kepatuhan perlindungan data digital Anda.
              </p>
            </div>

            <div>
              <h3 class="text-xs font-bold text-blue-600 tracking-wide uppercase mb-2">DATA PRIBADI YANG KAMI KUMPULKAN</h3>
              <div class="text-sm md:text-base text-slate-500 leading-relaxed space-y-2 pl-1">
                <p>Kami mengumpulkan data yang diperlukan untuk pemenuhan pengiriman belanjaan Anda:</p>
                <ul class="list-disc list-inside space-y-1 pl-1 text-slate-500">
                  <li>Nama lengkap dan alamat pengiriman logistik.</li>
                  <li>Nomor telepon / WhatsApp untuk kurir pengantar ikan.</li>
                  <li>Email konfirmasi tagihan pembayaran.</li>
                </ul>
              </div>
            </div>

            <div>
              <h3 class="text-xs font-bold text-blue-600 tracking-wide uppercase mb-2">PENGGUNAAN DATA PRIBADI</h3>
              <p class="text-sm md:text-base text-slate-500 leading-relaxed pl-1">
                Data Anda sepenuhnya digunakan untuk mempercepat proses verifikasi e-wallet (DANA, OVO, GoPay, ShopeePay), pembuatan manifest kurir, dan memberikan promo kuliner laut spesial dari Segar.
              </p>
            </div>

            <div>
              <h3 class="text-xs font-bold text-blue-600 tracking-wide uppercase mb-2">MELINDUNGI DATA PRIBADI ANDA</h3>
              <p class="text-sm md:text-base text-slate-500 leading-relaxed pl-1">
                Kami menerapkan enkripsi SSL berlapis untuk memastikan seluruh riwayat pembayaran perbankan maupun identitas digital Anda tidak bocor ke pihak ketiga yang tidak bertanggung jawab.
              </p>
            </div>

            <div>
              <h3 class="text-xs font-bold text-blue-600 tracking-wide uppercase mb-2">HAK ANDA SEBAGAI PENGGUNA</h3>
              <p class="text-sm md:text-base text-slate-500 leading-relaxed pl-1">
                Anda berhak menghapus akun, mengubah alamat pengiriman utama, atau menarik persetujuan langganan informasi berkala kapan pun melalui menu pengaturan profil pengguna.
              </p>
            </div>
          </div>
        </div>

        <!-- SECTION 4: SYARAT & KETENTUAN -->
        <div id="content-syarat-ketentuan" class="tab-content hidden space-y-6">
          <h2 class="text-2xl font-bold text-blue-950 border-b border-slate-100 pb-4">Syarat & Ketentuan</h2>
          <div class="space-y-4 text-sm text-slate-500 leading-relaxed">
            <p>Dengan melakukan pembelian di platform Segar, Anda menyetujui poin-poin kesepakatan berikut:</p>
            <ol class="list-decimal list-inside space-y-2 pl-1">
              <li><strong class="text-slate-700">Pemesanan:</strong> Batas waktu pemesanan untuk pengiriman pagi hari adalah maksimal pukul 21:00 WIB pada malam sebelumnya.</li>
              <li><strong class="text-slate-700">Pembatalan:</strong> Pesanan yang sudah diproses dan masuk ke manifes kurir pengantar tidak dapat dibatalkan atau diuangkan kembali.</li>
              <li><strong class="text-slate-700">Kebijakan Retur:</strong> Jika produk laut yang diterima terbukti tidak segar atau rusak saat tiba, klaim komplain wajib menyertakan video unboxing maksimal 2 jam setelah paket diterima.</li>
            </ol>
          </div>
        </div>

        <!-- SECTION 5: FAQ -->
        <div id="content-faq" class="tab-content hidden space-y-6">
          <h2 class="text-2xl font-bold text-blue-950 border-b border-slate-100 pb-4">FAQ (Pertanyaan Umum)</h2>
          <div class="space-y-4">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
              <h4 class="font-bold text-blue-950 text-sm mb-1">Apakah ikan dikirim dalam keadaan beku atau segar?</h4>
              <p class="text-xs text-slate-500">Semua produk kami dikirim dalam kondisi segar/chilled (bukan dibekukan berbulan-bulan) menggunakan kemasan khusus berpendingin es batu untuk menjaga kualitas.</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
              <h4 class="font-bold text-blue-950 text-sm mb-1">Berapa minimal pembelian di platform Segar?</h4>
              <p class="text-xs text-slate-500">Tidak ada minimal pembelian. Namun, Anda bisa mendapatkan gratis ongkir dengan kupon promo berkala atau pembelian di atas Rp150.000.</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
              <h4 class="font-bold text-blue-950 text-sm mb-1">Metode pembayaran apa saja yang didukung?</h4>
              <p class="text-xs text-slate-500">Kami mendukung transfer bank otomatis (Virtual Account) dan seluruh ekosistem e-wallet utama seperti DANA, OVO, GoPay, dan ShopeePay.</p>
            </div>
          </div>
        </div>

      </section>
    </main>

    <?php
      include '../includes/footer.php';
    ?>
    <?php
      include '../includes/script.php';
    ?>

    
  </body>
</html>