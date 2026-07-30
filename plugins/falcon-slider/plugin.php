<?php

/**
 * Falcon Slider — bootstrap.
 *
 * Registers the admin sidebar entry and the [falcon_slider] shortcode. Routes,
 * views, model and migration are wired by convention.
 */

use FalconSlider\Models\Slider;

// Admin sidebar entry — only shown to Pro sites with the capability.
if (function_exists('falcon_pro') && falcon_pro()) {
    falcon_add_menu_page([
        'slug'       => 'falcon-slider',
        'title'      => 'Falcon Slider',
        'icon'       => 'view_carousel',
        'route'      => 'admin.sliders.index',
        'permission' => 'manage_sliders',
        'position'   => 42.5,   // between Falcon Builder (42) and Plugins (43)
        'group'      => 'Main',
    ]);
}

// Frontend shortcode: [falcon_slider id="1"] or [falcon_slider slug="hero"].
add_falcon_shortcode('falcon_slider', function (array $atts) {
    $slider = null;
    if (! empty($atts['id'])) {
        $slider = Slider::find($atts['id']);
    } elseif (! empty($atts['slug'])) {
        $slider = Slider::where('slug', $atts['slug'])->first();
    }

    if (! $slider || ! $slider->is_active) {
        return '';
    }

    return view('falcon-slider::frontend.render', ['slider' => $slider])->render();
});

// Falcon Builder element — only registered while this plugin is active. General tab
// has a single dropdown of every slider; the chosen slider is what renders.
add_falcon_filter('falcon_builder_elements', function (array $elements) {
    $options = ['' => '— Select a slider —'];
    try {
        foreach (Slider::orderBy('name')->get(['id', 'name']) as $s) {
            $options[(string) $s->id] = $s->name;
        }
    } catch (\Throwable $e) {
        // sliders table not migrated yet — leave just the placeholder
    }

    $elements['falcon_slider'] = [
        'type'      => 'falcon_slider',
        'name'      => 'Falcon Slider',
        'icon'      => 'fas fa-images',
        'template'  => 'falcon-slider::builder.element',
        'singleTab' => true,   // only the General tab (no Design / Extras)
        'fields'    => [
            'sliderId' => [
                'type'    => 'select',
                'label'   => 'Slider',
                'options' => $options,
                'default' => '',
            ],
        ],
    ];

    return $elements;
});
