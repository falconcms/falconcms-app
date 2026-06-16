<?php $__env->startSection('title', $post->title); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $isBuilder = $post->editor_type === 'builder' || (is_string($post->content) && (str_starts_with($post->content, '[') || str_starts_with($post->content, '{')));
    ?>

    <?php if($isBuilder): ?>
        <div class="lazy-content-wrapper">
            <?php echo get_lazy_content($post->content); ?>

        </div>
    <?php else: ?>
        
        <!-- Page Content -->
        <section class="py-20 bg-white">
            <div class="container-custom">
                <div class="prose prose-lg prose-slate max-w-none">
                    <div class="lazy-content-wrapper">
                        <?php echo do_lazy_shortcode($post->content); ?>

                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('falcon-cms::themes.falcon-theme.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/themes/falcon-theme/page.blade.php ENDPATH**/ ?>