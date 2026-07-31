{{--
    Frontend slider. Outputs a container + the config JSON; the runtime engine
    (included once below) builds the scaled stage, renders every slide's
    background + layers, and plays slide transitions and per-layer animations
    with the Web Animations API.
--}}
@php
    $settings = $slider->settings ?? [];
    $first    = ($slider->slides ?? [])[0] ?? null;
    $w        = $settings['width'] ?? 1200;
    $h        = $settings['height'] ?? 600;
    $ratio    = $h / max(1, $w) * 100;
    $sizing   = $settings['sizing'] ?? 'auto';
    // The first slide's background image is (almost always) the page's LCP element —
    // preload it so the browser fetches it immediately, not after the runtime boots.
    $firstImg = ($first && !empty($first['bg']['url'])) ? falcon_safe_url($first['bg']['url']) : null;
    // Reserve the slider's height on the ROOT so it never collapses when the runtime
    // swaps in the stage — kills Cumulative Layout Shift (CLS). Auto uses the design
    // aspect-ratio; full-width/-screen reserve a min height.
    $reserve = $sizing === 'full-screen' ? 'min-height:100vh;'
             : ($sizing === 'full-width' ? 'min-height:' . $h . 'px;'
             : 'aspect-ratio:' . $w . '/' . $h . ';');
    // Root sizing. Auto is centered at the set width. Full-width/full-screen are made
    // full-bleed by the runtime in JS (sized to the document width, not 100vw, so the
    // vertical scrollbar can't trigger a horizontal one) — kept contained here pre-boot.
    $rootStyle = ($sizing === 'auto'
        ? 'width:100%;max-width:' . $w . 'px;margin:0 auto;'
        : 'width:100%;max-width:none;margin:0 auto;') . $reserve;
    // Placeholder height matches the runtime: full-screen = 100vh, full-width =
    // the set dimension height (width fills the viewport), auto = aspect ratio.
    if ($sizing === 'full-screen')    $phStyle = 'height:100vh;overflow:hidden;';
    elseif ($sizing === 'full-width') $phStyle = 'height:' . ($settings['height'] ?? 600) . 'px;overflow:hidden;';
    else                              $phStyle = 'padding-top:' . $ratio . '%;overflow:hidden;';
    // Placeholder background (first slide) shown until the runtime boots — no blank flash.
    $ph = 'background:#0f172a;';
    if ($first) {
        $bg = $first['bg'] ?? [];
        $hasGrad = (!empty($bg['gradient']) || ($bg['type'] ?? '') === 'gradient');
        $ph = 'background-color:' . ($bg['value'] ?? '#0f172a') . ';';
        $imgs = $sz = $ps = $rp = [];
        if ($hasGrad) {
            $imgs[] = 'linear-gradient(' . ($bg['angle'] ?? 135) . 'deg,' . ($bg['from'] ?? '#6366f1') . ',' . ($bg['to'] ?? '#ec4899') . ')';
            $sz[] = 'cover'; $ps[] = 'center'; $rp[] = 'no-repeat';
        }
        if (!empty($bg['url'])) {
            $imgs[] = 'url(' . falcon_safe_url($bg['url']) . ')';
            $sz[] = ($bg['size'] ?? 'cover') === 'contain' ? 'contain'
                : (($bg['size'] ?? '') === 'auto' ? 'auto'
                : (($bg['size'] ?? '') === 'percentage' ? (($bg['sizeX'] ?? 100) . '% ' . ($bg['sizeY'] ?? 100) . '%') : 'cover'));
            $ps[] = ($bg['position'] ?? '') === 'custom' ? (($bg['posX'] ?? 50) . '% ' . ($bg['posY'] ?? 50) . '%') : ($bg['position'] ?? 'center center');
            $rp[] = $bg['repeat'] ?? 'no-repeat';
        }
        if ($imgs) {
            $ph .= 'background-image:' . implode(',', $imgs) . ';background-size:' . implode(',', $sz) . ';background-position:' . implode(',', $ps) . ';background-repeat:' . implode(',', $rp) . ';';
        }
    }
    // Collect Google Fonts (text/button families) + Material Symbols icon names used here.
    $fsFonts = [];
    $fsIcons = [];
    $allLayerSets = array_merge(array_map(fn ($sl) => $sl['layers'] ?? [], $slider->slides ?? []), [$slider->settings['globalLayers'] ?? []]);
    foreach ($allLayerSets as $set) {
        foreach ($set as $ly) {
            if (!empty($ly['style']['family'] ?? null)) $fsFonts[] = $ly['style']['family'];
            if (($ly['type'] ?? '') === 'icon' && !empty($ly['iconName'] ?? null)) $fsIcons[] = $ly['iconName'];
        }
    }
    $fsFonts = array_values(array_unique(array_filter($fsFonts)));
    $fsIcons = array_values(array_unique(array_filter($fsIcons)));
@endphp

@if($firstImg)
    {{-- LCP: fetch the first slide's background image with top priority. --}}
    <link rel="preload" as="image" href="{{ $firstImg }}" fetchpriority="high">
@endif
@if(!empty($fsFonts) || !empty($fsIcons))
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@endif
@if(!empty($fsFonts))
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?{{ collect($fsFonts)->map(fn($f) => 'family=' . str_replace(' ', '+', $f) . ':ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700')->implode('&') }}&display=swap">
@endif
@if(!empty($fsIcons))
    {{-- Subset: only the icon glyphs this slider actually uses --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names={{ implode(',', $fsIcons) }}&display=block">
@endif

<div class="falcon-slider" data-slider-id="{{ $slider->id }}" style="position:relative;{{ $rootStyle }}">
    <div class="falcon-slider-ph" style="position:relative;width:100%;{{ $phStyle }}{{ $ph }}"></div>
    <script type="application/json" class="falcon-slider-config">@json(['settings' => $settings, 'slides' => $slider->slides ?? []])</script>
</div>

{{-- Always emit the runtime with each slider. A page-builder page can render its
     content in more than one pass (SEO/excerpt + display), and a server-side
     "render once" flag gets consumed by the first pass — leaving the visible page
     without the runtime (only the placeholder shows). The runtime self-dedupes in
     JS, so emitting it per-slider is safe and reliable. --}}
@include('falcon-slider::frontend.runtime')
