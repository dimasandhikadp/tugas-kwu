<!DOCTYPE html>
<html lang="id">
    <?php
    include '../includes/head.php'
    ?>
<body class="bg-slate-50 text-slate-700">
    <?php
    include '../includes/header.php'
    ?>

    <main class="max-w-xl mx-auto mt-12 px-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 md:p-8">
            <h1 class="text-2xl font-bold text-slate-900 mb-2 text-center">Hubungi Kami</h1>
            <p class="text-xs text-slate-400 text-center mb-6">Punya pertanyaan atau kendala? Kirimkan pesan di bawah ini.</p>
            
            <form class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1">Nama Lengkap</label>
                    <input type="text" class="w-full border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600 block mb-1">Pesan Anda</label>
                    <textarea rows="4" class="w-full border border-slate-200 rounded-lg p-2.5 text-sm outline-none focus:border-sky-500"></textarea>
                </div>
                <button class="w-full py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition">Kirim Pesan</button>
            </form>
        </div>
    </main>

    <?php
        include '../includes/footer.php';
        include '../includes/script.php';
    ?>
    
</body>
</html>