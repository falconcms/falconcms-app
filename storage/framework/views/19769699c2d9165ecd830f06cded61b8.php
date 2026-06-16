<div class="widget mb-12">
    <?php if($widget->title): ?>
        <h4 class="widget-title"><?php echo e($widget->title); ?></h4>
    <?php endif; ?>
    <ul class="space-y-3">
        <?php
            $postType = $widget->settings['post_type'] ?? 'auto';
            if ($postType === 'auto') {
                $currentPost = view()->shared('current_post');
                $postType = $currentPost ? $currentPost->type : 'post';
            }
            $categories = get_lazy_categories('category', $postType);
            $showCount  = ($widget->settings['show_count'] ?? '1') === '1';
            $catInfo    = get_lazy_category_taxonomy($postType);
        ?>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <?php
                    if ($catInfo['type'] === 'product') {
                        $catUrl = route('frontend.product_category', $cat->getFullSlugPath());
                    } elseif ($catInfo['type'] === 'acpt') {
                        $catUrl = route('frontend.show', ['typeOrSlug' => $catInfo['taxonomy_slug'], 'slug' => $cat->slug]);
                    } else {
                        $catUrl = route('frontend.category', $cat->slug);
                    }
                ?>
                <a href="<?php echo e($catUrl); ?>" class="flex items-center justify-between group">
                    <span class="text-sm text-slate-600 group-hover:text-primary transition-colors"><?php echo e($cat->name); ?></span>
                    <?php if($showCount): ?>
                        <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full"><?php echo e($cat->posts_count ?? 0); ?></span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/frontend/widgets/categories.blade.php ENDPATH**/ ?>