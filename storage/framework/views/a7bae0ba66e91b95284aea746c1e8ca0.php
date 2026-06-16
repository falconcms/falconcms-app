<?php
    // Use footer-specific colors; fall back to general Customizer > Colors values when empty.
    $footerBg     = get_cms_option('theme_footer_bg_color')     ?: get_cms_option('theme_body_bg_color',  '#1d2327');
    $footerText   = get_cms_option('theme_footer_text_color')   ?: get_cms_option('theme_text_color',     '#c3c4c7');
    $footerLink   = get_cms_option('theme_footer_link_color')   ?: get_cms_option('theme_link_color',     '#72aee6');
    $footerBorder = get_cms_option('theme_footer_border_color') ?: get_cms_option('theme_heading_color',  '#3c434a');
    $footerPaddingTop    = get_cms_option('theme_footer_padding_top',    '') ?: '40px';
    $footerPaddingBottom = get_cms_option('theme_footer_padding_bottom', '') ?: '40px';

    // Cast to int; use ?: to guard against empty-string corruption from a previous Customizer save bug.
    $footerColumns = (int)(get_cms_option('theme_footer_columns') ?: 4);
    $footerColumns = max(1, min(4, $footerColumns)); // clamp 1–4

    $gridClass = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'
    ][$footerColumns] ?? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
?>

<footer class="main-footer" style="background-color: <?php echo e($footerBg); ?>; color: <?php echo e($footerText); ?>; <?php if((get_cms_option('theme_footer_border_top') ?: '1') == '1'): ?> border-top: 1px solid <?php echo e($footerBorder); ?>; <?php endif; ?> padding-top: <?php echo e($footerPaddingTop); ?>; padding-bottom: <?php echo e($footerPaddingBottom); ?>;">
    <div class="container-custom">
        <div class="grid <?php echo e($gridClass); ?> gap-12 mb-16">
            <?php for($i = 1; $i <= $footerColumns; $i++): ?>
                <div class="col-span-1 footer-column">
                    <?php $widgetContent = render_lazy_widgets("footer-{$i}"); ?>
                    <?php if($widgetContent): ?>
                        <?php echo $widgetContent; ?>

                    <?php else: ?>
                        <?php if($i == 1): ?>
                            <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-2 mb-6">
                                <img src="<?php echo e(get_cms_option('theme_site_logo', asset('vendor/falcon-cms/images/falcon-cms-logo.png'))); ?>" alt="<?php echo e(get_cms_option('site_title', 'FalconCMS')); ?>" class="h-8 w-auto">
                            </a>
                            <p class="text-[14px] leading-relaxed mb-8 opacity-80">
                                <?php echo e(get_cms_option('footer_about', 'A minimalist, Astra-inspired theme for FalconCMS. Clean, fast, and professional design focusing on readability and content delivery.')); ?>

                            </p>

                            
                            <div class="flex items-center gap-3">
                                <?php
                                    $socials = [
                                        ['key' => 'theme_social_facebook',  'icon' => 'fab fa-facebook-f',  'color' => '#1877F2'],
                                        ['key' => 'theme_social_twitter',   'icon' => 'fab fa-x-twitter',   'color' => '#000000'],
                                        ['key' => 'theme_social_instagram', 'icon' => 'fab fa-instagram',   'color' => '#E4405F'],
                                        ['key' => 'theme_social_linkedin',  'icon' => 'fab fa-linkedin-in', 'color' => '#0077B5'],
                                        ['key' => 'theme_social_youtube',   'icon' => 'fab fa-youtube',     'color' => '#FF0000'],
                                        ['key' => 'theme_social_github',    'icon' => 'fab fa-github',      'color' => '#333333'],
                                        ['key' => 'theme_social_tiktok',    'icon' => 'fab fa-tiktok',      'color' => '#010101'],
                                        ['key' => 'theme_social_whatsapp',  'icon' => 'fab fa-whatsapp',    'color' => '#25D366'],
                                    ];
                                ?>
                                <?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($link = get_cms_option($social['key'])): ?>
                                        <a href="<?php echo e($link); ?>" target="_blank" class="w-9 h-9 rounded-lg flex items-center justify-center text-white transition-all hover:scale-110 shadow-sm" style="background-color: <?php echo e($social['color']); ?>;">
                                            <i class="<?php echo e($social['icon']); ?> text-sm"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php elseif($i == 2): ?>
                            <h4 class="font-bold mb-6" style="color: inherit;">Quick Links</h4>
                            <nav class="flex flex-col gap-3">
                                <?php $footerMenu = get_lazy_menu('footer'); ?>
                                <?php $__empty_1 = true; $__currentLoopData = $footerMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <a href="<?php echo e($item->url); ?>" class="text-[14px] opacity-70 hover:opacity-100 transition-colors" style="color: <?php echo e($footerLink); ?>;"><?php echo e($item->title); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <a href="<?php echo e(url('/')); ?>" class="text-[14px] opacity-70 hover:opacity-100 transition-colors" style="color: <?php echo e($footerLink); ?>;">Home</a>
                                <?php endif; ?>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <div style="border-top: 1px solid <?php echo e($footerBorder); ?>;"></div>

    <div class="container-custom">
        <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-[13px] opacity-60">
                <?php echo get_cms_option('theme_footer_copyright', '© ' . date('Y') . ' ' . get_cms_option('site_title', 'FalconCMS') . '. All rights reserved.'); ?>

            </div>
        </div>
    </div>
</footer>

<style>
    /* Headings in footer widget columns */
    .footer-column h1, .footer-column h2, .footer-column h3,
    .footer-column h4, .footer-column h5, .footer-column h6 {
        color: <?php echo e($footerText); ?> !important;
        opacity: 0.9;
    }
    /* All regular links in footer widget columns use the footer link color.
       Excludes icon buttons that carry an inline background-color (social media circles). */
    .footer-column a:not([style*="background-color"]) {
        color: <?php echo e($footerLink); ?> !important;
    }
    /* Force nested spans/li to inherit from the parent <a> so Tailwind classes
       like text-slate-600 don't override the footer link color. */
    .footer-column a:not([style*="background-color"]) span,
    .footer-column a:not([style*="background-color"]) li {
        color: inherit !important;
    }
    /* Widget title uses footer text color */
    .footer-column .widget-title {
        color: <?php echo e($footerText); ?> !important;
        border-bottom-color: <?php echo e($footerLink); ?> !important;
    }
</style>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/themes/falcon-theme/partials/footer.blade.php ENDPATH**/ ?>