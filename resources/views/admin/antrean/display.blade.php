<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Monitor Antrean Pasien - Klinik Nomor Satu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #090d16;
            color: #f3f4f6;
            overflow: hidden;
        }
        .font-display {
            font-family: 'Outfit', sans-serif;
        }
        .glass-card {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }
        .glass-card-active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(6, 182, 212, 0.15));
            border: 2px solid #10b981;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.3);
        }
        .text-glow-emerald {
            text-shadow: 0 0 30px rgba(16, 185, 129, 0.6);
        }
        .animate-pulse-subtle {
            animation: pulse-border 2s infinite ease-in-out;
        }
        @keyframes pulse-border {
            0%, 100% { border-color: rgba(16, 185, 129, 0.4); transform: scale(1); }
            50% { border-color: rgba(16, 185, 129, 1); transform: scale(1.008); }
        }
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
        }
        .marquee-content {
            display: inline-block;
            animation: marquee 35s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
    </style>
</head>
<body class="h-screen w-screen flex flex-col justify-between p-6 select-none">

    <!-- OVERLAY AUDIO USER INTERACTION PROMPT -->
    <div id="audioOverlay" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center transition-all duration-500">
        <div class="max-w-lg bg-slate-900 border border-emerald-500/30 rounded-3xl p-8 shadow-2xl space-y-6">
            <div class="w-20 h-20 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mx-auto animate-bounce">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white font-display">Aktifkan Suara Display TV</h2>
                <p class="text-slate-400 text-xs mt-2">Klik tombol di bawah agar browser diizinkan memutar bel panggilan antrean secara otomatis.</p>
            </div>

            <!-- Voice Selection Dropdown -->
            <div class="text-left bg-slate-800/80 p-4 rounded-2xl border border-slate-700 space-y-2">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-emerald-400">Pilih Karakter Suara TTS (Natural):</label>
                <select id="voiceSelect" onchange="changeVoice()" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs font-semibold text-slate-200 focus:border-emerald-500 focus:outline-none">
                    <option value="">Memuat daftar suara...</option>
                </select>
                <p class="text-[10px] text-slate-400 italic">Rekomendasi: Microsoft Gadis / Ardi Natural atau Google Bahasa Indonesia.</p>
            </div>

            <button onclick="enableAudioAndFullscreen()" class="w-full py-4 px-6 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/30 transform active:scale-95 transition-all text-base flex items-center justify-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mulai Display & Suara Natural
            </button>
        </div>
    </div>

    <!-- HEADER STATUS BAR -->
    <header class="glass-card rounded-3xl p-5 flex items-center justify-between border-slate-800">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-center justify-center text-emerald-400 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0a2 2 0 012-2h2a2 2 0 012 2m-6 0v-4a2 2 0 012-2h2a2 2 0 012 2v4f" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-black font-display tracking-tight text-white flex items-center gap-2">
                    KLINIK NOMOR SATU
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 uppercase tracking-widest">Live Display</span>
                </h1>
                <p class="text-slate-400 text-xs font-medium">Sistem Pemanggilan & Antrean Kunjungan Pasien Real-Time</p>
            </div>
        </div>

        <!-- RIGHT: DATE, REALTIME DIGITAL CLOCK & AUDIO TEST -->
        <div class="flex items-center gap-4">
            <button onclick="testAudio()" class="px-3.5 py-2 bg-slate-800/80 hover:bg-slate-700 text-emerald-400 hover:text-emerald-300 font-bold rounded-2xl border border-slate-700 text-xs transition flex items-center gap-1.5" title="Uji Suara Panggilan">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                <span>Tes Suara</span>
            </button>

            <div class="text-right">
                <div id="clockTime" class="text-3xl font-black font-display text-emerald-400 tracking-wider">00:00:00</div>
                <div id="clockDate" class="text-xs font-medium text-slate-400">Sabtu, 1 Agustus 2026</div>
            </div>
            <button onclick="toggleFullscreen()" class="p-3 bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white rounded-2xl transition border border-slate-700" title="Full Screen (F11)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                </svg>
            </button>
        </div>
    </header>

    <!-- MAIN GRID CONTAINER -->
    <main class="grid grid-cols-12 gap-6 my-4 flex-1">

        <!-- LEFT COLUMN: MAIN ANNOUNCEMENT CARD (SEDANG DIPANGGIL) -->
        <section class="col-span-12 lg:col-span-7 flex flex-col">
            <div id="mainCallCard" class="glass-card glass-card-active rounded-3xl p-8 flex flex-col justify-between flex-1 relative overflow-hidden transition-all duration-500">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- SUB-HEADER -->
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div class="flex items-center gap-3">
                        <span class="relative flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                        </span>
                        <span class="text-sm font-extrabold uppercase tracking-widest text-emerald-400">Panggilan Antrean Utama</span>
                    </div>
                    <span id="callStatusBadge" class="px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">SEDANG DIPANGGIL</span>
                </div>

                <!-- HUGE NUMBER DISPLAY -->
                <div class="text-center my-auto py-6">
                    <div class="text-slate-400 font-semibold text-sm uppercase tracking-widest mb-2">Nomor Antrean Pasien</div>
                    <div id="mainCallNumber" class="text-7xl xl:text-9xl font-black font-display tracking-tight text-white text-glow-emerald transition-all duration-300 transform">
                        ---
                    </div>
                    <div id="mainCallPatient" class="text-2xl xl:text-4xl font-extrabold text-cyan-300 mt-4 tracking-wide">
                        Menunggu Panggilan...
                    </div>
                </div>

                <!-- DESTINATION FOOTER -->
                <div class="bg-slate-900/80 rounded-2xl p-5 border border-white/5 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Tujuan Poliklinik / Ruangan</div>
                        <div id="mainCallPoli" class="text-xl xl:text-2xl font-black text-emerald-400 font-display mt-0.5">
                            -
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-medium text-slate-400 uppercase tracking-wider">Dokter Bertugas</div>
                        <div id="mainCallDoctor" class="text-base xl:text-lg font-bold text-slate-200 mt-0.5">
                            -
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- RIGHT COLUMN: POLICLINIC SUMMARY GRID -->
        <section class="col-span-12 lg:col-span-5 flex flex-col">
            <div class="glass-card rounded-3xl p-6 flex flex-col flex-1 border-slate-800">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
                    <h2 class="text-lg font-extrabold text-white font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Status Antrean Poliklinik
                    </h2>
                    <span class="text-xs font-semibold text-slate-400 bg-slate-800 px-3 py-1 rounded-full">Real-Time Update</span>
                </div>

                <!-- DYNAMIC POLI CARDS CONTAINER -->
                <div id="poliSummaryContainer" class="space-y-3 overflow-y-auto max-h-[calc(100vh-280px)] pr-1">
                    <!-- JS populate -->
                    <div class="text-center py-10 text-slate-500">Memuat status poliklinik...</div>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER RUNNING TEXT -->
    <footer class="glass-card rounded-2xl p-3 flex items-center gap-4 border-slate-800 overflow-hidden">
        <div class="bg-emerald-500/20 text-emerald-400 font-extrabold text-xs px-4 py-2 rounded-xl border border-emerald-500/30 flex items-center gap-2 whitespace-nowrap">
            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
            </svg>
            INFORMASI KLINIK
        </div>
        <div class="marquee-container text-slate-300 text-sm font-medium flex-1">
            <div class="marquee-content">
                Selamat Datang di Klinik Nomor Satu • Harap perhatikan panggilan nomor antrean Anda di layar monitor ini • Bagi Pasien BPJS harap menyerahkan berkas rujukan & kartu BPJS di Loket Resepsionis • Jagalah kebersihan dan ketertiban selama mengantre • Terima kasih atas kepercayaan Anda.
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let isAudioAllowed = false;
        let lastCalledToken = null;
        let isFirstFetch = true; // Flashing protection on initial load
        let speechSynth = window.speechSynthesis;
        let voicesList = [];
        let selectedVoice = null;

        function populateVoices() {
            if (!speechSynth) return;
            voicesList = speechSynth.getVoices();
            const selectEl = document.getElementById('voiceSelect');
            if (!selectEl) return;

            selectEl.innerHTML = '';

            // Filter & prioritize Indonesian & Natural voices
            let indonesianVoices = voicesList.filter(v => 
                v.lang.includes('id') || 
                v.lang.includes('ID') || 
                v.name.toLowerCase().includes('indonesia') ||
                v.name.toLowerCase().includes('gadis') ||
                v.name.toLowerCase().includes('ardi')
            );

            if (indonesianVoices.length === 0) {
                indonesianVoices = voicesList; // Fallback to all voices if no specific ID voice
            }

            indonesianVoices.forEach((voice, index) => {
                const option = document.createElement('option');
                option.value = voice.name;
                option.textContent = `${voice.name} (${voice.lang})${voice.name.toLowerCase().includes('natural') || voice.name.toLowerCase().includes('online') ? ' - 🔥 Natural' : ''}`;
                
                // Prioritize Natural / Neural / Google / Microsoft Gadis
                if (!selectedVoice && (
                    voice.name.toLowerCase().includes('gadis') ||
                    voice.name.toLowerCase().includes('natural') ||
                    voice.name.toLowerCase().includes('google bahasa')
                )) {
                    option.selected = true;
                    selectedVoice = voice;
                }
                selectEl.appendChild(option);
            });

            if (!selectedVoice && indonesianVoices.length > 0) {
                selectedVoice = indonesianVoices[0];
            }
        }

        if (speechSynth) {
            populateVoices();
            if (speechSynth.onvoiceschanged !== undefined) {
                speechSynth.onvoiceschanged = populateVoices;
            }
        }

        function changeVoice() {
            const selectEl = document.getElementById('voiceSelect');
            const voiceName = selectEl.value;
            selectedVoice = voicesList.find(v => v.name === voiceName) || selectedVoice;
        }

        // Clock Function
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateStr = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            document.getElementById('clockTime').textContent = timeStr;
            document.getElementById('clockDate').textContent = dateStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Audio & Fullscreen Handler
        function enableAudioAndFullscreen() {
            isAudioAllowed = true;
            document.getElementById('audioOverlay').classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                document.getElementById('audioOverlay').style.display = 'none';
            }, 500);

            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            }
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => console.log(err));
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Web Audio Synthesizer Chime Sound (Warm Airport/Hospital Dual Chime)
        function playChimeSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                
                // First Note (E5)
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, audioCtx.currentTime);
                gain1.gain.setValueAtTime(0.25, audioCtx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.9);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start(audioCtx.currentTime);
                osc1.stop(audioCtx.currentTime + 0.9);

                // Second Note (C5) after 380ms
                setTimeout(() => {
                    const osc2 = audioCtx.createOscillator();
                    const gain2 = audioCtx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(523.25, audioCtx.currentTime);
                    gain2.gain.setValueAtTime(0.35, audioCtx.currentTime);
                    gain2.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1.2);
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.start(audioCtx.currentTime);
                    osc2.stop(audioCtx.currentTime + 1.2);
                }, 380);

            } catch (e) {
                console.warn('Audio Context Error:', e);
            }
        }

        // Text-to-Speech Voice Synthesizer (Natural Articulation Engine)
        function speakText(text) {
            if (!speechSynth || !isAudioAllowed) return;

            // Play chime sound first
            playChimeSound();

            if (speechSynth.paused) {
                speechSynth.resume();
            }

            speechSynth.cancel();

            setTimeout(() => {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.92; // Kecepatan artikulasi alami (tidak terlalu cepat/lambat)
                utterance.pitch = 1.0; // Pitch nada manusia normal
                utterance.volume = 1.0;

                if (selectedVoice) {
                    utterance.voice = selectedVoice;
                }

                speechSynth.speak(utterance);
            }, 950);
        }

        function testAudio() {
            isAudioAllowed = true;
            speakText("Panggilan antrean. Nomor antrean B, U M, 0 0 1, atas nama Fitri, silakan menuju ke Poli Umum. Terima kasih.");
        }

        // Fetch Live Display Data via AJAX
        async function fetchDisplayData() {
            try {
                const response = await fetch("{{ route('admin.antrean-kunjungan.display-data') }}");
                if (!response.ok) return;

                const data = await response.json();
                
                // 1. Update Main Call Section
                const sedangDipanggil = data.sedang_dipanggil;
                const callNumberEl = document.getElementById('mainCallNumber');
                const callPatientEl = document.getElementById('mainCallPatient');
                const callPoliEl = document.getElementById('mainCallPoli');
                const callDoctorEl = document.getElementById('mainCallDoctor');
                const callCardEl = document.getElementById('mainCallCard');

                if (sedangDipanggil) {
                    callNumberEl.textContent = sedangDipanggil.kode;
                    callPatientEl.textContent = sedangDipanggil.nama_pasien;
                    callPoliEl.textContent = sedangDipanggil.nama_poli;
                    callDoctorEl.textContent = sedangDipanggil.nama_dokter || 'Dokter Jaga';

                    const currentToken = sedangDipanggil.call_token;

                    // Pada load pertama kali, hanya simpan token tanpa membunyikan suara antrean lama
                    if (isFirstFetch) {
                        lastCalledToken = currentToken;
                        isFirstFetch = false;
                    } else if (lastCalledToken !== currentToken) {
                        // Hanya bunyikan jika Resepsionis mengklik Panggil / Ulangi baru
                        lastCalledToken = currentToken;

                        // Visual Flash animation
                        callCardEl.classList.add('animate-pulse-subtle');
                        setTimeout(() => callCardEl.classList.remove('animate-pulse-subtle'), 4000);

                        // Format Pengejaan Suara Alami (Natural Indonesian Articulation)
                        let cleanKode = sedangDipanggil.kode.replace(/-/g, ' ');
                        let spelledKode = cleanKode.split('').map(char => {
                            switch(char) {
                                case '0': return 'Nol';
                                case '1': return 'Satu';
                                case '2': return 'Dua';
                                case '3': return 'Tiga';
                                case '4': return 'Empat';
                                case '5': return 'Lima';
                                case '6': return 'Enam';
                                case '7': return 'Tujuh';
                                case '8': return 'Delapan';
                                case '9': return 'Sembilan';
                                default: return char;
                            }
                        }).join(', ');

                        const textToSpeak = `Panggilan antrean. Nomor antrean, ${spelledKode}, atas nama, ${sedangDipanggil.nama_pasien}, silakan menuju ke, ${sedangDipanggil.nama_poli}. Terima kasih.`;
                        speakText(textToSpeak);
                    }
                } else {
                    isFirstFetch = false;
                    callNumberEl.textContent = "---";
                    callPatientEl.textContent = "Menunggu Panggilan...";
                    callPoliEl.textContent = "-";
                    callDoctorEl.textContent = "-";
                }

                // 2. Update Poli Summary Grid
                const poliContainer = document.getElementById('poliSummaryContainer');
                if (data.poli_summary && data.poli_summary.length > 0) {
                    let html = '';
                    data.poli_summary.forEach(p => {
                        html += `
                            <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 flex items-center justify-between hover:border-slate-700 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 font-black flex items-center justify-center font-display text-sm">
                                        ${p.kode_poli}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-base font-display">${p.nama_poli}</div>
                                        <div class="text-xs text-slate-400 flex items-center gap-2 mt-0.5">
                                            <span>Berikutnya: <strong class="text-amber-400">${p.kode_berikutnya}</strong></span>
                                            •
                                            <span>Sisa: <strong class="text-slate-200">${p.sisa_menunggu}</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Sedang Dilayani</div>
                                    <div class="text-2xl font-black font-display ${p.kode_aktif !== '-' ? 'text-emerald-400 text-glow-emerald' : 'text-slate-600'}">
                                        ${p.kode_aktif}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    poliContainer.innerHTML = html;
                } else {
                    poliContainer.innerHTML = '<div class="text-center py-6 text-slate-500 text-sm">Belum ada poliklinik terdaftar.</div>';
                }

            } catch (err) {
                console.error('Error fetching display data:', err);
            }
        }

        // Auto Poll Every 3 Seconds
        setInterval(fetchDisplayData, 3000);
        fetchDisplayData();

        // Allow any user interaction on page to enable audio
        document.addEventListener('click', () => {
            isAudioAllowed = true;
        });
    </script>
</body>
</html>
