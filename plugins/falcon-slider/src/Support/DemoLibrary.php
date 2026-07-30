<?php

namespace FalconSlider\Support;

/**
 * Ready-made slider designs (Corporate, Nutrition, Ecommerce, Education, Fashion,
 * Food). Each is a full slider — settings + 3 designed slides — that the editor can
 * drop in with one click. Design space is 1200×600; layer ids are assigned on insert.
 */
class DemoLibrary
{
    public static function all(): array
    {
        // ---- terse builders --------------------------------------------------
        $grad = fn ($f, $t, $a = 135) => ['type' => 'gradient', 'gradient' => true, 'from' => $f, 'to' => $t, 'angle' => $a, 'value' => $f];
        $solid = fn ($c) => ['type' => 'color', 'value' => $c];
        // text: content,x,y,w,h,size,color,weight,align,[preset,delay],extraStyle
        $t = fn ($c, $x, $y, $w, $h, $sz, $col, $wt = 800, $al = 'left', $a = ['fade', 0], $st = []) => [
            'type' => 'text', 'content' => $c, 'pos' => ['desktop' => ['x' => $x, 'y' => $y, 'w' => $w, 'h' => $h]],
            'style' => array_merge(['size' => $sz, 'color' => $col, 'weight' => $wt, 'align' => $al, 'lineHeight' => 1.12], $st),
            'anim' => ['in' => ['preset' => $a[0], 'delay' => $a[1] ?? 0, 'duration' => $a[2] ?? 550, 'easing' => 'ease-out'], 'loop' => 'none'],
        ];
        // button: content,x,y,w,h,bg,color,radius,[preset,delay]
        $b = fn ($c, $x, $y, $w, $h, $bg, $col, $r = 8, $a = ['zoom-in', 400], $st = []) => [
            'type' => 'button', 'content' => $c, 'link' => '#', 'pos' => ['desktop' => ['x' => $x, 'y' => $y, 'w' => $w, 'h' => $h]],
            'style' => array_merge(['bg' => $bg, 'color' => $col, 'size' => 16, 'radius' => $r, 'weight' => 700], $st),
            'anim' => ['in' => ['preset' => $a[0], 'delay' => $a[1] ?? 0, 'duration' => 500, 'easing' => 'ease-out'], 'loop' => 'none'],
        ];
        // shape: x,y,w,h,bg,shape,radius,[preset,delay]
        $sh = fn ($x, $y, $w, $h, $bg, $shape = 'square', $r = 0, $a = ['fade', 0]) => [
            'type' => 'shape', 'pos' => ['desktop' => ['x' => $x, 'y' => $y, 'w' => $w, 'h' => $h]],
            'style' => ['bg' => $bg, 'shape' => $shape, 'radius' => $r],
            'anim' => ['in' => ['preset' => $a[0], 'delay' => $a[1] ?? 0, 'duration' => 550, 'easing' => 'ease-out'], 'loop' => 'none'],
        ];
        $slide = fn ($name, $bg, $layers, $trans = 'fade') => ['name' => $name, 'transition' => $trans, 'bg' => $bg, 'layers' => $layers];
        $settings = fn ($trans, $arrow = 'chevron', $bullet = 'pills') => [
            'width' => 1200, 'height' => 600, 'sizing' => 'auto', 'autoplay' => true, 'autoplay_delay' => 5000,
            'transition' => $trans, 'transition_duration' => 700, 'loop' => true,
            'nav' => [
                'arrows'   => ['on' => true, 'style' => $arrow, 'pos' => 'middle', 'x' => 18, 'y' => 0],
                'bullets'  => ['on' => true, 'style' => $bullet, 'pos' => 'bc', 'x' => 0, 'y' => 18],
                'progress' => ['on' => false, 'color' => '#ffffff', 'height' => 4, 'pos' => 'bottom'],
            ],
        ];

        return [
            // ============================== CORPORATE ==============================
            [
                'name' => 'Corporate', 'category' => 'Corporate',
                'settings' => $settings('slide-left', 'chevron', 'pills'),
                'slides' => [
                    $slide('Slide 1', $grad('#0f172a', '#1e3a8a', 135), [
                        $t('CORPORATE SOLUTIONS', 100, 148, 500, 26, 14, '#60a5fa', 700, 'left', ['from-left', 0], ['letterSpacing' => 3]),
                        $t("Grow Your Business\nWith Confidence", 100, 184, 640, 150, 52, '#ffffff', 800, 'left', ['from-left', 150]),
                        $t('Strategic consulting and solutions that drive measurable, lasting results.', 100, 350, 560, 60, 19, 'rgba(255,255,255,.8)', 400, 'left', ['from-left', 300]),
                        $b('Get Started', 100, 432, 175, 52, '#3b82f6', '#ffffff', 8, ['from-bottom', 450]),
                        $sh(800, 80, 380, 380, 'rgba(96,165,250,.12)', 'circle', 0, ['fade', 0]),
                    ], 'slide-left'),
                    $slide('Slide 2', $grad('#1e3a8a', '#0ea5e9', 120), [
                        $t("Trusted by 500+\nCompanies Worldwide", 150, 175, 900, 150, 48, '#ffffff', 800, 'center', ['from-bottom', 0]),
                        $t('Join the growing list of businesses that rely on our expertise.', 300, 330, 600, 50, 18, 'rgba(255,255,255,.85)', 400, 'center', ['fade', 200]),
                        $b('Our Clients', 510, 408, 180, 52, '#ffffff', '#1e3a8a', 8, ['zoom-in', 400]),
                    ], 'fade'),
                    $slide('Slide 3', $grad('#0f172a', '#334155', 135), [
                        $t('READY TO START?', 200, 188, 800, 26, 14, '#60a5fa', 700, 'center', ['fade', 0], ['letterSpacing' => 3]),
                        $t("Let's Build Something\nGreat Together", 150, 224, 900, 150, 46, '#ffffff', 800, 'center', ['from-bottom', 150]),
                        $b('Contact Us', 490, 402, 220, 56, '#3b82f6', '#ffffff', 999, ['zoom-in', 350]),
                    ], 'slide-left'),
                ],
            ],

            // ============================== NUTRITION ==============================
            [
                'name' => 'Nutrition', 'category' => 'Nutrition',
                'settings' => $settings('fade', 'soft', 'dots'),
                'slides' => [
                    $slide('Slide 1', $grad('#065f46', '#10b981', 120), [
                        $t('HEALTHY LIVING', 100, 148, 500, 26, 14, '#a7f3d0', 700, 'left', ['from-left', 0], ['letterSpacing' => 3]),
                        $t("Fuel Your Body\nThe Right Way", 100, 184, 620, 150, 52, '#ffffff', 800, 'left', ['from-left', 150]),
                        $t('Natural nutrition plans designed around your wellness goals.', 100, 350, 540, 60, 19, 'rgba(255,255,255,.85)', 400, 'left', ['from-left', 300]),
                        $b('Start Today', 100, 432, 175, 52, '#ffffff', '#065f46', 999, ['from-bottom', 450]),
                        $sh(820, 100, 340, 340, 'rgba(167,243,208,.15)', 'circle', 0, ['fade', 0]),
                    ], 'fade'),
                    $slide('Slide 2', $grad('#10b981', '#84cc16', 135), [
                        $t('100% NATURAL', 100, 160, 150, 24, 15, '#ffffff', 800, 'center', ['from-left', 0], ['letterSpacing' => 1]),
                        $t("Real Ingredients,\nReal Results", 100, 208, 640, 150, 48, '#ffffff', 800, 'left', ['from-bottom', 150]),
                        $t('Every plan is crafted from whole, natural sources — nothing artificial.', 100, 350, 540, 50, 18, 'rgba(255,255,255,.85)', 400, 'left', ['fade', 300]),
                        $b('Learn More', 100, 422, 170, 52, '#065f46', '#ffffff', 999, ['from-bottom', 450]),
                        $sh(100, 150, 150, 44, 'rgba(255,255,255,.22)', 'square', 999, ['from-left', 0]),
                    ], 'fade'),
                    $slide('Slide 3', $grad('#064e3b', '#059669', 135), [
                        $t("Your Journey to\nBetter Health", 150, 185, 900, 150, 48, '#ffffff', 800, 'center', ['from-bottom', 0]),
                        $t("Thousands have transformed their lives. You could be next.", 280, 340, 640, 50, 18, 'rgba(255,255,255,.85)', 400, 'center', ['fade', 200]),
                        $b('Join Now', 510, 410, 180, 54, '#a3e635', '#064e3b', 999, ['zoom-in', 400], ['weight' => 800]),
                    ], 'fade'),
                ],
            ],

            // ============================== ECOMMERCE ==============================
            [
                'name' => 'Ecommerce', 'category' => 'Ecommerce',
                'settings' => $settings('slide-up', 'circle', 'pills'),
                'slides' => [
                    $slide('Slide 1', $grad('#7c3aed', '#ec4899', 120), [
                        $t('SALE', 100, 150, 130, 24, 18, '#7c2d12', 900, 'center', ['from-left', 0], ['letterSpacing' => 1]),
                        $t("Summer Sale\nUp to 50% Off", 100, 198, 640, 160, 56, '#ffffff', 900, 'left', ['from-bottom', 150], ['lineHeight' => 1.05]),
                        $t("Shop the season's hottest styles before they're gone.", 100, 362, 540, 50, 19, 'rgba(255,255,255,.9)', 400, 'left', ['fade', 300]),
                        $b('Shop Now', 100, 440, 180, 54, '#ffffff', '#7c3aed', 8, ['from-bottom', 450], ['weight' => 800]),
                        $sh(100, 140, 130, 42, '#facc15', 'square', 999, ['from-left', 0]),
                    ], 'slide-up'),
                    $slide('Slide 2', $grad('#ec4899', '#f97316', 135), [
                        $t('JUST DROPPED', 100, 150, 400, 26, 14, '#ffffff', 700, 'left', ['from-left', 0], ['letterSpacing' => 3]),
                        $t("New Arrivals\nAre Here", 100, 186, 620, 150, 52, '#ffffff', 800, 'left', ['from-left', 150]),
                        $t('Fresh looks added weekly. Be the first to wear them.', 100, 350, 540, 50, 18, 'rgba(255,255,255,.9)', 400, 'left', ['fade', 300]),
                        $b('Explore', 100, 424, 170, 52, '#111827', '#ffffff', 8, ['zoom-in', 400]),
                    ], 'fade'),
                    $slide('Slide 3', $grad('#111827', '#7c3aed', 135), [
                        $t("Free Shipping\nWorldwide", 150, 178, 900, 150, 50, '#ffffff', 800, 'center', ['from-bottom', 0]),
                        $t('On every order over $50 — no code needed.', 300, 336, 600, 50, 18, 'rgba(255,255,255,.85)', 400, 'center', ['fade', 200]),
                        $b('Start Shopping', 480, 408, 240, 56, '#ec4899', '#ffffff', 999, ['zoom-in', 400], ['weight' => 800]),
                    ], 'slide-up'),
                ],
            ],

            // ============================== EDUCATION ==============================
            [
                'name' => 'Education', 'category' => 'Education',
                'settings' => $settings('fade', 'glass', 'ring'),
                'slides' => [
                    $slide('Slide 1', $grad('#312e81', '#4f46e5', 120), [
                        $t('ONLINE LEARNING', 100, 148, 500, 26, 14, '#fcd34d', 700, 'left', ['from-left', 0], ['letterSpacing' => 3]),
                        $t("Learn Without\nLimits", 100, 184, 600, 150, 54, '#ffffff', 800, 'left', ['from-left', 150]),
                        $t('Access 1,000+ courses taught by real industry experts.', 100, 348, 540, 60, 19, 'rgba(255,255,255,.85)', 400, 'left', ['from-left', 300]),
                        $b('Enroll Now', 100, 432, 180, 52, '#f59e0b', '#111827', 8, ['from-bottom', 450]),
                        $sh(800, 90, 360, 360, 'rgba(252,211,77,.12)', 'circle', 0, ['fade', 0]),
                    ], 'fade'),
                    $slide('Slide 2', $grad('#4f46e5', '#0ea5e9', 135), [
                        $t("Expert Instructors,\nProven Results", 150, 178, 900, 150, 46, '#ffffff', 800, 'center', ['from-bottom', 0]),
                        $t('Learn from the best in the field, at your own pace.', 300, 332, 600, 50, 18, 'rgba(255,255,255,.85)', 400, 'center', ['fade', 200]),
                        $b('Meet the Team', 500, 408, 200, 52, '#ffffff', '#4f46e5', 8, ['zoom-in', 400]),
                    ], 'fade'),
                    $slide('Slide 3', $grad('#1e1b4b', '#6d28d9', 135), [
                        $t('10,000+', 150, 155, 900, 90, 64, '#fcd34d', 900, 'center', ['zoom-in', 0]),
                        $t('Students already learning with us', 200, 268, 800, 40, 22, '#ffffff', 600, 'center', ['fade', 200]),
                        $b('Join Them', 510, 380, 180, 54, '#f59e0b', '#111827', 999, ['from-bottom', 400], ['weight' => 800]),
                    ], 'fade'),
                ],
            ],

            // ============================== FASHION ==============================
            [
                'name' => 'Fashion', 'category' => 'Fashion',
                'settings' => $settings('fade', 'minimal', 'bars'),
                'slides' => [
                    $slide('Slide 1', $solid('#0a0a0a'), [
                        $t('— NEW COLLECTION —', 200, 198, 800, 26, 14, '#d4af37', 600, 'center', ['fade', 0], ['letterSpacing' => 5]),
                        $t("Autumn / Winter\n2026", 150, 238, 900, 150, 56, '#fafafa', 300, 'center', ['from-bottom', 200], ['lineHeight' => 1.1, 'letterSpacing' => 1]),
                        $b('Shop Collection', 480, 424, 240, 52, 'transparent', '#ffffff', 0, ['fade', 400], ['weight' => 600, 'border' => '1px solid rgba(255,255,255,.7)']),
                    ], 'fade'),
                    $slide('Slide 2', $grad('#1c1917', '#44403c', 135), [
                        $t('TIMELESS', 100, 168, 400, 26, 14, '#d4af37', 600, 'left', ['from-left', 0], ['letterSpacing' => 4]),
                        $t("Elegance in\nEvery Detail", 100, 204, 620, 150, 50, '#ffffff', 300, 'left', ['from-left', 150], ['lineHeight' => 1.15, 'letterSpacing' => 1]),
                        $t('Crafted for those who appreciate the finer things.', 100, 352, 520, 50, 18, 'rgba(255,255,255,.7)', 400, 'left', ['fade', 300]),
                        $b('Discover', 100, 424, 175, 50, '#d4af37', '#1c1917', 0, ['from-bottom', 400], ['weight' => 600]),
                    ], 'fade'),
                    $slide('Slide 3', $solid('#0a0a0a'), [
                        $t('Up to 40% Off', 150, 228, 900, 90, 58, '#fafafa', 300, 'center', ['from-bottom', 0], ['letterSpacing' => 1]),
                        $t('Selected styles · Limited time only', 300, 336, 600, 40, 18, '#a1a1aa', 400, 'center', ['fade', 200], ['letterSpacing' => 1]),
                        $b('Shop Sale', 500, 414, 200, 52, '#ffffff', '#0a0a0a', 0, ['zoom-in', 400], ['weight' => 700]),
                    ], 'fade'),
                ],
            ],

            // ================================ FOOD =================================
            [
                'name' => 'Food', 'category' => 'Food',
                'settings' => $settings('slide-left', 'pill', 'pills'),
                'slides' => [
                    $slide('Slide 1', $grad('#7c2d12', '#ea580c', 120), [
                        $t('AUTHENTIC CUISINE', 100, 148, 500, 26, 14, '#fed7aa', 700, 'left', ['from-left', 0], ['letterSpacing' => 3]),
                        $t("Delicious Moments\nMade Fresh", 100, 184, 640, 150, 52, '#ffffff', 800, 'left', ['from-left', 150]),
                        $t('Handcrafted dishes from the freshest local ingredients.', 100, 350, 540, 60, 19, 'rgba(255,255,255,.9)', 400, 'left', ['from-left', 300]),
                        $b('Order Now', 100, 432, 175, 52, '#ffffff', '#7c2d12', 999, ['from-bottom', 450]),
                    ], 'slide-left'),
                    $slide('Slide 2', $grad('#111827', '#b91c1c', 135), [
                        $t('FRESH DAILY', 100, 160, 160, 24, 15, '#111827', 800, 'center', ['from-left', 0], ['letterSpacing' => 1]),
                        $t("From Our Kitchen\nTo Your Table", 100, 208, 640, 150, 46, '#ffffff', 800, 'left', ['from-bottom', 150]),
                        $t('Every dish prepared to order with love and care.', 100, 348, 540, 50, 18, 'rgba(255,255,255,.85)', 400, 'left', ['fade', 300]),
                        $b('View Menu', 100, 420, 175, 52, '#f59e0b', '#111827', 999, ['from-bottom', 450]),
                        $sh(100, 150, 160, 44, '#f59e0b', 'square', 999, ['from-left', 0]),
                    ], 'fade'),
                    $slide('Slide 3', $grad('#7c2d12', '#1c1917', 135), [
                        $t("Reserve Your\nTable Tonight", 150, 180, 900, 150, 48, '#ffffff', 800, 'center', ['from-bottom', 0]),
                        $t('Unforgettable dining experiences await you.', 320, 340, 560, 50, 18, 'rgba(255,255,255,.85)', 400, 'center', ['fade', 200]),
                        $b('Book a Table', 490, 410, 220, 56, '#ea580c', '#ffffff', 999, ['zoom-in', 400], ['weight' => 800]),
                    ], 'slide-left'),
                ],
            ],
        ];
    }
}
