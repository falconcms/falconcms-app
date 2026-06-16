<?php
    $menuId   = $widget->settings['menu_id'] ?? null;
    $navItems = collect();

    if ($menuId) {
        $navMenu = \FalconCms\Core\Models\NavigationMenu::with(['items' => fn($q) => $q->orderBy('order')])->find($menuId);
        if ($navMenu) {
            $allItems  = $navMenu->items;
            $navItems  = $allItems->where('parent_id', null)->values();
        }
    }
?>

<?php if($navItems->isNotEmpty()): ?>
<div class="widget mb-12">
    <?php if($widget->title): ?>
        <h4 class="widget-title"><?php echo e($widget->title); ?></h4>
    <?php endif; ?>
    <ul class="space-y-1">
        <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $children = isset($allItems) ? $allItems->where('parent_id', $item->id)->values() : collect();
                $isActive = rtrim(request()->url(), '/') === rtrim($item->url, '/');
            ?>
            <li>
                <a href="<?php echo e($item->url); ?>"
                   target="<?php echo e($item->target ?? '_self'); ?>"
                   class="flex items-center gap-2 text-sm py-1.5 px-2 rounded-lg transition-colors <?php echo e($isActive ? 'text-primary font-bold bg-primary/5' : 'text-slate-600 hover:text-primary hover:bg-slate-50'); ?>">
                    <?php if(!empty($item->icon)): ?>
                        <i class="<?php echo e($item->icon); ?> w-4 text-center opacity-60 text-xs"></i>
                    <?php endif; ?>
                    <?php if(!$item->show_only_icon): ?>
                        <span><?php echo e($item->title); ?></span>
                    <?php endif; ?>
                </a>

                <?php if($children->isNotEmpty()): ?>
                    <ul class="ml-4 mt-0.5 space-y-0.5 border-l-2 border-slate-100 pl-3">
                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $childActive = rtrim(request()->url(), '/') === rtrim($child->url, '/'); ?>
                            <li>
                                <a href="<?php echo e($child->url); ?>"
                                   target="<?php echo e($child->target ?? '_self'); ?>"
                                   class="flex items-center gap-2 text-sm py-1 transition-colors <?php echo e($childActive ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary'); ?>">
                                    <?php if(!empty($child->icon)): ?>
                                        <i class="<?php echo e($child->icon); ?> text-xs opacity-60"></i>
                                    <?php endif; ?>
                                    <?php if(!$child->show_only_icon): ?>
                                        <span><?php echo e($child->title); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/frontend/widgets/nav_menu.blade.php ENDPATH**/ ?>