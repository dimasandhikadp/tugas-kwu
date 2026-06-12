<!DOCTYPE html>
<html lang="id">
    <?php include '../includes/head.php'; ?>
  <body class="bg-gray-50 text-gray-800 font-sans">

        
        
        <?php include '../includes/header.php'; ?>
    
    <main class="max-w-6xl mx-auto mt-5 px-5 grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="flex flex-col gap-4">
            <div class="relative bg-white border border-slate-200 rounded-2xl overflow-hidden aspect-[4/3] flex items-center justify-content p-5">
                <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                    <span class="bg-blue-600 text-white text-[11px] font-bold px-2.5 py-1 rounded-md w-fit">TERLARIS</span>
                    <span class="bg-blue-50 text-blue-700 text-[11px] font-semibold px-2.5 py-1 rounded-md w-fit flex items-center gap-1"><i class="fa-solid fa-snowflake"></i> 100% SEGAR</span>
                </div>
                <img id="main-product-img" src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80" alt="Ikan Kakap Merah" class="max-w-full max-h-full object-contain rounded-lg">
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white border border-blue-500 ring-2 ring-blue-500/10 rounded-xl aspect-square cursor-pointer overflow-hidden flex items-center justify-center p-2 transition thumb-box" onclick="changeImage('https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=600&q=80', this)">
                    <img src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=150&q=80" class="max-w-full max-h-full object-contain">
                </div>
                <div class="bg-white border border-slate-200 rounded-xl aspect-square cursor-pointer overflow-hidden flex items-center justify-center p-2 hover:border-blue-500 transition thumb-box" onclick="changeImage('https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80', this)">
                    <img src="https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=150&q=80" class="max-w-full max-h-full object-contain">
                </div>
                <div class="bg-white border border-slate-200 rounded-xl aspect-square cursor-pointer overflow-hidden flex items-center justify-center p-2 hover:border-blue-500 transition thumb-box" onclick="changeImage('https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80', this)">
                    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=150&q=80" class="max-w-full max-h-full object-contain">
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8 flex flex-col">
            <h1 class="text-3xl font-bold text-slate-900 leading-tight mb-1">Ikan Kakap Merah Segar</h1>
            <p class="text-sm text-slate-400 mb-5">± 1 kg / ekor • Tangkapan Harian</p>
            
            <div class="text-3xl font-bold text-slate-900 pb-4 mb-5 border-b border-slate-100">
                Rp 85.000 <span class="text-base text-slate-400 font-normal">/ ekor</span>
            </div>
            
            <div class="text-sm font-semibold text-slate-800 mb-2">Deskripsi Produk</div>
            <p class="text-sm text-slate-600 leading-relaxed mb-6">
                Ikan Kakap Merah segar berkualitas premium, ditangkap langsung oleh nelayan lokal dan segera didinginkan menggunakan standar cold chain. Cocok untuk dibakar, digulai, atau dibuat sup hangat. Tanpa bahan pengawet kimia apa pun. Kami pastikan pengiriman sampai ke rumah Anda dalam kondisi terbaik dan tetap segar.
            </p>

            <div class="flex gap-8 pb-5 mb-6 border-b border-slate-100">
                <div class="flex items-center gap-3 text-sm font-semibold text-slate-800">
                    <i class="fa-solid fa-truck-fast text-xl text-blue-600"></i>
                    <div>
                        Pengiriman Cepat
                        <span class="block text-xs text-slate-400 font-normal mt-0.5">Sameday / Nextday</span>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm font-semibold text-slate-800">
                    <i class="fa-solid fa-shield-halved text-xl text-blue-600"></i>
                    <div>
                        Garansi Segar
                        <span class="block text-xs text-slate-400 font-normal mt-0.5">Uang kembali jika rusak</span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center mb-4">
                <div class="flex items-center border border-slate-300 rounded-lg h-11 bg-white justify-between sm:justify-start">
                    <button class="w-10 h-full text-lg text-slate-500 hover:bg-slate-100 rounded-l-lg transition" onclick="updateQty(-1)">-</button>
                    <input type="number" id="product-qty" value="1" min="1" readonly class="w-10 text-center font-semibold text-slate-800 border-none outline-none">
                    <button class="w-10 h-full text-lg text-slate-500 hover:bg-slate-100 rounded-r-lg transition" onclick="updateQty(1)">+</button>
                </div>
                <button class="flex-1 h-11 bg-blue-600 text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 hover:bg-blue-700 transition shadow-sm shadow-blue-600/10">
                    <i class="fa-solid fa-cart-plus"></i> Masukkan Keranjang
                </button>
            </div>
            
            <button class="w-full h-11 bg-white text-blue-600 font-semibold border-2 border-blue-600 rounded-lg text-sm hover:bg-blue-50 transition">Beli Sekarang</button>
        </div>
    </main>
    
        <!-- Produk Serupa -->
    <section class="max-w-6xl mx-auto mt-12 px-5">
    <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl font-bold text-slate-900">Produk Serupa</h2>

        <a
        href="#"
        class="text-xs font-semibold text-blue-600 flex items-center gap-1 hover:underline"
        >
        Lihat Semua Produk
        <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Card -->
        <div
        class="bg-white border border-slate-200 rounded-xl p-4 relative flex flex-col group hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
        >
        <span
            class="absolute top-4 right-4 text-[10px] font-semibold px-2 py-0.5 rounded bg-green-50 text-green-600"
        >
            SEGAR
        </span>

        <div class="aspect-[4/3] flex items-center justify-center mb-3 p-1">
            <img
            src="https://images.unsplash.com/photo-1553618551-fba689030290?auto=format&fit=crop&w=300&q=80"
            alt="Udang"
            class="max-w-full max-h-full object-contain"
            />
        </div>

        <div class="text-sm font-semibold text-slate-800 mb-0.5">
            Udang Vaname
        </div>

        <div class="text-[11px] text-slate-400 mb-3">
            500 gr / pack
        </div>

        <div class="flex justify-between items-center mt-auto">
            <div class="text-base font-bold text-slate-900">
            Rp 65.000
            <span class="text-[11px] text-slate-400 font-normal">
                /pack
            </span>
            </div>
        </div>
        </div>

        <!-- Card -->
        <div
        class="bg-white border border-slate-200 rounded-xl p-4 relative flex flex-col group hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
        >
        <span
            class="absolute top-4 right-4 text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-600"
        >
            TERLARIS
        </span>

        <div class="aspect-[4/3] flex items-center justify-center mb-3 p-1">
            <img
            src="https://images.unsplash.com/photo-1604313493351-5125958ee1fc?auto=format&fit=crop&w=300&q=80"
            alt="Kepiting"
            class="max-w-full max-h-full object-contain"
            />
        </div>

        <div class="text-sm font-semibold text-slate-800 mb-0.5">
            Kepiting Bakau
        </div>

        <div class="text-[11px] text-slate-400 mb-3">
            ± 500 gr / ekor
        </div>

        <div class="flex justify-between items-center mt-auto">
            <div class="text-base font-bold text-slate-900">
            Rp 70.000
            <span class="text-[11px] text-slate-400 font-normal">
                /ekor
            </span>
            </div>
        </div>
        </div>

        <!-- Card -->
        <div
        class="bg-white border border-slate-200 rounded-xl p-4 relative flex flex-col group hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
        >
        <span
            class="absolute top-4 right-4 text-[10px] font-semibold px-2 py-0.5 rounded bg-green-50 text-green-600"
        >
            SEGAR
        </span>

        <div class="aspect-[4/3] flex items-center justify-center mb-3 p-1">
            <img
            src="https://images.unsplash.com/photo-1534177711674-6078c661de59?auto=format&fit=crop&w=300&q=80"
            alt="Cumi"
            class="max-w-full max-h-full object-contain"
            />
        </div>

        <div class="text-sm font-semibold text-slate-800 mb-0.5">
            Cumi Segar
        </div>

        <div class="text-[11px] text-slate-400 mb-3">
            500 gr / pack
        </div>

        <div class="flex justify-between items-center mt-auto">
            <div class="text-base font-bold text-slate-900">
            Rp 55.000
            <span class="text-[11px] text-slate-400 font-normal">
                /pack
            </span>
            </div>
        </div>
        </div>

        <!-- Card -->
        <div
        class="bg-white border border-slate-200 rounded-xl p-4 relative flex flex-col group hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
        >
        <span
            class="absolute top-4 right-4 text-[10px] font-semibold px-2 py-0.5 rounded bg-green-50 text-green-600"
        >
            SEGAR
        </span>

        <div class="aspect-[4/3] flex items-center justify-center mb-3 p-1">
            <img
            src="https://images.unsplash.com/photo-1534604973900-c43ab4c2e0ab?auto=format&fit=crop&w=300&q=80"
            alt="Ikan Dori"
            class="max-w-full max-h-full object-contain"
            />
        </div>

        <div class="text-sm font-semibold text-slate-800 mb-0.5">
            Ikan Dori Fillet
        </div>

        <div class="text-[11px] text-slate-400 mb-3">
            500 gr / pack
        </div>

        <div class="flex justify-between items-center mt-auto">
            <div class="text-base font-bold text-slate-900">
            Rp 55.000
            <span class="text-[11px] text-slate-400 font-normal">
                /pack
            </span>
            </div>
        </div>
        </div>

    </div>
    </section>
    
    <script>
        function changeImage(imgUrl, element) {
            document.getElementById('main-product-img').src = imgUrl;
            
            // Atur ulang class border semua thumbnail menggunakan Tailwind utility
            const thumbs = document.querySelectorAll('.thumb-box');
            thumbs.forEach(thumb => {
                thumb.classList.remove('border-blue-50', 'ring-2', 'ring-blue-500/10', 'border-blue-500');
                thumb.classList.add('border-slate-200');
            });
            
            // Tambahkan style aktif ke thumbnail terpilih
            element.classList.remove('border-slate-200');
            element.classList.add('border-blue-500', 'ring-2', 'ring-blue-500/10');
        }

        function updateQty(change) {
            const qtyInput = document.getElementById('product-qty');
            let currentQty = parseInt(qtyInput.value);
            currentQty += change;
            
            if (currentQty < 1) currentQty = 1;
            qtyInput.value = currentQty;
        }
        
    </script>

    <?php
        include '../includes/footer.php';
        include '../includes/script.php';         
    ?>

</body>
</html>