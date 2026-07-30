# Falcon Slider

A layer-based slider builder for **FalconCMS** — a Slider-Revolution-class engine for
hero sliders, banners and animated showcases. Design each slide on a visual canvas, drop
in layers (text, images, buttons, video, countdowns and more), give every layer its own
timeline animation, and embed the result anywhere with a shortcode.

> **Pro feature.** Falcon Slider appears in the admin once a FalconCMS Pro license is
> active. Requires FalconCMS **2.2+**.

## Features

- **Slides** — unlimited slides, per-slide backgrounds, transitions, whole-slide links
- **Backgrounds** — colour, gradient, image, background video (self-hosted / YouTube /
  Vimeo), colour & gradient overlays, Ken Burns, mouse parallax
- **Layers** — text (SEO `h1`–`h6`), image, button, shape, icon (750+ Material Symbols),
  video, audio, live countdown, raw HTML/embed
- **Animation** — 15 entrance/exit presets, text reveals (typewriter / split chars /
  words), idle loops, per-layer delay · duration · easing
- **Responsive** — position, size, font-size and visibility per device; layer groups and
  global (every-slide) layers
- **Navigation** — arrows, bullets, progress bar, thumbnails, autoplay, loop,
  pause-on-hover; auto / full-width / full-screen sizing
- **Productivity** — pre-built templates, save-as-template, duplicate, import / export,
  version history, full-screen live preview
- **Performance** — LCP background preload, layout-shift-free rendering, lazy media,
  subset font/icon loading

## Installation

1. In the admin, go to **Plugins → Add Plugin** and upload `falcon-slider-x.y.z.zip`.
2. Activate **Falcon Slider**. Activation registers the `manage_sliders` permission and
   runs the slider database migrations.
3. Open **Falcon Slider** in the sidebar and create your first slider.

## Usage

Embed a slider with the shortcode:

```
[falcon_slider id="1"]
```

…or by slug:

```
[falcon_slider slug="homepage-hero"]
```

You can also drop the **Falcon Slider** element onto any page in Falcon Builder and pick
the slider from a dropdown.

## Documentation

Full documentation: https://falconcms.github.io/falconcms/slider/overview

## License

Proprietary — part of FalconCMS Pro.
