<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Slider preview</title>
    <style>
        html, body { margin: 0; padding: 0; background: transparent; overflow: hidden; }
        /* The runtime scales the slider to its container width; the container is the body. */
        .falcon-slider { max-width: none !important; }
    </style>
</head>
<body>
    @include('falcon-slider::frontend.render')

    <script>
        // Same-origin iframe: size the parent <iframe> to our content so the slider
        // shows at its true height inside the builder canvas (no inner scrollbar).
        (function () {
            var last = 0;
            function fit() {
                try {
                    if (!window.frameElement) return;
                    var h = Math.max(
                        document.body.scrollHeight,
                        document.documentElement.scrollHeight,
                        (document.querySelector('.falcon-slider') || {}).offsetHeight || 0
                    );
                    if (h && Math.abs(h - last) > 1) { last = h; window.frameElement.style.height = h + 'px'; }
                } catch (e) {}
            }
            window.addEventListener('load', fit);
            window.addEventListener('resize', fit);
            // The runtime boots and scales asynchronously (fonts, images) — re-measure a few times.
            var t = 0, iv = setInterval(function () { fit(); if (++t > 20) clearInterval(iv); }, 150);
        })();
    </script>
</body>
</html>
