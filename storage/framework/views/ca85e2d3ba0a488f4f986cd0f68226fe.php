<?php
    $imageUrl   = $widget->settings['image_url']   ?? '';
    $linkUrl    = $widget->settings['link_url']    ?? '';
    $linkTarget = $widget->settings['link_target'] ?? '_self';
    $altText    = $widget->settings['alt_text']    ?? ($widget->title ?: '');
    $caption    = $widget->settings['caption']     ?? '';
?>

<?php if($imageUrl): ?>
<div class="widget mb-12">
    <?php if($widget->title): ?>
        <h4 class="widget-title"><?php echo e($widget->title); ?></h4>
    <?php endif; ?>

    <figure class="m-0">
        <?php if($linkUrl): ?>
            <a href="<?php echo e($linkUrl); ?>" target="<?php echo e($linkTarget); ?>" rel="<?php echo e($linkTarget === '_blank' ? 'noopener noreferrer' : ''); ?>" class="block rounded-xl">
                <img src="<?php echo e($imageUrl); ?>"
                     alt="<?php echo e($altText); ?>"
                     class="w-full h-auto rounded-xl"
                     loading="lazy">
            </a>
        <?php else: ?>
            <img src="<?php echo e($imageUrl); ?>"
                 alt="<?php echo e($altText); ?>"
                 class="w-full h-auto rounded-xl"
                 loading="lazy">
        <?php endif; ?>

        <?php if($caption): ?>
            <figcaption class="text-xs text-slate-400 text-center mt-2 leading-snug"><?php echo e($caption); ?></figcaption>
        <?php endif; ?>
    </figure>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/frontend/widgets/image.blade.php ENDPATH**/ ?>