<x-falcon-cms::layouts.admin active-menu="falcon-slider">
    <x-slot name="title">Edit {{ $slider->name }}</x-slot>

    {{-- Provides window.falconConfirm() — the CMS sweet-alert used for delete confirmations. --}}
    <x-falcon-cms::admin.delete-modal />

    @once
        <link rel="stylesheet" href="{{ \Illuminate\Support\Str::start((string) parse_url(asset('vendor/falcon-cms/css/pickr.classic.min.css'), PHP_URL_PATH), '/') }}">
        <script src="{{ \Illuminate\Support\Str::start((string) parse_url(asset('vendor/falcon-cms/js/pickr.min.js'), PHP_URL_PATH), '/') }}"></script>
        {{-- Icon picker font: load a Google-Fonts subset of EXACTLY the icon-library glyphs so
             every pickable icon renders (the admin's self-hosted font is only a partial subset). --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names={{ implode(',', $icons) }}&display=block">
        <style>
            /* Compact Pickr popup that stays tidy inside the panel (not over the canvas). */
            .pcr-app { width: 232px !important; padding: 8px !important; z-index: 3000 !important; }
            .pcr-app .pcr-palette, .pcr-app .pcr-color-palette { min-width: 0 !important; }
            .pcr-app .pcr-swatches { margin-top: 6px !important; }
            .pcr-app .pcr-interaction { margin-top: 6px !important; }
            .pcr-app .pcr-interaction input { font-size: 11px !important; }
        </style>
        <script>
            // Same picker the page builder uses — Pickr with opacity + swatches (HEXA output).
            window.fsPickr = function (el, get, set) {
                if (el.__pickr || typeof Pickr === 'undefined') return;
                const p = Pickr.create({
                    el: el, useAsButton: true, theme: 'classic', default: get() || '#000000',
                    position: 'bottom-start', defaultRepresentation: 'HEXA',
                    components: { preview: true, opacity: true, hue: true, interaction: { hex: true, input: true, clear: false, save: true } },
                    swatches: ['#000000','#ffffff','#f44336','#e91e63','#9c27b0','#673ab7','#3f51b5','#2196f3','#03a6f4','#00bcd4','#009688','#4caf50','#8bc34a','#cddc39']
                });
                el.__pickr = p;
                p.on('change', (c) => { if (c) set(c.toHEXA().toString()); });
                p.on('save', () => p.hide());
                // The editor root is position:fixed, which throws Pickr's own positioner off —
                // it placed the popup over the canvas centre, and re-ran on every scroll/resize
                // (hence the flashing). Pickr calls _rePositioningPicker() everywhere it wants to
                // position the popup (on open AND in its scroll rAF loop), so we replace that one
                // method with our own "pin under the swatch" logic. Single writer → no flash.
                // Whether to open above or below is decided ONCE per open and held for the
                // whole session — otherwise a scroll that opens up room below makes the popup
                // hop sides mid-scroll (the "bump"). Reset when it closes.
                let flipAbove = false, sideDecided = false;
                const place = () => {
                    const app = (p.getRoot && p.getRoot().app) || document.querySelector('.pcr-app.visible');
                    if (!app) return;
                    const r = el.getBoundingClientRect(), aw = app.offsetWidth || 232, ah = app.offsetHeight || 260;
                    if (!sideDecided) {
                        const roomBelow = window.innerHeight - r.bottom, roomAbove = r.top;
                        flipAbove = roomBelow < ah + 13 && roomAbove > roomBelow;
                        sideDecided = true;
                    }
                    let left = r.left;
                    if (left + aw > window.innerWidth - 8) left = window.innerWidth - aw - 8;
                    const top = flipAbove ? (r.top - ah - 5) : (r.bottom + 5);
                    app.style.position = 'fixed';
                    app.style.left = Math.max(8, left) + 'px';
                    app.style.top  = Math.max(8, top) + 'px';
                };
                p._rePositioningPicker = place;
                p.on('hide', () => { sideDecided = false; });
            };
        </script>
    @endonce

    <style>
        .fse { --bar:#23232b; --panel:#2a2a33; --work:#2b2b34; --line:#3a3a44; --ink:#c9c9d4; --mut:#8a8a99; --accent:#4a9eff;
               color:var(--ink); font-size:13px; }
        .fse, .fse *, .fse *::before, .fse *::after { box-sizing:border-box; }   /* keep inputs/grids inside the panel (no overflow under the scrollbar) */
        body.sidebar-collapsed .fse { left:48px !important; }                     /* follow the collapsed admin sidebar */
        @media (max-width:782px){ .fse { left:0 !important; } }
        .fse-bar { background:var(--bar); border-bottom:1px solid var(--line); }
        .fse-btn { display:inline-flex; align-items:center; gap:6px; height:30px; padding:0 11px; border-radius:5px; color:var(--ink); background:transparent; border:1px solid transparent; cursor:pointer; font-size:12.5px; }
        .fse-btn:hover { background:#33333e; }
        .fse-btn .material-symbols-outlined { font-size:18px; }
        .fse-num { display:inline-flex; align-items:center; gap:5px; height:28px; padding:0 8px; background:#1c1c24; border:1px solid var(--line); border-radius:5px; }
        .fse-num input { width:52px; background:transparent; border:0; color:var(--ink); text-align:center; outline:none; font-size:12.5px; }
        .fse-input { background:#1c1c24; border:1px solid var(--line); border-radius:5px; color:var(--ink); height:32px; padding:0 9px; width:100%; outline:none; font-size:12.5px; }
        .fse-input:focus { border-color:var(--accent); }
        /* Searchable font picker */
        .fse-fontpop { position:absolute; z-index:2000; left:0; right:0; top:calc(100% + 4px); background:#1c1c24; border:1px solid var(--line); border-radius:6px; padding:8px; box-shadow:0 12px 34px rgba(0,0,0,.55); }
        .fse-fontlist { max-height:260px; overflow-y:auto; }
        .fse-fontopt { display:flex; align-items:center; justify-content:space-between; gap:8px; padding:7px 9px; border-radius:4px; cursor:pointer; color:var(--ink); font-size:13px; }
        .fse-fontopt:hover { background:#2a2f3a; }
        .fse-fontopt.on { background:var(--accent); color:#fff; }
        .fse-fontcat { font-size:10px; color:var(--mut); flex-shrink:0; }
        .fse-fontopt.on .fse-fontcat { color:rgba(255,255,255,.7); }
        textarea.fse-input { height:auto; padding:7px 9px; }
        select.fse-input { height:32px; }
        /* Date/time inputs: dark UA styling so the calendar icon + popup are visible on the dark panel */
        input.fse-input[type="datetime-local"], input.fse-input[type="date"] { color-scheme:dark; cursor:pointer; }
        input.fse-input[type="datetime-local"]::-webkit-calendar-picker-indicator,
        input.fse-input[type="date"]::-webkit-calendar-picker-indicator { filter:invert(1); opacity:.65; cursor:pointer; transition:opacity .15s; }
        input.fse-input[type="datetime-local"]:hover::-webkit-calendar-picker-indicator,
        input.fse-input[type="date"]:hover::-webkit-calendar-picker-indicator { opacity:1; }
        .fse-lbl { display:block; font-size:11px; font-weight:600; color:var(--mut); margin-bottom:5px; text-transform:uppercase; letter-spacing:.03em; }
        .fse-field { margin-bottom:13px; }
        /* rulers */
        .fse-ruler { position:relative; background:#1a1a22; color:var(--mut); overflow:hidden; }
        .fse-tick { position:absolute; font-size:9px; color:#6f6f80; }
        .fse-tick-h { top:4px; transform:translateX(2px); border-left:1px solid #3a3a44; padding-left:3px; height:14px; }
        .fse-tick-v { left:3px; border-top:1px solid #3a3a44; padding-top:1px; width:16px; }
        /* checkerboard = transparency */
        .fse-checker { background-image:
            linear-gradient(45deg,#40404a 25%,transparent 25%),linear-gradient(-45deg,#40404a 25%,transparent 25%),
            linear-gradient(45deg,transparent 75%,#40404a 75%),linear-gradient(-45deg,transparent 75%,#40404a 75%);
            background-size:18px 18px; background-position:0 0,0 9px,9px -9px,-9px 0px; background-color:#4b4b55; }
        .fse-design { position:absolute; top:0; left:0; transform-origin:top left; border-left:1px dashed var(--accent); border-right:1px dashed var(--accent); box-sizing:content-box; }
        .fse-layer { position:absolute; box-sizing:border-box; cursor:move; }
        .fse-layer.sel { outline:1px solid var(--accent); }
        .fse-layer svg { width:100%; height:100%; display:block; }
        .fse-handle { position:absolute; width:12px; height:12px; background:#fff; border:2px solid var(--accent); border-radius:2px; right:-7px; bottom:-7px; cursor:nwse-resize; }
        /* right panel icon tabs */
        .fse-itab { flex:1; display:flex; align-items:center; justify-content:center; height:46px; color:var(--mut); cursor:pointer; border-bottom:2px solid transparent; }
        .fse-itab.on { color:#fff; background:#1c1c24; border-bottom-color:var(--accent); }
        .fse-itab .material-symbols-outlined { font-size:20px; }
        /* navigation panel */
        .fse-navhead { display:flex; align-items:center; justify-content:space-between; margin:10px 0 8px; font-size:13px; font-weight:600; color:#e6e6ee; }
        .fse-switch { position:relative; display:inline-block; width:38px; height:20px; cursor:pointer; }
        .fse-switch input { opacity:0; width:0; height:0; }
        .fse-switch span { position:absolute; inset:0; background:#3a3f4b; border-radius:20px; transition:.2s; }
        .fse-switch span::before { content:''; position:absolute; width:14px; height:14px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.2s; }
        .fse-switch input:checked + span { background:var(--accent); }
        .fse-switch input:checked + span::before { transform:translateX(18px); }
        .fse-seg { flex:1; text-transform:capitalize; background:#2a2f3a; border:1px solid var(--line); color:var(--mut); border-radius:5px; height:30px; cursor:pointer; font-size:12px; }
        .fse-seg.on { background:var(--accent); color:#fff; border-color:var(--accent); }
        .fse-dev { display:flex; align-items:center; justify-content:center; width:34px; height:28px; background:#1c1c24; color:var(--mut); border:0; cursor:pointer; }
        .fse-dev.on { background:var(--accent); color:#fff; }
        .fse-dev .material-symbols-outlined { font-size:17px; }
        .fse-guide { position:absolute; background:#22d3ee; pointer-events:none; z-index:820; box-shadow:0 0 0 .5px rgba(34,211,238,.4); }
        .fse-align { flex:1; display:flex; align-items:center; justify-content:center; height:30px; background:#2a2f3a; border:1px solid var(--line); color:var(--mut); border-radius:5px; cursor:pointer; }
        .fse-align:hover { color:#fff; border-color:var(--accent); }
        .fse-align.on { background:var(--accent); color:#fff; border-color:var(--accent); }
        .fse-pick { display:flex; align-items:center; justify-content:center; height:64px; background:#2a2f3a; border:1px solid var(--line); border-radius:8px; cursor:pointer; transition:border-color .15s,transform .1s; }
        .fse-pick:hover { border-color:var(--accent); transform:translateY(-1px); }
        /* modal overlay — display lives in the class so x-show can't clobber the flex centering */
        /* Modals teleport to <body> (outside .fse), so re-declare the palette vars here or their labels/inputs lose colour. */
        .fse-modal { --bar:#23232b; --panel:#2a2a33; --work:#2b2b34; --line:#3a3a44; --ink:#c9c9d4; --mut:#8a8a99; --accent:#4a9eff; color:var(--ink);
                     position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:5000; display:flex; align-items:center; justify-content:center; }
        /* Full-screen live-preview overlay. Display lives in the CLASS (not inline) so
           Alpine's x-show — which toggles the inline display property — can't wipe out the
           flex layout when the overlay is shown (that collapses the iframe's height). */
        .fse-livepreview { position:fixed; inset:0; z-index:100000; background:#0b0b0f; display:flex; flex-direction:column; }
        .fse-livepreview > iframe { flex:1 1 auto; width:100%; min-height:0; border:0; background:#0b0b0f; }
        .fse-align .material-symbols-outlined { font-size:17px; }
        .fse-layer.fs-loop-float { animation: fs-loop-float 3s ease-in-out infinite; }
        .fse-layer.fs-loop-bob   { animation: fs-loop-bob 2s ease-in-out infinite; }
        .fse-layer.fs-loop-pulse { animation: fs-loop-pulse 2s ease-in-out infinite; }
        .fse-layer.fs-loop-sway  { animation: fs-loop-sway 3.5s ease-in-out infinite; }
        .fse-layer.fs-loop-spin  { animation: fs-loop-spin 8s linear infinite; }
        @keyframes fs-loop-float { 50% { translate: 0 -12px; } }
        @keyframes fs-loop-bob   { 50% { translate: 0 8px; } }
        @keyframes fs-loop-pulse { 50% { scale: 1.06; } }
        @keyframes fs-loop-sway  { 0%,100% { rotate: -3deg; } 50% { rotate: 3deg; } }
        @keyframes fs-loop-spin  { to { rotate: 360deg; } }
        /* navigation preview inside the canvas (mirrors the frontend) */
        .fse-navp-arrow { position:absolute; color:#fff; display:flex; align-items:center; justify-content:center; line-height:1; }
        .fse-navp-arrow.st-chevron,.fse-navp-arrow.st-arrow,.fse-navp-arrow.st-circle { width:44px; height:44px; border-radius:50%; background:rgba(0,0,0,.4); font-size:22px; }
        .fse-navp-arrow.st-square { width:44px; height:44px; border-radius:6px; background:rgba(0,0,0,.4); font-size:22px; }
        .fse-navp-arrow.st-minimal { background:transparent; font-size:38px; text-shadow:0 1px 4px rgba(0,0,0,.5); }
        .fse-navp-arrow.st-pill { width:56px; height:36px; border-radius:20px; background:rgba(0,0,0,.4); font-size:20px; }
        .fse-navp-arrow.st-double { width:44px; height:44px; border-radius:50%; background:rgba(0,0,0,.4); font-size:20px; }
        .fse-navp-arrow.st-outline { width:44px; height:44px; border-radius:50%; background:transparent; border:2px solid rgba(255,255,255,.75); font-size:20px; }
        .fse-navp-arrow.st-glass { width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,.14); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); border:1px solid rgba(255,255,255,.28); font-size:20px; }
        .fse-navp-arrow.st-soft { width:44px; height:44px; border-radius:50%; background:#fff; color:#111827; font-size:20px; box-shadow:0 2px 10px rgba(0,0,0,.25); }
        .fse-navp-dots { position:absolute; display:flex; gap:8px; align-items:center; }
        .fse-navp-dot { background:rgba(255,255,255,.5); }
        .fse-navp-dot.st-dots { width:11px; height:11px; border-radius:50%; }
        .fse-navp-dot.st-bars { width:24px; height:6px; border-radius:3px; }
        .fse-navp-dot.st-numbers { width:26px; height:26px; border-radius:50%; color:#fff; font-size:12px; display:flex; align-items:center; justify-content:center; }
        .fse-navp-dot.st-ring { width:12px; height:12px; border-radius:50%; background:transparent; border:2px solid rgba(255,255,255,.75); }
        .fse-navp-dot.st-ring.active { background:#fff; border-color:#fff; }
        .fse-navp-dot.st-square { width:10px; height:10px; border-radius:2px; }
        .fse-navp-dot.st-pills { width:14px; height:6px; border-radius:3px; transition:width .25s ease, background .25s; }
        .fse-navp-dot.st-pills.active { width:30px; background:#fff; }
        .fse-navp-dot.st-thumbnails { width:52px; height:30px; border-radius:5px; background-size:cover; background-position:center; opacity:.55; border:2px solid transparent; box-shadow:0 2px 6px rgba(0,0,0,.3); }
        .fse-navp-dot.st-thumbnails.active { opacity:1; border-color:#fff; }
        .fse-navp-dot.active { background:#fff; }
        .fse-navp-dot.st-numbers.active { background:#fff; color:#111; }
        .fse-navp-progress { position:absolute; left:0; right:0; background:rgba(255,255,255,.25); overflow:hidden; }
        .fse-navp-progress > i { display:block; height:100%; }
        /* spinner / preloader (shared with the frontend runtime) */
        .fs-spinner { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(15,23,42,.85); z-index:20; }
        .fs-sp-box { position:relative; display:flex; align-items:center; justify-content:center; gap:6px; }
        .fs-spinner.sp-1 i { width:40px; height:40px; border:4px solid rgba(255,255,255,.25); border-top-color:var(--sp-color,#fff); border-radius:50%; animation:fs-spin .8s linear infinite; }
        .fs-spinner.sp-2 i { width:44px; height:44px; border:4px solid transparent; border-top-color:var(--sp-color,#fff); border-bottom-color:var(--sp-color,#fff); border-radius:50%; animation:fs-spin 1s linear infinite; }
        .fs-spinner.sp-3 i { width:12px; height:12px; border-radius:50%; background:var(--sp-color,#fff); animation:fs-bounce .6s infinite alternate; }
        .fs-spinner.sp-3 i:nth-child(2){ animation-delay:.2s } .fs-spinner.sp-3 i:nth-child(3){ animation-delay:.4s }
        .fs-spinner.sp-4 i { width:42px; height:42px; border-radius:50%; background:var(--sp-color,#fff); animation:fs-pulse 1s ease-in-out infinite; }
        .fs-spinner.sp-5 { }
        .fs-spinner.sp-5 .fs-sp-box { align-items:flex-end; gap:5px; height:32px; }
        .fs-spinner.sp-5 i { width:6px; height:30px; background:var(--sp-color,#fff); animation:fs-bars .9s ease-in-out infinite; }
        .fs-spinner.sp-5 i:nth-child(2){animation-delay:.15s}.fs-spinner.sp-5 i:nth-child(3){animation-delay:.3s}.fs-spinner.sp-5 i:nth-child(4){animation-delay:.45s}.fs-spinner.sp-5 i:nth-child(5){animation-delay:.6s}
        .fs-spinner.sp-6 .fs-sp-box { width:48px; height:48px; }
        .fs-spinner.sp-6 i { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); border:4px solid var(--sp-color,#fff); border-radius:50%; animation:fs-ripple 1.3s ease-out infinite; }
        .fs-spinner.sp-6 i:nth-child(2){ animation-delay:-.65s }
        @keyframes fs-spin { to { transform:rotate(360deg) } }
        @keyframes fs-bounce { to { transform:translateY(-14px) } }
        @keyframes fs-pulse { 0%,100% { transform:scale(.5); opacity:.5 } 50% { transform:scale(1); opacity:1 } }
        @keyframes fs-bars { 0%,100% { transform:scaleY(.4) } 50% { transform:scaleY(1) } }
        @keyframes fs-ripple { 0% { width:6px; height:6px; opacity:1 } 100% { width:48px; height:48px; opacity:0 } }
        /* slide strip + timeline */
        .fse-thumb { position:relative; flex:0 0 auto; width:104px; height:60px; border:2px solid #3a3a44; border-radius:4px; overflow:hidden; cursor:pointer; background:#0f172a; }
        .fse-tpl-card { transition:border-color .15s, transform .15s, box-shadow .15s; }
        .fse-tpl-card:hover { border-color:var(--accent) !important; transform:translateY(-3px); box-shadow:0 10px 24px rgba(0,0,0,.4); }
        .fse-iconbtn { width:100%; height:50px; display:flex; align-items:center; justify-content:center; background:#1c1c24; border:1px solid var(--line); border-radius:8px; color:#dbe0ea; cursor:pointer; transition:border-color .12s, background .12s, transform .12s, color .12s; }
        .fse-iconbtn:hover { border-color:var(--accent) !important; background:#23232e; transform:translateY(-2px); color:#fff; }
        .fse-iconbtn .material-symbols-outlined { color:inherit; }
        .fse-thumb.on { border-color:var(--accent); }
        .fse-tl-row { display:flex; align-items:center; height:30px; border-bottom:1px solid #2f2f38; }
        .fse-tl-bar { box-sizing:border-box; height:18px; width:max-content; border-radius:3px; background:linear-gradient(90deg,#3b82f6,#6366f1); display:inline-flex; align-items:center; padding:0 8px; font-size:10px; line-height:18px; color:#fff; white-space:nowrap; }
        .fse-lyr-row { display:flex; align-items:center; gap:6px; padding:5px 7px; border-radius:5px; font-size:12px; cursor:pointer; color:var(--ink); }
        .fse-lyr-row.on { background:#33333e; color:#fff; }
        .fse-lyr-row.sel { box-shadow:inset 0 0 0 1px var(--accent); background:rgba(74,158,255,.12); }
        .fse-lyr-row:hover { background:#2f2f38; }
        .fse-lyr-row.over { outline:1px dashed var(--accent); outline-offset:-1px; }
        .fse-cb { accent-color: var(--accent); }
        /* color palette control */
        .fse-swatch { width:34px; height:32px; flex:0 0 34px; border:1px solid var(--line); border-radius:5px; cursor:pointer; }
        .fse-color-pop { position:absolute; z-index:60; top:38px; left:0; width:214px; background:#20202a; border:1px solid var(--line); border-radius:7px; padding:10px; box-shadow:0 10px 30px -8px rgba(0,0,0,.5); }
        .fse-swatches { display:grid; grid-template-columns:repeat(8,1fr); gap:5px; }
        .fse-sw { width:100%; padding-top:100%; border-radius:4px; border:1px solid rgba(255,255,255,.12); cursor:pointer; position:relative; }
        .fse-sw.on { outline:2px solid var(--accent); outline-offset:1px; }
    </style>

    <div class="fse" x-data="sliderEditor()" x-init="init()" style="position:fixed; top:32px; left:160px; right:0; bottom:0; display:flex; flex-direction:column; overflow:hidden; z-index:1;">

        {{-- ===== Top toolbar ===== --}}
        <div class="fse-bar flex items-center px-3 gap-1" style="height:48px;position:relative;z-index:50;">
            <a href="{{ route('admin.sliders.index') }}" class="fse-btn"><span class="material-symbols-outlined">arrow_back</span> Back</a>
            <span class="fse-btn" style="cursor:default"><span class="material-symbols-outlined">view_carousel</span> Slides <span style="color:var(--mut)" x-text="'('+slides.length+')'"></span></span>
            <button class="fse-btn" @click="openDemos()" title="Apply a pre-built slider"><span class="material-symbols-outlined" style="color:var(--accent)">auto_awesome</span> Pre-built Sliders</button>

            <div class="relative" style="z-index:1000" @click.outside="addMenu=false">
                <button class="fse-btn" @click="addMenu=!addMenu"><span class="material-symbols-outlined">add_box</span> Add Layer</button>
                <div x-show="addMenu" x-cloak class="absolute mt-1 w-44 rounded shadow-lg py-1" style="z-index:1000;background:#2a2a33;border:1px solid var(--line);">
                    <button class="fse-btn w-full justify-start" @click="addLayer('text');addMenu=false"><span class="material-symbols-outlined">title</span> Text</button>
                    <button class="fse-btn w-full justify-start" @click="addLayer('image');addMenu=false"><span class="material-symbols-outlined">image</span> Image</button>
                    <button class="fse-btn w-full justify-start" @click="buttonPicker=true;addMenu=false"><span class="material-symbols-outlined">smart_button</span> Button</button>
                    <button class="fse-btn w-full justify-start" @click="shapePicker=true;addMenu=false"><span class="material-symbols-outlined">crop_square</span> Shape</button>
                    <button class="fse-btn w-full justify-start" @click="addLayer('video');addMenu=false"><span class="material-symbols-outlined">smart_display</span> Video</button>
                    <button class="fse-btn w-full justify-start" @click="addLayer('audio');addMenu=false"><span class="material-symbols-outlined">music_note</span> Audio</button>
                    <button class="fse-btn w-full justify-start" @click="openIconPicker('add');addMenu=false"><span class="material-symbols-outlined">emoji_emotions</span> Icon</button>
                    <button class="fse-btn w-full justify-start" @click="addLayer('countdown');addMenu=false"><span class="material-symbols-outlined">timer</span> Countdown</button>
                    <button class="fse-btn w-full justify-start" @click="addLayer('html');addMenu=false"><span class="material-symbols-outlined">code</span> HTML / Embed</button>
                </div>
            </div>
            <button class="fse-btn" @click="openSaveTemplate()" title="Save this design to your pre-built library"><span class="material-symbols-outlined">bookmark_add</span> Save as pre-built</button>
            <button class="fse-btn" @click="openRevisions()" title="Restore an earlier saved version"><span class="material-symbols-outlined">history</span> Revisions</button>

            <div style="position:absolute; left:calc(50% + 90px); transform:translateX(-50%); display:flex; align-items:center; gap:8px;">
                <button class="fse-btn" @click="undo()" :disabled="!history.length" title="Undo (Ctrl+Z)"><span class="material-symbols-outlined">undo</span></button>
                <button class="fse-btn" @click="redo()" :disabled="!redoStack.length" title="Redo (Ctrl+Shift+Z)"><span class="material-symbols-outlined">redo</span></button>
                <span class="fse-num"><span class="material-symbols-outlined" style="font-size:16px;color:var(--mut)">width</span><input type="number" :value="dw" @input="setDeviceWidth($event.target.value)" :title="device+' width'"><span style="color:var(--mut);font-size:11px">px</span></span>
                <span class="fse-num"><span class="material-symbols-outlined" style="font-size:16px;color:var(--mut)">height</span><input type="number" :value="dh" @input="setDeviceHeight($event.target.value)" :title="device+' height'"><span style="color:var(--mut);font-size:11px">px</span></span>
                <div class="flex items-center" style="border:1px solid var(--line);border-radius:5px;overflow:hidden">
                    <button class="fse-dev" :class="device==='desktop'?'on':''" @click="device='desktop'" title="Desktop"><span class="material-symbols-outlined">computer</span></button>
                    <button class="fse-dev" :class="device==='tablet'?'on':''"  @click="device='tablet'"  title="Tablet"><span class="material-symbols-outlined">tablet</span></button>
                    <button class="fse-dev" :class="device==='mobile'?'on':''"  @click="device='mobile'"  title="Mobile"><span class="material-symbols-outlined">smartphone</span></button>
                </div>
                <select x-model="zoom" title="Zoom" style="height:28px;background:#1c1c24;border:1px solid var(--line);color:var(--ink);border-radius:5px;padding:0 6px;font-size:12px;outline:none">
                    <option value="fit">Fit</option>
                    <option value="50">50%</option>
                    <option value="75">75%</option>
                    <option value="100">100%</option>
                    <option value="125">125%</option>
                    <option value="150">150%</option>
                </select>
                <button class="fse-btn" @click="previewAnims()" title="Preview animations"><span class="material-symbols-outlined">play_circle</span></button>
            </div>
            <button @click="openLivePreview()" title="Full-screen live preview (unsaved changes included)"
                    style="flex-shrink:0;margin-left:auto;display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 20px;border:0;border-radius:4px;background:#8b5cf6;color:#fff;font-weight:600;font-size:14px;cursor:pointer">
                <span class="material-symbols-outlined" style="font-size:20px">smart_display</span> Preview
            </button>
            {{-- Save. Free to design; saving needs Pro. When the site isn't Pro the button
                 is shown locked (greyed) — hovering explains it and clicking opens the
                 upgrade prompt, instead of saving. --}}
            <button @click="canSave ? save() : proSaveAlert()"
                    :disabled="canSave && (saving || !dirty)"
                    :title="canSave ? 'Save (Ctrl+S)' : 'Saving requires a premium subscription'"
                    :style="canSave
                        ? `flex-shrink:0;display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 22px;border:0;border-radius:4px;background:var(--accent);color:#fff;font-weight:600;font-size:14px;opacity:${(saving||!dirty)?0.55:1};cursor:${(saving||!dirty)?'default':'pointer'}`
                        : 'flex-shrink:0;display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 22px;border:0;border-radius:4px;background:#3a3a44;color:#9aa;font-weight:600;font-size:14px;opacity:.75;cursor:not-allowed'">
                <span class="material-symbols-outlined" style="font-size:20px" x-text="canSave ? 'save' : 'lock'"></span><span x-text="canSave ? (saving?'Saving…':'Save') : 'Save (Pro)'"></span>
            </button>
        </div>

        {{-- ===== Middle: workspace + right panel ===== --}}
        <div style="flex:1; display:flex; min-height:0;">

            {{-- Workspace with rulers --}}
            <div style="flex:1; min-width:0; background:var(--work); display:grid; grid-template-columns:22px 1fr; grid-template-rows:22px 1fr;">
                {{-- corner --}}
                <div style="background:#1a1a22;border-right:1px solid var(--line);border-bottom:1px solid var(--line)"></div>
                {{-- top ruler --}}
                <div class="fse-ruler" style="border-bottom:1px solid var(--line)">
                    <template x-for="m in hMarks" :key="'h'+m">
                        <span class="fse-tick fse-tick-h" :style="`left:${100 + m*scale}px`" x-text="m"></span>
                    </template>
                </div>
                {{-- left ruler --}}
                <div class="fse-ruler" style="border-right:1px solid var(--line)">
                    <template x-for="m in vMarks" :key="'v'+m">
                        <span class="fse-tick fse-tick-v" :style="`top:${m*scale}px`" x-text="m"></span>
                    </template>
                </div>
                {{-- canvas — 100px breathing room left & right of the slide --}}
                <div x-ref="canvas" style="position:relative; overflow:auto; padding:22px 100px;">
                    <template x-if="current">
                        {{-- scaled-outer takes the real (scaled) size so scroll works at any zoom --}}
                        <div :style="`position:relative; width:${dw*scale}px; height:${dh*scale}px; margin:0 auto; transition:width .18s ease;`">
                            <div class="fse-design fse-checker" :style="`width:${dw}px;height:${dh}px;transform:scale(${scale});transform-origin:top left;position:absolute;top:0;left:0`"
                                 @pointerdown.self="selectedLayer=-1">
                              {{-- slide inner: bg + layers; the transition preview animates THIS (no scale on it) --}}
                              <div class="fse-slide-inner" x-ref="slideInner" style="position:absolute;inset:0" @pointerdown.self="selectedLayer=-1">
                                {{-- background: color/gradient + image (clipped so a Ken Burns zoom stays inside) --}}
                                <div class="absolute inset-0 pointer-events-none" style="overflow:hidden">
                                    <div class="fse-bg-inner absolute inset-0" x-ref="bgInner" :style="bgStyle(current.bg)"></div>
                                    {{-- Background video preview: real <video> for direct files, a badge for YouTube/Vimeo --}}
                                    <video x-show="current.bg.video && !isEmbedUrl(current.bg.video)" x-ref="bgVideo" :src="current.bg.video" muted loop autoplay playsinline
                                           class="absolute inset-0" :style="`width:100%;height:100%;object-fit:${current.bg.videoFit==='contain'?'contain':'cover'}`"></video>
                                    <div x-show="current.bg.video && isEmbedUrl(current.bg.video)" class="absolute inset-0" style="display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);color:#fff;font-size:13px;gap:6px">
                                        <span class="material-symbols-outlined">smart_display</span> Video background (plays on the live page)
                                    </div>
                                    {{-- Colour/gradient overlay over image+video --}}
                                    <div x-show="current.bg.overlay" class="absolute inset-0" :style="overlayStyle(current.bg)"></div>
                                </div>
                                {{-- layers --}}
                                <template x-for="(layer,li) in current.layers" :key="layer.id">
                                    <div class="fse-layer" :class="(li===selectedLayer?'sel ':'') + (loopOf(layer)!=='none' ? 'fs-loop-'+loopOf(layer) : '')" :style="layerBoxStyle(layer) + ';z-index:' + layerZ(layer, li) + ';transition:transform .2s ease, box-shadow .2s ease, opacity .2s ease;' + fxCss(layer) + (hoverLayer===li ? fxHover(layer) : '')"
                                         draggable="false" @dragstart.prevent
                                         @pointerdown.stop="startDrag($event,layer,li)"
                                         @mouseenter="hoverLayer=li" @mouseleave="hoverLayer=-1">
                                        <template x-if="layer.type==='text'"><div class="fse-txt" :style="textStyle(layer, hoverLayer===li)" x-text="layer.content"></div></template>
                                        <template x-if="layer.type==='button'"><div :style="buttonStyle(layer, hoverLayer===li)" x-text="layer.content"></div></template>
                                        <template x-if="layer.type==='image' && layer.url"><img :src="layer.url" class="w-full h-full pointer-events-none" :style="imgLayerStyle(layer)" draggable="false" alt=""></template>
                                        <template x-if="layer.type==='image' && !layer.url">
                                            <div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;background:#475569;border:1px solid #64748b;border-radius:4px;color:#cbd5e1;pointer-events:none;box-sizing:border-box;overflow:hidden">
                                                <span class="material-symbols-outlined" style="font-size:34px">image</span>
                                                <span style="font-size:11px;letter-spacing:.02em">Choose an image</span>
                                            </div>
                                        </template>
                                        <template x-if="layer.type==='shape'"><div :style="shapeStyle(layer, hoverLayer===li)"></div></template>
                                        <template x-if="layer.type==='icon' && layer.iconName"><span class="material-symbols-outlined" :style="`display:flex;align-items:center;justify-content:center;width:100%;height:100%;line-height:1;pointer-events:none;color:${layer.style.color||'#fff'};font-size:${Math.min(posOf(layer).w,posOf(layer).h)}px`" x-text="layer.iconName"></span></template>
                                        <template x-if="layer.type==='icon' && !layer.iconName"><div :style="iconStyle(layer)" x-html="layer.content"></div></template>
                                        <template x-if="layer.type==='video'">
                                            <div style="position:relative;width:100%;height:100%;overflow:hidden;background:#0b1220;pointer-events:none;display:flex;align-items:center;justify-content:center">
                                                <template x-if="ytId(layer.url)"><img :src="'https://img.youtube.com/vi/'+ytId(layer.url)+'/hqdefault.jpg'" style="width:100%;height:100%;object-fit:cover" alt=""></template>
                                                <template x-if="!ytId(layer.url) && layer.url"><video :src="layer.url" muted style="width:100%;height:100%;object-fit:cover"></video></template>
                                                <span class="material-symbols-outlined" style="position:absolute;font-size:44px;color:#fff;text-shadow:0 2px 8px rgba(0,0,0,.6)">play_circle</span>
                                            </div>
                                        </template>
                                        <template x-if="layer.type==='audio'">
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;gap:8px;background:#111827;color:#94a3b8;pointer-events:none;padding:0 8px">
                                                <span class="material-symbols-outlined" style="font-size:24px">music_note</span>
                                                <span x-show="layer.url" style="font-size:11px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="(layer.url||'').split('/').pop()"></span>
                                                <span x-show="!layer.url" style="font-size:11px">No audio chosen</span>
                                            </div>
                                        </template>
                                        <template x-if="layer.type==='countdown' && !cdExpired(layer)">
                                            <div :style="`display:flex;align-items:center;gap:14px;width:100%;height:100%;pointer-events:none;color:${layer.style.color||'#fff'};justify-content:${layer.style.align==='center'?'center':(layer.style.align==='right'?'flex-end':'flex-start')};`+typoCss(layer.style)">
                                                <template x-for="u in ['Days','Hours','Mins','Secs']" :key="u">
                                                    <div style="text-align:center" :style="`min-width:${Math.round((layer.style.size||40)*1.5)}px`">
                                                        <div :style="`font-size:${layer.style.size||40}px;font-weight:${layer.style.weight||800};line-height:1`" x-text="cdPreview(layer,u)"></div>
                                                        <div :style="`font-size:${Math.max(9,Math.round((layer.style.size||40)*0.28))}px;opacity:.7;margin-top:5px;text-transform:uppercase;letter-spacing:1px`" x-text="u"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="layer.type==='countdown' && cdExpired(layer)">
                                            <div :style="`display:flex;align-items:center;width:100%;height:100%;pointer-events:none;justify-content:${layer.style.align==='center'?'center':(layer.style.align==='right'?'flex-end':'flex-start')};color:${(layer.countdown&&layer.countdown.expiredColor)||layer.style.color||'#fff'};font-size:${(layer.countdown&&layer.countdown.expiredSize)||layer.style.size||40}px;font-weight:${(layer.countdown&&layer.countdown.expiredWeight)||layer.style.weight||800};`+typoCss(layer.style)" x-text="(layer.countdown&&layer.countdown.expiredText)||'Offer ended'"></div>
                                        </template>
                                        <template x-if="layer.type==='html'"><div style="width:100%;height:100%;overflow:hidden;pointer-events:none" x-html="layer.content"></div></template>
                                        {{-- resize handle only exists for the selected layer (x-if avoids the x-show + :style display clash that leaked the handle onto every layer) --}}
                                        <template x-if="li===selectedLayer">
                                            <div class="fse-handle" :style="`transform:scale(${1/scale});transform-origin:bottom right`" @pointerdown.stop="startResize($event,layer)"></div>
                                        </template>
                                    </div>
                                </template>
                                {{-- smart alignment guides (shown while dragging) --}}
                                <template x-for="(g,gi) in guides" :key="'g'+gi">
                                    <div class="fse-guide" :style="g.type==='v' ? `left:${g.pos}px;top:0;bottom:0;width:${1/scale}px` : `top:${g.pos}px;left:0;right:0;height:${1/scale}px`"></div>
                                </template>
                              </div>{{-- /fse-slide-inner --}}

                                {{-- Navigation preview — mirrors the frontend, non-interactive --}}
                                <template x-if="settings.nav">
                                    <div style="position:absolute;inset:0;pointer-events:none;z-index:900">
                                        <template x-if="settings.nav.arrows.on">
                                            <div>
                                                <div class="fse-navp-arrow" :class="navArrowClass()" :style="navArrowStyle('left')"  x-text="navArrowGlyph(0)"></div>
                                                <div class="fse-navp-arrow" :class="navArrowClass()" :style="navArrowStyle('right')" x-text="navArrowGlyph(1)"></div>
                                            </div>
                                        </template>
                                        <template x-if="settings.nav.bullets.on">
                                            <div class="fse-navp-dots" :style="navBulletsStyle()">
                                                <template x-for="(sd,bi) in slides" :key="'nb'+bi">
                                                    <div class="fse-navp-dot" :class="navBulletClass() + (bi===selectedIndex?' active':'')"
                                                         :style="settings.nav.bullets.style==='thumbnails' ? bgStyle(sd.bg) : ''"
                                                         x-text="settings.nav.bullets.style==='numbers'?(bi+1):''"></div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="settings.nav.progress.on">
                                            <div class="fse-navp-progress" :style="navProgressStyle()">
                                                <i :style="`width:${(selectedIndex+1)/Math.max(1,slides.length)*100}%;background:${settings.nav.progress.color||'#fff'}`"></i>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="!current">
                        <div style="text-align:center;color:var(--mut);padding-top:80px">
                            <span class="material-symbols-outlined" style="font-size:44px">add_photo_alternate</span>
                            <p style="margin-top:8px">No slides yet — add one below.</p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right panel --}}
            <div style="width:320px; flex:0 0 320px; background:var(--panel); border-left:1px solid var(--line); display:flex; flex-direction:column; min-height:0; overflow:hidden;">
                {{-- icon tabs --}}
                <div style="display:flex; border-bottom:1px solid var(--line)">
                    <div class="fse-itab" :class="tab==='layers'?'on':''" @click="tab='layers'" title="Layers"><span class="material-symbols-outlined">layers</span></div>
                    <div class="fse-itab" x-show="!editingGlobal" :class="tab==='slide'?'on':''"  @click="tab='slide'"  title="Slide"><span class="material-symbols-outlined">wallpaper</span></div>
                    <div class="fse-itab" :class="tab==='nav'?'on':''"    @click="tab='nav'"    title="Navigation"><span class="material-symbols-outlined">swipe</span></div>
                    <div class="fse-itab" :class="tab==='slider'?'on':''" @click="tab='slider'" title="Slider"><span class="material-symbols-outlined">settings</span></div>
                </div>

                <div style="flex:1 1 auto; min-height:0; overflow-y:auto; overflow-x:hidden; padding:14px;">
                    {{-- name --}}
                    <div class="fse-field">
                        <label class="fse-lbl">Slider name</label>
                        <input type="text" x-model="name" class="fse-input">
                    </div>

                    {{-- LAYERS --}}
                    <div x-show="tab==='layers'">
                        <div x-show="editingGlobal" style="display:flex;align-items:center;gap:8px;background:#1e2a3a;border:1px solid #2f4763;border-radius:6px;padding:8px 10px;margin-bottom:12px;font-size:12px;color:#bcd3ee">
                            <span class="material-symbols-outlined" style="font-size:18px">layers</span>
                            <span>Editing <b>global layers</b> — these appear on <b>every</b> slide.</span>
                        </div>
                        <template x-if="current">
                            <div>
                                <div x-show="current.layers.length" style="border:1px solid var(--line);border-radius:6px;max-height:190px;overflow-y:auto;margin-bottom:8px">
                                    <template x-for="item in layerDisplay" :key="item.kind==='group'?('g'+item.group.id):('l'+item.layer.id)">
                                        <div>
                                            {{-- GROUP: header + indented members --}}
                                            <template x-if="item.kind==='group'">
                                                <div>
                                                    <div class="fse-lyr-row" :class="{on:selectedGroup===item.group.id}" @click="selectGroup(item.group.id)" style="background:#20242e">
                                                        <span class="material-symbols-outlined" style="font-size:15px;color:var(--accent)">folder</span>
                                                        <input @click.stop x-model="item.group.name" spellcheck="false" style="flex:1;min-width:0;background:transparent;border:0;color:var(--ink);font-size:12.5px;font-weight:600;outline:none;padding:0">
                                                        <span style="font-size:10px;color:var(--mut)" x-text="item.members.length"></span>
                                                        <button @click.stop="ungroup(item.group.id)" style="color:var(--mut);display:inline-flex" title="Ungroup"><span class="material-symbols-outlined" style="font-size:14px">folder_off</span></button>
                                                        <button @click.stop="deleteGroup(item.group.id)" style="color:#e06666" title="Delete group">×</button>
                                                    </div>
                                                    <template x-for="m in item.members" :key="m.layer.id">
                                                        <div class="fse-lyr-row" :class="{on:m.li===selectedLayer && !selectedGroup, sel:isSel(m.li), over:layerDragOver===m.li}" style="padding-left:26px" @click="selectLayerRow(m.li,$event)"
                                                             draggable="true" @dragstart="layerDrag=m.li" @dragover.prevent @dragenter="layerDragOver=m.li" @dragend="layerDrag=null;layerDragOver=null" @drop.prevent="dropLayer(m.li)">
                                                            <span class="material-symbols-outlined" style="font-size:15px" x-text="layerIcon(m.layer.type)"></span>
                                                            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="layerLabel(m.layer)"></span>
                                                            <button @click.stop="duplicateLayer(m.li)" style="color:var(--mut);display:inline-flex;align-items:center" title="Duplicate layer"><span class="material-symbols-outlined" style="font-size:12px !important;line-height:12px">content_copy</span></button>
                                                            <button @click.stop="deleteLayer(m.li)" style="color:#e06666" title="Delete layer">×</button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                            {{-- UNGROUPED layer --}}
                                            <template x-if="item.kind==='layer'">
                                                <div class="fse-lyr-row" :class="{on:item.li===selectedLayer && !selectedGroup, sel:isSel(item.li), over:layerDragOver===item.li}" @click="selectLayerRow(item.li,$event)"
                                                     draggable="true" @dragstart="layerDrag=item.li" @dragover.prevent @dragenter="layerDragOver=item.li" @dragend="layerDrag=null;layerDragOver=null" @drop.prevent="dropLayer(item.li)">
                                                    <span class="material-symbols-outlined" style="font-size:14px;color:var(--mut);cursor:grab" title="Drag to reorder">drag_indicator</span>
                                                    <span class="material-symbols-outlined" style="font-size:15px" x-text="layerIcon(item.layer.type)"></span>
                                                    <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="layerLabel(item.layer)"></span>
                                                    <button @click.stop="moveLayerZ(item.li,-1)" style="color:var(--mut)" title="Bring forward">▲</button>
                                                    <button @click.stop="moveLayerZ(item.li,1)" style="color:var(--mut)" title="Send back">▼</button>
                                                    <button @click.stop="duplicateLayer(item.li)" style="color:var(--mut);display:inline-flex;align-items:center" title="Duplicate layer"><span class="material-symbols-outlined" style="font-size:12px !important;line-height:12px">content_copy</span></button>
                                                    <button @click.stop="deleteLayer(item.li)" style="color:#e06666" title="Delete layer">×</button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                                {{-- Multi-select → Group. (Ctrl/⌘-click or Shift-click layers to multi-select.) --}}
                                <div x-show="multiSel.length>=2" style="display:flex;align-items:center;gap:8px;background:#1e2a3a;border:1px solid #2f4763;border-radius:6px;padding:6px 10px;margin-bottom:10px;font-size:12px;color:#bcd3ee">
                                    <span x-text="multiSel.length+' layers selected'"></span>
                                    <button @click="groupSelected()" class="fse-btn" style="margin-left:auto;background:var(--accent);color:#fff;height:26px;padding:0 12px;font-weight:600"><span class="material-symbols-outlined" style="font-size:15px">create_new_folder</span> Group</button>
                                </div>
                                <p x-show="current.layers.length && multiSel.length<2" style="color:var(--mut);font-size:11px;margin:-2px 0 10px">Tip: Ctrl/⌘-click layers to multi-select, then Group.</p>
                                <p x-show="!current.layers.length" style="color:var(--mut);margin-bottom:12px">No layers. Use “Add Layer” in the toolbar.</p>

                                {{-- GROUP settings (shown when a group is selected) --}}
                                <template x-if="selGroup">
                                    <div style="border-top:1px solid var(--line);padding-top:12px">
                                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;color:#fff;font-weight:600;font-size:13px"><span class="material-symbols-outlined" style="color:var(--accent);font-size:18px">folder</span> <span x-text="selGroup.name"></span> <span style="color:var(--mut);font-weight:400;font-size:11px" x-text="'('+membersOf(selGroup.id).length+' layers)'"></span></div>
                                        <div class="fse-field"><label class="fse-lbl">Group name</label><input type="text" x-model="selGroup.name" class="fse-input"></div>
                                        <div class="fse-field">
                                            <label class="fse-lbl">Visible on device</label>
                                            <div style="display:flex;gap:6px">
                                                <template x-for="dv in ['desktop','tablet','mobile']" :key="'gv'+dv">
                                                    <button type="button" @click="grpToggleVis(selGroup,dv)" :class="grpVisOn(selGroup,dv)?'fse-seg on':'fse-seg'" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;padding:6px 0;position:relative">
                                                        <span class="material-symbols-outlined" style="font-size:17px" x-text="dv==='desktop'?'computer':(dv==='tablet'?'tablet':'smartphone')"></span>
                                                        <span style="font-size:9px;text-transform:capitalize" x-text="dv"></span>
                                                        <span x-show="!grpVisOn(selGroup,dv)" class="material-symbols-outlined" style="font-size:12px;position:absolute;top:3px;right:5px;color:#e06666">visibility_off</span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="fse-field" style="border-top:1px solid var(--line);padding-top:10px">
                                            <label class="fse-lbl">Group animation
                                                <button type="button" @click="previewAnims()" title="Replay all" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button>
                                            </label>
                                            <select x-model="selGroup.anim.in.preset" @change="$nextTick(()=>previewAnims())" class="fse-input">
                                                <option value="none">None</option><option value="fade">Fade</option>
                                                <option value="from-bottom">From bottom</option><option value="from-top">From top</option>
                                                <option value="from-left">From left</option><option value="from-right">From right</option>
                                                <option value="zoom-in">Zoom in</option><option value="zoom-out">Zoom out</option>
                                                <option value="blur-in">Blur in</option><option value="rotate-in">Rotate in</option>
                                                <option value="flip-x">Flip X</option><option value="skew-in">Skew in</option>
                                            </select>
                                            <p style="font-size:10px;color:var(--mut);margin-top:4px">Applies to members without their own In-animation.</p>
                                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;margin-top:8px">
                                                <div><label class="fse-lbl" style="font-size:10px">Delay</label><input type="number" step="50" x-model.number="selGroup.anim.in.delay" class="fse-input" style="padding:0 5px"></div>
                                                <div><label class="fse-lbl" style="font-size:10px">Dur</label><input type="number" step="50" x-model.number="selGroup.anim.in.duration" class="fse-input" style="padding:0 5px"></div>
                                                <div><label class="fse-lbl" style="font-size:10px">Stagger</label><input type="number" step="10" x-model.number="selGroup.anim.stagger" class="fse-input" style="padding:0 5px"></div>
                                            </div>
                                        </div>
                                        <div style="display:flex;gap:6px;border-top:1px solid var(--line);padding-top:12px">
                                            <button @click="duplicateGroup(selGroup.id)" class="fse-btn" style="flex:1;border:1px solid var(--line);justify-content:center"><span class="material-symbols-outlined" style="font-size:15px">content_copy</span> Duplicate</button>
                                            <button @click="ungroup(selGroup.id)" class="fse-btn" style="flex:1;border:1px solid var(--line);justify-content:center"><span class="material-symbols-outlined" style="font-size:15px">folder_off</span> Ungroup</button>
                                            <button @click="deleteGroup(selGroup.id)" class="fse-btn" style="border:1px solid #5b2b2b;color:#e06666;justify-content:center" title="Delete group + layers"><span class="material-symbols-outlined" style="font-size:15px">delete</span></button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="layer && !selGroup">
                                    <div style="border-top:1px solid var(--line);padding-top:12px">
                                        {{-- content / source per type --}}
                                        <div class="fse-field" x-show="layer.type==='text' || layer.type==='button'">
                                            <label class="fse-lbl" x-text="layer.type==='button'?'Button text':'Text'"></label>
                                            <textarea x-model="layer.content" rows="2" class="fse-input"></textarea>
                                        </div>
                                        <div class="fse-field" x-show="layer.type==='text'" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                            <div>
                                                <label class="fse-lbl">Tag (SEO)</label>
                                                <select x-model="layer.tag" class="fse-input">
                                                    <option value="div">Div (default)</option>
                                                    <option value="h1">H1</option>
                                                    <option value="h2">H2</option>
                                                    <option value="h3">H3</option>
                                                    <option value="h4">H4</option>
                                                    <option value="p">Paragraph</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="fse-lbl">Reveal
                                                    <button type="button" @click="previewReveal(layer)" title="Replay" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button>
                                                </label>
                                                <select x-model="layer.reveal" @change="$nextTick(()=>previewReveal(layer))" class="fse-input">
                                                    <option value="none">None</option>
                                                    <option value="typewriter">Typewriter</option>
                                                    <option value="chars">Split — chars</option>
                                                    <option value="words">Split — words</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="fse-field" x-show="!['video','audio'].includes(layer.type)">
                                            <label class="fse-lbl" x-text="layer.type==='button'?'Link':'Link (makes the layer clickable)'"></label>
                                            <input type="text" x-model="layer.link" class="fse-input" placeholder="https://…">
                                            <label x-show="layer.link" style="display:flex;align-items:center;gap:7px;font-size:12px;margin-top:7px"><input type="checkbox" class="fse-cb" x-model="layer.linkNewTab"> Open in new tab</label>
                                        </div>

                                        <div x-show="layer.type==='image'">
                                            <div class="fse-field">
                                                <label class="fse-lbl">Image</label>
                                                <button @click="pickLayerImage()" class="fse-btn" style="border:1px solid var(--line)">Choose</button>
                                                <button x-show="layer.url" @click="layer.url=''" style="color:#e06666;margin-left:8px;font-size:12px">Remove</button>
                                                <img x-show="layer.url" :src="layer.url" style="margin-top:8px;width:100%;height:64px;object-fit:cover;border-radius:5px" alt="">
                                            </div>
                                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                <div><label class="fse-lbl">Fit</label>
                                                    <select x-model="layer.style.fit" class="fse-input"><option value="cover">Cover</option><option value="contain">Contain</option><option value="fill">Fill</option><option value="none">None</option></select>
                                                </div>
                                                <div><label class="fse-lbl">Radius</label><input type="number" x-model.number="layer.style.radius" class="fse-input"></div>
                                            </div>
                                        </div>

                                        <div class="fse-field" x-show="layer.type==='video'">
                                            <label class="fse-lbl">Video URL (YouTube / Vimeo / MP4)</label>
                                            <input type="text" x-model="layer.url" class="fse-input" placeholder="https://youtu.be/…">
                                            <button @click="pickLayerFile()" class="fse-btn" style="border:1px solid var(--line);margin-top:6px">Choose MP4 from media</button>
                                        </div>
                                        <template x-if="layer.type==='video' && layer.video">
                                        <div class="fse-field">
                                            <label class="fse-lbl">Playback</label>
                                            <div style="display:flex;flex-direction:column;gap:7px;font-size:12.5px">
                                                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" x-model="layer.video.autoplay"> Autoplay (muted)</label>
                                                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" x-model="layer.video.loop"> Loop</label>
                                                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" x-model="layer.video.mute"> Muted</label>
                                                <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" :checked="layer.video.controls!==false" @change="layer.video.controls=$event.target.checked"> Show controls</label>
                                            </div>
                                            <div style="margin-top:8px">
                                                <label class="fse-lbl">Poster (fallback image)</label>
                                                <button @click="pickVideoPoster()" class="fse-btn" style="border:1px solid var(--line)">Choose</button>
                                                <button x-show="layer.video.poster" @click="layer.video.poster=''" style="color:#e06666;margin-left:8px;font-size:12px">Remove</button>
                                                <img x-show="layer.video.poster" :src="layer.video.poster" style="margin-top:8px;width:100%;height:56px;object-fit:cover;border-radius:5px" alt="">
                                            </div>
                                        </div>
                                        </template>

                                        <div class="fse-field" x-show="layer.type==='audio'">
                                            <label class="fse-lbl">Audio URL (MP3)</label>
                                            <input type="text" x-model="layer.url" class="fse-input" placeholder="https://… .mp3">
                                            <button @click="pickLayerFile()" class="fse-btn" style="border:1px solid var(--line);margin-top:6px">Choose from media</button>
                                        </div>

                                        <div class="fse-field" x-show="layer.type==='icon'">
                                            <label class="fse-lbl">Icon</label>
                                            <div style="display:flex;align-items:center;gap:10px">
                                                <div style="width:46px;height:46px;flex:0 0 46px;display:flex;align-items:center;justify-content:center;background:#1c1c24;border:1px solid var(--line);border-radius:6px;color:var(--ink)">
                                                    <span x-show="layer.iconName" class="material-symbols-outlined" style="font-size:28px" x-text="layer.iconName"></span>
                                                    <span x-show="!layer.iconName" class="material-symbols-outlined" style="font-size:20px;color:var(--mut)">image</span>
                                                </div>
                                                <button @click="openIconPicker('change')" class="fse-btn" style="border:1px solid var(--line)">Choose icon</button>
                                            </div>
                                            <div style="margin-top:8px">@include('falcon-slider::partials.color', ['model' => 'layer.style.color', 'label' => 'Icon color'])</div>
                                            <details style="margin-top:8px"><summary style="font-size:11px;color:var(--mut);cursor:pointer">Advanced — paste custom SVG</summary>
                                                <textarea x-model="layer.content" @input="if(layer.content) layer.iconName=''" rows="3" class="fse-input" style="font-family:monospace;font-size:11px;margin-top:6px" placeholder="<svg …>…</svg>"></textarea>
                                            </details>
                                        </div>

                                        <template x-if="layer.type==='countdown' && layer.countdown">
                                        <div>
                                            <div class="fse-field">
                                                <label class="fse-lbl">Counts down to</label>
                                                <input type="datetime-local" x-model="layer.countdown.to" class="fse-input" @click="$event.target.showPicker && $event.target.showPicker()">
                                            </div>
                                            <div class="fse-field">
                                                <label class="fse-lbl">Text when expired</label>
                                                <input type="text" x-model="layer.countdown.expiredText" class="fse-input" placeholder="Offer ended">
                                            </div>
                                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                <div><label class="fse-lbl">Expired size</label><input type="number" x-model.number="layer.countdown.expiredSize" class="fse-input" :placeholder="layer.style.size||40" style="padding:0 6px"></div>
                                                <div>
                                                    <label class="fse-lbl">Expired weight</label>
                                                    <select x-model.number="layer.countdown.expiredWeight" class="fse-input">
                                                        <option value="">Default</option>
                                                        <option value="400">Regular</option>
                                                        <option value="600">Semi-bold</option>
                                                        <option value="700">Bold</option>
                                                        <option value="800">Extra-bold</option>
                                                        <option value="900">Black</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @include('falcon-slider::partials.color', ['model' => 'layer.countdown.expiredColor', 'label' => 'Expired text color'])
                                        </div>
                                        </template>

                                        <div class="fse-field" x-show="layer.type==='html'">
                                            <label class="fse-lbl">HTML / embed code</label>
                                            <textarea x-model="layer.content" rows="5" class="fse-input" style="font-family:monospace;font-size:11px" placeholder="<div>…</div> or an embed snippet"></textarea>
                                        </div>

                                        {{-- Effects — opacity / rotation / blur / shadow / custom class (all layer types) --}}
                                        <div class="fse-field" style="border-top:1px solid var(--line);padding-top:10px">
                                            <label class="fse-lbl">Effects</label>
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Opacity: <span x-text="(layer.style.opacity==null||layer.style.opacity==='')?'100':layer.style.opacity"></span>%</label>
                                                    <input type="range" min="0" max="100" step="5" :value="(layer.style.opacity==null||layer.style.opacity==='')?100:layer.style.opacity" @input="layer.style.opacity=+$event.target.value" style="width:100%">
                                                </div>
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Rotation: <span x-text="(+layer.style.rotate||0)"></span>°</label>
                                                    <input type="range" min="-180" max="180" step="1" :value="+layer.style.rotate||0" @input="layer.style.rotate=+$event.target.value" style="width:100%">
                                                </div>
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Blur: <span x-text="(+layer.style.blur||0)"></span>px</label>
                                                    <input type="range" min="0" max="20" step="1" :value="+layer.style.blur||0" @input="layer.style.blur=+$event.target.value" style="width:100%">
                                                </div>
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Shadow</label>
                                                    <select x-model="layer.style.shadow" class="fse-input">
                                                        <option value="none">None</option>
                                                        <option value="sm">Small</option>
                                                        <option value="md">Medium</option>
                                                        <option value="lg">Large</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div style="margin-top:8px;display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Parallax depth: <span x-text="(+layer.parallax||0)"></span></label>
                                                    <input type="range" min="0" max="6" step="1" :value="+layer.parallax||0" @input="layer.parallax=+$event.target.value" style="width:100%">
                                                </div>
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Custom CSS class</label>
                                                    <input type="text" x-model="layer.style.cssClass" class="fse-input" placeholder="my-class">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Hover — scale / opacity / shadow / colour / background on mouse-over (all layer types) --}}
                                        <template x-if="layer.hover">
                                        <div class="fse-field" style="border-top:1px solid var(--line);padding-top:10px">
                                            <label class="fse-lbl">Hover</label>
                                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Scale: <span x-text="(+layer.hover.scale||1).toFixed(2)"></span>×</label>
                                                    <input type="range" min="1" max="1.4" step="0.02" :value="+layer.hover.scale||1" @input="layer.hover.scale=+$event.target.value" style="width:100%">
                                                </div>
                                                <div>
                                                    <label class="fse-lbl" style="font-size:10px">Opacity: <span x-text="(layer.hover.opacity==null||layer.hover.opacity==='')?'—':layer.hover.opacity+'%'"></span></label>
                                                    <input type="range" min="0" max="100" step="5" :value="(layer.hover.opacity==null||layer.hover.opacity==='')?100:layer.hover.opacity" @input="layer.hover.opacity=+$event.target.value" style="width:100%">
                                                </div>
                                                <div style="grid-column:1 / -1">
                                                    <label class="fse-lbl" style="font-size:10px">Shadow</label>
                                                    <select x-model="layer.hover.shadow" class="fse-input">
                                                        <option value="none">None</option>
                                                        <option value="sm">Small</option>
                                                        <option value="md">Medium</option>
                                                        <option value="lg">Large</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div x-show="['text','icon','button'].includes(layer.type)" style="margin-top:8px">
                                                @include('falcon-slider::partials.color', ['model' => 'layer.hover.color', 'label' => 'Hover text color'])
                                            </div>
                                            <div x-show="['shape','button'].includes(layer.type)" style="margin-top:8px">
                                                @include('falcon-slider::partials.color', ['model' => 'layer.hover.bg', 'label' => 'Hover background'])
                                            </div>
                                        </div>
                                        </template>

                                        {{-- style per type --}}
                                        <template x-if="layer.type==='text' || layer.type==='button'">
                                            <div>
                                                {{-- Typography --}}
                                                {{-- Searchable font picker — all Google Fonts, grouped by category --}}
                                                <div class="fse-field">
                                                    <label class="fse-lbl">Font family</label>
                                                    <div x-data="{open:false, q:''}" @click.outside="open=false" style="position:relative">
                                                        <button type="button" @click="open=!open; if(open)$nextTick(()=>$refs.fq&&$refs.fq.focus())" class="fse-input" style="display:flex;align-items:center;justify-content:space-between;text-align:left;cursor:pointer">
                                                            <span x-text="layer.style.family||'Default (theme)'" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>
                                                            <span class="material-symbols-outlined" style="font-size:18px;color:var(--mut)">expand_more</span>
                                                        </button>
                                                        <div x-show="open" x-cloak class="fse-fontpop">
                                                            <input type="text" x-ref="fq" x-model="q" @click.stop placeholder="Search all fonts…" class="fse-input" style="margin-bottom:6px">
                                                            <div class="fse-fontlist">
                                                                <div class="fse-fontopt" :class="{on:!layer.style.family}" @click="setFontFamily(layer,'');open=false"><span>Default (theme)</span></div>
                                                                <template x-for="f in filteredFontCatalog(q)" :key="f[0]">
                                                                    <div class="fse-fontopt" :class="{on:layer.style.family===f[0]}" @click="setFontFamily(layer,f[0]);open=false">
                                                                        <span x-text="f[0]" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>
                                                                        <span class="fse-fontcat" x-text="fontCats[f[1]]"></span>
                                                                    </div>
                                                                </template>
                                                                <div x-show="filteredFontCatalog(q).length===0" style="padding:12px;color:var(--mut);font-size:12px;text-align:center">No fonts match “<span x-text="q"></span>”.</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                    <div><label class="fse-lbl" x-text="device==='desktop'?'Size':'Size ('+device+')'"></label><input type="number" :value="sizeVal(layer)" @input="setFontSize(layer,$event.target.value)" class="fse-input"></div>
                                                    <div><label class="fse-lbl">Weight</label>
                                                        <select x-model.number="layer.style.weight" class="fse-input">
                                                            <template x-for="w in weightsFor(layer)" :key="w"><option :value="w" x-text="weightLabel(w)"></option></template>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                    <div><label class="fse-lbl">Line height</label><input type="number" step="0.05" x-model.number="layer.style.lineHeight" class="fse-input" placeholder="1.15"></div>
                                                    <div><label class="fse-lbl">Letter sp. (px)</label><input type="number" step="0.5" x-model.number="layer.style.letterSpacing" class="fse-input" placeholder="0"></div>
                                                </div>
                                                <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                    <div><label class="fse-lbl">Transform</label>
                                                        <select x-model="layer.style.transform" class="fse-input"><option value="none">None</option><option value="uppercase">UPPER</option><option value="lowercase">lower</option><option value="capitalize">Capitalize</option></select>
                                                    </div>
                                                    <div><label class="fse-lbl">Style</label>
                                                        <label style="display:flex;align-items:center;gap:6px;height:32px;font-size:12.5px;cursor:pointer"><input type="checkbox" class="fse-cb" x-model="layer.style.italic"> Italic</label>
                                                    </div>
                                                </div>
                                                @include('falcon-slider::partials.color', ['model' => 'layer.style.color', 'label' => 'Color'])
                                                <div x-show="layer.type==='button'">
                                                    @include('falcon-slider::partials.color', ['model' => 'layer.style.bg', 'label' => 'Button background'])
                                                    <p class="fse-lbl" style="margin-top:4px;color:var(--accent)">Hover</p>
                                                    @include('falcon-slider::partials.color', ['model' => 'layer.style.hoverBg', 'label' => 'Hover background'])
                                                    @include('falcon-slider::partials.color', ['model' => 'layer.style.hoverColor', 'label' => 'Hover text color'])
                                                </div>
                                                {{-- Text background (+ padding so the text isn't flush to the edge) --}}
                                                <div x-show="layer.type==='text'">
                                                    @include('falcon-slider::partials.color', ['model' => 'layer.style.bg', 'label' => 'Text background'])
                                                    <div class="fse-field" x-show="layer.style.bg"><label class="fse-lbl">Padding (px)</label><input type="number" min="0" x-model.number="layer.style.padding" class="fse-input" placeholder="0"></div>
                                                    {{-- Text stroke (outline). Set fill Color to transparent for outline-only text. --}}
                                                    <div class="fse-field" style="border-top:1px solid var(--line);padding-top:10px;margin-top:8px">
                                                        <label class="fse-lbl">Text stroke (outline)</label>
                                                        <label class="fse-lbl" style="font-size:10px">Width (px)</label>
                                                        <input type="number" min="0" step="0.5" x-model.number="layer.style.strokeWidth" class="fse-input" placeholder="0">
                                                    </div>
                                                    <div x-show="(+layer.style.strokeWidth||0)>0">
                                                        @include('falcon-slider::partials.color', ['model' => 'layer.style.strokeColor', 'label' => 'Stroke color'])
                                                    </div>
                                                </div>
                                                {{-- Border + corner radius (text & button) --}}
                                                <div class="fse-field" style="border-top:1px solid var(--line);padding-top:10px;margin-top:8px">
                                                    <label class="fse-lbl">Border</label>
                                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                                        <div><label class="fse-lbl" style="font-size:10px">Width (px)</label><input type="number" min="0" x-model.number="layer.style.borderWidth" class="fse-input" placeholder="0"></div>
                                                        <div><label class="fse-lbl" style="font-size:10px">Style</label>
                                                            <select x-model="layer.style.borderStyle" class="fse-input">
                                                                <option value="solid">Solid</option><option value="dashed">Dashed</option><option value="dotted">Dotted</option><option value="double">Double</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="margin-top:6px" x-show="(+layer.style.borderWidth||0)>0">
                                                        @include('falcon-slider::partials.color', ['model' => 'layer.style.borderColor', 'label' => 'Border color'])
                                                    </div>
                                                </div>
                                                <div class="fse-field"><label class="fse-lbl">Corner radius (px)</label><input type="number" min="0" x-model.number="layer.style.radius" class="fse-input" placeholder="0"></div>
                                                <div class="fse-field" x-show="layer.type==='text'"><label class="fse-lbl">Align</label>
                                                    <select x-model="layer.style.align" class="fse-input"><option value="left">Left</option><option value="center">Center</option><option value="right">Right</option></select>
                                                </div>
                                            </div>
                                        </template>

                                        <div x-show="layer.type==='shape'">
                                            <div class="fse-field">
                                                <label class="fse-lbl">Shape</label>
                                                <select x-model="layer.style.shape" class="fse-input">
                                                    <option value="square">Square / Rectangle</option>
                                                    <option value="circle">Circle / Ellipse</option>
                                                    <option value="triangle">Triangle</option>
                                                    <option value="triangle-down">Triangle (down)</option>
                                                    <option value="diamond">Diamond</option>
                                                    <option value="pentagon">Pentagon</option>
                                                    <option value="hexagon">Hexagon</option>
                                                    <option value="star">Star</option>
                                                    <option value="parallelogram">Parallelogram</option>
                                                    <option value="chevron">Chevron</option>
                                                </select>
                                            </div>
                                            @include('falcon-slider::partials.color', ['model' => 'layer.style.bg', 'label' => 'Fill'])
                                            <div class="fse-field" x-show="(layer.style.shape||'square')==='square'"><label class="fse-lbl">Corner radius</label><input type="number" x-model.number="layer.style.radius" class="fse-input"></div>
                                            <div class="fse-field">
                                                <label class="fse-lbl flex items-center gap-2" style="cursor:pointer">
                                                    <input type="checkbox" @change="toggleShapeFull(layer, $event.target.checked)"
                                                           :checked="!!(layer.style && layer.style.fullWidth)">
                                                    Full width (100%)
                                                </label>
                                            </div>
                                        </div>

                                        <div x-show="layer.type==='icon'">
                                            @include('falcon-slider::partials.color', ['model' => 'layer.style.color', 'label' => 'Icon color'])
                                        </div>
                                        {{-- Align to canvas --}}
                                        <div class="fse-field" style="border-top:1px solid var(--line);padding-top:12px">
                                            <label class="fse-lbl">Align to canvas</label>
                                            <div style="display:flex;gap:5px">
                                                <button type="button" class="fse-align" :class="isAligned(layer,'left')&&'on'"    @click="alignLayer(layer,'left')"    title="Left"><span class="material-symbols-outlined">align_horizontal_left</span></button>
                                                <button type="button" class="fse-align" :class="isAligned(layer,'hcenter')&&'on'" @click="alignLayer(layer,'hcenter')" title="Center"><span class="material-symbols-outlined">align_horizontal_center</span></button>
                                                <button type="button" class="fse-align" :class="isAligned(layer,'right')&&'on'"   @click="alignLayer(layer,'right')"   title="Right"><span class="material-symbols-outlined">align_horizontal_right</span></button>
                                                <span style="width:1px;background:var(--line);margin:2px 2px"></span>
                                                <button type="button" class="fse-align" :class="isAligned(layer,'top')&&'on'"     @click="alignLayer(layer,'top')"     title="Top"><span class="material-symbols-outlined">align_vertical_top</span></button>
                                                <button type="button" class="fse-align" :class="isAligned(layer,'vmiddle')&&'on'" @click="alignLayer(layer,'vmiddle')" title="Middle"><span class="material-symbols-outlined">align_vertical_center</span></button>
                                                <button type="button" class="fse-align" :class="isAligned(layer,'bottom')&&'on'"  @click="alignLayer(layer,'bottom')"  title="Bottom"><span class="material-symbols-outlined">align_vertical_bottom</span></button>
                                            </div>
                                        </div>
                                        <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:6px">
                                            <div><label class="fse-lbl">X</label><input type="number" :value="posOf(layer).x" @input="writePos(layer,'x',$event.target.value)" class="fse-input" style="padding:0 5px"></div>
                                            <div><label class="fse-lbl">Y</label><input type="number" :value="posOf(layer).y" @input="writePos(layer,'y',$event.target.value)" class="fse-input" style="padding:0 5px"></div>
                                            <div><label class="fse-lbl">W</label><input type="number" :value="posOf(layer).w" @input="writePos(layer,'w',$event.target.value)" class="fse-input" style="padding:0 5px"></div>
                                            <div><label class="fse-lbl">H</label><input type="number" :value="posOf(layer).h" @input="writePos(layer,'h',$event.target.value)" class="fse-input" style="padding:0 5px"></div>
                                        </div>
                                        <p x-show="device!=='desktop'" style="color:var(--accent);font-size:11px;margin:-4px 0 8px;display:flex;align-items:center;justify-content:space-between">
                                            <span>Editing <b x-text="device"></b> — changes apply to this device only.</span>
                                            <button type="button" @click="resetDevicePos(layer)" style="color:var(--accent);background:none;border:0;font-size:11px;cursor:pointer">Reset to desktop</button>
                                        </p>
                                        {{-- Per-device visibility — toggle where this layer shows (independent of the current view) --}}
                                        <div class="fse-field">
                                            <label class="fse-lbl">Visible on device</label>
                                            <div style="display:flex;gap:6px">
                                                <template x-for="dv in ['desktop','tablet','mobile']" :key="'v'+dv">
                                                    <button type="button" @click="toggleVis(layer,dv)" :class="visOn(layer,dv)?'fse-seg on':'fse-seg'" :title="visOn(layer,dv)?('Shown on '+dv):('Hidden on '+dv)" style="flex:1;display:flex;flex-direction:column;align-items:center;gap:2px;padding:6px 0;position:relative">
                                                        <span class="material-symbols-outlined" style="font-size:17px" x-text="dv==='desktop'?'computer':(dv==='tablet'?'tablet':'smartphone')"></span>
                                                        <span style="font-size:9px;text-transform:capitalize" x-text="dv"></span>
                                                        <span x-show="!visOn(layer,dv)" class="material-symbols-outlined" style="font-size:12px;position:absolute;top:3px;right:5px;color:#e06666">visibility_off</span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <div style="border-top:1px solid var(--line);padding-top:12px">
                                            <div class="fse-field"><label class="fse-lbl">Animation — in
                                                <button type="button" @click="previewLayerAnim(layer)" title="Replay" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button></label>
                                                <select x-model="animOf(layer).preset" @change="$nextTick(()=>previewLayerAnim(layer))" class="fse-input">
                                                    <option value="none">None</option><option value="fade">Fade</option>
                                                    <option value="from-bottom">From bottom</option><option value="from-top">From top</option>
                                                    <option value="from-left">From left</option><option value="from-right">From right</option>
                                                    <option value="from-bottom-left">From bottom-left</option><option value="from-top-right">From top-right</option>
                                                    <option value="zoom-in">Zoom in</option><option value="zoom-out">Zoom out</option>
                                                    <option value="zoom-blur">Zoom blur</option><option value="blur-in">Blur in</option>
                                                    <option value="rotate-in">Rotate in</option><option value="flip-x">Flip X</option><option value="flip-y">Flip Y</option>
                                                    <option value="skew-in">Skew in</option>
                                                </select>
                                            </div>
                                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px" x-show="animOf(layer).preset!=='none'">
                                                <div><label class="fse-lbl">Delay</label><input type="number" step="100" x-model.number="animOf(layer).delay" @change="previewLayerAnim(layer)" class="fse-input" style="padding:0 5px"></div>
                                                <div><label class="fse-lbl">Dur</label><input type="number" step="100" x-model.number="animOf(layer).duration" @change="previewLayerAnim(layer)" class="fse-input" style="padding:0 5px"></div>
                                                <div><label class="fse-lbl">Ease</label>
                                                    <select x-model="animOf(layer).easing" @change="previewLayerAnim(layer)" class="fse-input" style="padding:0 3px">
                                                        <option value="ease">ease</option><option value="ease-out">out</option><option value="ease-in">in</option><option value="ease-in-out">in-out</option><option value="cubic-bezier(.34,1.56,.64,1)">back</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Animation — out (plays when the slide leaves) --}}
                                            <div class="fse-field"><label class="fse-lbl">Animation — out
                                                <button type="button" @click="previewLayerAnimOut(layer)" title="Preview out" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button></label>
                                                <select x-model="animOutOf(layer).preset" @change="$nextTick(()=>previewLayerAnimOut(layer))" class="fse-input">
                                                    <option value="none">None</option><option value="fade">Fade</option>
                                                    <option value="from-bottom">To bottom</option><option value="from-top">To top</option>
                                                    <option value="from-left">To left</option><option value="from-right">To right</option>
                                                    <option value="zoom-in">Zoom out-in</option><option value="zoom-out">Zoom out</option>
                                                    <option value="zoom-blur">Zoom blur</option><option value="blur-in">Blur</option>
                                                    <option value="rotate-in">Rotate</option><option value="flip-x">Flip X</option><option value="flip-y">Flip Y</option>
                                                </select>
                                            </div>
                                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px" x-show="animOutOf(layer).preset!=='none'">
                                                <div><label class="fse-lbl">Delay</label><input type="number" step="100" x-model.number="animOutOf(layer).delay" class="fse-input" style="padding:0 5px"></div>
                                                <div><label class="fse-lbl">Dur</label><input type="number" step="100" x-model.number="animOutOf(layer).duration" class="fse-input" style="padding:0 5px"></div>
                                                <div><label class="fse-lbl">Ease</label>
                                                    <select x-model="animOutOf(layer).easing" class="fse-input" style="padding:0 3px">
                                                        <option value="ease-in">in</option><option value="ease">ease</option><option value="ease-out">out</option><option value="ease-in-out">in-out</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Loop / idle motion (runs continuously while shown) --}}
                                            <div class="fse-field"><label class="fse-lbl">Loop / idle</label>
                                                <select :value="loopOf(layer)" @change="setLoop(layer, $event.target.value)" class="fse-input">
                                                    <option value="none">None</option><option value="float">Float</option><option value="bob">Bob</option><option value="pulse">Pulse</option><option value="sway">Sway</option><option value="spin">Spin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="!current" style="color:var(--mut)">Add a slide first.</p>
                    </div>

                    {{-- SLIDE --}}
                    <div x-show="tab==='slide'" style="display:none">
                        <template x-if="current">
                            <div>
                                <div class="fse-field"><label class="fse-lbl">Slide name</label><input type="text" :value="slideLabel(current, selectedIndex)" @input="current.name = $event.target.value" class="fse-input" placeholder="Slide name"></div>
                                {{-- Whole-slide link — the entire slide (empty areas + non-interactive layers) becomes clickable. Buttons/links keep their own actions. --}}
                                <div class="fse-field">
                                    <label class="fse-lbl">Slide link (make the whole slide clickable)</label>
                                    <input type="url" x-model="current.link" class="fse-input" placeholder="https://example.com  (leave blank for none)">
                                    <label style="display:flex;align-items:center;gap:7px;margin-top:6px;color:var(--mut);font-size:12px" x-show="current.link">
                                        <input type="checkbox" x-model="current.linkNewTab"> Open in a new tab
                                    </label>
                                </div>
                                {{-- Slide transition (how THIS slide animates in) — previews live on the canvas --}}
                                <div class="fse-field">
                                    <label class="fse-lbl">Slide transition
                                        <button type="button" @click="previewTransition(current.transition||settings.transition)" title="Preview" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button>
                                    </label>
                                    <select x-model="current.transition" @change="previewTransition(current.transition||settings.transition)" class="fse-input">
                                        <option value="">— Global default —</option>
                                        <option value="fade">Fade</option>
                                        <option value="slide-left">Slide left</option>
                                        <option value="slide-right">Slide right</option>
                                        <option value="slide-up">Slide up</option>
                                        <option value="slide-down">Slide down</option>
                                        <option value="zoom">Zoom in</option>
                                        <option value="zoom-out">Zoom out</option>
                                        <option value="fade-scale">Fade scale</option>
                                        <option value="rotate">Rotate CW</option>
                                        <option value="rotate-ccw">Rotate CCW</option>
                                        <option value="roll">Roll</option>
                                        <option value="flip">Flip (Y)</option>
                                        <option value="flip-x">Flip (X)</option>
                                        <option value="skew">Skew</option>
                                        <option value="glide-up">Glide up</option>
                                        <option value="glide-down">Glide down</option>
                                        <option value="blur">Blur</option>
                                    </select>
                                    <p style="color:var(--mut);font-size:11px;margin-top:4px">Empty = use the slider's global transition.</p>
                                </div>
                                <div class="fse-field">
                                    <label class="fse-lbl">Transition speed override (ms)</label>
                                    <input type="number" step="100" min="100" x-model.number="current.transition_duration" @change="previewTransition(current.transition||settings.transition)" class="fse-input"
                                           :placeholder="'Uses global (' + (settings.transition_duration||700) + ' ms)'">
                                    <p style="color:var(--mut);font-size:11px;margin-top:4px">Empty = use the global transition speed.</p>
                                </div>
                                <hr style="border-color:var(--line);margin:12px 0">

                                {{-- Background: color + image + optional gradient, all available together --}}
                                @include('falcon-slider::partials.color', ['model' => 'current.bg.value', 'label' => 'Background color'])

                                <div class="fse-field">
                                    <label class="fse-lbl">Background image</label>
                                    <button @click="pickBackground()" class="fse-btn" style="border:1px solid var(--line)">Choose image</button>
                                    <button x-show="current.bg.url" @click="current.bg.url=''" style="color:#e06666;margin-left:8px;font-size:12px">Remove</button>
                                    <img x-show="current.bg.url" :src="current.bg.url" style="margin-top:8px;width:100%;height:70px;object-fit:cover;border-radius:5px" alt="">
                                    <p x-show="current.bg.url" style="font-size:11px;color:var(--mut);margin-top:4px">Image sits on top of the color.</p>
                                </div>
                                {{-- Background video — direct file, or a YouTube / Vimeo URL. Plays muted + looped;
                                     the bg image (if any) is used as its poster / fallback. --}}
                                <div class="fse-field">
                                    <label class="fse-lbl">Background video</label>
                                    <div style="display:flex;gap:6px;align-items:center">
                                        <button @click="pickBackgroundVideo()" class="fse-btn" style="border:1px solid var(--line)">Choose file</button>
                                        <button x-show="current.bg.video" @click="current.bg.video=''" style="color:#e06666;font-size:12px">Remove</button>
                                    </div>
                                    <input type="text" x-model="current.bg.video" class="fse-input" placeholder="…or paste a video / YouTube / Vimeo URL" style="margin-top:8px">
                                    <div x-show="current.bg.video" style="margin-top:8px">
                                        <label class="fse-lbl">Video fit</label>
                                        <select x-model="current.bg.videoFit" class="fse-input">
                                            <option value="cover">Cover (fill)</option>
                                            <option value="contain">Contain (letterbox)</option>
                                        </select>
                                        <p style="font-size:11px;color:var(--mut);margin-top:4px">Autoplays muted &amp; looped. Set a bg image above as the poster/fallback.</p>
                                    </div>
                                </div>
                                {{-- Overlay — a colour/gradient tint over the image + video (great for text contrast) --}}
                                <div class="fse-field" style="border-top:1px solid var(--line);padding-top:10px">
                                    <label style="display:flex;align-items:center;gap:8px;font-size:12px"><input type="checkbox" class="fse-cb" x-model="current.bg.overlay"> Color / gradient overlay</label>
                                    <div x-show="current.bg.overlay" style="margin-top:10px">
                                        <div class="fse-field">
                                            <label class="fse-lbl">Overlay type</label>
                                            <div style="display:flex;gap:14px;font-size:13px">
                                                <label style="display:flex;align-items:center;gap:6px"><input type="radio" class="fse-cb" value="gradient" x-model="current.bg.overlayType"> Gradient</label>
                                                <label style="display:flex;align-items:center;gap:6px"><input type="radio" class="fse-cb" value="color" x-model="current.bg.overlayType"> Solid</label>
                                            </div>
                                        </div>
                                        <template x-if="current.bg.overlayType==='color'">
                                            <div>@include('falcon-slider::partials.color', ['model' => 'current.bg.overlayColor', 'label' => 'Overlay color'])</div>
                                        </template>
                                        <template x-if="current.bg.overlayType!=='color'">
                                            <div>
                                                @include('falcon-slider::partials.color', ['model' => 'current.bg.overlayFrom', 'label' => 'Gradient from'])
                                                @include('falcon-slider::partials.color', ['model' => 'current.bg.overlayTo', 'label' => 'Gradient to'])
                                                <div class="fse-field">
                                                    <label class="fse-lbl">Angle: <span x-text="(current.bg.overlayAngle==null?180:current.bg.overlayAngle)+'°'"></span></label>
                                                    <input type="range" min="0" max="360" step="5" :value="current.bg.overlayAngle==null?180:current.bg.overlayAngle" @input="current.bg.overlayAngle=+$event.target.value" style="width:100%">
                                                </div>
                                            </div>
                                        </template>
                                        <div class="fse-field">
                                            <label class="fse-lbl">Overlay opacity: <span x-text="(current.bg.overlayOpacity==null?100:current.bg.overlayOpacity)+'%'"></span></label>
                                            <input type="range" min="0" max="100" step="5" :value="current.bg.overlayOpacity==null?100:current.bg.overlayOpacity" @input="current.bg.overlayOpacity=+$event.target.value" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                                {{-- Ken Burns — slow background zoom/pan while the slide is shown --}}
                                <div class="fse-field">
                                    <label class="fse-lbl">Ken Burns (bg motion)
                                        <button type="button" @click="previewKenburns()" title="Preview" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button>
                                    </label>
                                    <select x-model="current.bg.kenburns" @change="previewKenburns()" class="fse-input">
                                        <option value="none">None</option>
                                        <option value="zoom-in">Zoom in</option>
                                        <option value="zoom-out">Zoom out</option>
                                        <option value="pan-left">Pan left</option>
                                        <option value="pan-right">Pan right</option>
                                        <option value="pan-up">Pan up</option>
                                        <option value="zoom-pan">Zoom + pan</option>
                                    </select>
                                </div>
                                {{-- Image Settings (only when a bg image is set) --}}
                                <div x-show="current.bg.url" style="border-top:1px solid var(--line);padding-top:10px;margin-top:4px">
                                    <div class="fse-field">
                                        <label class="fse-lbl">BG Fit</label>
                                        <div style="display:flex;flex-direction:column;gap:6px;font-size:13px">
                                            <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="cover" x-model="current.bg.size"> Cover</label>
                                            <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="contain" x-model="current.bg.size"> Contain</label>
                                            <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="percentage" x-model="current.bg.size"> Percentage</label>
                                            <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="auto" x-model="current.bg.size"> Auto</label>
                                        </div>
                                        <div x-show="current.bg.size==='percentage'" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px">
                                            <div><label class="fse-lbl">Width %</label><input type="number" x-model.number="current.bg.sizeX" class="fse-input" placeholder="100"></div>
                                            <div><label class="fse-lbl">Height %</label><input type="number" x-model.number="current.bg.sizeY" class="fse-input" placeholder="100"></div>
                                        </div>
                                    </div>

                                    <div class="fse-field">
                                        <label class="fse-lbl">Repeat</label>
                                        <select x-model="current.bg.repeat" class="fse-input">
                                            <option value="no-repeat">no-repeat</option>
                                            <option value="repeat">repeat</option>
                                            <option value="repeat-x">repeat-x</option>
                                            <option value="repeat-y">repeat-y</option>
                                        </select>
                                    </div>

                                    <div class="fse-field">
                                        <label class="fse-lbl">Position</label>
                                        <div style="display:flex;gap:14px;align-items:flex-start">
                                            <div style="display:grid;grid-template-columns:repeat(3,22px);grid-template-rows:repeat(3,22px);gap:3px">
                                                <template x-for="pp in positions" :key="pp.v">
                                                    <button type="button" @click="current.bg.position=pp.v" :title="pp.v"
                                                        :style="`width:22px;height:22px;border-radius:3px;border:1px solid var(--line);cursor:pointer;${current.bg.position===pp.v?'background:var(--accent)':'background:#1c1c24'}`"></button>
                                                </template>
                                            </div>
                                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--mut)">
                                                <input type="checkbox" class="fse-cb" :checked="current.bg.position==='custom'"
                                                       @change="current.bg.position = $event.target.checked ? 'custom' : 'center center'"> X% Y%
                                            </label>
                                        </div>
                                        <div x-show="current.bg.position==='custom'" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:6px">
                                            <div><label class="fse-lbl">X %</label><input type="number" x-model.number="current.bg.posX" class="fse-input" placeholder="50"></div>
                                            <div><label class="fse-lbl">Y %</label><input type="number" x-model.number="current.bg.posY" class="fse-input" placeholder="50"></div>
                                        </div>
                                    </div>
                                </div>

                                <label style="display:flex;align-items:center;gap:8px;font-size:12px;margin-bottom:10px"><input type="checkbox" class="fse-cb" x-model="current.bg.gradient"> Use gradient</label>
                                <div x-show="current.bg.gradient">
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                        @include('falcon-slider::partials.color', ['model' => 'current.bg.from', 'label' => 'From'])
                                        @include('falcon-slider::partials.color', ['model' => 'current.bg.to', 'label' => 'To'])
                                    </div>
                                    <div class="fse-field"><label class="fse-lbl">Angle</label><input type="number" x-model.number="current.bg.angle" class="fse-input"></div>
                                </div>
                                <div class="fse-field" style="border-top:1px solid var(--line);padding-top:12px">
                                    <label class="fse-lbl">Duration override (ms)</label>
                                    <input type="number" step="500" min="0" x-model.number="current.duration" class="fse-input"
                                           :placeholder="'Uses Autoplay delay (' + (settings.autoplay_delay||6000) + ' ms)'">
                                    <p style="color:var(--mut);font-size:11px;margin-top:4px">Leave empty to use the slider's <b>Autoplay delay</b>. Set a value only to make <i>this</i> slide stay longer/shorter.</p>
                                </div>
                                <button @click="duplicateSlide(selectedIndex)" class="fse-btn" style="border:1px solid var(--line);width:100%;justify-content:center;margin-top:4px">Duplicate slide</button>
                            </div>
                        </template>
                    </div>

                    {{-- SLIDER --}}
                    <div x-show="tab==='slider'" style="display:none">
                        {{-- Layout (works live on the frontend) --}}
                        <div class="fse-field">
                            <label class="fse-lbl">Type</label>
                            <div style="display:flex;flex-direction:column;gap:7px;font-size:13px">
                                <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="slider" x-model="settings.layout_type"> Slider</label>
                                <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="scene" x-model="settings.layout_type"> Scene <span style="color:var(--mut);font-size:11px">— single, no cycling</span></label>
                                <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="carousel" x-model="settings.layout_type"> Carousel <span style="color:var(--mut);font-size:11px">— always rotating</span></label>
                            </div>
                        </div>
                        <div class="fse-field">
                            <label class="fse-lbl">Sizing</label>
                            <div style="display:flex;flex-direction:column;gap:7px;font-size:13px">
                                <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="auto" x-model="settings.sizing"> Auto <span style="color:var(--mut);font-size:11px">— fixed width</span></label>
                                <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="full-width" x-model="settings.sizing"> Full-Width</label>
                                <label style="display:flex;align-items:center;gap:8px"><input type="radio" class="fse-cb" value="full-screen" x-model="settings.sizing"> Full-Screen</label>
                            </div>
                        </div>
                        <hr style="border-color:var(--line);margin:12px 0">

                        {{-- Global slide transition (default for every slide) — previews live on the canvas --}}
                        <div class="fse-field">
                            <label class="fse-lbl">Slide transition (global)
                                <button type="button" @click="previewTransition(settings.transition)" title="Preview" style="float:right;background:none;border:0;color:var(--accent);cursor:pointer;padding:0"><span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">play_circle</span></button>
                            </label>
                            <select x-model="settings.transition" @change="previewTransition(settings.transition)" class="fse-input">
                                <option value="fade">Fade</option>
                                <option value="slide-left">Slide left</option>
                                <option value="slide-right">Slide right</option>
                                <option value="slide-up">Slide up</option>
                                <option value="slide-down">Slide down</option>
                                <option value="zoom">Zoom in</option>
                                <option value="zoom-out">Zoom out</option>
                                <option value="fade-scale">Fade scale</option>
                                <option value="rotate">Rotate CW</option>
                                <option value="rotate-ccw">Rotate CCW</option>
                                <option value="roll">Roll</option>
                                <option value="flip">Flip (Y)</option>
                                <option value="flip-x">Flip (X)</option>
                                <option value="skew">Skew</option>
                                <option value="glide-up">Glide up</option>
                                <option value="glide-down">Glide down</option>
                                <option value="blur">Blur</option>
                            </select>
                            <p style="color:var(--mut);font-size:11px;margin-top:4px">Applies to slides set to “Global default”. Individual slides can override in the Slide tab.</p>
                        </div>
                        <div class="fse-field">
                            <label class="fse-lbl">Transition speed (ms)</label>
                            <input type="number" step="100" min="100" x-model.number="settings.transition_duration" @change="previewTransition(settings.transition)" class="fse-input" placeholder="700">
                            <p style="color:var(--mut);font-size:11px;margin-top:4px">How fast each slide animates in. Default 700.</p>
                        </div>
                        <hr style="border-color:var(--line);margin:12px 0">

                        <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                            <div><label class="fse-lbl" x-text="device+' width'"></label><input type="number" :value="dw" @input="setDeviceWidth($event.target.value)" class="fse-input"></div>
                            <div><label class="fse-lbl" x-text="device+' height'"></label><input type="number" :value="dh" @input="setDeviceHeight($event.target.value)" class="fse-input"></div>
                        </div>
                        <p style="color:var(--mut);font-size:11px;margin:-6px 0 4px">Switch device (top bar) to set a custom size for Tablet / Mobile.</p>
                        <hr style="border-color:var(--line);margin:12px 0">
                        {{-- Spinner / preloader --}}
                        <div class="fse-field">
                            <label class="fse-lbl">Spinner</label>
                            <select x-model="settings.spinner.style" class="fse-input">
                                <option value="none">None</option>
                                <option value="1">Spinner 1 — Ring</option>
                                <option value="2">Spinner 2 — Dual Ring</option>
                                <option value="3">Spinner 3 — Dots</option>
                                <option value="4">Spinner 4 — Pulse</option>
                                <option value="5">Spinner 5 — Bars</option>
                                <option value="6">Spinner 6 — Ripple</option>
                            </select>
                        </div>
                        <template x-if="settings.spinner.style!=='none'">
                            <div>
                                @include('falcon-slider::partials.color', ['model' => 'settings.spinner.color', 'label' => 'Spinner color'])
                                <div class="fse-field">
                                    <label class="fse-lbl">Preview</label>
                                    <div style="height:84px;border:1px solid var(--line);border-radius:6px;position:relative;overflow:hidden;background:#0f172a">
                                        <div class="fs-spinner" :class="'sp-'+settings.spinner.style" :style="`--sp-color:${settings.spinner.color||'#ffffff'}`" x-html="spinnerMarkupEd(settings.spinner.style)"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <hr style="border-color:var(--line);margin:12px 0">
                        <label style="display:flex;align-items:center;gap:8px;margin-bottom:10px"><input type="checkbox" class="fse-cb" x-model="settings.autoplay"> Autoplay</label>
                        <div class="fse-field" x-show="settings.autoplay"><label class="fse-lbl">Autoplay delay (ms)</label><input type="number" step="500" x-model.number="settings.autoplay_delay" class="fse-input"></div>
                        <div style="display:flex;flex-direction:column;gap:8px">
                            <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" x-model="settings.loop"> Loop</label>
                            <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" x-model="settings.pause_on_hover"> Pause on hover</label>
                            <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" :checked="settings.parallax!==false" @change="settings.parallax=$event.target.checked"> Parallax on mouse move</label>
                            <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" class="fse-cb" x-model="settings.rtl"> RTL (right-to-left)</label>
                            <label style="display:flex;align-items:center;gap:8px" title="Off (default): animations always play. On: honour the visitor's OS 'reduce motion' setting for accessibility."><input type="checkbox" class="fse-cb" x-model="settings.respectReducedMotion"> Respect reduced-motion (accessibility)</label>
                        </div>
                        <p style="color:var(--mut);font-size:11px;margin-top:8px">Set per-layer <b>Parallax depth</b> in the Layers tab · Effects.</p>
                        <hr style="border-color:var(--line);margin:12px 0">
                        {{-- Developer: custom CSS + JS scoped to this slider --}}
                        <div class="fse-field">
                            <label class="fse-lbl">Custom CSS</label>
                            <textarea x-model="settings.customCss" rows="4" class="fse-input" style="font-family:monospace;font-size:11px" placeholder="/* use $slider as this slider's selector */"></textarea>
                        </div>
                        <div class="fse-field">
                            <label class="fse-lbl">Custom JS</label>
                            <textarea x-model="settings.customJs" rows="4" class="fse-input" style="font-family:monospace;font-size:11px" placeholder="// vars: root, wrap, api.next()/prev()/goTo(i)"></textarea>
                        </div>
                        <p style="color:var(--mut);font-size:11px;margin-top:-4px">Runs on the live page only. Advanced — use with care.</p>
                        <p style="color:var(--mut);font-size:11px;margin-top:8px">Arrows, bullets &amp; progress bar → <b>Navigation</b> tab.</p>
                    </div>

                    {{-- ===== Navigation ===== --}}
                    <div x-show="tab==='nav'" style="display:none">
                        {{-- Arrows --}}
                        <div class="fse-navhead">
                            <span>Arrows</span>
                            <label class="fse-switch"><input type="checkbox" x-model="settings.nav.arrows.on"><span></span></label>
                        </div>
                        <div x-show="settings.nav.arrows.on" style="padding:2px 0 12px">
                            <div class="fse-field">
                                <label class="fse-lbl">Style</label>
                                <select x-model="settings.nav.arrows.style" class="fse-input">
                                    <option value="chevron">Chevron ‹ ›</option>
                                    <option value="arrow">Arrow ← →</option>
                                    <option value="double">Double « »</option>
                                    <option value="circle">Circle</option>
                                    <option value="square">Square</option>
                                    <option value="pill">Pill</option>
                                    <option value="outline">Outline</option>
                                    <option value="glass">Glass (blur)</option>
                                    <option value="soft">Soft (white)</option>
                                    <option value="minimal">Minimal</option>
                                </select>
                            </div>
                            <div class="fse-field">
                                <label class="fse-lbl">Vertical position</label>
                                <div style="display:flex;gap:6px">
                                    <template x-for="vp in ['top','middle','bottom']" :key="vp">
                                        <button type="button" @click="settings.nav.arrows.pos=vp"
                                            :class="settings.nav.arrows.pos===vp?'fse-seg on':'fse-seg'" x-text="vp"></button>
                                    </template>
                                </div>
                            </div>
                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                <div><label class="fse-lbl">Offset X</label><input type="number" x-model.number="settings.nav.arrows.x" class="fse-input"></div>
                                <div><label class="fse-lbl">Offset Y</label><input type="number" x-model.number="settings.nav.arrows.y" class="fse-input"></div>
                            </div>
                        </div>
                        <hr style="border-color:var(--line);margin:4px 0">

                        {{-- Bullets --}}
                        <div class="fse-navhead">
                            <span>Bullets</span>
                            <label class="fse-switch"><input type="checkbox" x-model="settings.nav.bullets.on"><span></span></label>
                        </div>
                        <div x-show="settings.nav.bullets.on" style="padding:2px 0 12px">
                            <div class="fse-field">
                                <label class="fse-lbl">Style</label>
                                <select x-model="settings.nav.bullets.style" class="fse-input">
                                    <option value="dots">Dots</option>
                                    <option value="ring">Ring (hollow)</option>
                                    <option value="square">Squares</option>
                                    <option value="bars">Bars</option>
                                    <option value="pills">Pills (expanding)</option>
                                    <option value="numbers">Numbers</option>
                                    <option value="thumbnails">Thumbnails</option>
                                </select>
                            </div>
                            <div class="fse-field">
                                <label class="fse-lbl">Position</label>
                                <div style="display:grid;grid-template-columns:repeat(3,22px);gap:5px">
                                    <template x-for="pp in ['tl','tc','tr','ml','mc','mr','bl','bc','br']" :key="pp">
                                        <button type="button" @click="settings.nav.bullets.pos=pp"
                                            :style="`width:22px;height:22px;border:1px solid var(--line);border-radius:3px;cursor:pointer;background:${settings.nav.bullets.pos===pp?'var(--accent)':'#2a2f3a'}`"></button>
                                    </template>
                                </div>
                            </div>
                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                <div><label class="fse-lbl">Offset X</label><input type="number" x-model.number="settings.nav.bullets.x" class="fse-input"></div>
                                <div><label class="fse-lbl">Offset Y</label><input type="number" x-model.number="settings.nav.bullets.y" class="fse-input"></div>
                            </div>
                        </div>
                        <hr style="border-color:var(--line);margin:4px 0">

                        {{-- Progress bar --}}
                        <div class="fse-navhead">
                            <span>Progress bar</span>
                            <label class="fse-switch"><input type="checkbox" x-model="settings.nav.progress.on"><span></span></label>
                        </div>
                        <div x-show="settings.nav.progress.on" style="padding:2px 0 12px">
                            @include('falcon-slider::partials.color', ['model' => 'settings.nav.progress.color', 'label' => 'Color'])
                            <div class="fse-field" style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                                <div><label class="fse-lbl">Height (px)</label><input type="number" x-model.number="settings.nav.progress.height" class="fse-input"></div>
                                <div>
                                    <label class="fse-lbl">Position</label>
                                    <select x-model="settings.nav.progress.pos" class="fse-input">
                                        <option value="top">Top</option>
                                        <option value="bottom">Bottom</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Bottom: slide strip + timeline ===== --}}
        <div style="height:150px; background:var(--bar); border-top:1px solid var(--line); display:flex; min-height:0;">
            {{-- slide strip --}}
            <div style="width:250px; flex:0 0 250px; border-right:1px solid var(--line); padding:10px; overflow-x:auto; display:flex; gap:8px; align-items:flex-start;">
                <template x-for="(slide,i) in slides" :key="slide.id">
                    <div class="fse-thumb" :class="{on:i===selectedIndex && !editingGlobal}" draggable="true"
                         @dragstart="dragIndex=i" @dragover.prevent @drop.prevent="drop(i)" @click="selectSlide(i)">
                        <div class="absolute inset-0" :style="bgStyle(slide.bg)"></div>
                        <span style="position:absolute;top:2px;left:3px;font-size:10px;background:rgba(0,0,0,.5);color:#fff;padding:0 4px;border-radius:3px" x-text="i+1"></span>
                        <button @click.stop="duplicateSlide(i)" title="Duplicate slide" style="position:absolute;top:2px;right:22px;width:18px;height:18px;background:rgba(0,0,0,.5);color:#fff;border-radius:3px;display:flex;align-items:center;justify-content:center"><span class="material-symbols-outlined" style="font-size:12px !important;line-height:12px">content_copy</span></button>
                        <button @click.stop="deleteSlide(i)" title="Delete slide" style="position:absolute;top:2px;right:2px;width:18px;height:18px;background:rgba(0,0,0,.5);color:#fff;border-radius:3px;font-size:12px">×</button>
                        <span style="position:absolute;bottom:0;left:0;right:0;font-size:10px;background:rgba(0,0,0,.6);color:#fff;padding:1px 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" x-text="slideLabel(slide,i)"></span>
                    </div>
                </template>
                <button @click="openTemplates()" class="fse-thumb" style="border-style:dashed;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--accent);gap:2px">
                    <span class="material-symbols-outlined">add</span>
                    <span style="font-size:10px">New slide</span>
                </button>
                {{-- Global layers: one set shown on EVERY slide (logo, watermark, fixed CTA…) --}}
                <button @click="selectGlobal()" class="fse-thumb" :class="{on:editingGlobal}" title="Global layers — shown on every slide"
                        style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:2px;color:#cbd5e1;background:#1e2530">
                    <span class="material-symbols-outlined">layers</span>
                    <span style="font-size:10px">Global</span>
                    <span x-show="settings.globalLayers && settings.globalLayers.length" x-text="(settings.globalLayers||[]).length"
                          style="position:absolute;top:2px;right:3px;font-size:9px;background:var(--accent);color:#fff;min-width:14px;height:14px;border-radius:7px;display:flex;align-items:center;justify-content:center;padding:0 3px"></span>
                </button>
            </div>

            {{-- timeline (ruler extends dynamically to fit the longest layer) --}}
            <div style="flex:1; min-width:0; overflow:auto;">
                <div :style="`min-width:${150 + timelineMax*70}px`">
                    {{-- ruler: one 70px cell per second (matches the bar grid), extends with timelineMax --}}
                    <div style="display:flex; height:24px; border-bottom:1px solid var(--line); color:var(--mut); font-size:10px;">
                        <div style="width:150px;flex:0 0 150px;padding:0 10px;display:flex;align-items:center;color:#fff;font-weight:600;font-size:11px">EDITOR</div>
                        <div style="flex:1;display:flex;height:100%">
                            <template x-for="s in timelineMax" :key="'t'+s">
                                <div style="flex:0 0 70px;width:70px;border-right:1px solid #33333e;display:flex;align-items:center;justify-content:flex-end;padding-right:5px" x-text="s+'s'"></div>
                            </template>
                        </div>
                    </div>
                    <template x-if="current">
                        <div>
                            <template x-for="(layer,li) in current.layers" :key="'tl'+layer.id">
                                <div class="fse-tl-row" @click="selectedLayer=li" style="cursor:pointer" :style="li===selectedLayer?'background:#2f2f38':''">
                                    <div style="width:150px;flex:0 0 150px;padding:0 10px;display:flex;align-items:center;gap:6px;overflow:hidden">
                                        <span class="material-symbols-outlined" style="font-size:14px" x-text="layerIcon(layer.type)"></span>
                                        <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="layerLabel(layer)"></span>
                                    </div>
                                    <div style="flex:1;position:relative;height:100%;display:flex;align-items:center">
                                        <div class="fse-tl-bar"
                                             :style="`margin-left:${(animOf(layer).delay||0)/1000*70}px;min-width:${Math.max(30,(animOf(layer).duration||700)/1000*70)}px`"
                                             :title="animLabel(animOf(layer).preset)"
                                             x-text="animLabel(animOf(layer).preset)"></div>
                                    </div>
                                </div>
                            </template>
                            <p x-show="!current.layers.length" style="color:var(--mut);padding:10px">No layers on this slide.</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- New-slide modal --}}
        <template x-teleport="body">
        <div x-show="slideModal" class="fse-modal" x-transition.opacity @keydown.escape.window="slideModal=false" @click.self="slideModal=false">
            <div style="background:#1c1c24;border:1px solid var(--line);border-radius:10px;width:340px;max-width:92vw;padding:18px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;color:#fff;font-weight:600;font-size:15px"><span class="material-symbols-outlined" style="color:var(--accent)">add_photo_alternate</span> New slide</div>
                <label class="fse-lbl">Slide name</label>
                <input x-ref="slideNameInput" x-model="newSlideName" @keydown.enter="createSlideFromModal()" class="fse-input" placeholder="Slide name" style="margin-bottom:16px">
                <div style="display:flex;gap:8px;justify-content:flex-end">
                    <button @click="slideModal=false" class="fse-btn" style="border:1px solid var(--line)">Cancel</button>
                    <button @click="createSlideFromModal()" class="fse-btn" style="background:var(--accent);color:#fff;font-weight:600">Create slide</button>
                </div>
            </div>
        </div>
        </template>

        {{-- Template gallery: pick a designed starting point (or a blank slide) --}}
        <template x-teleport="body">
        <div x-show="templateModal" class="fse-modal" x-transition.opacity @keydown.escape.window="templateModal=false" @click.self="templateModal=false" style="z-index:4000">
            <div style="background:#16161d;border:1px solid var(--line);border-radius:12px;width:820px;max-width:94vw;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 24px 70px rgba(0,0,0,.6)">
                <div style="display:flex;align-items:center;gap:9px;padding:16px 20px;border-bottom:1px solid var(--line);color:#fff;font-weight:600;font-size:16px">
                    <span class="material-symbols-outlined" style="color:var(--accent)">dashboard_customize</span> Choose a starting point
                    <button @click="templateModal=false" style="margin-left:auto;color:var(--mut);background:none;border:0;cursor:pointer;display:flex"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div style="padding:18px 20px;overflow-y:auto;display:flex;flex-wrap:wrap;gap:16px">
                    {{-- Blank --}}
                    <button @click="addSlide(''); templateModal=false" class="fse-tpl-card" style="width:232px;text-align:left;background:#111116;border:1px solid var(--line);border-radius:10px;overflow:hidden;cursor:pointer;padding:0">
                        <div style="width:232px;height:116px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;background:#0e0e13;border-bottom:1px solid var(--line);color:#64748b">
                            <span class="material-symbols-outlined" style="font-size:30px">add</span>
                            <span style="font-size:11px">Empty canvas</span>
                        </div>
                        <div style="padding:9px 11px;color:#e5e7eb;font-size:12.5px;font-weight:600">Blank slide</div>
                    </button>
                    {{-- Designed presets — each preview is the real 1200×600 layout scaled down --}}
                    <template x-for="(tpl,ti) in slideTemplates" :key="ti">
                        <button @click="addSlideFromTemplate(tpl)" class="fse-tpl-card" style="width:232px;text-align:left;background:#111116;border:1px solid var(--line);border-radius:10px;overflow:hidden;cursor:pointer;padding:0">
                            <div :style="'position:relative;width:232px;height:116px;overflow:hidden;border-bottom:1px solid var(--line);'+bgStyle(tpl.slide.bg)">
                                <div style="position:absolute;top:0;left:0;width:1200px;height:600px;transform:scale(.19334);transform-origin:top left">
                                    <template x-for="(ly,li) in (tpl.slide.layers||[])" :key="li">
                                        <div :style="`position:absolute;left:${ly.pos.desktop.x}px;top:${ly.pos.desktop.y}px;width:${ly.pos.desktop.w}px;height:${ly.pos.desktop.h}px`">
                                            <template x-if="ly.type==='text'"><div :style="textStyle(ly)" x-text="ly.content"></div></template>
                                            <template x-if="ly.type==='button'"><div :style="buttonStyle(ly,false)" x-text="ly.content"></div></template>
                                            <template x-if="ly.type==='shape'"><div :style="shapeStyle(ly)"></div></template>
                                            <template x-if="ly.type==='image'"><div style="width:100%;height:100%;background:#334155;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#94a3b8"><span class="material-symbols-outlined" style="font-size:64px">image</span></div></template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div style="padding:9px 11px;color:#e5e7eb;font-size:12.5px;font-weight:600" x-text="tpl.name"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        </template>

        {{-- Ready-made slider gallery — full designs (settings + 3 slides), applied in one click --}}
        <template x-teleport="body">
        <div x-show="demoModal" class="fse-modal" x-transition.opacity @keydown.escape.window="demoModal=false" @click.self="demoModal=false" style="z-index:4000">
            <div style="background:#16161d;border:1px solid var(--line);border-radius:12px;width:860px;max-width:94vw;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 24px 70px rgba(0,0,0,.6)">
                <div style="display:flex;align-items:center;gap:9px;padding:16px 20px;border-bottom:1px solid var(--line);color:#fff;font-weight:600;font-size:16px">
                    <span class="material-symbols-outlined" style="color:var(--accent)">auto_awesome</span> Pre-built Sliders
                    <span style="font-size:12px;color:var(--mut);font-weight:400">— pick one, then customise</span>
                    <button @click="demoModal=false" style="margin-left:auto;color:var(--mut);background:none;border:0;cursor:pointer;display:flex"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div style="padding:18px 20px;overflow-y:auto">
                    {{-- User-saved designs --}}
                    <template x-if="(userTemplates||[]).length">
                        <div style="margin-bottom:8px">
                            <div style="font-size:12px;font-weight:700;letter-spacing:.04em;color:var(--mut);text-transform:uppercase;margin-bottom:12px">My designs</div>
                            <div style="display:flex;flex-wrap:wrap;gap:16px">
                                <template x-for="(tpl,ti) in userTemplates" :key="'u'+tpl.id">
                                    <div style="position:relative">
                                        <button @click="useDemo(tpl)" class="fse-tpl-card" style="width:232px;text-align:left;background:#111116;border:1px solid var(--line);border-radius:10px;overflow:hidden;cursor:pointer;padding:0">
                                            <div :style="'position:relative;width:232px;height:116px;overflow:hidden;border-bottom:1px solid var(--line);'+bgStyle(((tpl.slides||[])[0]||{}).bg)">
                                                <div style="position:absolute;top:0;left:0;width:1200px;height:600px;transform:scale(.19334);transform-origin:top left">
                                                    <template x-for="(ly,li) in (((tpl.slides||[])[0]||{}).layers||[])" :key="li">
                                                        <div :style="`position:absolute;left:${ly.pos.desktop.x}px;top:${ly.pos.desktop.y}px;width:${ly.pos.desktop.w}px;height:${ly.pos.desktop.h}px`">
                                                            <template x-if="ly.type==='text'"><div :style="textStyle(ly)" x-text="ly.content"></div></template>
                                                            <template x-if="ly.type==='button'"><div :style="buttonStyle(ly,false)" x-text="ly.content"></div></template>
                                                            <template x-if="ly.type==='shape'"><div :style="shapeStyle(ly)"></div></template>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            <div style="padding:9px 11px;display:flex;align-items:center;justify-content:space-between;color:#e5e7eb;font-size:12.5px;font-weight:600">
                                                <span x-text="tpl.name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>
                                                <span style="font-size:10px;color:var(--mut);font-weight:400" x-text="(tpl.slides||[]).length+' slides'"></span>
                                            </div>
                                        </button>
                                        <button @click.stop="deleteUserTemplate(tpl)" title="Delete design" style="position:absolute;top:6px;right:6px;width:24px;height:24px;background:rgba(0,0,0,.6);color:#fca5a5;border:0;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center"><span class="material-symbols-outlined" style="font-size:15px">delete</span></button>
                                    </div>
                                </template>
                            </div>
                            <div style="font-size:12px;font-weight:700;letter-spacing:.04em;color:var(--mut);text-transform:uppercase;margin:20px 0 12px">Starter designs</div>
                        </div>
                    </template>
                    <div style="display:flex;flex-wrap:wrap;gap:16px">
                    <template x-for="(demo,di) in demoSliders" :key="di">
                        <button @click="useDemo(demo)" class="fse-tpl-card" style="width:244px;text-align:left;background:#111116;border:1px solid var(--line);border-radius:10px;overflow:hidden;cursor:pointer;padding:0">
                            <div :style="'position:relative;width:244px;height:122px;overflow:hidden;border-bottom:1px solid var(--line);'+bgStyle((demo.slides[0]||{}).bg)">
                                <div style="position:absolute;top:0;left:0;width:1200px;height:600px;transform:scale(.20334);transform-origin:top left">
                                    <template x-for="(ly,li) in ((demo.slides[0]||{}).layers||[])" :key="li">
                                        <div :style="`position:absolute;left:${ly.pos.desktop.x}px;top:${ly.pos.desktop.y}px;width:${ly.pos.desktop.w}px;height:${ly.pos.desktop.h}px`">
                                            <template x-if="ly.type==='text'"><div :style="textStyle(ly)" x-text="ly.content"></div></template>
                                            <template x-if="ly.type==='button'"><div :style="buttonStyle(ly,false)" x-text="ly.content"></div></template>
                                            <template x-if="ly.type==='shape'"><div :style="shapeStyle(ly)"></div></template>
                                        </div>
                                    </template>
                                </div>
                                <span style="position:absolute;top:8px;left:8px;font-size:10px;font-weight:700;letter-spacing:.04em;background:rgba(0,0,0,.55);color:#fff;padding:2px 8px;border-radius:999px" x-text="demo.category"></span>
                            </div>
                            <div style="padding:9px 11px;display:flex;align-items:center;justify-content:space-between;color:#e5e7eb;font-size:12.5px;font-weight:600">
                                <span x-text="demo.name"></span>
                                <span style="font-size:10px;color:var(--mut);font-weight:400" x-text="(demo.slides||[]).length+' slides'"></span>
                            </div>
                        </button>
                    </template>
                    </div>{{-- /starter grid --}}
                </div>{{-- /modal body --}}
            </div>
        </div>
        </template>

        {{-- Save-as-pre-built prompt --}}
        <template x-teleport="body">
        <div x-show="saveTplModal" class="fse-modal" x-transition.opacity @keydown.escape.window="saveTplModal=false" @click.self="saveTplModal=false" style="z-index:4300">
            <div style="background:#1c1c24;border:1px solid var(--line);border-radius:10px;width:380px;max-width:92vw;padding:18px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;color:#fff;font-weight:600;font-size:15px"><span class="material-symbols-outlined" style="color:var(--accent)">bookmark_add</span> Save as pre-built</div>
                <label class="fse-lbl">Design name</label>
                <input x-model="saveTplName" @keydown.enter="saveAsTemplate()" class="fse-input" placeholder="My hero design" style="margin-bottom:12px">
                <label class="fse-lbl">Category</label>
                <input x-model="saveTplCat" class="fse-input" placeholder="My designs" style="margin-bottom:16px">
                <div style="display:flex;gap:8px;justify-content:flex-end">
                    <button @click="saveTplModal=false" class="fse-btn" style="border:1px solid var(--line)">Cancel</button>
                    <button @click="saveAsTemplate()" :disabled="savingTpl || !saveTplName.trim()" class="fse-btn" style="background:var(--accent);color:#fff;font-weight:600" :style="(savingTpl||!saveTplName.trim())?'opacity:.55':''"><span x-text="savingTpl?'Saving…':'Save design'"></span></button>
                </div>
            </div>
        </div>
        </template>

        {{-- Icon library picker --}}
        <template x-teleport="body">
        <div x-show="iconPicker" class="fse-modal" x-transition.opacity @keydown.escape.window="iconPicker=false" @click.self="iconPicker=false" style="z-index:4200">
            <div style="background:#16161d;border:1px solid var(--line);border-radius:12px;width:560px;max-width:94vw;max-height:80vh;display:flex;flex-direction:column;box-shadow:0 24px 70px rgba(0,0,0,.6)">
                <div style="display:flex;align-items:center;gap:9px;padding:14px 18px;border-bottom:1px solid var(--line);color:#fff;font-weight:600;font-size:15px">
                    <span class="material-symbols-outlined" style="color:var(--accent)">emoji_emotions</span> Choose an icon
                    <button @click="iconPicker=false" style="margin-left:auto;color:var(--mut);background:none;border:0;cursor:pointer;display:flex"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div style="padding:12px 18px 0">
                    <input type="text" x-model="iconSearch" class="fse-input" placeholder="Search icons…" x-init="$watch('iconPicker', v=>{ if(v) $nextTick(()=>$el.focus()) })">
                </div>
                <div style="padding:14px 18px;flex:1 1 auto;min-height:0;overflow-y:auto;overflow-x:hidden;display:grid;grid-template-columns:repeat(auto-fill,minmax(46px,1fr));gap:8px;align-content:start">
                    <template x-for="ic in filteredIcons" :key="ic">
                        <button @click="applyIcon(ic)" :title="ic.replace(/_/g,' ')" class="fse-iconbtn">
                            <span class="material-symbols-outlined" style="font-size:26px" x-text="ic"></span>
                        </button>
                    </template>
                    <p x-show="!filteredIcons.length" style="grid-column:1/-1;color:var(--mut);font-size:13px;text-align:center;padding:20px">No icons match “<span x-text="iconSearch"></span>”.</p>
                </div>
            </div>
        </div>
        </template>

        {{-- Shape picker modal --}}
        <template x-teleport="body">
        <div x-show="shapePicker" class="fse-modal" x-transition.opacity @keydown.escape.window="shapePicker=false" @click.self="shapePicker=false">
            <div style="background:#1c1c24;border:1px solid var(--line);border-radius:10px;width:440px;max-width:92vw;padding:18px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;color:#fff;font-weight:600;font-size:15px"><span class="material-symbols-outlined" style="color:var(--accent)">category</span> Choose a shape</div>
                <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px">
                    <template x-for="t in shapeTypes" :key="t">
                        <button type="button" @click="addShape(t)" class="fse-pick" :title="t">
                            <div :style="'width:40px;height:40px;'+shapeStyle({style:{bg:'#6366f1',shape:t,radius:6}}).replace('width:100%;height:100%;','').replace('pointer-events:none;','')"></div>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        </template>

        {{-- Button picker modal --}}
        <template x-teleport="body">
        <div x-show="buttonPicker" class="fse-modal" x-transition.opacity @keydown.escape.window="buttonPicker=false" @click.self="buttonPicker=false">
            <div style="background:#1c1c24;border:1px solid var(--line);border-radius:10px;width:460px;max-width:92vw;padding:18px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;color:#fff;font-weight:600;font-size:15px"><span class="material-symbols-outlined" style="color:var(--accent)">smart_button</span> Choose a button style</div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:10px">
                    <template x-for="(b,bi) in buttonPresets" :key="bi">
                        <button type="button" @click="addButtonStyle(b)" class="fse-pick" style="padding:12px;background:#0f172a">
                            <span :style="`display:inline-flex;align-items:center;justify-content:center;padding:8px 18px;font-size:14px;background:${b.style.bg};color:${b.style.color};border-radius:${b.style.radius}px;font-weight:${b.style.weight};${b.style.border?'border:'+b.style.border+';':''}`" x-text="b.name"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        </template>

        {{-- Full-screen live preview — renders the CURRENT (unsaved) design via a POST into an iframe --}}
        <template x-teleport="body">
        <div x-show="livePreview" x-cloak class="fse-livepreview" @keydown.escape.window="closeLivePreview()">
            <div style="height:44px;flex-shrink:0;display:flex;align-items:center;gap:12px;padding:0 14px;background:#15151c;border-bottom:1px solid var(--line)">
                <span class="material-symbols-outlined" style="color:var(--accent)">smart_display</span>
                <span style="color:#fff;font-weight:600;font-size:14px">Live preview</span>
                <span style="color:var(--mut);font-size:12px">Showing your current (unsaved) design — autoplay & all animations run.</span>
                <div style="margin-left:auto;display:flex;align-items:center;gap:8px">
                    <button class="fse-btn" @click="submitLivePreview()" title="Reload with the latest changes"><span class="material-symbols-outlined">refresh</span> Reload</button>
                    <button @click="closeLivePreview()" title="Exit preview (Esc)"
                            style="display:inline-flex;align-items:center;gap:6px;height:32px;padding:0 16px;border:0;border-radius:4px;background:#ef4444;color:#fff;font-weight:600;font-size:13px;cursor:pointer">
                        <span class="material-symbols-outlined" style="font-size:18px">logout</span> Exit
                    </button>
                </div>
            </div>
            <iframe name="fsPreviewFrame" x-ref="previewFrame"></iframe>
            <form x-ref="previewForm" method="POST" action="{{ url('admin/falcon-slider/'.$slider->id.'/preview-live') }}" target="fsPreviewFrame" style="display:none">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="settings" x-ref="previewSettings">
                <input type="hidden" name="slides" x-ref="previewSlides">
            </form>
        </div>
        </template>

        {{-- Revisions / version history --}}
        <template x-teleport="body">
        <div x-show="revModal" class="fse-modal" x-cloak x-transition.opacity @keydown.escape.window="revModal=false" @click.self="revModal=false">
            <div style="background:#1c1c24;border:1px solid var(--line);border-radius:10px;width:480px;max-width:94vw;max-height:82vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(0,0,0,.5)">
                <div style="display:flex;align-items:center;gap:8px;padding:16px 18px 12px;color:#fff;font-weight:600;font-size:15px;border-bottom:1px solid var(--line)">
                    <span class="material-symbols-outlined" style="color:var(--accent)">history</span> Version history
                    <button type="button" @click="revModal=false" style="margin-left:auto;background:none;border:0;color:var(--mut);cursor:pointer"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div style="padding:14px 18px;overflow:auto">
                    <p style="color:var(--mut);font-size:12px;margin:0 0 12px">A snapshot is saved automatically every time you Save. Restore loads that version into the editor — review it, then Save to keep it.</p>
                    <div x-show="revLoading" style="color:var(--mut);font-size:13px;padding:16px 0;text-align:center">Loading…</div>
                    <div x-show="!revLoading && revList.length===0" style="color:var(--mut);font-size:13px;padding:16px 0;text-align:center">No saved versions yet — Save at least once and future saves will appear here.</div>
                    <template x-for="(r,ri) in revList" :key="r.id">
                        <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--line)">
                            <div style="flex:1;min-width:0">
                                <div style="color:#fff;font-size:13px;font-weight:600" x-text="r.name"></div>
                                <div style="color:var(--mut);font-size:11px" x-text="r.when + ' · ' + r.at + ' · ' + r.slides + ' slide' + (r.slides===1?'':'s')"></div>
                            </div>
                            <button type="button" class="fse-btn" @click="restoreRevision(r)" title="Load this version into the editor"><span class="material-symbols-outlined" style="font-size:16px">restore</span> Restore</button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        </template>
    </div>

    @push('scripts')
    <script>
        function sliderEditor() {
            return {
                name: @json($slider->name),
                settings: Object.assign({ width:1200, height:600, layout_type:'slider', sizing:'auto', autoplay:true, autoplay_delay:6000, loop:true, arrows:true, dots:true, pause_on_hover:true, transition:'fade', transition_duration:700, tablet_width:768, mobile_width:480 }, @json($slider->settings ?? (object)[])),
                navDefaults: {
                    arrows:   { on:true,  style:'chevron', pos:'middle', x:16, y:0 },
                    bullets:  { on:true,  style:'dots',    pos:'bc',     x:0,  y:16 },
                    progress: { on:false, color:'#ffffff', height:4,     pos:'bottom' },
                },
                spinnerDefault: { style:'none', color:'#ffffff' },
                slides: @json($slider->slides ?? []),
                demoSliders: @json($demos ?? []),
                userTemplates: @json($userTemplates ?? []),
                iconLib: @json($icons ?? []),
                sliderIsNew: @json($isNew ?? false),
                demoModal: false, iconPicker: false, iconSearch: '', iconMode: 'add',
                saveTplModal: false, saveTplName: '', saveTplCat: 'My designs', savingTpl: false,
                selectedIndex: 0, selectedLayer: -1, tab: 'layers', saving: false, dragIndex: null, stageW: 0, uid: 0, addMenu: false,
                multiSel: [], selectedGroup: null,
                layerDrag: null, layerDragOver: null, hoverLayer: -1, zoom: 'fit',
                editingGlobal: false,   // when true the layer editor targets settings.globalLayers (shown on every slide)
                cdNow: Date.now(),      // ticks each second so countdown layers count live in the canvas
                _gslide: { name:'Global', __global:true, bg:{type:'color',value:'#0f172a'}, transition:'fade', layers:[] },
                // Ready-made slide layouts (design space is 1200×600). Layer order = z-order:
                // earlier = on top. Inserted with fresh ids via addSlideFromTemplate().
                slideTemplates: [
                    { name:'Hero Spotlight', slide:{ transition:'fade', bg:{type:'gradient',gradient:true,from:'#4f46e5',to:'#7c3aed',angle:135,value:'#4f46e5'}, layers:[
                        { type:'text', content:'INTRODUCING', pos:{desktop:{x:200,y:150,w:800,h:30}}, style:{size:15,color:'#c7d2fe',weight:700,align:'center',letterSpacing:4}, anim:{in:{preset:'fade',delay:0,duration:500,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Build Something Amazing', pos:{desktop:{x:150,y:196,w:900,h:96}}, style:{size:58,color:'#ffffff',weight:800,align:'center',lineHeight:1.1}, anim:{in:{preset:'from-bottom',delay:120,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'The modern way to create stunning presentations in minutes.', pos:{desktop:{x:250,y:320,w:700,h:60}}, style:{size:22,color:'rgba(255,255,255,.85)',weight:400,align:'center',lineHeight:1.4}, anim:{in:{preset:'fade',delay:300,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'button', content:'Get Started', link:'', pos:{desktop:{x:510,y:410,w:180,h:54}}, style:{bg:'#ffffff',color:'#4f46e5',size:17,radius:8,weight:700}, anim:{in:{preset:'zoom-in',delay:500,duration:500,easing:'ease-out'},loop:'none'} },
                    ]}},
                    { name:'Hero Left', slide:{ transition:'slide-left', bg:{type:'gradient',gradient:true,from:'#0f172a',to:'#1e3a8a',angle:120,value:'#0f172a'}, layers:[
                        { type:'text', content:'WELCOME', pos:{desktop:{x:100,y:145,w:400,h:26}}, style:{size:14,color:'#38bdf8',weight:700,align:'left',letterSpacing:3}, anim:{in:{preset:'from-left',delay:0,duration:500,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Grow Your Business Faster', pos:{desktop:{x:100,y:180,w:620,h:150}}, style:{size:52,color:'#ffffff',weight:800,align:'left',lineHeight:1.1}, anim:{in:{preset:'from-left',delay:150,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Everything you need to launch, scale, and succeed — all in one place.', pos:{desktop:{x:100,y:340,w:560,h:70}}, style:{size:20,color:'rgba(255,255,255,.8)',align:'left',lineHeight:1.5}, anim:{in:{preset:'from-left',delay:300,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'button', content:'Start Free', link:'', pos:{desktop:{x:100,y:440,w:150,h:52}}, style:{bg:'#38bdf8',color:'#04263b',size:16,radius:8,weight:700}, anim:{in:{preset:'from-bottom',delay:450,duration:500,easing:'ease-out'},loop:'none'} },
                        { type:'button', content:'Learn More', link:'', pos:{desktop:{x:266,y:440,w:150,h:52}}, style:{bg:'rgba(255,255,255,.06)',color:'#ffffff',size:16,radius:8,weight:600,border:'1px solid rgba(255,255,255,.4)'}, anim:{in:{preset:'from-bottom',delay:560,duration:500,easing:'ease-out'},loop:'none'} },
                        { type:'shape', pos:{desktop:{x:780,y:90,w:360,h:360}}, style:{bg:'rgba(56,189,248,.14)',shape:'circle'}, anim:{in:{preset:'fade',delay:0,duration:700,easing:'ease-out'},loop:'none'} },
                    ]}},
                    { name:'Split Feature', slide:{ transition:'fade', bg:{type:'color',value:'#eef2f7'}, layers:[
                        { type:'text', content:'Design that speaks.', pos:{desktop:{x:60,y:170,w:440,h:120}}, style:{size:44,color:'#ffffff',weight:800,align:'left',lineHeight:1.1}, anim:{in:{preset:'from-left',delay:150,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Clean, modern layouts crafted to turn visitors into customers.', pos:{desktop:{x:60,y:300,w:430,h:80}}, style:{size:18,color:'rgba(255,255,255,.75)',align:'left',lineHeight:1.5}, anim:{in:{preset:'from-left',delay:300,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'button', content:'Explore Work', link:'', pos:{desktop:{x:60,y:410,w:170,h:48}}, style:{bg:'#f59e0b',color:'#111827',size:16,radius:6,weight:700}, anim:{in:{preset:'from-bottom',delay:450,duration:500,easing:'ease-out'},loop:'none'} },
                        { type:'image', url:'', pos:{desktop:{x:620,y:70,w:520,h:460}}, style:{fit:'cover',radius:12}, anim:{in:{preset:'from-right',delay:200,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'shape', pos:{desktop:{x:0,y:0,w:560,h:600}}, style:{bg:'#111827',shape:'square',radius:0}, anim:{in:{preset:'fade',delay:0,duration:500,easing:'ease-out'},loop:'none'} },
                    ]}},
                    { name:'Call to Action', slide:{ transition:'zoom', bg:{type:'gradient',gradient:true,from:'#f59e0b',to:'#ef4444',angle:100,value:'#f59e0b'}, layers:[
                        { type:'text', content:'Ready to get started?', pos:{desktop:{x:150,y:200,w:900,h:90}}, style:{size:52,color:'#ffffff',weight:800,align:'center',lineHeight:1.1}, anim:{in:{preset:'from-bottom',delay:0,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Join thousands of teams already building with us.', pos:{desktop:{x:250,y:310,w:700,h:50}}, style:{size:21,color:'rgba(255,255,255,.92)',align:'center'}, anim:{in:{preset:'fade',delay:200,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'button', content:'Sign Up Free', link:'', pos:{desktop:{x:490,y:395,w:220,h:58}}, style:{bg:'#111827',color:'#ffffff',size:18,radius:999,weight:700}, anim:{in:{preset:'zoom-in',delay:400,duration:500,easing:'ease-out'},loop:'none'} },
                    ]}},
                    { name:'Mega Sale', slide:{ transition:'slide-up', bg:{type:'gradient',gradient:true,from:'#111827',to:'#312e81',angle:135,value:'#111827'}, layers:[
                        { type:'text', content:'SALE', pos:{desktop:{x:100,y:150,w:130,h:24}}, style:{size:18,color:'#ffffff',weight:800,align:'center',letterSpacing:2}, anim:{in:{preset:'from-left',delay:0,duration:400,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'50% OFF Everything', pos:{desktop:{x:100,y:205,w:780,h:130}}, style:{size:60,color:'#ffffff',weight:900,align:'left',lineHeight:1.05}, anim:{in:{preset:'from-bottom',delay:150,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Limited time only. The biggest sale of the year ends soon.', pos:{desktop:{x:100,y:345,w:580,h:60}}, style:{size:20,color:'rgba(255,255,255,.8)',align:'left',lineHeight:1.5}, anim:{in:{preset:'fade',delay:300,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'button', content:'Shop Now', link:'', pos:{desktop:{x:100,y:430,w:180,h:54}}, style:{bg:'#ef4444',color:'#ffffff',size:17,radius:8,weight:800}, anim:{in:{preset:'from-bottom',delay:450,duration:500,easing:'ease-out'},loop:'none'} },
                        { type:'shape', pos:{desktop:{x:100,y:140,w:130,h:42}}, style:{bg:'#ef4444',shape:'square',radius:999}, anim:{in:{preset:'from-left',delay:0,duration:400,easing:'ease-out'},loop:'none'} },
                    ]}},
                    { name:'Minimal', slide:{ transition:'fade', bg:{type:'color',value:'#0a0a0f'}, layers:[
                        { type:'text', content:'— PORTFOLIO 2026 —', pos:{desktop:{x:200,y:225,w:800,h:26}}, style:{size:14,color:'#a1a1aa',weight:600,align:'center',letterSpacing:5}, anim:{in:{preset:'fade',delay:0,duration:600,easing:'ease-out'},loop:'none'} },
                        { type:'text', content:'Timeless. Minimal. Bold.', pos:{desktop:{x:150,y:265,w:900,h:100}}, style:{size:60,color:'#fafafa',weight:300,align:'center',letterSpacing:1,lineHeight:1.1}, anim:{in:{preset:'from-bottom',delay:150,duration:700,easing:'ease-out'},loop:'none'} },
                    ]}},
                ],
                previewUrl: @json($slider->slides ? url('/') : ''),
                _move:null, _up:null,
                history:[], redoStack:[], _baseline:null, _restoring:false, _ready:false, _histTimer:null, _keysBound:false, _histBound:false,
                dirty:false, _savedState:null, device:'desktop', guides:[], _dragLayer:null, slideModal:false, templateModal:false, newSlideName:'', shapePicker:false, buttonPicker:false,
                livePreview:false, revModal:false, revList:[], revLoading:false,
                canSave: @json($canSave ?? true), upgradeUrl: @json($upgradeUrl ?? 'https://falconcms.com/#pricing'),
                shapeTypes:['square','circle','triangle','triangle-down','diamond','pentagon','hexagon','star','parallelogram','chevron'],
                buttonPresets:[
                    { name:'Solid',    style:{bg:'#2271b1',color:'#fff',size:16,radius:6,weight:600} },
                    { name:'Pill',     style:{bg:'#2271b1',color:'#fff',size:16,radius:999,weight:600} },
                    { name:'Outline',  style:{bg:'transparent',color:'#2271b1',size:16,radius:6,weight:600,border:'2px solid #2271b1'} },
                    { name:'Gradient', style:{bg:'linear-gradient(135deg,#6366f1,#ec4899)',color:'#fff',size:16,radius:8,weight:700} },
                    { name:'Dark',     style:{bg:'#111827',color:'#fff',size:16,radius:6,weight:600} },
                    { name:'Soft',     style:{bg:'#e0e7ff',color:'#3730a3',size:16,radius:8,weight:600} },
                    { name:'Ghost',    style:{bg:'transparent',color:'#fff',size:16,radius:6,weight:600,border:'1px solid rgba(255,255,255,.7)'} },
                    { name:'Large',    style:{bg:'#2271b1',color:'#fff',size:20,radius:8,weight:700} },
                ],

                init() {
                    this.slides.forEach(s => { if(!s.id) s.id=this.nextId(); if(!Array.isArray(s.layers)) s.layers=[]; s.layers.forEach(l=>{ if(!l.id) l.id=this.nextId(); if(l.type==='video'&&!l.video) l.video={}; if(l.type==='countdown'&&!l.countdown) l.countdown={}; if(!l.hover) l.hover={}; if(!l.style) l.style={}; }); });
                    if(!Array.isArray(this.settings.globalLayers)) this.settings.globalLayers=[];
                    this.settings.globalLayers.forEach(l=>{ if(!l.hover) l.hover={}; if(!l.style) l.style={}; });
                    this.settings.globalLayers.forEach(l=>{ if(!l.id) l.id=this.nextId(); if(l.style&&l.style.family) this.loadFont(l.style.family); });
                    this._gslide.layers = this.settings.globalLayers;
                    this.normalizeNav();
                    this.slides.forEach(s=>(s.layers||[]).forEach(l=>{ if(l.style&&l.style.family) this.loadFont(l.style.family); }));
                    // A brand-new slider starts with one blank slide so the Slide tab's
                    // background options are available right away (no need to add a layer first).
                    if (this.slides.length===0) this.slides.push({ id:this.nextId(), name:'Slide 1', bg:{type:'color',value:'#1e293b'}, transition:'fade', layers:[] });
                    this.selectedIndex = 0;
                    this.$nextTick(()=>{ this.measure(); if(window.ResizeObserver && this.$refs.canvas) new ResizeObserver(()=>this.measure()).observe(this.$refs.canvas); });
                    window.addEventListener('resize',()=>this.measure());
                    // init() runs twice (Alpine auto-init + x-init); bind the key handler only once
                    // so an arrow press doesn't move twice / Delete doesn't remove two layers.
                    if(!this._keysBound){ this._keysBound=true; window.addEventListener('keydown',(e)=>this.onKey(e)); }
                    this.setupHistory();
                },
                // ---- Undo / Redo history (debounced snapshots of slides + settings) ----
                serialize(){ return JSON.stringify({ name:this.name, slides:this.slides, settings:this.settings }); },
                setupHistory(){
                    if(this._histBound) return; this._histBound=true;
                    this._baseline = this._savedState = this.serialize();
                    if(window.Alpine && window.Alpine.effect){
                        // effect deep-reads via serialize() → re-runs on any name/slides/settings change.
                        window.Alpine.effect(()=>{ const cur=this.serialize(); if(this._ready){ this.dirty = (cur !== this._savedState); this.scheduleCommit(); } });
                    }
                    this._ready = true;
                    setInterval(() => { this.cdNow = Date.now(); }, 1000);   // live countdown ticking in the canvas
                    // Brand-new slider → offer the ready-made design gallery straight away.
                    if(this.sliderIsNew && (this.demoSliders||[]).length){ this.$nextTick(()=>{ this.demoModal=true; }); }
                },
                scheduleCommit(){ if(this._restoring) return; clearTimeout(this._histTimer); this._histTimer=setTimeout(()=>this.commit(),400); },
                commit(){ const cur=this.serialize(); if(cur===this._baseline) return; this.history.push(this._baseline); if(this.history.length>60) this.history.shift(); this._baseline=cur; this.redoStack=[]; },
                undo(){
                    clearTimeout(this._histTimer); this.commit();          // fold any pending edits into history first
                    if(!this.history.length) return;
                    this._restoring=true;
                    this.redoStack.push(this._baseline);
                    const prev=this.history.pop();
                    this.applyState(prev); this._baseline=prev;
                    this.$nextTick(()=>{ this._restoring=false; });
                },
                redo(){
                    if(!this.redoStack.length) return;
                    this._restoring=true;
                    this.history.push(this._baseline);
                    const next=this.redoStack.pop();
                    this.applyState(next); this._baseline=next;
                    this.$nextTick(()=>{ this._restoring=false; });
                },
                applyState(json){
                    const st=JSON.parse(json);
                    this.slides=st.slides; this.settings=st.settings;
                    if(this.selectedIndex>=this.slides.length) this.selectedIndex=this.slides.length-1;
                    if(this.selectedIndex<0 && this.slides.length) this.selectedIndex=0;
                    if(this.selectedLayer>=0 && this.current && this.selectedLayer>=this.current.layers.length) this.selectedLayer=-1;
                    this.normalizeNav();
                },
                // ---- Full-screen live preview (renders current, UNSAVED state) ----
                openLivePreview(){ this.livePreview=true; this.$nextTick(()=>this.submitLivePreview()); },
                submitLivePreview(){
                    if(!this.$refs.previewForm) return;
                    this.$refs.previewSettings.value = JSON.stringify(this.settings);
                    this.$refs.previewSlides.value   = JSON.stringify(this.slides);
                    this.$refs.previewForm.submit();
                },
                closeLivePreview(){ this.livePreview=false; try{ this.$refs.previewFrame.src='about:blank'; }catch(e){} },
                // ---- Revisions / version history ----
                async openRevisions(){
                    this.revModal=true; this.revLoading=true; this.revList=[];
                    try{
                        const r=await fetch('{{ url('admin/falcon-slider/'.$slider->id.'/revisions') }}',{headers:{'Accept':'application/json'}});
                        const j=await r.json();
                        this.revList = (j && j.revisions) ? j.revisions : [];
                    }catch(e){ if(window.showToast)window.showToast('Could not load history','error'); }
                    finally{ this.revLoading=false; }
                },
                restoreRevision(r){
                    // Load the snapshot into the editor as an undoable edit; the user reviews then Saves.
                    clearTimeout(this._histTimer); this.commit();
                    if(r.name) this.name=r.name;
                    this.applyState(JSON.stringify({ name:r.name, slides:r.slidesData||[], settings:r.settings||this.settings }));
                    this.commit();
                    this.revModal=false;
                    this.$nextTick(()=>this.measure());
                    if(window.showToast)window.showToast('Version restored — review, then Save to keep it','success');
                },
                // Keyboard: arrow keys nudge the selected layer (Shift = 10px), Delete/Backspace removes it.
                onKey(e){
                    // Ctrl/Cmd+S saves (works even while typing in a field).
                    if((e.ctrlKey||e.metaKey) && (e.key||'').toLowerCase()==='s'){ e.preventDefault(); if(!this.canSave){ this.proSaveAlert(); return; } if(this.dirty && !this.saving) this.save(); return; }
                    const t=e.target;
                    if(t && (t.tagName==='INPUT'||t.tagName==='TEXTAREA'||t.tagName==='SELECT'||t.isContentEditable)) return;
                    // Undo / Redo — work regardless of layer selection.
                    const k=(e.key||'').toLowerCase();
                    if((e.ctrlKey||e.metaKey) && k==='z' && !e.shiftKey){ e.preventDefault(); this.undo(); return; }
                    if((e.ctrlKey||e.metaKey) && (k==='y' || (k==='z' && e.shiftKey))){ e.preventDefault(); this.redo(); return; }
                    // Ctrl/Cmd+D duplicates the selected layer in place.
                    if((e.ctrlKey||e.metaKey) && k==='d' && this.selectedLayer>=0){ e.preventDefault(); this.duplicateLayer(this.selectedLayer); return; }
                    if(!this.current || this.selectedLayer<0) return;
                    const layer=this.current.layers[this.selectedLayer]; if(!layer) return;
                    if(e.key==='Delete'||e.key==='Backspace'){ e.preventDefault(); this.deleteLayer(this.selectedLayer); return; }
                    const step=e.shiftKey?10:1, d=this.p(layer);
                    const W=this.dw, H=this.dh;
                    if(e.key==='ArrowLeft')       d.x=Math.max(0, d.x-step);
                    else if(e.key==='ArrowRight')  d.x=Math.min(Math.max(0,W-d.w), d.x+step);
                    else if(e.key==='ArrowUp')     d.y=Math.max(0, d.y-step);
                    else if(e.key==='ArrowDown')   d.y=Math.min(Math.max(0,H-d.h), d.y+step);
                    else return;
                    e.preventDefault();
                },
                measure(){ if(this.$refs.canvas){ var el=this.$refs.canvas, cs=getComputedStyle(el); this.stageW = el.clientWidth - parseFloat(cs.paddingLeft||0) - parseFloat(cs.paddingRight||0); } },
                // Size the editor to exactly fill from its top to the viewport bottom, so the admin
                // page never scrolls (a page scrollbar was reaching the right panel / Save button).
                fitHeight(){ const el=this.$el; if(!el)return; const docTop=el.getBoundingClientRect().top + window.scrollY; el.style.height=Math.max(360, window.innerHeight - docTop) + 'px'; },
                // Build settings.nav (deep) from defaults, migrating the old arrows/dots booleans.
                normalizeNav(){
                    const d = this.navDefaults, n = this.settings.nav || {};
                    n.arrows   = Object.assign({}, d.arrows,   n.arrows   || {});
                    n.bullets  = Object.assign({}, d.bullets,  n.bullets  || {});
                    n.progress = Object.assign({}, d.progress, n.progress || {});
                    if (!this.settings.nav) {   // first time: seed on/off from the legacy booleans
                        n.arrows.on  = this.settings.arrows !== false;
                        n.bullets.on = this.settings.dots   !== false;
                    }
                    this.settings.nav = n;
                    if (!this.settings.spinner || typeof this.settings.spinner !== 'object') this.settings.spinner = Object.assign({}, this.spinnerDefault);
                    else this.settings.spinner = Object.assign({}, this.spinnerDefault, this.settings.spinner);
                },
                // Spinner preview markup (matches the runtime): a sized box with N animated bits.
                spinnerMarkupEd(style){ const n=({'1':1,'2':1,'3':3,'4':1,'5':5,'6':2})[style]||1; let s=''; for(let k=0;k<n;k++) s+='<i></i>'; return '<div class="fs-sp-box">'+s+'</div>'; },
                get scale(){
                    if(this.zoom==='fit'){ return this.stageW ? Math.min(this.stageW/(this.dw||1200), 1) : 1; }   // never upscale → device canvas shows narrow, layers at real size
                    return (parseInt(this.zoom)||100)/100;   // 100% = actual size = matches the frontend (auto)
                },
                get current(){
                    if(this.editingGlobal){ this._gslide.layers = this.settings.globalLayers; return this._gslide; }
                    return (this.selectedIndex>=0 && this.selectedIndex<this.slides.length) ? this.slides[this.selectedIndex] : null;
                },
                get layer(){ return (this.current && this.selectedLayer>=0 && this.selectedLayer<this.current.layers.length) ? this.current.layers[this.selectedLayer] : null; },
                // ---- Layer groups ----
                get groups(){ if(this.current && !Array.isArray(this.current.groups)) this.current.groups=[]; return this.current ? this.current.groups : []; },
                groupOf(id){ return this.groups.find(g=>g.id===id) || null; },
                get selGroup(){ return this.selectedGroup ? this.groupOf(this.selectedGroup) : null; },
                // Ordered display: interleave group blocks (header + members) with ungrouped layers.
                get layerDisplay(){
                    if(!this.current) return [];
                    const layers=this.current.layers||[], gmap={}; this.groups.forEach(g=>gmap[g.id]=g);
                    const out=[], seen={};
                    layers.forEach((l,li)=>{
                        if(l.groupId && gmap[l.groupId]){
                            if(!seen[l.groupId]){ seen[l.groupId]=true;
                                const members=[]; layers.forEach((x,xi)=>{ if(x.groupId===l.groupId) members.push({layer:x,li:xi}); });
                                out.push({kind:'group', group:gmap[l.groupId], members});
                            }
                        } else out.push({kind:'layer', layer:l, li});
                    });
                    return out;
                },
                membersOf(gid){ const r=[]; (this.current.layers||[]).forEach((l,i)=>{ if(l.groupId===gid) r.push(i); }); return r; },
                isSel(li){ return this.multiSel.includes(li); },
                // Layer-list click: Ctrl/Cmd toggles, Shift ranges, plain click selects one.
                selectLayerRow(li,e){
                    this.selectedGroup=null;
                    if(e && (e.ctrlKey||e.metaKey)){ const i=this.multiSel.indexOf(li); if(i>=0)this.multiSel.splice(i,1); else this.multiSel.push(li); this.selectedLayer=li; }
                    else if(e && e.shiftKey && this.selectedLayer>=0){ const a=Math.min(this.selectedLayer,li), b=Math.max(this.selectedLayer,li); this.multiSel=[]; for(let k=a;k<=b;k++)this.multiSel.push(k); this.selectedLayer=li; }
                    else { this.multiSel=[li]; this.selectedLayer=li; }
                    this.tab='layers';
                },
                pruneGroups(){ if(!this.current)return; const used={}; (this.current.layers||[]).forEach(l=>{ if(l.groupId) used[l.groupId]=true; }); this.current.groups=this.groups.filter(g=>used[g.id]); },
                groupSelected(){
                    if(this.multiSel.length<2) return;
                    if(!Array.isArray(this.current.groups)) this.current.groups=[];
                    const gid='g'+this.nextId(), name='Group '+(this.current.groups.length+1);
                    this.current.groups.push({id:gid, name, hidden:{}, anim:{in:{preset:'none',delay:0,duration:600,easing:'ease-out'}, stagger:120}});
                    this.multiSel.forEach(li=>{ if(this.current.layers[li]) this.current.layers[li].groupId=gid; });
                    this.pruneGroups();
                    this.multiSel=[]; this.selectedGroup=gid; this.selectedLayer=-1;
                },
                selectGroup(gid){ this.selectedGroup=gid; this.selectedLayer=-1; this.multiSel=[]; this.tab='layers'; },
                ungroup(gid){ (this.current.layers||[]).forEach(l=>{ if(l.groupId===gid) delete l.groupId; }); this.current.groups=this.groups.filter(g=>g.id!==gid); if(this.selectedGroup===gid)this.selectedGroup=null; },
                deleteGroup(gid){ this.current.layers=(this.current.layers||[]).filter(l=>l.groupId!==gid); this.current.groups=this.groups.filter(g=>g.id!==gid); if(this.selectedGroup===gid)this.selectedGroup=null; this.selectedLayer=-1; },
                duplicateGroup(gid){
                    const src=this.groupOf(gid); if(!src)return;
                    const ngid='g'+this.nextId(); const ng=JSON.parse(JSON.stringify(src)); ng.id=ngid; ng.name=src.name+' copy';
                    this.current.groups.push(ng);
                    (this.current.layers||[]).filter(l=>l.groupId===gid).forEach(l=>{ const c=JSON.parse(JSON.stringify(l)); c.id=this.nextId(); c.groupId=ngid; const d=(c.pos&&c.pos.desktop)||null; if(d){ d.x=(+d.x||0)+16; d.y=(+d.y||0)+16; } this.current.layers.push(c); });
                    this.selectedGroup=ngid;
                },
                // Group per-device visibility (mirrors the per-layer one).
                grpVisOn(g,dev){ return !(g.hidden && g.hidden[dev]); },
                grpToggleVis(g,dev){ g.hidden=g.hidden||{}; if(g.hidden[dev]) delete g.hidden[dev]; else g.hidden[dev]=true; },
                get timelineMax(){
                    let max = 0;
                    if (this.current) {
                        (this.current.layers||[]).forEach(l => {
                            const a = (l.anim && l.anim.in) || {};
                            const end = ((a.delay||0) + (a.duration||0)) / 1000;
                            if (end > max) max = end;
                        });
                        const dur = (this.current.duration||0) / 1000;
                        if (dur > max) max = dur;
                    }
                    return Math.max(10, Math.ceil(max) + 1); // default 10s, grows past it
                },
                get hMarks(){ const a=[],w=this.dw; for(let x=0;x<=w;x+=100)a.push(x); return a; },
                get vMarks(){ const a=[],h=this.dh; for(let y=0;y<=h;y+=100)a.push(y); return a; },
                nextId(){ return 'x'+Date.now().toString(36)+(this.uid++); },
                // Each device has its own custom width/height (tablet/mobile default 768/480, height falls back to desktop).
                deviceDims(dev){ const S=this.settings; if(dev==='tablet') return {dw:S.tablet_width||768, dh:S.tablet_height||S.height||600}; if(dev==='mobile') return {dw:S.mobile_width||480, dh:S.mobile_height||S.height||600}; return {dw:S.width||1200, dh:S.height||600}; },
                get dw(){ return this.deviceDims(this.device).dw; },
                get dh(){ return this.deviceDims(this.device).dh; },
                setDeviceWidth(v){ v=Math.max(1,+v||0); const S=this.settings; if(this.device==='tablet')S.tablet_width=v; else if(this.device==='mobile')S.mobile_width=v; else S.width=v; },
                setDeviceHeight(v){ v=Math.max(1,+v||0); const S=this.settings; if(this.device==='tablet')S.tablet_height=v; else if(this.device==='mobile')S.mobile_height=v; else S.height=v; },
                // Per-device position. Layers keep their DESKTOP size (no scaling) — a device just
                // gives a narrower canvas to re-arrange within. posOf = read (own → inherited, no write).
                posOf(l){ if(!l.pos)l.pos={}; const P=l.pos, dv=this.device; if(P[dv]) return P[dv]; if(dv==='mobile'&&P.tablet) return P.tablet; if(!P.desktop) P.desktop={x:0,y:0,w:200,h:60}; return P.desktop; },
                ensurePos(l){ if(!l.pos)l.pos={}; const dv=this.device; if(!l.pos[dv]) l.pos[dv]=Object.assign({}, this.posOf(l)); return l.pos[dv]; },
                alignLayer(l,dir){ const d=this.ensurePos(l), W=this.dw, H=this.dh;
                    if(dir==='left')d.x=0; else if(dir==='hcenter')d.x=Math.round((W-d.w)/2); else if(dir==='right')d.x=W-d.w;
                    else if(dir==='top')d.y=0; else if(dir==='vmiddle')d.y=Math.round((H-d.h)/2); else if(dir==='bottom')d.y=H-d.h; },
                isAligned(l,dir){ const d=this.posOf(l), W=this.dw, H=this.dh;
                    if(dir==='left')return d.x===0; if(dir==='hcenter')return d.x===Math.round((W-d.w)/2); if(dir==='right')return d.x===W-d.w;
                    if(dir==='top')return d.y===0; if(dir==='vmiddle')return d.y===Math.round((H-d.h)/2); if(dir==='bottom')return d.y===H-d.h; return false; },
                writePos(l,k,v){ const d=this.ensurePos(l); d[k] = (v===''||v===null||isNaN(v)) ? 0 : +v; },
                p(l){ return this.ensurePos(l); },   // legacy alias (writes to the active device)
                isHiddenDev(l){ return !!(l.hidden && l.hidden[this.device]); },
                toggleHideDev(l){ l.hidden = l.hidden || {}; if(l.hidden[this.device]) delete l.hidden[this.device]; else l.hidden[this.device]=true; },
                // Per-device visibility (Desktop / Tablet / Mobile), independent of the current view.
                visOn(l, dev){ return !(l.hidden && l.hidden[dev]); },
                toggleVis(l, dev){ l.hidden = l.hidden || {}; if(l.hidden[dev]) delete l.hidden[dev]; else l.hidden[dev]=true; },
                resetDevicePos(l){ if(this.device==='desktop' || !l.pos) return; delete l.pos[this.device]; if(l.hidden) delete l.hidden[this.device]; },
                animOf(l){ if(!l.anim)l.anim={}; if(!l.anim.in)l.anim.in={preset:'fade',delay:200,duration:700,easing:'ease-out'}; return l.anim.in; },
                animOutOf(l){ if(!l.anim)l.anim={}; if(!l.anim.out)l.anim.out={preset:'none',delay:0,duration:500,easing:'ease-in'}; return l.anim.out; },
                loopOf(l){ return (l.anim && l.anim.loop) || 'none'; },
                setLoop(l,v){ l.anim=l.anim||{}; l.anim.loop=v; },
                previewLayerAnimOut(layer){
                    if(!this.current||!this.$refs.canvas)return;
                    const li=this.current.layers.indexOf(layer); if(li<0)return;
                    const el=this.$refs.canvas.querySelectorAll('.fse-layer')[li]; if(!el||!el.animate)return;
                    const a=this.animOutOf(layer), f=this.PRESETS[a.preset]; if(!f||a.preset==='none')return;
                    const F=this.animFromEd(f);
                    el.animate([{opacity:1,transform:'none',filter:'none'},{opacity:F.opacity,transform:F.transform,filter:F.filter}],{delay:a.delay||0,duration:a.duration||500,easing:a.easing||'ease-in'});
                },
                PRESETS:{ none:{},fade:{opacity:0},'from-bottom':{opacity:0,y:60},'from-top':{opacity:0,y:-60},'from-left':{opacity:0,x:-80},'from-right':{opacity:0,x:80},'zoom-in':{opacity:0,scale:0.8},'zoom-out':{opacity:0,scale:1.2},
                          'from-bottom-left':{opacity:0,x:-70,y:70},'from-top-right':{opacity:0,x:70,y:-70},'rotate-in':{opacity:0,rotate:-160,scale:0.4},'flip-x':{opacity:0,rotateX:90},'flip-y':{opacity:0,rotateY:90},'blur-in':{opacity:0,blur:16},'zoom-blur':{opacity:0,scale:1.5,blur:12},'skew-in':{opacity:0,x:-90,skewX:14} },
                // Build the "from" keyframe (matches the frontend runtime exactly).
                animFromEd(f){ const tx=f.x||0,ty=f.y||0,s=(f.scale==null?1:f.scale),r=f.rotate||0,rx=f.rotateX||0,ry=f.rotateY||0,sk=f.skewX||0; const tf=((rx||ry)?'perspective(800px) ':'')+`translate(${tx}px,${ty}px) scale(${s}) rotate(${r}deg)`+(rx?` rotateX(${rx}deg)`:'')+(ry?` rotateY(${ry}deg)`:'')+(sk?` skewX(${sk}deg)`:''); return { opacity:(f.opacity==null?1:f.opacity), transform:tf, filter:(f.blur?`blur(${f.blur}px)`:'none') }; },
                palette:['#ffffff','#f8fafc','#e2e8f0','#94a3b8','#475569','#1e293b','#0f172a','#000000',
                         '#ef4444','#f97316','#f59e0b','#eab308','#84cc16','#22c55e','#10b981','#14b8a6',
                         '#06b6d4','#0ea5e9','#3b82f6','#6366f1','#8b5cf6','#a855f7','#d946ef','#ec4899'],

                // Effective in-animation for a layer: its own, or the group's (staggered) if it has none.
                // Group animation is authoritative for its members (with stagger) whenever the
                // group has one set — matches the runtime, so canvas and front-end agree.
                effLayerAnim(L){ const own=this.animOf(L); if(L.groupId){ const g=this.groupOf(L.groupId), gp=g&&g.anim&&g.anim.in&&g.anim.in.preset; if(gp&&gp!=='none'){ const mem=this.membersOf(L.groupId), idx=mem.indexOf(this.current.layers.indexOf(L)); return {preset:gp, delay:(g.anim.in.delay||0)+Math.max(0,idx)*(g.anim.stagger!=null?g.anim.stagger:120), duration:g.anim.in.duration||600, easing:g.anim.in.easing||'ease-out'}; } } return own; },
                previewAnims(){ if(!this.current||!this.$refs.canvas)return; const n=this.$refs.canvas.querySelectorAll('.fse-layer'); this.current.layers.forEach((L,i)=>{ const el=n[i]; if(!el)return; if(L.type==='text'&&(L.reveal||'none')!=='none'){ this.previewReveal(L); return; } const a=this.effLayerAnim(L),f=this.PRESETS[a.preset]; if(!f||a.preset==='none')return; const F=this.animFromEd(f); if(el.animate)el.animate([{opacity:F.opacity,transform:F.transform,filter:F.filter},{opacity:1,transform:'none',filter:'none'}],{delay:a.delay||0,duration:a.duration||700,easing:a.easing||'ease-out',fill:'both'}); }); },
                // Live-preview a single layer's animation on the canvas (fires when its preset/timing changes).
                previewLayerAnim(layer){
                    if(!this.current||!this.$refs.canvas)return;
                    // Text with a reveal effect plays the reveal instead of a box animation (matches runtime).
                    if(layer.type==='text' && (layer.reveal||'none')!=='none'){ this.previewReveal(layer); return; }
                    const li=this.current.layers.indexOf(layer); if(li<0)return;
                    const el=this.$refs.canvas.querySelectorAll('.fse-layer')[li]; if(!el||!el.animate)return;
                    const a=this.animOf(layer), f=this.PRESETS[a.preset]; if(!f||a.preset==='none')return;
                    const F=this.animFromEd(f);
                    el.animate([{opacity:F.opacity,transform:F.transform,filter:F.filter},{opacity:1,transform:'none',filter:'none'}],{delay:a.delay||0,duration:a.duration||700,easing:a.easing||'ease-out'});
                },
                // Live-preview a text layer's reveal (typewriter / split chars / split words) on the canvas.
                previewReveal(layer){
                    if(!this.current||!this.$refs.canvas)return;
                    const li=this.current.layers.indexOf(layer); if(li<0)return;
                    const box=this.$refs.canvas.querySelectorAll('.fse-layer')[li]; if(!box)return;
                    const t=box.querySelector('.fse-txt'); if(!t)return;
                    const rev=layer.reveal||'none', content=layer.content||'';
                    if(box.__twiv){ clearInterval(box.__twiv); box.__twiv=null; }
                    if(rev==='typewriter'){
                        t.textContent=''; let n=0;
                        box.__twiv=setInterval(()=>{ if(n>=content.length){ clearInterval(box.__twiv); box.__twiv=null; return; } t.textContent+=content.charAt(n++); }, 55);
                    } else if(rev==='chars'||rev==='words'){
                        const parts = rev==='words' ? content.split(/(\s+)/) : content.split('');
                        t.innerHTML=''; const spans=[];
                        parts.forEach(ch=>{ if(ch==='')return; if(/^\s+$/.test(ch)){ t.appendChild(document.createTextNode(ch)); return; } const sp=document.createElement('span'); sp.textContent=ch; sp.style.cssText='display:inline-block;white-space:pre;opacity:0'; t.appendChild(sp); spans.push(sp); });
                        spans.forEach((sp,ci)=>{ if(sp.animate) sp.animate([{opacity:0,transform:'translateY(.4em)'},{opacity:1,transform:'none'}],{delay:ci*(rev==='words'?90:35),duration:450,easing:'cubic-bezier(.2,.7,.2,1)',fill:'both'}); else sp.style.opacity=1; });
                    } else { t.textContent=content; }
                },
                // ---- Navigation preview helpers (mirror the runtime placement) ----
                navArrowClass(){ return 'st-'+((this.settings.nav&&this.settings.nav.arrows.style)||'chevron'); },
                navArrowGlyph(i){ const st=(this.settings.nav&&this.settings.nav.arrows.style)||'chevron'; const g=({chevron:['‹','›'],arrow:['←','→'],circle:['‹','›'],square:['‹','›'],minimal:['‹','›'],pill:['‹','›'],outline:['‹','›'],glass:['‹','›'],soft:['‹','›'],double:['«','»']})[st]||['‹','›']; return g[i]; },
                navArrowStyle(side){ const a=this.settings.nav.arrows, css=[]; if(a.pos==='top')css.push('top:'+(a.y||0)+'px'); else if(a.pos==='bottom')css.push('bottom:'+(a.y||0)+'px'); else { css.push('top:50%','transform:translateY(-50%)'); if(a.y)css.push('margin-top:'+a.y+'px'); } css.push(side+':'+(a.x||0)+'px'); return css.join(';'); },
                navBulletClass(){ return 'st-'+((this.settings.nav&&this.settings.nav.bullets.style)||'dots'); },
                navBulletsStyle(){ const b=this.settings.nav.bullets, pos=b.pos||'bc', v=pos[0], h=pos[1], css=[], tf=[]; if(v==='t')css.push('top:'+(b.y||0)+'px'); else if(v==='b')css.push('bottom:'+(b.y||0)+'px'); else { css.push('top:50%'); tf.push('translateY(-50%)'); if(b.y)css.push('margin-top:'+b.y+'px'); } if(h==='l')css.push('left:'+(b.x||0)+'px'); else if(h==='r')css.push('right:'+(b.x||0)+'px'); else { css.push('left:50%'); tf.push('translateX(-50%)'); if(b.x)css.push('margin-left:'+b.x+'px'); } if(tf.length)css.push('transform:'+tf.join(' ')); return css.join(';'); },
                navProgressStyle(){ const p=this.settings.nav.progress; return (p.pos==='top'?'top:0':'bottom:0')+';height:'+(p.height||4)+'px'; },
                // ---- Slide transition (mirrors the runtime; previewed live on the canvas) ----
                transitionKeyframesEd(t){
                    switch(t){
                        case 'slide-left':  return [{transform:'translateX(100%)'},{transform:'translateX(0)'}];
                        case 'slide-right': return [{transform:'translateX(-100%)'},{transform:'translateX(0)'}];
                        case 'slide-up':    return [{transform:'translateY(100%)'},{transform:'translateY(0)'}];
                        case 'slide-down':  return [{transform:'translateY(-100%)'},{transform:'translateY(0)'}];
                        case 'zoom':        return [{transform:'scale(1.15)',opacity:0},{transform:'scale(1)',opacity:1}];
                        case 'zoom-out':    return [{transform:'scale(0.85)',opacity:0},{transform:'scale(1)',opacity:1}];
                        case 'rotate':      return [{transform:'rotate(7deg) scale(1.1)',opacity:0},{transform:'rotate(0) scale(1)',opacity:1}];
                        case 'rotate-ccw':  return [{transform:'rotate(-7deg) scale(1.1)',opacity:0},{transform:'rotate(0) scale(1)',opacity:1}];
                        case 'flip':        return [{transform:'perspective(1200px) rotateY(90deg)',opacity:0},{transform:'perspective(1200px) rotateY(0)',opacity:1}];
                        case 'flip-x':      return [{transform:'perspective(1200px) rotateX(90deg)',opacity:0},{transform:'perspective(1200px) rotateX(0)',opacity:1}];
                        case 'blur':        return [{filter:'blur(20px)',opacity:0},{filter:'blur(0)',opacity:1}];
                        case 'fade-scale':  return [{transform:'scale(1.06)',opacity:0},{transform:'scale(1)',opacity:1}];
                        case 'skew':        return [{transform:'skewX(14deg) translateX(60px)',opacity:0},{transform:'skewX(0) translateX(0)',opacity:1}];
                        case 'glide-up':    return [{transform:'translateY(48px)',opacity:0},{transform:'translateY(0)',opacity:1}];
                        case 'glide-down':  return [{transform:'translateY(-48px)',opacity:0},{transform:'translateY(0)',opacity:1}];
                        case 'roll':        return [{transform:'translateX(70%) rotate(120deg)',opacity:0},{transform:'translateX(0) rotate(0)',opacity:1}];
                        default:            return [{opacity:0},{opacity:1}];
                    }
                },
                get currentTransition(){ return (this.current && this.current.transition) || this.settings.transition || 'fade'; },
                transDurEd(){ const d=Number(this.current && this.current.transition_duration); return d>0 ? d : (Number(this.settings.transition_duration)||700); },
                previewTransition(t){ const el=this.$refs.slideInner; if(!el||!el.animate)return; el.animate(this.transitionKeyframesEd(t||this.currentTransition),{duration:this.transDurEd(),easing:'ease'}); },
                // ---- Ken Burns (background zoom/pan) — mirrors the runtime ----
                kenburnsFramesEd(k){
                    switch(k){
                        case 'zoom-in':   return [{transform:'scale(1)'},{transform:'scale(1.18)'}];
                        case 'zoom-out':  return [{transform:'scale(1.18)'},{transform:'scale(1)'}];
                        case 'pan-left':  return [{transform:'scale(1.18) translateX(3%)'},{transform:'scale(1.18) translateX(-3%)'}];
                        case 'pan-right': return [{transform:'scale(1.18) translateX(-3%)'},{transform:'scale(1.18) translateX(3%)'}];
                        case 'pan-up':    return [{transform:'scale(1.18) translateY(3%)'},{transform:'scale(1.18) translateY(-3%)'}];
                        case 'zoom-pan':  return [{transform:'scale(1) translate(0,0)'},{transform:'scale(1.2) translate(-3%,-2%)'}];
                        default: return null;
                    }
                },
                previewKenburns(){ const el=this.$refs.bgInner; if(!el||!el.animate)return; const kf=this.kenburnsFramesEd((this.current&&this.current.bg&&this.current.bg.kenburns)||'none'); if(kf) el.animate(kf,{duration:3500,easing:'ease-out',fill:'both'}); },
                // Short, tidy label for the timeline bars (avoids ugly wrapping of "from-left" etc.).
                animLabel(p){ return ({none:'—',fade:'Fade','from-bottom':'Bottom','from-top':'Top','from-left':'Left','from-right':'Right','from-bottom-left':'Btm-Left','from-top-right':'Top-Right','zoom-in':'Zoom In','zoom-out':'Zoom Out','zoom-blur':'Zoom Blur','blur-in':'Blur In','rotate-in':'Rotate','flip-x':'Flip X','flip-y':'Flip Y','skew-in':'Skew In'})[p] || p; },

                selectSlide(i){ this.editingGlobal=false; this.selectedIndex=i; this.selectedLayer=-1; this.multiSel=[]; this.selectedGroup=null; this.$nextTick(()=>this.measure()); },
                // Switch the layer editor to the slider-wide global layers (shown on every slide).
                // The canvas shows the current slide's background as read-only context.
                selectGlobal(){
                    if(!Array.isArray(this.settings.globalLayers)) this.settings.globalLayers=[];
                    this._gslide.bg = JSON.parse(JSON.stringify(((this.slides[this.selectedIndex]||{}).bg) || {type:'color',value:'#0f172a'}));
                    this._gslide.layers = this.settings.globalLayers;
                    this.editingGlobal=true; this.selectedLayer=-1; this.tab='layers'; this.$nextTick(()=>this.measure());
                },
                addSlide(name){ const nm=(name&&String(name).trim())||('Slide '+(this.slides.length+1)); this.slides.push({id:this.nextId(),name:nm,bg:{type:'color',value:'#1e293b'},transition:'fade',layers:[]}); this.selectSlide(this.slides.length-1); this.tab='slide'; },
                openSlideModal(){ this.newSlideName='Slide '+(this.slides.length+1); this.slideModal=true; this.$nextTick(()=>{ const el=this.$refs.slideNameInput; if(el){ el.focus(); el.select(); } }); },
                createSlideFromModal(){ this.addSlide(this.newSlideName); this.slideModal=false; this.newSlideName=''; },
                openTemplates(){ this.templateModal=true; },
                openDemos(){ this.demoModal=true; },
                openSaveTemplate(){ if(!this.canSave){ this.proSaveAlert(); return; } this.demoModal=false; this.saveTplName = this.name ? (this.name+' design') : 'My design'; this.saveTplCat='My designs'; this.saveTplModal=true; },
                // Save the current slider (settings + slides) as a reusable pre-built template.
                async saveAsTemplate(){
                    const nm=(this.saveTplName||'').trim(); if(!nm){ return; }
                    this.savingTpl=true;
                    try{
                        const r=await fetch(@json(route('admin.sliders.templates.save')),{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({name:nm,category:this.saveTplCat||'My designs',settings:this.settings,slides:this.slides})});
                        const j=await r.json();
                        if(r.ok && j.template){ this.userTemplates.unshift(j.template); this.saveTplModal=false; if(window.showToast)window.showToast('Saved to your pre-built designs','success'); }
                        else if(window.showToast)window.showToast('Save failed','error');
                    }catch(e){ if(window.showToast)window.showToast('Save failed','error'); }
                    finally{ this.savingTpl=false; }
                },
                async deleteUserTemplate(tpl){
                    if(window.falconConfirm){ const ok=await window.falconConfirm({title:'Delete design?',message:'Remove “'+tpl.name+'” from your pre-built designs.',confirmText:'Delete',isDanger:true}); if(!ok)return; }
                    try{
                        const r=await fetch('{{ url('admin/falcon-slider-templates') }}/'+tpl.id,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||'{{ csrf_token() }}','X-HTTP-Method-Override':'DELETE','Accept':'application/json'}});
                        if(r.ok){ this.userTemplates=this.userTemplates.filter(t=>t.id!==tpl.id); }
                    }catch(e){}
                },
                openIconPicker(mode){ this.iconMode=mode||'change'; this.iconSearch=''; this.iconPicker=true; },
                get filteredIcons(){ const q=(this.iconSearch||'').toLowerCase().trim(); return q ? this.iconLib.filter(n=>n.replace(/_/g,' ').includes(q)) : this.iconLib; },
                // Pick an icon: either create a new icon layer or swap the current one's icon.
                applyIcon(name){
                    if(this.iconMode==='change' && this.layer && this.layer.type==='icon'){ this.layer.iconName=name; delete this.layer.content; }
                    else { this.addLayer('icon'); this.layer.iconName=name; delete this.layer.content; }
                    this.iconPicker=false;
                },
                // Apply a ready-made slider (its settings + 3 designed slides), fresh ids.
                async useDemo(demo){
                    const hasContent = (this.slides||[]).some(s=>(s.layers||[]).length>0);
                    if(hasContent && window.falconConfirm){
                        const ok = await window.falconConfirm({title:'Use this design?',message:'This replaces all current slides and settings with the selected ready-made design.',confirmText:'Use design',isDanger:true});
                        if(!ok) return;
                    }
                    const d = JSON.parse(JSON.stringify(demo));
                    this.settings = Object.assign({}, this.settings, d.settings||{});
                    if(!Array.isArray(this.settings.globalLayers)) this.settings.globalLayers=[];
                    this.slides = (d.slides||[]).map(s=>{ s.id=this.nextId(); (s.layers||[]).forEach(l=>{ l.id=this.nextId(); if(l.type==='video'&&!l.video)l.video={}; if(!l.hover)l.hover={}; if(!l.style)l.style={}; if(l.style&&l.style.family)this.loadFont(l.style.family); }); return s; });
                    this.editingGlobal=false; this.selectedIndex=0; this.selectedLayer=-1; this.tab='slide';
                    this.sliderIsNew=false; this.demoModal=false;
                    this.$nextTick(()=>this.measure());
                    if(window.showToast) window.showToast('Design applied — customise away!','success');
                },
                // Insert a designed preset as a fresh slide (deep-cloned, new ids, auto name).
                addSlideFromTemplate(tpl){
                    const s = JSON.parse(JSON.stringify(tpl.slide));
                    s.id = this.nextId();
                    s.name = 'Slide '+(this.slides.length+1);
                    s.transition = s.transition || 'fade';
                    (s.layers||[]).forEach(l=>{ l.id=this.nextId(); if(l.style&&l.style.family) this.loadFont(l.style.family); });
                    this.editingGlobal=false;
                    this.slides.push(s);
                    this.selectSlide(this.slides.length-1);
                    this.tab='layers'; this.templateModal=false;
                },
                duplicateSlide(i){ const c=JSON.parse(JSON.stringify(this.slides[i])); c.id=this.nextId(); c.layers.forEach(l=>l.id=this.nextId()); this.slides.splice(i+1,0,c); this.selectSlide(i+1); },
                deleteSlide(i){ this.slides.splice(i,1); if(this.selectedIndex>=this.slides.length)this.selectedIndex=this.slides.length-1; this.selectedLayer=-1; },
                // Auto names ("Slide N", or blank) follow the slide's position so duplicating /
                // reordering / deleting renumbers them 1,2,3… A custom name is shown as-is.
                slideLabel(s,i){ const n=((s&&s.name)||'').trim(); return (!n || /^slide\s+\d+$/i.test(n)) ? ('Slide '+(i+1)) : n; },
                drop(i){ if(this.dragIndex===null||this.dragIndex===i)return; const m=this.slides.splice(this.dragIndex,1)[0]; this.slides.splice(i,0,m); this.selectedIndex=i; this.dragIndex=null; },

                addLayer(t){
                    if(!this.current){ this.addSlide(); }   // no slide yet → create one first
                    if(!this.current) return;
                    const sizes={text:{w:420,h:70},button:{w:190,h:54},image:{w:300,h:220},shape:{w:300,h:200},video:{w:480,h:270},audio:{w:320,h:54},icon:{w:90,h:90},countdown:{w:520,h:90},html:{w:400,h:200}};
                    const sz=sizes[t]||{w:200,h:100};
                    const b={id:this.nextId(),type:t,pos:{desktop:{x:80,y:80,w:sz.w,h:sz.h}},hover:{}};
                    if(t==='text'){b.content='Your text here';b.style={size:40,color:'#ffffff',align:'left',weight:700};}
                    else if(t==='button'){b.content='Click me';b.link='#';b.style={size:16,color:'#ffffff',bg:'#2271b1',radius:6};}
                    else if(t==='image'){b.url='';b.style={};}
                    else if(t==='shape'){b.style={bg:'#3b82f6',radius:8};}
                    else if(t==='video'){b.url='';b.style={};b.video={};}
                    else if(t==='audio'){b.url='';b.style={};}
                    else if(t==='countdown'){b.countdown={to:'',expiredText:'Offer ended'};b.style={size:40,color:'#ffffff',weight:800,align:'left'};}
                    else if(t==='html'){b.content='<div style="color:#fff;font-size:16px">Your HTML here</div>';b.style={};}
                    else if(t==='icon'){b.iconName='star';b.style={color:'#facc15'};}
                    this.current.layers.push(b); this.selectedLayer=this.current.layers.length-1; this.multiSel=[]; this.selectedGroup=null; this.tab='layers';
                },
                addShape(type){ this.shapePicker=false; this.addLayer('shape'); const l=this.current.layers[this.selectedLayer]; l.style=l.style||{}; l.style.shape=type; },
                addButtonStyle(preset){ this.buttonPicker=false; this.addLayer('button'); const l=this.current.layers[this.selectedLayer]; l.style=Object.assign({size:16,radius:6,weight:600}, JSON.parse(JSON.stringify(preset.style))); l.content='Click me'; },
                shapeClip(t){ return ({triangle:'polygon(50% 0,100% 100%,0 100%)','triangle-down':'polygon(0 0,100% 0,50% 100%)',diamond:'polygon(50% 0,100% 50%,50% 100%,0 50%)',pentagon:'polygon(50% 0%,100% 38%,82% 100%,18% 100%,0% 38%)',hexagon:'polygon(25% 0%,75% 0%,100% 50%,75% 100%,25% 100%,0% 50%)',star:'polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)',parallelogram:'polygon(25% 0%,100% 0%,75% 100%,0% 100%)',chevron:'polygon(0 0,75% 0,100% 50%,75% 100%,0 100%,25% 50%)'})[t]||''; },
                shapeStyle(l,hov){ const s=l.style||{}, t=s.shape||'square'; const bg=(hov&&l.hover&&l.hover.bg)?l.hover.bg:(s.bg||'#3b82f6'); let css=`width:100%;height:100%;background:${bg};pointer-events:none;`; if(t==='circle')css+='border-radius:50%;'; else if(t==='square')css+=`border-radius:${s.radius||0}px;`; else { const c=this.shapeClip(t); if(c)css+=`clip-path:${c};`; } return css; },
                // Shape "full width": flag it as a background overlay + snap x=0 / w=design width.
                toggleShapeFull(layer, on){ layer.style = layer.style || {}; layer.style.fullWidth = on; if(on){ const d=this.p(layer); d.x=0; d.w=this.dw; } },
                // A full-width shape is a background overlay → render it behind the other layers (matches frontend).
                layerZ(layer, li){ if(layer.type==='shape' && layer.style && layer.style.fullWidth) return 0; return this.current.layers.length - li; },
                imgLayerStyle(l){ const s=l.style||{}; return `object-fit:${s.fit||'cover'};border-radius:${s.radius||0}px;${l.url?'':'background:#334155'}`; },
                iconStyle(l){ const s=l.style||{}; return `width:100%;height:100%;color:${s.color||'#fff'};pointer-events:none;display:flex;align-items:center;justify-content:center`; },
                phStyle(bg){ return `width:100%;height:100%;background:${bg};color:#94a3b8;display:flex;align-items:center;justify-content:center;pointer-events:none`; },
                ytId(url){ if(!url)return ''; const m=String(url).match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{6,})/); return m?m[1]:''; },
                pickLayerFile(){ this._media(u=>{ if(this.layer)this.layer.url=u; }); },
                deleteLayer(i){ this.current.layers.splice(i,1); this.multiSel=[]; if(this.selectedLayer>=this.current.layers.length)this.selectedLayer=this.current.layers.length-1; },
                // Duplicate a layer in place — clone right after it, nudged 10px so it doesn't hide the original.
                duplicateLayer(i){ if(!this.current||i<0||!this.current.layers[i])return; const c=JSON.parse(JSON.stringify(this.current.layers[i])); c.id=this.nextId(); this.current.layers.splice(i+1,0,c); this.selectedLayer=i+1; const d=this.p(c); if(d){ d.x=(+d.x||0)+10; d.y=(+d.y||0)+10; } this.tab='layers'; },
                moveLayerZ(i,d){ const j=i+d; if(j<0||j>=this.current.layers.length)return; const a=this.current.layers; [a[i],a[j]]=[a[j],a[i]]; this.selectedLayer=j; },
                dropLayer(i){ if(this.layerDrag===null||this.layerDrag===i){ this.layerDrag=null;this.layerDragOver=null; return; } const a=this.current.layers; const m=a.splice(this.layerDrag,1)[0]; a.splice(i,0,m); this.selectedLayer=i; this.layerDrag=null; this.layerDragOver=null; },
                layerIcon(t){ return {text:'title',image:'image',button:'smart_button',shape:'crop_square',video:'smart_display',audio:'music_note',icon:'star'}[t]||'category'; },
                layerLabel(l){ const m={image:'Image',video:'Video',audio:'Audio',shape:'Shape',icon:'Icon'}; return m[l.type] || (l.content||l.type).slice(0,22); },

                positions:[{v:'left top'},{v:'center top'},{v:'right top'},{v:'left center'},{v:'center center'},{v:'right center'},{v:'left bottom'},{v:'center bottom'},{v:'right bottom'}],
                bgSizeCss(bg){ if(bg.size==='contain')return'contain'; if(bg.size==='auto')return'auto'; if(bg.size==='percentage')return (bg.sizeX||100)+'% '+(bg.sizeY||100)+'%'; return 'cover'; },
                bgPosCss(bg){ if(bg.position==='custom')return (bg.posX||50)+'% '+(bg.posY||50)+'%'; return bg.position||'center center'; },
                bgStyle(bg){
                    if(!bg)return'';
                    // Multi-layer: gradient sits ON TOP of the image (overlay); colour is the backdrop.
                    var hasGrad = (bg.gradient||bg.type==='gradient');
                    var css = `background-color:${bg.value||'#1e293b'};`;
                    var imgs=[],sz=[],ps=[],rp=[];
                    if(hasGrad){ imgs.push(`linear-gradient(${bg.angle??135}deg,${bg.from||'#6366f1'},${bg.to||'#ec4899'})`); sz.push('cover'); ps.push('center'); rp.push('no-repeat'); }
                    if(bg.url){ imgs.push(`url('${bg.url}')`); sz.push(this.bgSizeCss(bg)); ps.push(this.bgPosCss(bg)); rp.push(bg.repeat||'no-repeat'); }
                    if(imgs.length){ css += `background-image:${imgs.join(',')};background-size:${sz.join(',')};background-position:${ps.join(',')};background-repeat:${rp.join(',')};`; }
                    return css;
                },
                layerBoxStyle(l){ const d=this.posOf(l); let s=`left:${d.x}px;top:${d.y}px;width:${d.w}px;height:${d.h}px`; if(this.isHiddenDev(l)) s+=';opacity:.25'; return s; },
                typoCss(s){ let c=''; if(s.family)c+=`font-family:'${s.family}',sans-serif;`; if(s.lineHeight)c+=`line-height:${s.lineHeight};`; if(s.letterSpacing!=null&&s.letterSpacing!=='')c+=`letter-spacing:${s.letterSpacing}px;`; if(s.transform&&s.transform!=='none')c+=`text-transform:${s.transform};`; if(s.italic)c+='font-style:italic;'; return c; },
                borderCss(s){ const w=parseFloat(s.borderWidth); if(w>0) return `border:${w}px ${s.borderStyle||'solid'} ${s.borderColor||'#000'};`; if(s.border) return `border:${s.border};`; return ''; },
                strokeCss(s){ const w=parseFloat(s.strokeWidth); return w>0?`-webkit-text-stroke:${w}px ${s.strokeColor||'#000'};`:''; },
                textStyle(l,hov){ const s=l.style||{}; const col=(hov&&l.hover&&l.hover.color)?l.hover.color:(s.color||'#fff'); return `font-size:${this.fontSizeFor(l)||36}px;color:${col};text-align:${s.align||'left'};font-weight:${s.weight||700};line-height:${s.lineHeight||1.15};width:100%;height:100%;overflow:hidden;white-space:pre-line;pointer-events:none;box-sizing:border-box;${s.bg?'background:'+s.bg+';':''}${(+s.padding)?'padding:'+(+s.padding)+'px;':''}${this.borderCss(s)}${(+s.radius)?'border-radius:'+(+s.radius)+'px;':''}${this.strokeCss(s)}${this.typoCss(s)}`; },
                buttonStyle(l, hov){ const s=l.style||{}; const bg=(hov&&s.hoverBg)?s.hoverBg:(s.bg||'#2271b1'); const col=(hov&&s.hoverColor)?s.hoverColor:(s.color||'#fff'); return `display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:${bg};color:${col};font-size:${this.fontSizeFor(l)||16}px;border-radius:${s.radius||6}px;font-weight:${s.weight||600};${this.borderCss(s)}box-sizing:border-box;pointer-events:none;transition:background .2s,color .2s;${this.typoCss(s)}`; },
                // Device-aware font size + Google-fonts list/loader.
                fontSizeFor(l){ const s=l.style||{}, dv=this.device; if(dv==='mobile') return s.sizeMobile||s.sizeTablet||s.size; if(dv==='tablet') return s.sizeTablet||s.size; return s.size; },
                sizeVal(l){ return this.fontSizeFor(l); },
                setFontSize(l,v){ l.style=l.style||{}; v=(v===''||isNaN(v))?'':+v; const dv=this.device; if(dv==='mobile')l.style.sizeMobile=v; else if(dv==='tablet')l.style.sizeTablet=v; else l.style.size=v; },
                loadFont(f){ if(!f)return; const id='fsfont-'+f.replace(/[^a-z0-9]/gi,''); if(document.getElementById(id))return; const l=document.createElement('link'); l.id=id; l.rel='stylesheet'; l.href='https://fonts.googleapis.com/css2?family='+f.replace(/ /g,'+')+':ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&display=swap'; document.head.appendChild(l); },
                fontCatalog: @json($fontCatalog),
                fontCats: @json($fontCats),
                _fontIdx: null,
                // Search the full Google-Fonts catalog (name match), capped for a smooth list.
                filteredFontCatalog(q){ q=(q||'').toLowerCase().trim(); const cat=this.fontCatalog||[]; if(!q) return cat.slice(0,300); const out=[]; for(let i=0;i<cat.length && out.length<300;i++){ if(cat[i][0].toLowerCase().indexOf(q)>=0) out.push(cat[i]); } return out; },
                setFontFamily(layer,name){ if(!layer.style)layer.style={}; layer.style.family=name; if(name){ this.loadFont(name); const ws=this.weightsFor(layer); if(ws.length && ws.indexOf(+layer.style.weight)<0){ layer.style.weight = ws.indexOf(400)>=0?400:ws[Math.floor(ws.length/2)]; } } },
                // Available weights for the layer's font (from the catalog); a full set otherwise.
                weightsFor(layer){ const fam=layer&&layer.style&&layer.style.family; if(fam){ if(!this._fontIdx){ this._fontIdx={}; (this.fontCatalog||[]).forEach(f=>{ this._fontIdx[f[0]]=f; }); } const e=this._fontIdx[fam]; if(e&&e[2]&&e[2].length) return e[2]; } return [300,400,500,600,700,800,900]; },
                weightLabel(w){ return ({100:'Thin',200:'ExtraLight',300:'Light',400:'Regular',500:'Medium',600:'Semibold',700:'Bold',800:'ExtraBold',900:'Black'})[w] || (''+w); },

                startDrag(e,layer,li){
                    e.preventDefault();
                    this.selectedLayer=li; this.tab='layers'; this._dragLayer=layer;
                    if(layer.groupId){ this.selectedGroup=layer.groupId; } else { this.multiSel=[li]; }
                    const d=this.p(layer),sx=e.clientX,sy=e.clientY,ox=d.x,oy=d.y,sc=this.scale||1;
                    const W=this.dw, H=this.dh;
                    // If grouped, drag the whole group: record each member's box + start position.
                    const mates = layer.groupId ? (this.current.layers||[]).filter(l=>l!==layer && l.groupId===layer.groupId).map(l=>{ const md=this.p(l); return {d:md, ox:md.x, oy:md.y}; }) : [];
                    this._move=(ev)=>{
                        var nx=Math.round(ox+(ev.clientX-sx)/sc), ny=Math.round(oy+(ev.clientY-sy)/sc);
                        d.x=Math.max(0, Math.min(nx, Math.max(0, W-d.w)));   // keep the layer inside the canvas
                        d.y=Math.max(0, Math.min(ny, Math.max(0, H-d.h)));
                        this.snap(d);                                        // smart alignment (centre/edge) snap
                        d.x=Math.max(0, Math.min(d.x, Math.max(0, W-d.w)));
                        d.y=Math.max(0, Math.min(d.y, Math.max(0, H-d.h)));
                        var mdx=d.x-ox, mdy=d.y-oy;                          // apply the same delta to group mates
                        mates.forEach(m=>{ m.d.x=Math.max(0,Math.min(m.ox+mdx, Math.max(0,W-m.d.w))); m.d.y=Math.max(0,Math.min(m.oy+mdy, Math.max(0,H-m.d.h))); });
                    };
                    this._begin(e);
                },
                // Snap the dragged box to canvas & sibling centre/edges; records guide lines to draw.
                snap(d){
                    this.guides=[]; const th=6/(this.scale||1), W=this.dw, H=this.dh;
                    let vt=[0,W/2,W], ht=[0,H/2,H];
                    (this.current.layers||[]).forEach(o=>{ if(o===this._dragLayer)return; const p=this.posOf(o); vt.push(p.x,p.x+p.w/2,p.x+p.w); ht.push(p.y,p.y+p.h/2,p.y+p.h); });
                    const vlines=[d.x,d.x+d.w/2,d.x+d.w], hlines=[d.y,d.y+d.h/2,d.y+d.h];
                    let bx=null; vlines.forEach(lv=>vt.forEach(t=>{ const dl=t-lv; if(Math.abs(dl)<=th && (bx===null||Math.abs(dl)<Math.abs(bx.dl))) bx={dl,t}; }));
                    if(bx){ d.x=Math.round(d.x+bx.dl); this.guides.push({type:'v',pos:bx.t}); }
                    let by=null; hlines.forEach(lv=>ht.forEach(t=>{ const dl=t-lv; if(Math.abs(dl)<=th && (by===null||Math.abs(dl)<Math.abs(by.dl))) by={dl,t}; }));
                    if(by){ d.y=Math.round(d.y+by.dl); this.guides.push({type:'h',pos:by.t}); }
                },
                startResize(e,layer){
                    e.preventDefault();
                    const d=this.p(layer),sx=e.clientX,sy=e.clientY,ow=d.w,oh=d.h,sc=this.scale||1;
                    const W=this.dw, H=this.dh;
                    this._move=(ev)=>{
                        var nw=Math.round(ow+(ev.clientX-sx)/sc), nh=Math.round(oh+(ev.clientY-sy)/sc);
                        d.w=Math.max(20, Math.min(nw, W-d.x));   // don't grow past the right edge
                        d.h=Math.max(20, Math.min(nh, H-d.y));   // don't grow past the bottom edge
                    };
                    this._begin(e);
                },
                // Capture the pointer on the dragged element so a fast/off-element move can't
                // "detach" and leave the layer stuck to the cursor. pointercancel + capture
                // release guarantee cleanup even if the browser aborts the gesture.
                _begin(e){
                    const el = e.currentTarget || e.target, pid = e.pointerId;
                    try { if (pid != null && el && el.setPointerCapture) el.setPointerCapture(pid); } catch (_) {}
                    document.body.style.userSelect = 'none';
                    this._up = () => {
                        document.removeEventListener('pointermove', this._move);
                        document.removeEventListener('pointerup', this._up);
                        document.removeEventListener('pointercancel', this._up);
                        document.body.style.userSelect = '';
                        this.guides = []; this._dragLayer = null;
                        try { if (pid != null && el && el.releasePointerCapture) el.releasePointerCapture(pid); } catch (_) {}
                    };
                    document.addEventListener('pointermove', this._move);
                    document.addEventListener('pointerup', this._up);
                    document.addEventListener('pointercancel', this._up);
                },

                pickBackground(){ this._media(u=>{ if(this.current)this.current.bg.url=u; }); },
                pickBackgroundVideo(){ this._media(u=>{ if(this.current)this.current.bg.video=u; }); },
                pickVideoPoster(){ this._media(u=>{ if(this.layer){ this.layer.video=this.layer.video||{}; this.layer.video.poster=u; } }); },
                // Effects (opacity/rotate/blur/shadow) for the canvas preview — mirrors the runtime.
                fxCss(l){ const s=l.style||{}; let c=''; const op=(s.opacity==null||s.opacity==='')?1:Math.max(0,Math.min(1,(+s.opacity)/100)); if(op!==1)c+=`opacity:${op};`; if(+s.rotate)c+=`transform:rotate(${+s.rotate}deg);`; if(+s.blur>0)c+=`filter:blur(${+s.blur}px);`;
                    if(s.shadow&&s.shadow!=='none'){ if(l.type==='text')c+=`text-shadow:${({sm:'0 1px 2px rgba(0,0,0,.5)',md:'0 2px 6px rgba(0,0,0,.55)',lg:'0 4px 14px rgba(0,0,0,.6)'})[s.shadow]||s.shadow};`; else c+=`box-shadow:${({sm:'0 1px 3px rgba(0,0,0,.3)',md:'0 6px 18px rgba(0,0,0,.35)',lg:'0 14px 40px rgba(0,0,0,.45)'})[s.shadow]||s.shadow};`; } return c; },
                // Hover styles for the canvas preview (applied while the layer is hovered) — mirrors the runtime.
                fxHover(l){ const h=l.hover||{}, s=l.style||{}; let c=''; const rot=+s.rotate||0, sc=+h.scale||1;
                    if(sc!==1)c+=`transform:${rot?`rotate(${rot}deg) `:''}scale(${sc});`;
                    if(h.opacity!=null&&h.opacity!=='')c+=`opacity:${Math.max(0,Math.min(1,(+h.opacity)/100))};`;
                    if(h.shadow&&h.shadow!=='none'){ if(l.type==='text')c+=`text-shadow:${({sm:'0 1px 2px rgba(0,0,0,.5)',md:'0 2px 6px rgba(0,0,0,.55)',lg:'0 4px 14px rgba(0,0,0,.6)'})[h.shadow]||h.shadow};`; else c+=`box-shadow:${({sm:'0 1px 3px rgba(0,0,0,.3)',md:'0 6px 18px rgba(0,0,0,.35)',lg:'0 14px 40px rgba(0,0,0,.45)'})[h.shadow]||h.shadow};`; } return c; },
                isEmbedUrl(u){ return /youtube\.com|youtu\.be|vimeo\.com/i.test(u||''); },
                // Static countdown value for the canvas preview (the frontend ticks live).
                cdExpired(l){ const to=(l.countdown&&l.countdown.to)?new Date(l.countdown.to).getTime():0; return to>0 && (to-this.cdNow)<=0; },
                cdPreview(l,u){ const to=(l.countdown&&l.countdown.to)?new Date(l.countdown.to).getTime():0; const diff=to-this.cdNow; if(!to||diff<=0)return '00'; const pad=n=>(n<10?'0':'')+n; if(u==='Days')return pad(Math.floor(diff/86400000)); if(u==='Hours')return pad(Math.floor(diff/3600000)%24); if(u==='Mins')return pad(Math.floor(diff/60000)%60); return pad(Math.floor(diff/1000)%60); },
                overlayStyle(bg){
                    const op = (bg.overlayOpacity==null?100:bg.overlayOpacity)/100;
                    const bgv = bg.overlayType==='color'
                        ? (bg.overlayColor||'#000000')
                        : `linear-gradient(${bg.overlayAngle==null?180:bg.overlayAngle}deg,${bg.overlayFrom||'#000000'},${bg.overlayTo||'rgba(0,0,0,0)'})`;
                    return `background:${bgv};opacity:${op};pointer-events:none`;
                },
                pickLayerImage(){ this._media(u=>{ if(this.layer)this.layer.url=u; }); },
                _media(cb){ if(typeof window.openMediaModal!=='function'){ if(window.showToast)window.showToast('Media picker not available.','error'); return; } window.openMediaModal((att)=>{ let u=att.full_url||att.url||att.path||att.guid||''; if(!u)return; if(u.indexOf('media/')===0)u='/storage/'+u; else if(u.indexOf('http')!==0&&u.indexOf('/')!==0)u='/'+u; cb(u); }); },

                // Premium gate: designing is free, saving needs Pro. Shows an upgrade prompt
                // using the CMS's sweet-alert confirm (the same one used for deletes).
                proSaveAlert(){
                    if(window.falconConfirm){
                        window.falconConfirm({
                            title:'Saving needs Pro',
                            message:'Designing sliders is free — but saving your slider needs an active FalconCMS Pro subscription.',
                            confirmText:'Get Pro',
                            cancelText:'Keep designing',
                        }).then(ok=>{ if(ok) window.open(this.upgradeUrl,'_blank','noopener'); });
                    } else if(window.showToast){ window.showToast('Saving requires a premium subscription','error'); }
                    else { alert('Saving requires a premium subscription.'); }
                },
                async save(){ if(!this.canSave){ this.proSaveAlert(); return; } this.saving=true; const sent=this.serialize(); try{ const r=await fetch(@json(route('admin.sliders.update',$slider)),{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]')?.content||'{{ csrf_token() }}','X-HTTP-Method-Override':'PUT','Accept':'application/json'},body:JSON.stringify({name:this.name,settings:this.settings,slides:this.slides})}); if(r.status===403){ this.canSave=false; this.proSaveAlert(); return; } if(r.ok){ this._savedState=sent; this.dirty=(this.serialize()!==sent); } if(window.showToast)window.showToast(r.ok?'Saved':'Save failed',r.ok?'success':'error'); }catch(e){ if(window.showToast)window.showToast('Save failed','error'); } finally{ this.saving=false; } },
            };
        }
    </script>
    @endpush
</x-falcon-cms::layouts.admin>
