<div id="adminmenuwrap" class="fixed top-8 left-0 bottom-0 w-40 bg-[#1d2327] overflow-y-auto overflow-x-hidden text-[#c3c4c7] z-40 pb-10 custom-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
    <?php
        // Defined once up front so it is always available below — even when there are no
        // menu groups yet (e.g. a freshly-migrated database before seeding).
        $user = auth()->user();
        $isAdmin = $user ? $user->isAdmin() : false;
    ?>
    <ul class="pt-0">
        <?php $__currentLoopData = $menuGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $menus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Multiple-roles aware: admin if ANY of the user's roles is admin/super-admin.
                $isAdmin = $user ? $user->isAdmin() : false;
                
                $visibleMenus = $menus->filter(function($menu) use ($getPermission, $isAdmin, $user) {
                    if ($isAdmin) return true;
                    if ($user->hasPermission($getPermission($menu))) return true;
                    foreach($menu->children as $child) {
                        if ($user->hasPermission($getPermission($child))) return true;
                    }
                    return false;
                });
            ?>

            <?php if($visibleMenus->isNotEmpty()): ?>
                <?php if($groupName && $groupName !== 'Main'): ?>
                    <li class="mt-4 mb-1 px-3 text-[11px] font-semibold text-[#8c8f94] uppercase tracking-wider"><?php echo e($groupName); ?></li>
                <?php endif; ?>
                <?php $__currentLoopData = $visibleMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $hasChildren = $menu->children->isNotEmpty();
                                    $href = $resolveRoute($menu);
                                    if ($hasChildren) {
                                        $hasParentPermission = $isAdmin || auth()->user()->hasPermission($getPermission($menu));
                                        foreach($menu->children as $child) {
                                            if ($isAdmin || auth()->user()->hasPermission($getPermission($child))) {
                                                if (!$hasParentPermission || $menu->route === $child->route || !$menu->route || $menu->route === '#') {
                                                    $href = $resolveRoute($child);
                                                }
                                                break; 
                                            }
                                        }
                                    }
                                    if ($menu->title === 'Help' && get_cms_option('enable_documentation', '1') !== '1') continue;
                                    $isActive = \FalconCms\Core\View\Components\Admin\Sidebar::isUrlActive($href);
                                    if (!$isActive && $hasChildren) {
                                        foreach($menu->children as $child) {
                                            if ($isAdmin || auth()->user()->hasPermission($getPermission($child))) {
                                                if (\FalconCms\Core\View\Components\Admin\Sidebar::isUrlActive($resolveRoute($child))) {
                                                    $isActive = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                    $isComments = ($menu->title === 'Comments');
                                    $isForms    = ($menu->title === 'Forms');
                                    $isShop     = ($menu->title === 'Shop');
                                    $liClasses = 'group sidebar-item relative';
                                ?>
                            <li class="<?php echo e($liClasses); ?>">
                                <a href="<?php echo e($href); ?>" class="sidebar-item-link relative flex items-center px-3 py-[8px] transition-colors <?php echo e($isActive ? 'bg-[#2271b1] text-white' : 'hover:bg-[#1d2327] hover:text-[#72aee6] text-[#c3c4c7]'); ?>">
                                    <div class="w-6 h-6 mr-3 flex items-center justify-center <?php echo $isActive ? 'text-white' : 'text-[#a7aaad] group-hover:text-[#72aee6]'; ?>">
                                        <?php if(str_starts_with($menu->icon, '<svg')): ?>
                                            <div class="w-5 h-5 flex items-center justify-center"><?php echo $menu->icon; ?></div>
                                        <?php else: ?>
                                            <span class="material-symbols-outlined text-[20px] leading-none" style="font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 20;"><?php echo e($menu->icon ?: 'radio_button_unchecked'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[14px] leading-none <?php echo e($isActive ? 'font-semibold' : ''); ?> flex items-center gap-2">
                                        <?php echo e($menu->title); ?>

                                        <?php if($isComments): ?>
                                            <?php $unreadComments = \FalconCms\Core\Models\Comment::where('is_read', false)->count(); ?>
                                            <?php if($unreadComments > 0): ?>
                                                <span class="bg-[#d63638] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[16px] text-center"><?php echo e($unreadComments); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if($isForms): ?>
                                            <?php $unreadSubmissions = \FalconCms\Core\Models\FormSubmission::where('is_read', false)->count(); ?>
                                            <?php if($unreadSubmissions > 0): ?>
                                                <span class="bg-[#d63638] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[16px] text-center"><?php echo e($unreadSubmissions); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if($isShop): ?>
                                            <?php try { $unreadOrders = \FalconCms\Core\Models\Order::where('is_read', false)->count(); } catch (\Exception $e) { $unreadOrders = 0; } ?>
                                            <?php if($unreadOrders > 0): ?>
                                                <span class="bg-[#d63638] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[16px] text-center"><?php echo e($unreadOrders); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </span>
                                    <?php if($isActive): ?>
                                        <div class="absolute -right-[1px] top-1/2 -translate-y-1/2 w-0 h-0 border-y-[7px] border-y-transparent border-r-[7px] border-r-[#f0f0f1] z-50"></div>
                                    <?php endif; ?>
                                </a>
                                <?php if($hasChildren): ?>
                                    <?php if($isActive): ?>
                                        <div class="bg-[#2c3338] block w-full">
                                            <ul class="py-1">
                                                <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        if (!$isAdmin && !auth()->user()->hasPermission($getPermission($child))) continue;
                                                        $childHref = $resolveRoute($child);
                                                        $isChildActive = \FalconCms\Core\View\Components\Admin\Sidebar::isUrlActive($childHref, true);
                                                    ?>
                                                    <li>
                                                        <a href="<?php echo e($childHref); ?>" class="block px-3 py-[6px] transition text-[13px] <?php echo e($isChildActive ? 'text-white font-semibold' : 'text-[#c3c4c7] hover:text-[#72aee6]'); ?>">
                                                            <?php echo e($child->title); ?>

                                                            <?php if($child->title === 'Orders'): ?>
                                                                <?php try { $unreadOrders = \FalconCms\Core\Models\Order::where('is_read', false)->count(); } catch (\Exception $e) { $unreadOrders = 0; } ?>
                                                                <?php if($unreadOrders > 0): ?>
                                                                    <span class="bg-[#d63638] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1"><?php echo e($unreadOrders); ?></span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php if($child->title === 'Updates'): ?>
                                                                <?php $lzUpd = cache()->get('falcon_cms_update_check'); ?>
                                                                <?php if(!empty($lzUpd['has_update'])): ?>
                                                                    <span class="bg-[#46b450] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1">NEW</span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <div class="sidebar-flyout hidden bg-[#2c3338] w-40 z-[9999] shadow-lg">
                                            <div class="absolute -left-[6px] top-[10px] w-0 h-0 border-y-[6px] border-y-transparent border-r-[6px] border-r-[#2c3338]"></div>
                                            <ul class="py-1">
                                                <?php $__currentLoopData = $menu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if (!$isAdmin && !auth()->user()->hasPermission($getPermission($child))) continue; ?>
                                                    <li>
                                                        <a href="<?php echo e($resolveRoute($child)); ?>" class="block px-3 py-[6px] transition text-[13px] hover:text-[#72aee6] text-[#c3c4c7]">
                                                            <?php echo e($child->title); ?>

                                                            <?php if($child->title === 'Orders'): ?>
                                                                <?php try { $unreadOrders = \FalconCms\Core\Models\Order::where('is_read', false)->count(); } catch (\Exception $e) { $unreadOrders = 0; } ?>
                                                                <?php if($unreadOrders > 0): ?>
                                                                    <span class="bg-[#d63638] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1"><?php echo e($unreadOrders); ?></span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <?php if($child->title === 'Updates'): ?>
                                                                <?php $lzUpd = cache()->get('falcon_cms_update_check'); ?>
                                                                <?php if(!empty($lzUpd['has_update'])): ?>
                                                                    <span class="bg-[#46b450] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full ml-1">NEW</span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php 
            $customPages = config('falcon-options.pages') ?? []; 
            $groupedPages = [];
            foreach($customPages as $slug => $page) {
                $group = $page['group'] ?? 'Custom Options';
                if (is_array($group)) $group = reset($group); // Safety for array_merge_recursive leftovers
                $groupedPages[(string)$group][$slug] = $page;
            }
        ?>

        <?php $__currentLoopData = $groupedPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $pages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $visiblePages = array_filter($pages, function($slug) use ($isAdmin) {
                    return $isAdmin || auth()->user()->hasPermission('manage_options_' . $slug);
                }, ARRAY_FILTER_USE_KEY);
            ?>

            <?php if(!empty($visiblePages)): ?>
                <li class="mt-4 mb-1 px-3 text-[10px] font-semibold text-[#8c8f94] uppercase tracking-wider"><?php echo e($groupName); ?></li>
                <?php $__currentLoopData = $visiblePages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $href = route('admin.options.index', $slug);
                        $isActive = request()->is('admin/options/' . $slug);
                    ?>
                    <li class="group sidebar-item relative">
                        <a href="<?php echo e($href); ?>" class="sidebar-item-link relative flex items-center px-3 py-[8px] transition-colors <?php echo e($isActive ? 'bg-[#2271b1] text-white' : 'hover:bg-[#2c3338] hover:text-[#72aee6] text-[#c3c4c7]'); ?>">
                            <div class="w-6 h-6 mr-3 flex items-center justify-center <?php echo $isActive ? 'text-white' : 'text-[#a7aaad] group-hover:text-[#72aee6]'; ?>">
                                <?php if(isset($page['icon']) && str_starts_with($page['icon'], '<svg')): ?>
                                    <div class="w-5 h-5 flex items-center justify-center"><?php echo $page['icon']; ?></div>
                                <?php elseif(isset($page['icon'])): ?>
                                    <span class="material-symbols-outlined text-[20px] leading-none" style="font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 20;"><?php echo e($page['icon']); ?></span>
                                <?php else: ?>
                                    <span class="material-symbols-outlined text-[20px] leading-none" style="font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 20;">settings</span>
                                <?php endif; ?>
                            </div>
                            <span class="text-[14px] leading-none <?php echo e($isActive ? 'font-semibold' : ''); ?>"><?php echo e($page['title']); ?></span>
                            <?php if($isActive): ?>
                                <div class="absolute -right-[1px] top-1/2 -translate-y-1/2 w-0 h-0 border-y-[7px] border-y-transparent border-r-[7px] border-r-[#f0f0f1] z-50"></div>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <li class="border-t border-[#2c3338] mt-2">
            <button id="sidebar-toggle-btn"
                class="w-full flex items-center px-3 py-[8px] text-[#a7aaad] hover:text-[#72aee6] hover:bg-[#2c3338] transition-colors">
                <div class="w-6 h-6 mr-3 flex items-center justify-center flex-shrink-0 text-[#a7aaad]">
                    <span class="material-symbols-outlined collapse-icon text-[20px] leading-none" style="font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 20;">keyboard_double_arrow_left</span>
                </div>
                <span class="collapse-text text-[13px]">Collapse Menu</span>
            </button>
        </li>

    </ul>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('sidebar-toggle-btn');
        const body = document.body;

        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.querySelector('.collapse-icon').classList.add('rotate-180');
        }

        // Remove no-transition class after first paint so animations work normally after that
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                body.classList.remove('sidebar-no-transition');
            });
        });

        btn?.addEventListener('click', function() {
            body.classList.toggle('sidebar-collapsed');
            const isCollapsed = body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            document.querySelector('.collapse-icon').classList.toggle('rotate-180', isCollapsed);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<style>
.custom-scrollbar::-webkit-scrollbar { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarWrap = document.getElementById('adminmenuwrap');
    
    document.querySelectorAll('.sidebar-item').forEach(li => {
        const flyout = li.querySelector('.sidebar-flyout');
        const link = li.querySelector('a');
        
        if (flyout) {
            li.addEventListener('mouseenter', () => {
                const rect = li.getBoundingClientRect();
                flyout.style.position = 'fixed';
                flyout.style.top = rect.top + 'px';
                flyout.style.left = rect.right + 'px';
                flyout.classList.remove('hidden');
                
                // Add hover effect to parent link manually since we moved out of group-hover
                link.classList.add('bg-[#2c3338]', 'text-[#72aee6]');
            });
            
            li.addEventListener('mouseleave', () => {
                flyout.classList.add('hidden');
                link.classList.remove('bg-[#2c3338]', 'text-[#72aee6]');
            });
        }
    });

    // Make sure sidebar scrolling hides flyouts completely
    sidebarWrap.addEventListener('scroll', () => {
        document.querySelectorAll('.sidebar-flyout').forEach(flyout => {
            flyout.classList.add('hidden');
        });
    });
});
</script>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/components/admin/sidebar.blade.php ENDPATH**/ ?>