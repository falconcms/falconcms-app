<?php

namespace FalconSlider\Support;

/**
 * The Google Fonts catalog for the layer font picker. Each entry is a compact tuple:
 *   [ family name, category index, [available weights] ]
 * category index → 0 Sans-serif · 1 Serif · 2 Monospace · 3 Display · 4 Handwriting.
 *
 * The list is generated from Google's public font metadata (brand-only fonts that the
 * public CSS API can't serve are excluded) and sorted most-popular-first. The editor
 * loads any chosen font on demand; the frontend loads only the fonts a slider uses.
 */
class FontLibrary
{
    /** @return array<int,array{0:string,1:int,2:array<int,int>}> */
    public static function catalog(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }
        $file = __DIR__ . '/../../resources/google-fonts.json';
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;

        return $cache = is_array($data) ? $data : [];
    }

    /** Category labels, indexed to match the catalog's category index. */
    public static function categories(): array
    {
        return ['Sans-serif', 'Serif', 'Monospace', 'Display', 'Handwriting'];
    }
}
