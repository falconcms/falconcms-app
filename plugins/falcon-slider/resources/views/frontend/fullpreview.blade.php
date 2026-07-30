<!doctype html>
<html lang="en">
@php $fpSizing = $slider->settings['sizing'] ?? 'auto'; @endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Live preview — {{ $slider->name }}</title>
    <style>
        html, body { margin: 0; padding: 0; background: #0b0b0f; }
        body { min-height: 100vh; }
        @if($fpSizing === 'auto')
            /* Auto sliders keep a fixed width — center them in the viewport. Auto sliders
               never full-bleed, so flex centering is safe here. */
            body { display: flex; align-items: center; justify-content: center; }
        @else
            /* Full-width / full-screen size themselves to the DOCUMENT width in JS
               (applyFullBleed uses getBoundingClientRect().left). A flex COLUMN keeps the
               cross-axis stretched (root stays full-width, left = 0, so the math holds)
               while justify-content only centers it vertically. */
            body { display: flex; flex-direction: column; justify-content: center; overflow-x: hidden; }
        @endif
    </style>
</head>
<body>
    @include('falcon-slider::frontend.render')
</body>
</html>
