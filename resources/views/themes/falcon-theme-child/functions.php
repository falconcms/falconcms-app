<?php
/*
 * Lazy Theme Child — functions.php
 *
 * Add your custom hooks, filters, and PHP logic here.
 * This file is loaded AFTER the parent theme's functions.php,
 * so you can safely override or extend any parent hook.
 *
 * This file is NEVER overwritten by CMS updates.
 */

add_falcon_filter('falcon_builder_elements', function ($elements) {
    $elements['my_card'] = [
        'type'      => 'my_card',
        'name'      => 'My Card',
        'shortcode' => 'my_card',
        'icon'      => 'fa fa-th-large',
        'template'  => 'my-theme::builder.elements.my-card',
        'params'    => [
            ['type' => 'textfield', 'heading' => 'Title',    'param_name' => 'title',    'tab' => 'general', 'dynamic' => true],
            ['type' => 'textfield', 'heading' => 'Name',     'param_name' => 'name',     'tab' => 'general'],
            ['type' => 'textfield', 'heading' => 'Subtitle', 'param_name' => 'subtitle', 'value' => 'Hello', 'tab' => 'general'],
            ['type' => 'wysiwyg',   'heading' => 'Content',  'param_name' => 'content',  'value' => 'Hello', 'tab' => 'general'],
            ['type' => 'colorpickeralpha', 'heading' => 'Title Color',       'value' => '#222', 'tab' => 'design', 'param_name' => ['title', 'subtitle', 'name']],
            ['type' => 'colorpickeralpha', 'heading' => 'Title Hover Color', 'value' => '#222', 'tab' => 'design', 'param_name' => ['title_hover_color', 'subtitle_hover_color', 'name_hover_color']],
            ['type' => 'image', 'heading' => 'Image', 'tab' => ''],
            ['type' => 'date',  'heading' => 'Date',  'param_name' => 'date'],
            ['type' => 'icon',  'heading' => 'Icons', 'param_name' => 'icon'],
        ],
    ];
    return $elements;
});


// =============================================================================
// CHECKOUT FORM FIELD EXAMPLE
// =============================================================================
//
// falcon_billing_fields  — add / remove / reorder billing form fields
// falcon_shipping_fields — same for the shipping form
//
// Each field:
//   name      (string, required) — input name
//   type      — text|email|tel|select|country|textarea|checkbox|hidden
//   label     — visible label  (null = no label)
//   required  — true adds server-side validation automatically
//   width     — 'half' (one column) | 'full' (both columns, default)
//   priority  — sort order (lower = higher up); default fields use 10–100
//   options   — array for select type  (key => label)
//   placeholder, rows, rules, class, default  — optional extras
//
// Custom fields (any name NOT in the standard billing/shipping list) are:
//   • Validated server-side if required => true
//   • Saved automatically to $order->meta['checkout_fields']
//   • Shown in: order confirmation, admin order detail, invoice print
//
// =============================================================================

// ── Add "Company Name" to billing (between Last name and Country) ─────────────
add_falcon_filter('falcon_billing_fields', function ($fields) {
    $fields[] = [
        'name'     => 'billing_company',
        'type'     => 'text',
        'label'    => 'Company Name',
        'required' => false,
        'width'    => 'full',
        'priority' => 20,           // after last name (20), before country (30)
        'placeholder' => 'Optional',
    ];
    return $fields;
});

// ── Human-readable label for order pages ─────────────────────────────────────
add_falcon_filter('falcon_checkout_field_labels', function ($labels) {
    $labels['billing_company'] = 'Company';
    return $labels;
});


// =============================================================================
// PRICE ADD-ON EXAMPLE — Protective Case
// =============================================================================
//
// This example shows how to add a paid option that adjusts the unit price.
// The same pattern works for: warranty, gift wrapping, insurance, engraving, etc.
//
// HOW IT WORKS
// ─────────────
// 1. falcon_product_fields  — adds the select field to ALL product types at once.
//    (Use falcon_simple_product_fields / falcon_variable_product_fields for type-specific fields.)
//
// 2. falcon_cart_item_data  — fires when the item is added to cart.
//    The numeric add-on is absorbed into the stored unit price so that
//    cart subtotal, checkout total, tax, coupon base, and invoices are
//    all automatically correct — no patching needed anywhere else.
//
// 3. falcon_custom_field_labels — provides human-readable labels used by
//    falcon_render_item_custom_fields() on mini-cart, cart, checkout,
//    order confirmation, and admin order detail pages.
//
// 4. falcon_admin_order_item_meta — renders the label line in the admin panel.
//
// RESULT ON EVERY PAGE
// ─────────────────────
//  Product page  → select + "+$5.00 will be added" note
//  Mini-cart     → unit price $104  •  Protective Case: Slim Case
//  Cart          → same
//  Checkout      → same
//  Confirmation  → same
//  Invoice       → same (labels resolved via falcon_custom_field_labels)
//  Admin order   → unit price $104  •  Protective Case: Slim Case
//
// =============================================================================


// ── 1. Field ──────────────────────────────────────────────────────────────────
// falcon_product_fields applies to BOTH simple and variable products in one hook.

add_falcon_filter('falcon_product_fields', function ($fields) {

    ob_start(); ?>

    <div class="mb-5">
        <label class="block text-sm font-semibold text-heading mb-1.5">
            Protective Case
        </label>
        <select name="falcon_custom_case"
                class="w-full border border-gray-300 rounded-sm px-3 py-2 text-sm outline-none bg-white"
                onchange="falconCaseChanged(this)">
            <option value="" data-price="0">No case — free</option>
            <option value="Slim Case"   data-price="5">Slim Case — +$5.00</option>
            <option value="Rugged Case" data-price="12">Rugged Case — +$12.00</option>
        </select>
        <input type="hidden" name="falcon_custom_case_addon" id="falcon-case-price-input" value="0">
        <p id="falcon-case-note" style="display:none"
           class="mt-1.5 text-sm font-semibold text-primary">
            + <span id="falcon-case-amount"></span> will be added to the price.
        </p>
    </div>
    <script>
    function falconCaseChanged(sel) {
        var price = parseFloat(sel.options[sel.selectedIndex].dataset.price) || 0;
        document.getElementById('falcon-case-price-input').value = price;
        var note = document.getElementById('falcon-case-note');
        note.style.display = price > 0 ? '' : 'none';
        document.getElementById('falcon-case-amount').textContent = '$' + price.toFixed(2);
    }
    </script>

    <?php
    $fields[] = ['type' => 'raw', 'wrapper' => false, 'content' => ob_get_clean()];
    return $fields;

});


// ── 2. Bake add-on into unit price at add-to-cart time ───────────────────────

add_falcon_filter('falcon_cart_item_data', function ($item) {

    $addon = (float) ($item['meta']['custom_fields']['case_addon'] ?? 0);

    if ($addon > 0) {
        $key = !empty($item['sale_price']) ? 'sale_price' : 'price';
        $item[$key] = (float) $item[$key] + $addon;
    }

    // Remove the raw number — it's been absorbed into the price.
    unset($item['meta']['custom_fields']['case_addon']);

    // Remove empty selection ("No case").
    if (empty($item['meta']['custom_fields']['case'])) {
        unset($item['meta']['custom_fields']['case']);
    }

    if (empty($item['meta']['custom_fields'])) {
        $item['meta'] = [];
    }

    return $item;

});


// ── 3. Human-readable label ───────────────────────────────────────────────────
// Used by mini-cart, cart, checkout, confirmation, and invoice.

add_falcon_filter('falcon_custom_field_labels', function ($labels) {
    $labels['case'] = 'Protective Case';
    return $labels;
});


// ── 4. Admin order detail ─────────────────────────────────────────────────────

add_falcon_action('falcon_admin_order_item_meta', function ($item) {

    $labels = apply_falcon_filters('falcon_custom_field_labels', []);

    foreach ($item->meta['custom_fields'] ?? [] as $key => $value) {
        if (!$value) continue;
        $label = $labels[$key] ?? ucwords(str_replace('_', ' ', $key));
        echo '<div class="text-[11px] text-[#646970] mt-0.5">'
           . '<span class="font-semibold text-[#1d2327]">' . e($label) . ':</span> '
           . e($value)
           . '</div>';
    }

});

