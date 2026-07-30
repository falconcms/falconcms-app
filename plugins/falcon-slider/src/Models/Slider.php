<?php

namespace FalconSlider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Slider extends Model
{
    protected $fillable = ['name', 'slug', 'settings', 'slides', 'is_active'];

    /** Version-history snapshots, newest last by id. */
    public function revisions(): HasMany
    {
        return $this->hasMany(SliderRevision::class);
    }

    protected $casts = [
        'settings'  => 'array',
        'slides'    => 'array',
        'is_active' => 'boolean',
    ];

    /** Sensible defaults for a brand-new slider. */
    public static function defaultSettings(): array
    {
        return [
            'width'          => 1200,
            'height'         => 600,
            'layout_type'    => 'slider',
            'sizing'         => 'auto',
            'autoplay'       => true,
            'autoplay_delay' => 6000,
            'loop'           => true,
            'arrows'         => true,
            'dots'           => true,
            'pause_on_hover' => true,
            'nav'            => [
                'arrows'   => ['on' => true,  'style' => 'chevron', 'pos' => 'middle', 'x' => 16, 'y' => 0],
                'bullets'  => ['on' => true,  'style' => 'dots',    'pos' => 'bc',     'x' => 0,  'y' => 16],
                'progress' => ['on' => false, 'color' => '#ffffff', 'height' => 4,     'pos' => 'bottom'],
            ],
            'spinner'             => ['style' => 'none', 'color' => '#ffffff'],
            'transition'          => 'fade',
            'transition_duration' => 700,
            'tablet_width'        => 768,
            'mobile_width'        => 480,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Slider $slider) {
            if (empty($slider->slug)) {
                $slider->slug = static::uniqueSlug($slider->name);
            }
            if ($slider->settings === null) {
                $slider->settings = static::defaultSettings();
            }
            if ($slider->slides === null) {
                $slider->slides = [];
            }
        });
    }

    protected static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'slider';
        $slug = $base;
        $i = 2;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
