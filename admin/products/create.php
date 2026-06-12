<!DOCTYPE html>
<html lang="id">
  
<?php
  include '../../includes/head.php';
?>
<body class="bg-gray-50 text-gray-800 font-sans">

    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div
        class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between"
      >
        <!-- Logo -->
        <div class="flex items-center space-x-1">
          <img
            src="../../assets/img/logo.png"
            alt="Logo Segar"
            class="w-15 h-10 object-contain"
            style="transform: scaleX(1.5)"
          />
          <div>
            <span class="text-xl font-bold text-blue-600 block leading-none">
              Se<span class="text-blue-950">gar</span>
            </span>
            <span class="text-xs text-gray-400 leading-none">
              Hasil Laut Segar, Dari Laut Ke Meja Anda
            </span>
          </div>
        </div>

        <!-- Tombol kembali -->
        <a
          href="../index.php"
          class="text-sm font-medium text-gray-600 hover:text-blue-600 transition"
        >
          Kembali ke Beranda
        </a>
      </div>
    </header>

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-5 py-8">

        <div class="mb-6">
            <h2 class="text-3xl font-bold text-blue-950">
                Tambah Produk
            </h2>

            <p class="text-slate-500 mt-1">
                Tambahkan produk baru ke katalog SeaFresh.
            </p>
        </div>

        <form
            method="POST"
            enctype="multipart/form-data"
            class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm"
        >

            <!-- Informasi Produk -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">
                    Informasi Produk
                </h3>

                <div class="grid md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Nama Produk
                        </label>

                        <input
                            type="text"
                            name="nama_produk"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Slug
                        </label>

                        <input
                            type="text"
                            name="slug"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-600"
                        >
                    </div>

                </div>

                <div class="mt-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi Produk
                    </label>

                    <textarea
                        rows="5"
                        name="deskripsi"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 resize-none focus:outline-none focus:ring-2 focus:ring-blue-600"
                    ></textarea>
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">
                    Detail Produk
                </h3>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Harga
                        </label>

                        <input
                            type="number"
                            name="harga"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Stok
                        </label>

                        <input
                            type="number"
                            name="stok"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Berat
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="berat"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Satuan
                        </label>

                        <select
                            name="satuan"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                            <option value="kg">kg</option>
                            <option value="gram">gram</option>
                            <option value="ekor">ekor</option>
                            <option value="pack">pack</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Badge & Asal Produk -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">
                    Informasi Tambahan
                </h3>

                <div class="grid md:grid-cols-3 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Asal Produk
                        </label>

                        <input
                            type="text"
                            name="asal_produk"
                            placeholder="Tangkapan Harian"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Badge
                        </label>

                        <select
                            name="badge"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                            <option value="">Tidak Ada</option>
                            <option value="TERLARIS">TERLARIS</option>
                            <option value="PROMO">PROMO</option>
                            <option value="BARU">BARU</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 outline-none"
                        >
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                </div>
            </div>

            <!-- Upload -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-950 mb-4">
                    Gambar Produk
                </h3>

                <input
                    type="file"
                    name="gambar[]"
                    multiple
                    class="block w-full text-sm border border-slate-300 rounded-xl p-3"
                >
            </div>

            <!-- Button -->
            <div class="flex justify-end gap-3">
                <a
                    href="products.php"
                    class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition"
                >
                    Simpan Produk
                </button>
            </div>

        </form>

    </main>

</body>
</html>