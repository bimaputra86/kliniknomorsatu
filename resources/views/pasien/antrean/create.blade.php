@extends('layouts.pasien')

@section('title', 'Ambil Antrean Online Cerdas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Ambil Nomor Antrean Online</h1>
        <p class="text-sm text-slate-500 mt-1">Sistem antrean cerdas real-time dengan validasi hari praktik & proteksi kuota harian dokter.</p>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 text-xs font-bold flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('pasien.antrean.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Step 1: Pilih Poliklinik -->
            <div>
                <label for="poli_select" class="block text-xs font-bold text-slate-700 mb-1.5">
                    1. Pilih Poliklinik Tujuan <span class="text-teal-600">*</span>
                </label>
                <select id="poli_select" onchange="onPoliOrDateChange()" required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                    <option value="">-- Pilih Poliklinik --</option>
                    @foreach($polis as $p)
                        <option value="{{ $p->id_poli }}">{{ $p->nama_poli }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Step 2: Pilih Tanggal Berobat (Max H+7) -->
            <div>
                <label for="tanggal_berobat" class="block text-xs font-bold text-slate-700 mb-1.5">
                    2. Tanggal Berobat / Kunjungan <span class="text-teal-600">* (Maksimal H+7)</span>
                </label>
                <input type="date" name="tanggal_berobat" id="tanggal_berobat" 
                       value="{{ old('tanggal_berobat', date('Y-m-d')) }}" 
                       min="{{ date('Y-m-d') }}" 
                       max="{{ date('Y-m-d', strtotime('+7 days')) }}" 
                       onchange="onPoliOrDateChange()" 
                       required class="w-full px-4 py-3 rounded-2xl bg-white border border-slate-300 text-slate-900 text-sm font-medium focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                <p id="selectedDayBadge" class="text-xs font-bold text-teal-700 mt-1.5 hidden"></p>
            </div>

            <!-- Step 3: Dokter & Jam Praktik Tersedia (Filtered Real-Time via AJAX) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    3. Dokter Bertugas & Slot Kuota Real-Time <span class="text-teal-600">*</span>
                </label>
                <div id="doctorCardsContainer" class="grid grid-cols-1 gap-3">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-400 font-medium">
                        Silakan pilih Poliklinik & Tanggal Berobat terlebih dahulu.
                    </div>
                </div>
                <input type="hidden" name="id_jadwal" id="selected_id_jadwal" required>
                @error('id_jadwal')
                    <p class="mt-1.5 text-xs text-red-500 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" id="submitBtn" disabled class="w-full py-4 px-6 bg-teal-600 hover:bg-teal-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white rounded-2xl font-extrabold text-sm shadow-lg shadow-teal-600/20 transition-all">
                    Terbitkan Tiket Nomor Antrean Digital
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    function onPoliOrDateChange() {
        const poli = document.getElementById('poli_select').value;
        const tanggal = document.getElementById('tanggal_berobat').value;
        const container = document.getElementById('doctorCardsContainer');
        const submitBtn = document.getElementById('submitBtn');
        const hiddenInput = document.getElementById('selected_id_jadwal');
        const dayBadge = document.getElementById('selectedDayBadge');

        hiddenInput.value = '';
        submitBtn.disabled = true;

        if (tanggal) {
            const dateObj = new Date(tanggal + 'T00:00:00');
            const dayName = dayNames[dateObj.getDay()];
            dayBadge.innerText = `Hari Pilihan: ${dayName} (${tanggal})`;
            dayBadge.classList.remove('hidden');
        } else {
            dayBadge.classList.add('hidden');
        }

        if (!poli || !tanggal) {
            container.innerHTML = `<div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center text-xs text-slate-400 font-medium">Silakan pilih Poliklinik & Tanggal Berobat terlebih dahulu.</div>`;
            return;
        }

        container.innerHTML = `<div class="p-4 text-center text-xs text-teal-600 font-bold">Mengecek jadwal dokter & sisa kuota real-time...</div>`;

        fetch(`{{ route('pasien.antrean.doctors') }}?poli=${encodeURIComponent(poli)}&tanggal_berobat=${encodeURIComponent(tanggal)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    container.innerHTML = `<div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-center text-xs text-amber-800 font-bold">Tidak ada dokter yang berpraktik pada hari tersebut untuk poli ini. Silakan pilih tanggal berobat lainnya.</div>`;
                    return;
                }

                let html = '';
                data.forEach(item => {
                    const isFull = item.is_full;
                    const cardClass = isFull 
                        ? 'p-4 rounded-2xl bg-slate-100 border border-slate-200 opacity-60 cursor-not-allowed flex items-center justify-between'
                        : 'doctor-card p-4 rounded-2xl bg-white border border-slate-200 hover:border-teal-500 cursor-pointer flex items-center justify-between transition-all';

                    const badgeHtml = isFull 
                        ? `<span class="px-2.5 py-1 rounded-xl bg-red-100 text-red-700 text-xs font-black border border-red-200">KUOTA HABIS</span>`
                        : `<span class="px-2.5 py-1 rounded-xl bg-teal-50 text-teal-700 text-xs font-bold border border-teal-100">Sisa Kuota: ${item.sisa_kuota} Pasien</span>`;

                    html += `
                        <div onclick="${isFull ? '' : 'selectDoctor(' + item.id_jadwal + ', this)'}" class="${cardClass}">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="schedule_radio" value="${item.id_jadwal}" ${isFull ? 'disabled' : ''} class="text-teal-600 focus:ring-teal-500">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">${item.nama_lengkap}</h4>
                                    <p class="text-xs text-slate-500">Praktik Hari ${item.hari} • Jam ${item.jam_mulai.substr(0,5)} - ${item.jam_selesai.substr(0,5)} WIB</p>
                                </div>
                            </div>
                            ${badgeHtml}
                        </div>
                    `;
                });
                container.innerHTML = html;
            });
    }

    function selectDoctor(idJadwal, element) {
        document.getElementById('selected_id_jadwal').value = idJadwal;
        document.getElementById('submitBtn').disabled = false;
        
        // Highlight active radio
        const radio = element.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }
</script>
@endsection
