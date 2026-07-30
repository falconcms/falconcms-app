# Changelog — Falcon Slider

All notable changes to the Falcon Slider plugin are documented here.

## v1.0.0 — 2026-07-30

First public release.

### Added
- **Visual slide builder** — full-screen editor with a slides rail, live scaled canvas,
  and per-layer / per-slide / per-slider settings panels.
- **Layers** — text (with SEO `h1`–`h6` tags), image, button, shape, icon, video
  (self-hosted / YouTube / Vimeo), audio, live countdown, and raw HTML/embed.
- **Icon library** — 750+ curated Material Symbols, searchable in the picker.
- **Per-layer timeline animation** — 15 entrance/exit presets, text reveals (typewriter /
  split chars / split words), idle loops (float / bob / pulse / sway / spin), and
  per-layer delay · duration · easing.
- **Backgrounds** — colour, gradient, image, background video, colour & gradient
  overlays, Ken Burns and mouse parallax.
- **Responsive** — position, size, font-size and visibility per device
  (desktop / tablet / mobile); layer groups with staggered animation; global layers.
- **Navigation** — arrows, bullets, progress bar, thumbnails, autoplay, loop and
  pause-on-hover; auto / full-width / full-screen sizing.
- **Whole-slide links** — make an entire slide clickable while interactive layers keep
  their own actions.
- **Productivity** — pre-built template gallery, save-as-template, duplicate,
  import / export, version history (auto-snapshot on save, 20 kept) and a full-screen
  live preview of unsaved changes.
- **Performance** — LCP background preload, layout-shift-free reservation, lazy media and
  subset font/icon loading for Core Web Vitals.
- Embed via the `[falcon_slider id="…"]` / `[falcon_slider slug="…"]` shortcode or the
  Falcon Builder **Falcon Slider** element. Access governed by the `manage_sliders`
  permission and a Pro license.

### Requires
- FalconCMS **2.2+** (plugin + shortcode API), PHP **8.1+**.
