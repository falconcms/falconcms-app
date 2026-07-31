<style>
    .falcon-slider .fs-rt-wrap { position:relative; width:100%; overflow:hidden; }
    .falcon-slider .fs-rt-design { position:absolute; top:0; left:0; transform-origin:top left; }
    .falcon-slider .fs-rt-slide { position:absolute; inset:0; }
    .falcon-slider .fs-rt-arrow { position:absolute; z-index:6; border:0; color:#fff; cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1; transition:background .2s,filter .2s; }
    .falcon-slider .fs-rt-arrow.st-chevron, .falcon-slider .fs-rt-arrow.st-arrow, .falcon-slider .fs-rt-arrow.st-circle { width:44px; height:44px; border-radius:50%; background:rgba(0,0,0,.4); font-size:22px; }
    .falcon-slider .fs-rt-arrow.st-square { width:44px; height:44px; border-radius:6px; background:rgba(0,0,0,.4); font-size:22px; }
    .falcon-slider .fs-rt-arrow.st-minimal { width:auto; height:auto; background:transparent; font-size:38px; text-shadow:0 1px 4px rgba(0,0,0,.5); }
    .falcon-slider .fs-rt-arrow.st-pill { width:56px; height:36px; border-radius:20px; background:rgba(0,0,0,.4); font-size:20px; }
    .falcon-slider .fs-rt-arrow.st-double { width:44px; height:44px; border-radius:50%; background:rgba(0,0,0,.4); font-size:20px; }
    .falcon-slider .fs-rt-arrow.st-outline { width:44px; height:44px; border-radius:50%; background:transparent; border:2px solid rgba(255,255,255,.75); font-size:20px; }
    .falcon-slider .fs-rt-arrow.st-glass { width:44px; height:44px; border-radius:50%; background:rgba(255,255,255,.14); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); border:1px solid rgba(255,255,255,.28); font-size:20px; }
    .falcon-slider .fs-rt-arrow.st-soft { width:44px; height:44px; border-radius:50%; background:#fff; color:#111827; font-size:20px; box-shadow:0 2px 10px rgba(0,0,0,.25); }
    .falcon-slider .fs-rt-arrow:hover { filter:brightness(1.25); }
    .falcon-slider .fs-rt-arrow.st-chevron:hover, .falcon-slider .fs-rt-arrow.st-arrow:hover, .falcon-slider .fs-rt-arrow.st-circle:hover, .falcon-slider .fs-rt-arrow.st-square:hover, .falcon-slider .fs-rt-arrow.st-pill:hover, .falcon-slider .fs-rt-arrow.st-double:hover { background:rgba(0,0,0,.65); }
    .falcon-slider .fs-rt-arrow.st-outline:hover { background:rgba(255,255,255,.18); }
    .falcon-slider .fs-rt-arrow.st-glass:hover { background:rgba(255,255,255,.28); }
    .falcon-slider .fs-rt-arrow.st-soft:hover { background:#e2e8f0; }
    .falcon-slider .fs-rt-dots { position:absolute; z-index:6; display:flex; gap:8px; align-items:center; }
    .falcon-slider .fs-rt-dot { border:0; cursor:pointer; padding:0; background:rgba(255,255,255,.5); transition:background .2s,transform .2s; }
    .falcon-slider .fs-rt-dot.st-dots { width:11px; height:11px; border-radius:50%; }
    .falcon-slider .fs-rt-dot.st-bars { width:24px; height:6px; border-radius:3px; }
    .falcon-slider .fs-rt-dot.st-numbers { width:26px; height:26px; border-radius:50%; color:#fff; font-size:12px; display:flex; align-items:center; justify-content:center; }
    .falcon-slider .fs-rt-dot.st-ring { width:12px; height:12px; border-radius:50%; background:transparent; border:2px solid rgba(255,255,255,.75); }
    .falcon-slider .fs-rt-dot.st-ring.active { background:#fff; border-color:#fff; }
    .falcon-slider .fs-rt-dot.st-square { width:10px; height:10px; border-radius:2px; }
    .falcon-slider .fs-rt-dot.st-pills { width:14px; height:6px; border-radius:3px; transition:width .25s ease, background .25s; }
    .falcon-slider .fs-rt-dot.st-pills.active { width:30px; background:#fff; }
    .falcon-slider .fs-rt-dots.fs-rt-thumbs { gap:6px; }
    .falcon-slider .fs-rt-dot.st-thumbnails { width:56px; height:32px; border-radius:5px; background-color:#334155; background-size:cover; background-position:center; opacity:.55; border:2px solid transparent; box-shadow:0 2px 6px rgba(0,0,0,.3); transition:opacity .2s,border-color .2s,transform .2s; }
    .falcon-slider .fs-rt-dot.st-thumbnails:hover { opacity:.85; }
    .falcon-slider .fs-rt-dot.st-thumbnails.active { opacity:1; border-color:#fff; transform:translateY(-2px); }
    .falcon-slider .fs-rt-dot.active { background:#fff; }
    .falcon-slider .fs-tw-cursor { display:inline-block; margin-left:1px; font-weight:400; animation:fs-blink 1s step-end infinite; }
    @keyframes fs-blink { 0%,100%{opacity:1} 50%{opacity:0} }
    /* Accessibility — visible keyboard focus on interactive controls */
    .falcon-slider .fs-rt-arrow:focus-visible, .falcon-slider .fs-rt-dot:focus-visible, .falcon-slider .fs-rt-wrap:focus-visible { outline:3px solid #4a9eff; outline-offset:2px; }
    {{-- Reduced-motion is now handled in JS (per-slider, so a slider can opt out via
         settings.ignoreReducedMotion) — no blanket CSS kill-switch. --}}
    .falcon-slider .fs-rt-dot.st-numbers.active { background:#fff; color:#111; }
    .falcon-slider .fs-rt-progress { position:absolute; left:0; right:0; z-index:6; background:rgba(255,255,255,.25); overflow:hidden; }
    .falcon-slider .fs-rt-progress-bar { height:100%; width:0; }
    /* spinner / preloader (shared with the editor preview) */
    .falcon-slider .fs-spinner { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(15,23,42,.85); z-index:20; }
    .falcon-slider .fs-sp-box { position:relative; display:flex; align-items:center; justify-content:center; gap:6px; }
    .falcon-slider .fs-spinner.sp-1 i { width:40px; height:40px; border:4px solid rgba(255,255,255,.25); border-top-color:var(--sp-color,#fff); border-radius:50%; animation:fs-spin .8s linear infinite; }
    .falcon-slider .fs-spinner.sp-2 i { width:44px; height:44px; border:4px solid transparent; border-top-color:var(--sp-color,#fff); border-bottom-color:var(--sp-color,#fff); border-radius:50%; animation:fs-spin 1s linear infinite; }
    .falcon-slider .fs-spinner.sp-3 i { width:12px; height:12px; border-radius:50%; background:var(--sp-color,#fff); animation:fs-bounce .6s infinite alternate; }
    .falcon-slider .fs-spinner.sp-3 i:nth-child(2){ animation-delay:.2s } .falcon-slider .fs-spinner.sp-3 i:nth-child(3){ animation-delay:.4s }
    .falcon-slider .fs-spinner.sp-4 i { width:42px; height:42px; border-radius:50%; background:var(--sp-color,#fff); animation:fs-pulse 1s ease-in-out infinite; }
    .falcon-slider .fs-spinner.sp-5 .fs-sp-box { align-items:flex-end; gap:5px; height:32px; }
    .falcon-slider .fs-spinner.sp-5 i { width:6px; height:30px; background:var(--sp-color,#fff); animation:fs-bars .9s ease-in-out infinite; }
    .falcon-slider .fs-spinner.sp-5 i:nth-child(2){animation-delay:.15s}.falcon-slider .fs-spinner.sp-5 i:nth-child(3){animation-delay:.3s}.falcon-slider .fs-spinner.sp-5 i:nth-child(4){animation-delay:.45s}.falcon-slider .fs-spinner.sp-5 i:nth-child(5){animation-delay:.6s}
    .falcon-slider .fs-spinner.sp-6 .fs-sp-box { width:48px; height:48px; }
    .falcon-slider .fs-spinner.sp-6 i { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); border:4px solid var(--sp-color,#fff); border-radius:50%; animation:fs-ripple 1.3s ease-out infinite; }
    .falcon-slider .fs-spinner.sp-6 i:nth-child(2){ animation-delay:-.65s }
    @keyframes fs-spin { to { transform:rotate(360deg) } }
    @keyframes fs-bounce { to { transform:translateY(-14px) } }
    @keyframes fs-pulse { 0%,100% { transform:scale(.5); opacity:.5 } 50% { transform:scale(1); opacity:1 } }
    @keyframes fs-bars { 0%,100% { transform:scaleY(.4) } 50% { transform:scaleY(1) } }
    @keyframes fs-ripple { 0% { width:6px; height:6px; opacity:1 } 100% { width:48px; height:48px; opacity:0 } }
    /* loop / idle motion — uses individual transform props so it composes with the WAAPI in-animation */
    .falcon-slider .fs-rt-layer.fs-loop-float { animation: fs-loop-float 3s ease-in-out infinite; }
    .falcon-slider .fs-rt-layer.fs-loop-bob   { animation: fs-loop-bob 2s ease-in-out infinite; }
    .falcon-slider .fs-rt-layer.fs-loop-pulse { animation: fs-loop-pulse 2s ease-in-out infinite; }
    .falcon-slider .fs-rt-layer.fs-loop-sway  { animation: fs-loop-sway 3.5s ease-in-out infinite; }
    .falcon-slider .fs-rt-layer.fs-loop-spin  { animation: fs-loop-spin 8s linear infinite; }
    @keyframes fs-loop-float { 50% { translate: 0 -12px; } }
    @keyframes fs-loop-bob   { 50% { translate: 0 8px; } }
    @keyframes fs-loop-pulse { 50% { scale: 1.06; } }
    @keyframes fs-loop-sway  { 0%,100% { rotate: -3deg; } 50% { rotate: 3deg; } }
    @keyframes fs-loop-spin  { to { rotate: 360deg; } }
</style>
<script>
(function () {
    // Emitted once per slider; the heavy setup runs only the first time, later copies
    // just (re)boot to catch any sliders that appeared after the first script ran.
    if (window.__falconSliderRT) { if (window.FalconSliderBoot) window.FalconSliderBoot(); return; }
    window.__falconSliderRT = 1;

    // Honour the OS "reduce motion" setting — entrance/loop/parallax/ken-burns are skipped.
    var FS_REDUCED = !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    // Effective reduced-motion flag — the OS preference UNLESS a slider opts out
    // (settings.ignoreReducedMotion). Set per-slider in boot(); defaults to the OS value.
    var FS_RM = FS_REDUCED;

    // Responsive: each device is its own aspect-locked canvas; viewport width picks the device.
    function deviceFor(w) { return w <= 767 ? 'mobile' : (w <= 1024 ? 'tablet' : 'desktop'); }
    function deviceDims(dev, S) {
        if (dev === 'tablet') return { dw:(S.tablet_width||768), dh:(S.tablet_height||S.height||600) };
        if (dev === 'mobile') return { dw:(S.mobile_width||480), dh:(S.mobile_height||S.height||600) };
        return { dw:(S.width||1200), dh:(S.height||600) }; }
    // Layers keep their DESKTOP size; a device just re-arranges within a narrower canvas.
    function resolvePos(ly, dev) { var P = (ly && ly.pos) || {}; return P[dev] || (dev === 'mobile' && P.tablet) || P.desktop || { x:0, y:0, w:200, h:60 }; }
    function isHidden(ly, dev) { return !!(ly && ly.hidden && ly.hidden[dev]); }
    // Typography (font family + text styling); font-size is applied per device in repositionLayers.
    function applyTypo(node, s) {
        if (s.family) node.style.fontFamily = "'" + s.family + "', sans-serif";
        if (s.letterSpacing != null && s.letterSpacing !== '') node.style.letterSpacing = s.letterSpacing + 'px';
        if (s.transform && s.transform !== 'none') node.style.textTransform = s.transform;
        if (s.italic) node.style.fontStyle = 'italic';
    }
    // Composed border from structured fields (borderWidth/Style/Color), with a fallback
    // to the legacy raw `border` string. Returns '' when there's no border.
    function borderCss(s) {
        var w = parseFloat(s.borderWidth);
        if (w > 0) return w + 'px ' + (s.borderStyle || 'solid') + ' ' + (s.borderColor || '#000');
        if (s.border) return s.border;
        return '';
    }
    function fontSizeFor(ly, dev) { var s = ly.style || {}; if (dev === 'mobile') return s.sizeMobile || s.sizeTablet || s.size; if (dev === 'tablet') return s.sizeTablet || s.size; return s.size; }
    function shapeClip(t) { return ({
        triangle:'polygon(50% 0,100% 100%,0 100%)', 'triangle-down':'polygon(0 0,100% 0,50% 100%)',
        diamond:'polygon(50% 0,100% 50%,50% 100%,0 50%)', pentagon:'polygon(50% 0%,100% 38%,82% 100%,18% 100%,0% 38%)',
        hexagon:'polygon(25% 0%,75% 0%,100% 50%,75% 100%,25% 100%,0% 50%)',
        star:'polygon(50% 0%,61% 35%,98% 35%,68% 57%,79% 91%,50% 70%,21% 91%,32% 57%,2% 35%,39% 35%)',
        parallelogram:'polygon(25% 0%,100% 0%,75% 100%,0% 100%)', chevron:'polygon(0 0,75% 0,100% 50%,75% 100%,0 100%,25% 50%)'
    })[t] || ''; }

    var PRESETS = { none:{}, fade:{opacity:0}, 'from-bottom':{opacity:0,y:60}, 'from-top':{opacity:0,y:-60},
                    'from-left':{opacity:0,x:-80}, 'from-right':{opacity:0,x:80}, 'zoom-in':{opacity:0,scale:0.8}, 'zoom-out':{opacity:0,scale:1.2},
                    'from-bottom-left':{opacity:0,x:-70,y:70}, 'from-top-right':{opacity:0,x:70,y:-70},
                    'rotate-in':{opacity:0,rotate:-160,scale:0.4}, 'flip-x':{opacity:0,rotateX:90}, 'flip-y':{opacity:0,rotateY:90},
                    'blur-in':{opacity:0,blur:16}, 'zoom-blur':{opacity:0,scale:1.5,blur:12}, 'skew-in':{opacity:0,x:-90,skewX:14} };
    // Build the "from" keyframe (opacity + transform + filter) for any preset.
    function fsAnimFrom(f){
        var tx=f.x||0, ty=f.y||0, s=(f.scale==null?1:f.scale), r=f.rotate||0, rx=f.rotateX||0, ry=f.rotateY||0, sk=f.skewX||0;
        var tf=((rx||ry)?'perspective(800px) ':'')+'translate('+tx+'px,'+ty+'px) scale('+s+') rotate('+r+'deg)'
             +(rx?' rotateX('+rx+'deg)':'')+(ry?' rotateY('+ry+'deg)':'')+(sk?' skewX('+sk+'deg)':'');
        return { opacity:(f.opacity==null?1:f.opacity), transform:tf, filter:(f.blur?'blur('+f.blur+'px)':'none') };
    }

    function boxShadowFor(v){ return ({ sm:'0 1px 3px rgba(0,0,0,.3)', md:'0 6px 18px rgba(0,0,0,.35)', lg:'0 14px 40px rgba(0,0,0,.45)' })[v] || v; }
    function textShadowFor(v){ return ({ sm:'0 1px 2px rgba(0,0,0,.5)', md:'0 2px 6px rgba(0,0,0,.55)', lg:'0 4px 14px rgba(0,0,0,.6)' })[v] || v; }

    // Split text into an array of pieces for char/word reveals (whitespace preserved).
    function splitText(str, mode) {
        if (mode === 'words') return (str || '').split(/(\s+)/);
        return (str || '').split('');
    }

    function buildLayer(ly) {
        var d = (ly.pos && ly.pos.desktop) || { x:0, y:0, w:200, h:60 };
        // SEO: text layers can render as a real heading (h1–h6) or paragraph.
        var elTag = (ly.type === 'text' && /^(h[1-6]|p)$/.test(ly.tag || '')) ? ly.tag : 'div';
        var el = document.createElement(elTag);
        el.className = 'fs-rt-layer';
        el.__ly = ly;   // keep the config so applyDevice() can reposition/hide per breakpoint
        el.style.cssText = 'position:absolute;margin:0;left:' + d.x + 'px;top:' + d.y + 'px;width:' + d.w + 'px;height:' + d.h + 'px;';
        el.__anim = (ly.anim && ly.anim.in) || null;
        el.__animOut = (ly.anim && ly.anim.out) || null;
        var loop = (ly.anim && ly.anim.loop) || 'none';
        if (loop && loop !== 'none' && !FS_RM) el.classList.add('fs-loop-' + loop);   // no idle loops under reduced-motion
        var s = ly.style || {};
        if (ly.type === 'text') {
            var reveal = ly.reveal || 'none';
            if (reveal === 'chars' || reveal === 'words') {
                var spans = [];
                splitText(ly.content, reveal).forEach(function (ch) {
                    if (ch === '') return;
                    if (/^\s+$/.test(ch)) { el.appendChild(document.createTextNode(ch)); return; }
                    var sp = document.createElement('span');
                    sp.textContent = ch; sp.style.cssText = 'display:inline-block;opacity:0;white-space:pre;will-change:transform,opacity;';
                    el.appendChild(sp); spans.push(sp);
                });
                el.__reveal = function () {
                    spans.forEach(function (sp, ci) {
                        if (FS_RM || !sp.animate) { sp.style.opacity = 1; sp.style.transform = 'none'; return; }
                        sp.animate([{ opacity:0, transform:'translateY(.4em)' }, { opacity:1, transform:'none' }],
                            { delay: ci * (reveal === 'words' ? 90 : 35), duration:450, easing:'cubic-bezier(.2,.7,.2,1)', fill:'both' });
                    });
                };
            } else if (reveal === 'typewriter') {
                var _full = ly.content || '';
                var _cur = document.createElement('span'); _cur.className = 'fs-tw-cursor'; _cur.textContent = '|';
                el.__reveal = function () {
                    if (FS_RM) { el.textContent = _full; return; }
                    el.textContent = ''; var tn = document.createTextNode(''); el.appendChild(tn); el.appendChild(_cur);
                    var i = 0; if (el.__twiv) clearInterval(el.__twiv);
                    el.__twiv = setInterval(function () { if (i >= _full.length) { clearInterval(el.__twiv); return; } tn.textContent += _full.charAt(i++); }, 55);
                };
            } else {
                el.textContent = ly.content || '';
            }
            el.style.fontSize = (s.size || 36) + 'px'; el.style.color = s.color || '#fff';
            el.style.textAlign = s.align || 'left'; el.style.fontWeight = s.weight || 700;
            el.style.lineHeight = (s.lineHeight || 1.15); el.style.overflow = 'hidden';
            el.style.whiteSpace = 'pre-line';   // honour explicit line breaks in the content
            applyTypo(el, s);
            if (s.bg) el.style.background = s.bg;
            if (s.padding) { el.style.padding = (parseFloat(s.padding) || 0) + 'px'; el.style.boxSizing = 'border-box'; }
            if (parseFloat(s.strokeWidth) > 0) {
                var _stk = parseFloat(s.strokeWidth) + 'px ' + (s.strokeColor || '#000');
                el.style.webkitTextStroke = _stk;
                // reveal split spans don't inherit stroke-width — apply to each.
                el.querySelectorAll('span').forEach(function (sp) { sp.style.webkitTextStroke = _stk; });
            }
            var _tb = borderCss(s);
            if (_tb) { el.style.border = _tb; el.style.boxSizing = 'border-box'; }
            if (s.radius) el.style.borderRadius = (parseFloat(s.radius) || 0) + 'px';
        } else if (ly.type === 'button') {
            var a = document.createElement('a');
            a.href = ly.link || '#'; a.textContent = ly.content || '';
            if (ly.linkNewTab) { a.target = '_blank'; a.rel = 'noopener noreferrer'; }
            var nb = s.bg || '#2271b1', nc = s.color || '#fff';
            var _bb = borderCss(s);
            a.style.cssText = 'display:flex;align-items:center;justify-content:center;width:100%;height:100%;box-sizing:border-box;text-decoration:none;font-weight:' + (s.weight || 600) + ';background:' +
                nb + ';color:' + nc + ';font-size:' + (s.size || 16) + 'px;border-radius:' + (s.radius || 6) + 'px;' + (_bb ? 'border:' + _bb + ';' : '') + 'transition:background .2s,color .2s;';
            applyTypo(a, s);
            if (s.hoverBg || s.hoverColor) {
                a.addEventListener('mouseenter', function () { if (s.hoverBg) a.style.background = s.hoverBg; if (s.hoverColor) a.style.color = s.hoverColor; });
                a.addEventListener('mouseleave', function () { a.style.background = nb; a.style.color = nc; });
            }
            el.appendChild(a);
        } else if (ly.type === 'image' && ly.url) {
            var fit = s.fit || 'cover';
            var isz = fit === 'contain' ? 'contain' : (fit === 'fill' ? '100% 100%' : (fit === 'none' ? 'auto' : 'cover'));
            var _lbg = 'url(' + ly.url + ') center/' + isz + ' no-repeat';   // applied lazily (see loadSlideMedia)
            el.__lazyLoad = function () { el.style.background = _lbg; };
            el.style.borderRadius = (s.radius || 0) + 'px';
        } else if (ly.type === 'shape') {
            el.style.background = s.bg || '#3b82f6';
            var st = s.shape || 'square';
            if (st === 'circle') el.style.borderRadius = '50%';
            else if (st === 'square') el.style.borderRadius = (s.radius || 0) + 'px';
            else { var clip = shapeClip(st); if (clip) el.style.clipPath = clip; }
        } else if (ly.type === 'icon') {
            el.style.color = s.color || '#fff';
            if (ly.iconName) {
                var ic = document.createElement('span');
                ic.className = 'material-symbols-outlined'; ic.textContent = ly.iconName;
                ic.style.cssText = 'display:flex;align-items:center;justify-content:center;width:100%;height:100%;line-height:1;font-size:' + Math.min(d.w, d.h) + 'px;';
                el.appendChild(ic); el.__iconSpan = ic;
            } else {
                el.innerHTML = ly.content || '';
                var svg = el.querySelector('svg'); if (svg) { svg.style.width = '100%'; svg.style.height = '100%'; }
            }
        } else if (ly.type === 'video') {
            var vo = ly.video || {};   // { autoplay, mute, loop, controls, poster }
            var emb = videoEmbed(ly.url);
            if (emb) {
                var vp = [];
                if (vo.autoplay) { vp.push('autoplay=1'); vp.push('mute=1'); vp.push('muted=1'); }   // browsers only autoplay muted
                if (vo.loop) { vp.push('loop=1'); var ym = emb.match(/embed\/([\w-]+)/); if (ym) vp.push('playlist=' + ym[1]); }
                if (vo.controls === false) vp.push('controls=0');
                var f = document.createElement('iframe');
                f.src = emb + (vp.length ? ((emb.indexOf('?') >= 0 ? '&' : '?') + vp.join('&')) : '');
                f.setAttribute('frameborder', '0'); f.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture'); f.allowFullscreen = true;
                f.style.cssText = 'width:100%;height:100%;border:0;'; el.appendChild(f);
            } else if (ly.url) {
                var v = document.createElement('video'); v.src = ly.url;
                v.controls = vo.controls !== false;
                if (vo.autoplay) { v.autoplay = true; v.muted = true; v.setAttribute('muted', ''); v.playsInline = true; v.setAttribute('playsinline', ''); }
                if (vo.mute) { v.muted = true; v.setAttribute('muted', ''); }
                if (vo.loop) v.loop = true;
                if (vo.poster) v.poster = vo.poster;
                v.style.cssText = 'width:100%;height:100%;object-fit:cover;'; el.appendChild(v);
            }
        } else if (ly.type === 'audio' && ly.url) {
            var au = document.createElement('audio'); au.src = ly.url; au.controls = true; au.style.width = '100%'; el.appendChild(au);
        } else if (ly.type === 'countdown') {
            var cd = ly.countdown || {}, cdTarget = cd.to ? new Date(cd.to).getTime() : 0;
            el.style.color = s.color || '#fff'; applyTypo(el, s);
            // Inner flex wrapper — the layer box itself stays block so repositionLayers'
            // `display:''` (for visibility) can't flatten the row into a vertical stack.
            var cdWrap = document.createElement('div');
            cdWrap.style.cssText = 'display:flex;align-items:center;gap:14px;width:100%;height:100%;justify-content:' + (s.align === 'center' ? 'center' : (s.align === 'right' ? 'flex-end' : 'flex-start')) + ';';
            el.appendChild(cdWrap);
            var cdSz = s.size || 40, cdBoxes = {};
            [['d', 'Days'], ['h', 'Hours'], ['m', 'Mins'], ['s', 'Secs']].forEach(function (u) {
                var box = document.createElement('div'); box.style.cssText = 'text-align:center;min-width:' + Math.round(cdSz * 1.5) + 'px;';
                var num = document.createElement('div'); num.style.cssText = 'font-size:' + cdSz + 'px;font-weight:' + (s.weight || 800) + ';line-height:1;';
                var lab = document.createElement('div'); lab.textContent = u[1]; lab.style.cssText = 'font-size:' + Math.max(9, Math.round(cdSz * 0.28)) + 'px;opacity:.7;margin-top:5px;text-transform:uppercase;letter-spacing:1px;';
                box.appendChild(num); box.appendChild(lab); cdWrap.appendChild(box); cdBoxes[u[0]] = num;
            });
            var cdPad = function (n) { return (n < 10 ? '0' : '') + n; };
            var cdTick = function () {
                var diff = cdTarget - Date.now();
                if (diff <= 0) {
                    if (el.__cdiv) clearInterval(el.__cdiv);
                    cdWrap.innerHTML = ''; cdWrap.textContent = cd.expiredText || 'Offer ended';
                    cdWrap.style.color = cd.expiredColor || s.color || '#fff';
                    cdWrap.style.fontSize = (cd.expiredSize || cdSz) + 'px';
                    cdWrap.style.fontWeight = (cd.expiredWeight || s.weight || 800);
                    return;
                }
                cdBoxes.d.textContent = cdPad(Math.floor(diff / 86400000));
                cdBoxes.h.textContent = cdPad(Math.floor(diff / 3600000) % 24);
                cdBoxes.m.textContent = cdPad(Math.floor(diff / 60000) % 60);
                cdBoxes.s.textContent = cdPad(Math.floor(diff / 1000) % 60);
            };
            cdTick(); el.__cdiv = setInterval(cdTick, 1000);
        } else if (ly.type === 'html') {
            el.innerHTML = ly.content || '';   // raw embed / HTML (admin-authored)
        }
        // Styling depth: opacity / rotation / blur become the layer's RESTING state (stored so
        // the in-animation ends here instead of at a flat opacity:1/no-transform). Shadow and a
        // custom class are static and never touched by the animation.
        var op = (s.opacity == null || s.opacity === '') ? 1 : Math.max(0, Math.min(1, (+s.opacity) / 100));
        var rot = +s.rotate || 0, bl = +s.blur || 0;
        el.__rest = { opacity: op, transform: rot ? ('rotate(' + rot + 'deg)') : 'none', filter: bl > 0 ? ('blur(' + bl + 'px)') : 'none', rotate: rot };
        el.style.opacity = op;
        if (bl > 0) el.style.filter = 'blur(' + bl + 'px)';
        // One place composes the layer transform from: parallax offset + hover scale + resting tilt,
        // so mouse-parallax and hover can coexist without fighting each other's transform.
        el.__px = 0; el.__py = 0; el.__hovS = 1;
        el.__parallax = Math.max(0, Math.min(6, +(ly.parallax) || 0));
        el.__applyTf = function () {
            var t = '';
            if (el.__px || el.__py) t += 'translate3d(' + el.__px + 'px,' + el.__py + 'px,0) ';
            if (el.__hovS && el.__hovS !== 1) t += 'scale(' + el.__hovS + ') ';
            if (rot) t += 'rotate(' + rot + 'deg)';
            el.style.transform = t.trim();
        };
        el.__applyTf();
        if (s.shadow && s.shadow !== 'none') {
            if (ly.type === 'text') el.style.textShadow = ({ sm:'0 1px 2px rgba(0,0,0,.5)', md:'0 2px 6px rgba(0,0,0,.55)', lg:'0 4px 14px rgba(0,0,0,.6)' })[s.shadow] || s.shadow;
            else el.style.boxShadow = ({ sm:'0 1px 3px rgba(0,0,0,.3)', md:'0 6px 18px rgba(0,0,0,.35)', lg:'0 14px 40px rgba(0,0,0,.45)' })[s.shadow] || s.shadow;
        }
        if (s.cssClass) { var cc = String(s.cssClass).replace(/[^\w\s-]/g, '').trim(); if (cc) el.className += ' ' + cc; }
        // Hover state (all layer types) — scale / opacity / shadow / colour / background, restored
        // on mouse-leave. Colour+background paint the inner <a> for buttons, the box otherwise.
        var hv = ly.hover || {};
        var hvScale = (hv.scale != null && hv.scale !== '' && +hv.scale !== 1) ? +hv.scale : null;
        var hvOp = (hv.opacity != null && hv.opacity !== '') ? Math.max(0, Math.min(1, (+hv.opacity) / 100)) : null;
        var hvSh = (hv.shadow && hv.shadow !== 'none') ? hv.shadow : null;
        if (hvScale || hvOp != null || hvSh || hv.color || hv.bg) {
            var paint = (ly.type === 'button') ? (el.querySelector('a') || el) : el;
            var baseRot = (el.__rest && el.__rest.rotate) || 0;
            var restTf = (el.__rest && el.__rest.transform !== 'none') ? el.__rest.transform : '';
            var restOp = (el.__rest ? el.__rest.opacity : 1);
            var restBoxSh = el.style.boxShadow || '', restTxtSh = el.style.textShadow || '';
            var restColor = (ly.type === 'button') ? (s.color || '#fff') : (s.color || '');
            var restBg = (ly.type === 'button') ? (s.bg || '#2271b1') : (ly.type === 'shape' ? (s.bg || '') : '');
            el.style.transition = (el.style.transition ? el.style.transition + ',' : '') + 'transform .25s ease, box-shadow .25s ease, opacity .2s ease';
            if (paint !== el) paint.style.transition = (paint.style.transition ? paint.style.transition + ',' : '') + 'color .2s ease, background .2s ease';
            el.style.cursor = el.style.cursor || 'pointer';
            el.addEventListener('mouseenter', function () {
                if (hvScale) { el.__hovS = hvScale; el.__applyTf(); }   // composed with parallax + tilt
                if (hvOp != null) el.style.opacity = hvOp;
                if (hvSh) { if (ly.type === 'text') el.style.textShadow = textShadowFor(hvSh); else el.style.boxShadow = boxShadowFor(hvSh); }
                if (hv.color) paint.style.color = hv.color;
                if (hv.bg) paint.style.background = hv.bg;
            });
            el.addEventListener('mouseleave', function () {
                if (hvScale) { el.__hovS = 1; el.__applyTf(); }
                if (hvOp != null) el.style.opacity = restOp;
                if (hvSh) { if (ly.type === 'text') el.style.textShadow = restTxtSh; else el.style.boxShadow = restBoxSh; }
                if (hv.color) paint.style.color = restColor;
                if (hv.bg) paint.style.background = restBg;
            });
        }
        // Smooth the parallax follow (if not already transitioning transform for hover).
        if (el.__parallax && (el.style.transition || '').indexOf('transform') < 0) {
            el.style.transition = (el.style.transition ? el.style.transition + ',' : '') + 'transform .18s ease-out';
        }
        // Make any non-button layer (text / image / shape / icon) clickable via a full-cover
        // anchor — real link semantics (middle-click, right-click, _blank). Video/audio keep
        // their own controls, so they're never overlaid.
        if (ly.link && ly.type !== 'button' && ly.type !== 'video' && ly.type !== 'audio') {
            var lk = document.createElement('a');
            lk.href = ly.link;
            if (ly.linkNewTab) { lk.target = '_blank'; lk.rel = 'noopener noreferrer'; }
            lk.setAttribute('aria-label', (ly.type === 'text' ? (ly.content || 'link') : 'link'));
            lk.style.cssText = 'position:absolute;inset:0;z-index:2;display:block;';
            el.style.cursor = 'pointer';
            el.appendChild(lk);
        }
        // A layer is "interactive" if clicking it does something (button, its own link,
        // media controls, embedded HTML). Used by the whole-slide link so passive layers
        // (plain text/images/shapes) let clicks fall through to the slide's link anchor.
        el.__interactive = (ly.type === 'button' || ly.type === 'video' || ly.type === 'audio' || ly.type === 'html' || !!ly.link);
        return el;
    }

    function videoEmbed(url) {
        if (!url) return null;
        var yt = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{6,})/);
        if (yt) return 'https://www.youtube.com/embed/' + yt[1] + '?rel=0&modestbranding=1&playsinline=1';
        var vim = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
        if (vim) return 'https://player.vimeo.com/video/' + vim[1];
        return null; // treat as a direct video file
    }

    function playLayer(el) {
        var R = el.__rest || { opacity:1, transform:'none', filter:'none', rotate:0 };
        // Reduced-motion: no movement/parallax/loops — but layers still gently FADE in (respecting
        // the delay), so the slider isn't dead-static. Reveals show their final text at once.
        if (FS_RM) {
            el.style.transform = R.transform; el.style.filter = R.filter;
            if (el.__reveal) { el.style.opacity = R.opacity; el.__reveal(); return; }
            var ar = el.__anim;
            if (ar && ar.preset && ar.preset !== 'none' && el.animate) {
                el.animate([{ opacity:0 }, { opacity:R.opacity }], { delay:(ar.delay || 0), duration:Math.min(ar.duration || 500, 500), easing:'ease', fill:'both' });
            } else { el.style.opacity = R.opacity; }
            return;
        }
        // Text reveal (typewriter / char / word) IS the entrance — show the box, then run the
        // reveal AFTER the layer's delay (the reveal keeps its content hidden until it fires).
        if (el.__reveal) {
            el.style.opacity = R.opacity; el.style.transform = R.transform; el.style.filter = R.filter;
            var rd = (el.__anim && el.__anim.delay) || 0;
            if (el.__revealT) clearTimeout(el.__revealT);
            if (rd > 0) el.__revealT = setTimeout(function () { el.__reveal(); }, rd); else el.__reveal();
            return;
        }
        var a = el.__anim, from = a ? PRESETS[a.preset] : null;
        if (!a || !from || a.preset === 'none') { el.style.opacity = R.opacity; el.style.transform = R.transform; el.style.filter = R.filter; return; }
        var F = fsAnimFrom(from);
        // Animate from the hidden preset state → the layer's resting state (keeping any tilt/blur).
        var toT = 'translate(0px,0px) scale(1) rotate(' + (R.rotate || 0) + 'deg)';
        var toF = R.filter, fromF = (F.filter !== 'none') ? F.filter : R.filter;
        if (el.animate) {
            var anim = el.animate(
                [{ opacity:F.opacity, transform:F.transform, filter:fromF }, { opacity:R.opacity, transform:toT, filter:toF }],
                { delay:(a.delay||0), duration:(a.duration||700), easing:(a.easing||'ease-out'), fill:'both' }
            );
            // Commit the resting values to inline style then drop the animation, so a hover can
            // override transform/opacity (a filled WAAPI animation otherwise wins the cascade).
            if (anim.finished && anim.finished.then) anim.finished.then(function () { try { anim.commitStyles(); anim.cancel(); } catch (e) {} }).catch(function () {});
        } else { el.style.opacity = R.opacity; }
    }

    function transitionKeyframes(t) {
        switch (t) {
            case 'slide-left':  return [{ transform:'translateX(100%)' },  { transform:'translateX(0)' }];
            case 'slide-right': return [{ transform:'translateX(-100%)' }, { transform:'translateX(0)' }];
            case 'slide-up':    return [{ transform:'translateY(100%)' },  { transform:'translateY(0)' }];
            case 'slide-down':  return [{ transform:'translateY(-100%)' }, { transform:'translateY(0)' }];
            case 'zoom':        return [{ transform:'scale(1.15)', opacity:0 }, { transform:'scale(1)', opacity:1 }];
            case 'zoom-out':    return [{ transform:'scale(0.85)', opacity:0 }, { transform:'scale(1)', opacity:1 }];
            case 'rotate':      return [{ transform:'rotate(7deg) scale(1.1)', opacity:0 }, { transform:'rotate(0) scale(1)', opacity:1 }];
            case 'rotate-ccw':  return [{ transform:'rotate(-7deg) scale(1.1)', opacity:0 }, { transform:'rotate(0) scale(1)', opacity:1 }];
            case 'flip':        return [{ transform:'perspective(1200px) rotateY(90deg)', opacity:0 }, { transform:'perspective(1200px) rotateY(0)', opacity:1 }];
            case 'flip-x':      return [{ transform:'perspective(1200px) rotateX(90deg)', opacity:0 }, { transform:'perspective(1200px) rotateX(0)', opacity:1 }];
            case 'blur':        return [{ filter:'blur(20px)', opacity:0 }, { filter:'blur(0)', opacity:1 }];
            case 'fade-scale':  return [{ transform:'scale(1.06)', opacity:0 }, { transform:'scale(1)', opacity:1 }];
            case 'skew':        return [{ transform:'skewX(14deg) translateX(60px)', opacity:0 }, { transform:'skewX(0) translateX(0)', opacity:1 }];
            case 'glide-up':    return [{ transform:'translateY(48px)', opacity:0 }, { transform:'translateY(0)', opacity:1 }];
            case 'glide-down':  return [{ transform:'translateY(-48px)', opacity:0 }, { transform:'translateY(0)', opacity:1 }];
            case 'roll':        return [{ transform:'translateX(70%) rotate(120deg)', opacity:0 }, { transform:'translateX(0) rotate(0)', opacity:1 }];
            default:            return [{ opacity:0 }, { opacity:1 }]; // fade
        }
    }

    function boot(root) {
        if (root.__fsInit) return; root.__fsInit = true;
        var cfgEl = root.querySelector('.falcon-slider-config'); if (!cfgEl) return;
        var cfg; try { cfg = JSON.parse(cfgEl.textContent); } catch (e) { return; }
        var S = cfg.settings || {}, slides = cfg.slides || [];
        if (!slides.length) return;
        // Animations ALWAYS play by default (editor and front-end match). Accessibility is
        // opt-in: only when settings.respectReducedMotion is on do we honour the OS preference.
        FS_RM = FS_REDUCED && !!S.respectReducedMotion;
        // Device-aware design size — recomputed whenever the breakpoint changes.
        var curDevice = deviceFor(window.innerWidth), dw, dh;
        function setDims() { var D = deviceDims(curDevice, S); dw = D.dw; dh = D.dh; }
        setDims();

        // Layout: Type (slider | scene | carousel) + Sizing (auto | full-width | full-screen).
        var type = S.layout_type || 'slider', sizing = S.sizing || 'auto', isScene = (type === 'scene');
        if (type === 'carousel') { S.autoplay = true; S.loop = true; }

        // Navigation config (falls back to the legacy arrows/dots booleans).
        var N = S.nav || {};
        var nav = {
            arrows:   Object.assign({ on: S.arrows !== false, style:'chevron', pos:'middle', x:16, y:0 }, N.arrows   || {}),
            bullets:  Object.assign({ on: S.dots   !== false, style:'dots',    pos:'bc',     x:0,  y:16 }, N.bullets  || {}),
            progress: Object.assign({ on:false, color:'#ffffff', height:4, pos:'bottom' },                 N.progress || {}),
        };

        root.innerHTML = '';
        if (S.rtl) root.setAttribute('dir', 'rtl');   // right-to-left text for RTL languages
        // Per-slider custom CSS (scoped under this slider's id) + custom JS (admin-authored).
        if (S.customCss) {
            var st0 = document.createElement('style');
            st0.textContent = String(S.customCss).replace(/\$slider/g, '.falcon-slider[data-slider-id="' + (root.getAttribute('data-slider-id') || '') + '"]');
            root.appendChild(st0);
        }
        if (sizing === 'auto') {
            // Exact dimension, centered on the page (left & right auto).
            root.style.maxWidth = dw + 'px'; root.style.width = '100%';
            root.style.marginTop = '0'; root.style.marginBottom = '0';
            root.style.marginLeft = 'auto'; root.style.marginRight = 'auto';
        } else {
            // Full-bleed: break out of the theme's centered container. Sized in JS to the
            // document width (NOT 100vw) so the vertical scrollbar can't spill it over and
            // add a horizontal scrollbar to the page.
            root.style.marginTop = '0'; root.style.marginBottom = '0'; root.style.marginRight = '0';
            applyFullBleed();
        }

        // Break the root out to span the full document width (excludes the scrollbar).
        function applyFullBleed() {
            root.style.maxWidth = 'none'; root.style.marginLeft = '0'; root.style.width = '100%';
            var docW = document.documentElement.clientWidth;
            var left = root.getBoundingClientRect().left;
            root.style.width = docW + 'px';
            root.style.marginLeft = (-left) + 'px';
        }

        var wrap = document.createElement('div'); wrap.className = 'fs-rt-wrap'; root.appendChild(wrap);

        // Spinner / preloader — covers the stage until the first slide's background is ready.
        var spCfg = S.spinner || {}, spStyle = (typeof spCfg === 'string') ? spCfg : (spCfg.style || 'none');
        var spColor = (spCfg && spCfg.color) || '#ffffff', spinner = null;
        if (spStyle && spStyle !== 'none') {
            spinner = document.createElement('div');
            spinner.className = 'fs-spinner sp-' + spStyle;
            spinner.style.setProperty('--sp-color', spColor);
            var n = ({ '1':1, '2':1, '3':3, '4':1, '5':5, '6':2 })[spStyle] || 1, bits = '';
            for (var sp = 0; sp < n; sp++) bits += '<i></i>';
            spinner.innerHTML = '<div class="fs-sp-box">' + bits + '</div>';
            wrap.appendChild(spinner);
        }
        function hideSpinner() {
            if (!spinner) return;
            spinner.style.transition = 'opacity .35s'; spinner.style.opacity = '0';
            setTimeout(function () { if (spinner && spinner.parentNode) spinner.parentNode.removeChild(spinner); spinner = null; }, 380);
        }

        // Two design-sized grids per slide:
        //   • bgGrid   — scaled to FILL the frame (so the background always covers it,
        //                exactly like the editor's cover/position math on the design box).
        //   • layerGrid — scaled to fit but NEVER above 1×, then centred, so text/buttons
        //                never blow up larger than they were designed (matches the editor).
        var bgGrids = [], layerGrids = [], mediaLoaders = [];
        var slideEls = slides.map(function (sl, si) {
            var loaders = [];   // heavy media (bg image, bg video, layer images) — run on demand, not at build
            var el = document.createElement('div'); el.className = 'fs-rt-slide'; el.style.display = 'none';

            var bgGrid = document.createElement('div');
            bgGrid.style.cssText = 'position:absolute;width:' + dw + 'px;height:' + dh + 'px;transform-origin:top left;overflow:hidden;';
            var bg = sl.bg || {}, bgDiv = document.createElement('div'); bgDiv.className = 'fs-bg'; bgDiv.style.cssText = 'position:absolute;inset:0;';
            var hasGrad = (bg.gradient || bg.type === 'gradient');
            bgDiv.style.backgroundColor = bg.value || '#0f172a';
            var imgs = [], sz = [], ps = [], rp = [];
            if (hasGrad) {
                imgs.push('linear-gradient(' + (bg.angle||135) + 'deg,' + (bg.from||'#6366f1') + ',' + (bg.to||'#ec4899') + ')');
                sz.push('cover'); ps.push('center'); rp.push('no-repeat');
            }
            if (bg.url) {
                imgs.push("url('" + bg.url + "')");
                sz.push(bg.size === 'contain' ? 'contain' : (bg.size === 'auto' ? 'auto'
                    : (bg.size === 'percentage' ? ((bg.sizeX||100) + '% ' + (bg.sizeY||100) + '%') : 'cover')));
                ps.push(bg.position === 'custom' ? ((bg.posX||50) + '% ' + (bg.posY||50) + '%') : (bg.position || 'center center'));
                rp.push(bg.repeat || 'no-repeat');
            }
            if (imgs.length) {
                var _bi = imgs.join(','), _bs = sz.join(','), _bp = ps.join(','), _br = rp.join(',');
                // Gradients are cheap (no network) — paint them now; the actual image URL loads lazily.
                if (hasGrad && !bg.url) { bgDiv.style.backgroundImage = _bi; bgDiv.style.backgroundSize = _bs; bgDiv.style.backgroundPosition = _bp; bgDiv.style.backgroundRepeat = _br; }
                else loaders.push(function () { bgDiv.style.backgroundImage = _bi; bgDiv.style.backgroundSize = _bs; bgDiv.style.backgroundPosition = _bp; bgDiv.style.backgroundRepeat = _br; });
            }
            // Background video — sits over the colour/image (the image acts as its poster).
            // Muted + looped so browsers allow autoplay; covers the design box. Deferred so
            // off-screen slides don't download video until they're about to show.
            if (bg.video) loaders.push(function () {
                var vemb = videoEmbed(bg.video);
                if (vemb) {
                    // YouTube / Vimeo: size to COVER the dw×dh box (vs 16:9) and hide the chrome.
                    var vAsp = 16 / 9, iw, ih;
                    if (dw / dh > vAsp) { iw = dw; ih = dw / vAsp; } else { ih = dh; iw = dh * vAsp; }
                    var sep = vemb.indexOf('?') >= 0 ? '&' : '?';
                    var params = 'autoplay=1&mute=1&muted=1&controls=0&loop=1&playsinline=1&showinfo=0&modestbranding=1';
                    var ytm = vemb.match(/youtube\.com\/embed\/([\w-]+)/); if (ytm) params += '&playlist=' + ytm[1]; // YT loop needs playlist=self
                    if (vemb.indexOf('player.vimeo.com') >= 0) params += '&background=1';
                    var vfr = document.createElement('iframe');
                    vfr.src = vemb + sep + params;
                    vfr.setAttribute('frameborder', '0'); vfr.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
                    vfr.style.cssText = 'position:absolute;left:' + ((dw - iw) / 2) + 'px;top:' + ((dh - ih) / 2) + 'px;width:' + iw + 'px;height:' + ih + 'px;border:0;pointer-events:none;';
                    bgDiv.appendChild(vfr);
                } else {
                    var bvid = document.createElement('video');
                    bvid.src = bg.video; bvid.autoplay = true; bvid.loop = true; bvid.muted = true;
                    bvid.setAttribute('muted', ''); bvid.setAttribute('playsinline', ''); bvid.playsInline = true;
                    if (bg.url) bvid.poster = bg.url;
                    bvid.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:' + (bg.videoFit === 'contain' ? 'contain' : 'cover') + ';';
                    var pp = bvid.play && bvid.play(); if (pp && pp.catch) pp.catch(function () {});
                    bgDiv.appendChild(bvid);
                }
            });
            // Colour / gradient overlay — sits ON TOP of the image + video (but under the
            // layers), e.g. to darken a busy video so text stays readable.
            if (bg.overlay) {
                var ov = document.createElement('div');
                var ocss = 'position:absolute;inset:0;pointer-events:none;opacity:' + ((bg.overlayOpacity == null ? 100 : bg.overlayOpacity) / 100) + ';';
                if (bg.overlayType === 'color') ocss += 'background:' + (bg.overlayColor || '#000000') + ';';
                else ocss += 'background:linear-gradient(' + (bg.overlayAngle == null ? 180 : bg.overlayAngle) + 'deg,' + (bg.overlayFrom || '#000000') + ',' + (bg.overlayTo || 'rgba(0,0,0,0)') + ');';
                ov.style.cssText = ocss;
                bgDiv.appendChild(ov);
            }
            bgGrid.appendChild(bgDiv); el.appendChild(bgGrid); bgGrids.push(bgGrid);

            // Whole-slide link: a full-cover anchor beneath the layers. Interactive layers
            // (buttons/links/media) keep their own clicks; empty space + passive layers navigate.
            if (sl.link) {
                var slLink = document.createElement('a');
                slLink.href = sl.link;
                if (sl.linkNewTab) { slLink.target = '_blank'; slLink.rel = 'noopener noreferrer'; }
                slLink.setAttribute('aria-label', 'Open slide link');
                slLink.style.cssText = 'position:absolute;inset:0;display:block;z-index:0;';
                el.appendChild(slLink);
            }

            var layerGrid = document.createElement('div');
            layerGrid.style.cssText = 'position:absolute;width:' + dw + 'px;height:' + dh + 'px;transform-origin:top left;';
            var lyrs = sl.layers || [];
            // Layer groups: fold each group's per-device hide + (optional) staggered group
            // animation onto its member layers before they're built.
            var _gmap = {}; (sl.groups || []).forEach(function (g) { _gmap[g.id] = g; });
            var _gIdx = {};
            lyrs.forEach(function (ly) {
                var g = ly.groupId && _gmap[ly.groupId]; if (!g) return;
                if (g.hidden) { ly.hidden = ly.hidden || {}; ['desktop', 'tablet', 'mobile'].forEach(function (dv) { if (g.hidden[dv]) ly.hidden[dv] = true; }); }
                // The group animation drives every member (with a stagger) whenever the group
                // has one set — the group is authoritative, so an editor default like 'fade'
                // baked onto a member can't suppress it. Group preset 'none' → members keep
                // their own animation.
                var gp = g.anim && g.anim.in && g.anim.in.preset;
                if (gp && gp !== 'none') {
                    var k = _gIdx[ly.groupId] || 0; _gIdx[ly.groupId] = k + 1;
                    ly.anim = ly.anim || {};   // replacing .in keeps any existing .loop (idle loop)
                    ly.anim.in = { preset: gp, delay: (g.anim.in.delay || 0) + k * (g.anim.stagger != null ? g.anim.stagger : 120), duration: g.anim.in.duration || 600, easing: g.anim.in.easing || 'ease-out' };
                }
            });
            lyrs.forEach(function (ly, i) {
                var lyEl = buildLayer(ly);
                if (ly.type === 'shape' && ly.style && ly.style.fullWidth) {
                    // A full-width shape is a background overlay: put it in the bg grid so it
                    // fills the whole frame (like the background) and sits behind the text/buttons.
                    bgGrid.appendChild(lyEl);
                } else {
                    lyEl.style.zIndex = (lyrs.length - i); layerGrid.appendChild(lyEl);
                }
                if (lyEl.__lazyLoad) loaders.push(lyEl.__lazyLoad);   // defer this layer's image
            });
            // Global layers — the same set drawn on top of every slide (logo, watermark, fixed CTA).
            (S.globalLayers || []).forEach(function (gly, gi) {
                var gEl = buildLayer(gly);
                gEl.style.zIndex = 1000 + gi;
                layerGrid.appendChild(gEl);
                if (gEl.__lazyLoad) loaders.push(gEl.__lazyLoad);
            });
            el.appendChild(layerGrid); layerGrids.push(layerGrid);

            // With a whole-slide link, let clicks on passive layers fall through to the
            // slide anchor; only interactive layers keep capturing their own clicks.
            if (sl.link) {
                layerGrid.style.pointerEvents = 'none';
                Array.prototype.forEach.call(layerGrid.children, function (c) {
                    c.style.pointerEvents = c.__interactive ? 'auto' : 'none';
                });
            }

            // Run all of this slide's deferred media once — called on show + for neighbours.
            mediaLoaders[si] = function () { if (el.__mediaLoaded) return; el.__mediaLoaded = true; loaders.forEach(function (f) { f(); }); };

            wrap.appendChild(el);
            return el;
        });
        // Load slide i's heavy media (idempotent). Called for the shown slide + its neighbours.
        function loadSlideMedia(i) { i = ((i % slides.length) + slides.length) % slides.length; if (mediaLoaders[i]) mediaLoaders[i](); }

        function fit() {
            // Height FIRST so the page's vertical scrollbar settles, THEN size the full-bleed
            // width against the real (post-scrollbar) document width. Measuring width before
            // the height is applied is what let a horizontal scrollbar sneak in.
            var cH, bgSc, laySc;
            if (sizing === 'full-screen') cH = window.innerHeight;
            else                          cH = dh;                 // full-width fixed height; auto recomputes below
            wrap.style.height = cH + 'px';
            if (sizing !== 'auto') applyFullBleed();               // recompute full-bleed (scrollbar-safe) after height is set

            var cW = wrap.clientWidth;
            if (sizing === 'full-width')      { bgSc = Math.max(cW/dw, cH/dh);  laySc = Math.min(cW / dw, 1); }
            else if (sizing === 'full-screen'){ bgSc = Math.max(cW/dw, cH/dh);  laySc = Math.min(cW/dw, cH/dh, 1); }
            else                              { bgSc = cW / dw; cH = dh*bgSc; wrap.style.height = cH + 'px'; laySc = bgSc; }
            var bgL=(cW-dw*bgSc)/2, bgT=(cH-dh*bgSc)/2, lL=(cW-dw*laySc)/2, lT=(cH-dh*laySc)/2;
            bgGrids.forEach(function (g) { g.style.transform='scale('+bgSc+')'; g.style.left=bgL+'px'; g.style.top=bgT+'px'; });
            layerGrids.forEach(function (g) { g.style.transform='scale('+laySc+')'; g.style.left=lL+'px'; g.style.top=lT+'px'; });
        }
        // Resize the grids to the current device canvas + position/show-hide each layer for it.
        function resizeGrids() {
            bgGrids.forEach(function (g) { g.style.width=dw+'px'; g.style.height=dh+'px'; });
            layerGrids.forEach(function (g) { g.style.width=dw+'px'; g.style.height=dh+'px'; });
        }
        function repositionLayers(dev) {
            wrap.querySelectorAll('.fs-rt-layer').forEach(function (el) {
                var ly = el.__ly; if (!ly) return;
                var d = resolvePos(ly, dev);
                el.style.left = d.x + 'px'; el.style.top = d.y + 'px'; el.style.width = d.w + 'px'; el.style.height = d.h + 'px';
                el.style.display = isHidden(ly, dev) ? 'none' : '';
                if (ly.type === 'icon' && el.__iconSpan) el.__iconSpan.style.fontSize = Math.min(d.w, d.h) + 'px';   // keep the glyph filling the box
                // Per-device font size for text/button.
                var fz = fontSizeFor(ly, dev);
                if (fz) { if (ly.type === 'text') el.style.fontSize = fz + 'px'; else if (ly.type === 'button') { var a = el.querySelector('a'); if (a) a.style.fontSize = fz + 'px'; } }
            });
        }
        function onResize() {
            var dev = deviceFor(window.innerWidth);
            if (dev !== curDevice) { curDevice = dev; setDims(); resizeGrids(); repositionLayers(dev); }
            fit();
        }
        resizeGrids(); repositionLayers(curDevice); fit();
        window.addEventListener('resize', onResize);

        var idx = -1, timer = null, paused = false, dots = [], progBar = null, slideStart = 0, remaining = 0;
        function slideDelay(sl) { var d = Number(sl && sl.duration); return d > 0 ? d : (Number(S.autoplay_delay) || 6000); }
        function transDur(sl) { var d = Number(sl && sl.transition_duration); return d > 0 ? d : (Number(S.transition_duration) || 700); }
        function kenburnsFrames(k) {
            switch (k) {
                case 'zoom-in':   return [{ transform:'scale(1)' },               { transform:'scale(1.18)' }];
                case 'zoom-out':  return [{ transform:'scale(1.18)' },            { transform:'scale(1)' }];
                case 'pan-left':  return [{ transform:'scale(1.18) translateX(3%)' },  { transform:'scale(1.18) translateX(-3%)' }];
                case 'pan-right': return [{ transform:'scale(1.18) translateX(-3%)' }, { transform:'scale(1.18) translateX(3%)' }];
                case 'pan-up':    return [{ transform:'scale(1.18) translateY(3%)' },  { transform:'scale(1.18) translateY(-3%)' }];
                case 'zoom-pan':  return [{ transform:'scale(1) translate(0,0)' },     { transform:'scale(1.2) translate(-3%,-2%)' }];
                default: return null;
            }
        }
        function autoplayOn() { return !isScene && S.autoplay !== false && slides.length > 1; }

        // Play each layer's OUT animation on the leaving slide, then hide it.
        function playSlideOut(el) {
            var maxT = 0;
            el.querySelectorAll('.fs-rt-layer').forEach(function (l) {
                var a = l.__animOut; if (!a || !a.preset || a.preset === 'none' || !PRESETS[a.preset] || !l.animate) return;
                var F = fsAnimFrom(PRESETS[a.preset]);
                var R = l.__rest || { opacity:1, transform:'none', filter:'none', rotate:0 };
                var fromT = 'translate(0px,0px) scale(1) rotate(' + (R.rotate || 0) + 'deg)';
                l.animate([{ opacity:R.opacity, transform:fromT, filter:R.filter }, { opacity:F.opacity, transform:F.transform, filter:F.filter }],
                    { delay:(a.delay||0), duration:(a.duration||500), easing:(a.easing||'ease-in'), fill:'both' });
                var t = (a.delay||0) + (a.duration||500); if (t > maxT) maxT = t;
            });
            if (maxT) setTimeout(function () { if (el !== slideEls[idx]) el.style.display = 'none'; }, maxT + 40);
            else el.style.display = 'none';
            return maxT;
        }

        // Longest OUT animation across a slide's layers (0 if none has an out set).
        function slideOutMax(el) {
            var m = 0;
            el.querySelectorAll('.fs-rt-layer').forEach(function (l) {
                var a = l.__animOut; if (!a || !a.preset || a.preset === 'none' || !PRESETS[a.preset]) return;
                var t = (a.delay || 0) + (a.duration || 500); if (t > m) m = t;
            });
            return m;
        }

        var transitioning = false;
        function show(i) {
            i = (i + slides.length) % slides.length;
            if (transitioning) return;                       // ignore input while a sequenced out is playing
            var prev = idx;
            var prevEl = (prev >= 0 && prev !== i) ? slideEls[prev] : null;
            // If the leaving slide has OUT animations, they were invisible before because the
            // incoming slide covered them instantly. Keep the leaving slide ON TOP, play the
            // out, THEN reveal the next one underneath — so the out actually shows.
            var outMax = prevEl ? slideOutMax(prevEl) : 0;

            function reveal() {
                transitioning = false;
                if (prevEl) prevEl.style.zIndex = 1;         // drop the leaving slide below the incoming one
                loadSlideMedia(i); loadSlideMedia(i + 1); loadSlideMedia(i - 1);   // this slide + neighbours
                var el = slideEls[i], sl = slides[i];
                el.style.display = 'block'; el.style.zIndex = 2;
                slideEls.forEach(function (e2, k) { if (k !== i && k !== prev) { e2.style.display = 'none'; e2.style.zIndex = 0; } });
                idx = i;
                // Fill any letterbox gap (e.g. full-screen on tall designs) with the slide's colour.
                wrap.style.backgroundColor = (sl.bg && sl.bg.value) || '#0f172a';
                if (!FS_RM && el.animate) el.animate(transitionKeyframes(sl.transition || S.transition), { duration:transDur(sl), easing:'ease', fill:'both' });
                el.style.opacity = 1;
                // Ken Burns — slowly zoom/pan the background over the time the slide is shown (off under reduced-motion).
                var kbFrames = FS_RM ? null : kenburnsFrames((sl.bg && sl.bg.kenburns) || 'none'), bgd = el.querySelector('.fs-bg');
                if (kbFrames && bgd && bgd.animate) bgd.animate(kbFrames, { duration: slideDelay(sl) + 900, easing:'ease-out', fill:'both' });
                // Restart the slide's background video (if any) from the top.
                var bv = el.querySelector('.fs-bg video'); if (bv) { try { bv.currentTime = 0; var pr = bv.play(); if (pr && pr.catch) pr.catch(function(){}); } catch (e) {} }
                el.querySelectorAll('.fs-rt-layer').forEach(playLayer);
                dots.forEach(function (d, k) { d.classList.toggle('active', k === i); });
                // Start this slide's autoplay countdown + progress fill from the full delay.
                remaining = slideDelay(sl); slideStart = Date.now();
                fillProgress(remaining, true);
                if (timer) clearTimeout(timer);
                if (autoplayOn() && !paused) timer = setTimeout(function () { show(idx + 1); }, remaining);
            }

            if (prevEl && outMax > 0) {
                transitioning = true;
                prevEl.style.zIndex = 3;                     // above the incoming slide so its out is visible
                playSlideOut(prevEl);
                if (timer) clearTimeout(timer);              // hold autoplay until the out finishes
                setTimeout(reveal, Math.min(outMax, 1200));
            } else {
                if (prevEl) playSlideOut(prevEl);
                reveal();
            }
        }

        // Progress bar — animate width over `dur` ms; `reset` restarts from 0.
        function fillProgress(dur, reset) {
            if (!progBar) return;
            if (reset) { progBar.style.transition = 'none'; progBar.style.width = '0%'; void progBar.offsetWidth; }
            if (autoplayOn()) { progBar.style.transition = 'width ' + Math.max(0, dur) + 'ms linear'; progBar.style.width = '100%'; }
        }
        // Pause on hover — freeze the countdown + progress, remembering the time left.
        function pauseAutoplay() {
            if (paused) return; paused = true;
            if (timer) { clearTimeout(timer); timer = null; }
            if (autoplayOn()) remaining = Math.max(0, remaining - (Date.now() - slideStart));
            if (progBar) { var w = getComputedStyle(progBar).width; progBar.style.transition = 'none'; progBar.style.width = w; }
        }
        function resumeAutoplay() {
            if (!paused) return; paused = false;
            if (!autoplayOn()) return;
            slideStart = Date.now();
            void (progBar && progBar.offsetWidth);
            fillProgress(remaining, false);
            timer = setTimeout(function () { show(idx + 1); }, remaining);
        }

        // ---- Navigation (a Scene is a single, static slide — no arrows/dots) ----
        // vpos: top|middle|bottom for arrows; pos: 2-char (t/m/b + l/c/r) for bullets.
        function placeArrow(elm, side, vpos, x, y) {
            var tf = '';
            if (vpos === 'top') elm.style.top = y + 'px';
            else if (vpos === 'bottom') elm.style.bottom = y + 'px';
            else { elm.style.top = '50%'; tf = 'translateY(-50%)'; if (y) elm.style.marginTop = y + 'px'; }
            elm.style[side] = x + 'px';
            if (tf) elm.style.transform = tf;
        }
        function placeBox(elm, pos, x, y) {
            var v = pos[0], h = pos[1], tf = '';
            if (v === 't') elm.style.top = y + 'px';
            else if (v === 'b') elm.style.bottom = y + 'px';
            else { elm.style.top = '50%'; tf += 'translateY(-50%) '; if (y) elm.style.marginTop = y + 'px'; }
            if (h === 'l') elm.style.left = x + 'px';
            else if (h === 'r') { elm.style.right = x + 'px'; elm.style.left = 'auto'; }
            else { elm.style.left = '50%'; elm.style.right = 'auto'; tf += 'translateX(-50%)'; if (x) elm.style.marginLeft = x + 'px'; }
            if (tf) elm.style.transform = tf.trim();
        }

        if (!isScene && nav.arrows.on && slides.length > 1) {
            var st = nav.arrows.style || 'chevron';
            var glyph = { chevron:['‹','›'], arrow:['←','→'], circle:['‹','›'], square:['‹','›'], minimal:['‹','›'], pill:['‹','›'], outline:['‹','›'], glass:['‹','›'], soft:['‹','›'], double:['«','»'] }[st] || ['‹','›'];
            var prev = document.createElement('button'); prev.className = 'fs-rt-arrow st-' + st; prev.innerHTML = glyph[0];
            var next = document.createElement('button'); next.className = 'fs-rt-arrow st-' + st; next.innerHTML = glyph[1];
            prev.type = 'button'; next.type = 'button';
            prev.setAttribute('aria-label', 'Previous slide'); next.setAttribute('aria-label', 'Next slide');
            prev.onclick = function () { show(idx - 1); }; next.onclick = function () { show(idx + 1); };
            placeArrow(prev, 'left',  nav.arrows.pos, nav.arrows.x, nav.arrows.y);
            placeArrow(next, 'right', nav.arrows.pos, nav.arrows.x, nav.arrows.y);
            wrap.appendChild(prev); wrap.appendChild(next);
        }
        if (!isScene && nav.bullets.on && slides.length > 1) {
            var bst = nav.bullets.style || 'dots';
            var dotsWrap = document.createElement('div'); dotsWrap.className = 'fs-rt-dots' + (bst === 'thumbnails' ? ' fs-rt-thumbs' : '');
            slides.forEach(function (sl, k) {
                var d = document.createElement('button'); d.className = 'fs-rt-dot st-' + bst;
                if (bst === 'numbers') d.textContent = (k + 1);
                if (bst === 'thumbnails') {                                   // mini preview of the slide's background
                    var bg = sl.bg || {}, imgs = [];
                    d.style.backgroundColor = bg.value || '#334155';
                    if (bg.gradient || bg.type === 'gradient') imgs.push('linear-gradient(' + (bg.angle || 135) + 'deg,' + (bg.from || '#6366f1') + ',' + (bg.to || '#ec4899') + ')');
                    if (bg.url) imgs.push("url('" + bg.url + "')");
                    if (imgs.length) { d.style.backgroundImage = imgs.join(','); d.style.backgroundSize = 'cover'; d.style.backgroundPosition = 'center'; }
                }
                d.type = 'button'; d.setAttribute('aria-label', 'Go to slide ' + (k + 1));
                d.onclick = function () { show(k); };
                dots.push(d); dotsWrap.appendChild(d);
            });
            placeBox(dotsWrap, nav.bullets.pos, nav.bullets.x, nav.bullets.y);
            wrap.appendChild(dotsWrap);
        }
        if (nav.progress.on && !isScene && slides.length > 1) {
            var pw = document.createElement('div'); pw.className = 'fs-rt-progress';
            pw.style.height = (nav.progress.height || 4) + 'px';
            pw.style[nav.progress.pos === 'top' ? 'top' : 'bottom'] = '0';
            progBar = document.createElement('div'); progBar.className = 'fs-rt-progress-bar';
            progBar.style.background = nav.progress.color || '#fff';
            pw.appendChild(progBar); wrap.appendChild(pw);
        }
        var isHover = false;
        wrap.addEventListener('mouseenter', function () { isHover = true; if (S.pause_on_hover !== false) pauseAutoplay(); });
        wrap.addEventListener('mouseleave', function () { isHover = false; if (S.pause_on_hover !== false) resumeAutoplay(); });

        if (slides.length > 1) {
            // Keyboard: ← / → step slides, but only while the slider is hovered or focused
            // (one document listener, gated per-instance, so it never hijacks the whole page).
            wrap.tabIndex = 0;
            wrap.setAttribute('role', 'region'); wrap.setAttribute('aria-roledescription', 'carousel');
            document.addEventListener('keydown', function (e) {
                if (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight') return;
                if (!isHover && !wrap.contains(document.activeElement)) return;
                e.preventDefault();
                show(e.key === 'ArrowLeft' ? idx - 1 : idx + 1);
            });

            // Touch: horizontal swipe steps slides. Passive listeners + no preventDefault, so
            // vertical page scrolling still works — we only act on a clearly-horizontal flick.
            var tsx = 0, tsy = 0, tst = 0, tracking = false;
            wrap.addEventListener('touchstart', function (e) {
                if (e.touches.length !== 1) { tracking = false; return; }
                var t = e.touches[0]; tsx = t.clientX; tsy = t.clientY; tst = Date.now(); tracking = true;
            }, { passive: true });
            wrap.addEventListener('touchend', function (e) {
                if (!tracking) return; tracking = false;
                var t = e.changedTouches[0], dx = t.clientX - tsx, dy = t.clientY - tsy, dt = Date.now() - tst;
                if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy) * 1.5 && dt < 800) {
                    show(dx < 0 ? idx + 1 : idx - 1);   // swipe left → next, right → prev
                }
            }, { passive: true });
        }

        // Parallax — layers with a depth drift opposite to the pointer for a 3-D feel.
        // Off under reduced-motion. Only the visible slide's layers move.
        if (!FS_RM && S.parallax !== false) {
            var pxRaf = 0, pxNX = 0, pxNY = 0;
            wrap.addEventListener('mousemove', function (e) {
                var r = wrap.getBoundingClientRect(); if (!r.width) return;
                pxNX = ((e.clientX - r.left) / r.width - 0.5) * 2;   // -1 … 1
                pxNY = ((e.clientY - r.top) / r.height - 0.5) * 2;
                if (pxRaf) return;
                pxRaf = requestAnimationFrame(function () {
                    pxRaf = 0; var cur = slideEls[idx]; if (!cur) return;
                    cur.querySelectorAll('.fs-rt-layer').forEach(function (el) {
                        if (!el.__parallax) return;
                        el.__px = -pxNX * el.__parallax * 7; el.__py = -pxNY * el.__parallax * 7;
                        if (el.__applyTf) el.__applyTf();
                    });
                });
            });
            wrap.addEventListener('mouseleave', function () {
                var cur = slideEls[idx]; if (!cur) return;
                cur.querySelectorAll('.fs-rt-layer').forEach(function (el) { if (el.__parallax) { el.__px = 0; el.__py = 0; if (el.__applyTf) el.__applyTf(); } });
            });
        }

        show(0);

        // Dismiss the spinner once the first slide's background image is ready (with a safety timeout).
        if (spinner) {
            var firstUrl = slides[0] && slides[0].bg && slides[0].bg.url;
            if (firstUrl) { var im = new Image(); im.onload = hideSpinner; im.onerror = hideSpinner; im.src = firstUrl; setTimeout(hideSpinner, 4000); }
            else { setTimeout(hideSpinner, 400); }
        }

        // Per-slider custom JS (admin-authored) — runs with `root`, `wrap` and a small api.
        if (S.customJs) {
            try { new Function('root', 'wrap', 'api', String(S.customJs))(root, wrap, { goTo: show, next: function () { show(idx + 1); }, prev: function () { show(idx - 1); }, current: function () { return idx; } }); }
            catch (e) { if (window.console) console.warn('Falcon Slider custom JS error:', e); }
        }
    }

    function bootAll() { document.querySelectorAll('.falcon-slider').forEach(boot); }
    if (document.readyState !== 'loading') bootAll();
    else document.addEventListener('DOMContentLoaded', bootAll);
    window.FalconSliderBoot = bootAll;
})();
</script>
