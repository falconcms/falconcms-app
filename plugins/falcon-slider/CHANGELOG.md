# Changelog — Falcon Slider

All notable changes to the Falcon Slider plugin are documented here.

## v1.1.0 — 2026-07-31

### Added
- **Text & button borders** — width, style, colour and corner radius.
- **Text background** — background colour + padding for text layers.
- **Text stroke / outline** — `-webkit-text-stroke` width + colour (works with reveals).
- **Full Google Fonts** — a searchable font picker with **all ~1,700 Google Fonts**, each
  showing its own real weights; the full weight range (100–900) loads for every font.

### Fixed
- **Group-layer animations** now drive their members correctly (with stagger) on both the
  editor canvas and the front-end — an editor default could previously suppress them.
- **Font-family dropdown** no longer restyles its whole list to the selected font.

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
