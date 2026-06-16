<?php
    $content = $widget->settings['content'] ?? '';
?>

<?php if($content): ?>
<div class="widget mb-12">
    <?php if($widget->title): ?>
        <h4 class="widget-title"><?php echo e($widget->title); ?></h4>
    <?php endif; ?>
    <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed
                prose-headings:text-slate-800 prose-headings:font-semibold
                prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                prose-strong:text-slate-700 prose-li:my-0.5">
        <?php echo falcon_sanitize_html($content); ?>

    </div>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/frontend/widgets/text.blade.php ENDPATH**/ ?>