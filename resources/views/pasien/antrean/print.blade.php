<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrean {{ $antrean->kode_antrean ?? ('#' . $antrean->nomor_antrean) }} - Klinik Nomor Satu</title>
    
    <!-- Tailwind CSS CDN for Print Styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { border: none !important; shadow: none !important; }
        }
        @page { size: 80mm 150mm; margin: 0; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased p-4 sm:p-8 min-h-screen flex flex-col items-center justify-center">

    <!-- Action Bar / Buttons (No Print) -->
    <div class="no-print mb-6 flex gap-3 max-w-sm w-full">
        <a href="{{ route('pasien.dashboard') }}" class="flex-1 py-3 px-4 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-2xl text-xs text-center transition-all">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="flex-1 py-3 px-4 bg-teal-600 hover:bg-teal-700 text-white font-extrabold rounded-2xl text-xs text-center shadow-lg shadow-teal-600/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Tiket</span>
        </button>
    </div>

    <!-- Printable Thermal Struk Card (80mm Width Template) -->
    <div class="print-card bg-white p-6 rounded-3xl shadow-xl border border-slate-200 max-w-sm w-full text-center space-y-4">
        
        <!-- Header Clinic Info -->
        <div class="border-b border-dashed border-slate-300 pb-3">
            <h2 class="font-extrabold text-lg text-slate-900 tracking-tight">KLINIK NOMOR SATU</h2>
            <p class="text-[10px] text-slate-500 font-medium">Jl. Raya Padang No. 1 • Telp: (0751) 123456</p>
            <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full bg-teal-50 text-teal-700 text-[10px] font-bold border border-teal-200">
                TIKET ANTREAN DIGITALLY VERIFIED
            </span>
        </div>

        <!-- Ticket Number Display -->
        <div class="py-2 space-y-1">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nomor Antrean Anda</span>
            <h1 class="text-5xl font-black text-teal-700 tracking-tight font-mono">
                {{ $antrean->kode_antrean ?? ('#' . $antrean->nomor_antrean) }}
            </h1>
            <p class="text-xs font-bold text-slate-600">
                Jalur: <span class="text-teal-700">{{ $antrean->jenis_pasien ?? $pasien->jenis_pasien }}</span>
            </p>
        </div>

        <!-- Details List -->
        <div class="border-t border-b border-dashed border-slate-300 py-3 space-y-1.5 text-xs text-left text-slate-600">
            <div class="flex justify-between">
                <span class="text-slate-400">Poli Tujuan:</span>
                <span class="font-extrabold text-teal-700 font-mono">{{ $antrean->nama_poli_master ?? ($antrean->nama_poli_jadwal ?? 'Poli Kunjungan') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Dokter Bertugas:</span>
                <span class="font-bold text-slate-900 truncate max-w-[180px]">{{ $antrean->nama_dokter ?? 'Dokter Jaga' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Nama Pasien:</span>
                <span class="font-bold text-slate-900 truncate max-w-[180px]">{{ $pasien->nama_lengkap }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">ID Pasien / NIK:</span>
                <span class="font-mono text-slate-800">{{ $pasien->id_pasien }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Tgl Kunjungan:</span>
                <span class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($antrean->tanggal_antrean)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Status Tiket:</span>
                <span class="font-bold text-emerald-600">{{ $antrean->status_antrean }}</span>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="pt-1 text-[10px] text-slate-400 space-y-1">
            <p>Harap hadir 15 menit sebelum panggilan pemeriksaan.</p>
            <p class="font-mono text-[9px] text-slate-300">Dicetak: {{ date('d-m-Y H:i:s') }} WIB</p>
        </div>
    </div>

</body>
</html>
