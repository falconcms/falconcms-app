<!-- Header Area -->
<header class="main-header w-full sticky top-0 z-[100]">
    <div class="container-custom h-full">
        <div class="flex items-center justify-between h-full">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="<?php echo e(url('/')); ?>" class="flex items-center gap-2">
                    <img src="<?php echo e(get_cms_option('theme_site_logo', asset('vendor/falcon-cms/images/falcon-cms-logo.png'))); ?>" alt="<?php echo e(get_cms_option('site_title', 'FalconCMS')); ?>" class="h-10 w-auto">
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-8 h-full lb-desktop-nav">
                <?php $menuItems = get_lazy_menu('header'); ?>
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $isActive = (url()->current() == $item->url) || (request()->is(ltrim(parse_url($item->url, PHP_URL_PATH), '/')));
                        $itemHoverColor = get_cms_option('theme_menu_hover_color', '#0091ea');
                    ?>
                    <div class="relative group h-full flex items-center">
                        <a href="<?php echo e($item->url); ?>" target="<?php echo e($item->target ?? '_self'); ?>" class="nav-style <?php echo e($isActive ? 'text-primary' : ''); ?> hover:text-[<?php echo e($itemHoverColor); ?>] transition-colors flex items-center gap-1">
                            <?php
                                $__ic = $item->icon ?? '';
                                $__io = !empty($item->show_only_icon) && $__ic !== '';
                                $__iconHtml = $__ic !== '' ? '<i class="'.e($__ic).'"'.($__io ? ' title="'.e($item->title).'"' : '').'></i>' : '';
                            ?>
                            <?php echo $__iconHtml; ?><?php if(!$__io): ?><?php echo e($item->title); ?><?php endif; ?>
                            <?php if($item->children->count() > 0): ?>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 group-hover:text-primary transition-colors <?php echo e($isActive ? 'text-primary' : ''); ?>"></i>
                            <?php endif; ?>
                        </a>
                        
                        <?php if($item->children->count() > 0): ?>
                            <div class="absolute top-full left-0 w-56 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50"
                                 style="background-color: <?php echo e(get_cms_option('theme_dropdown_bg', '#ffffff')); ?>; border: 1px solid var(--border-color);">
                                <ul class="py-2">
                                    <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="relative group/sub">
                                            <a href="<?php echo e($child->url); ?>" target="<?php echo e($child->target ?? '_self'); ?>" class="flex items-center justify-between px-5 py-2.5 text-[13px] font-medium hover:bg-slate-50 transition-all"
                                               style="color: <?php echo e(get_cms_option('theme_dropdown_text_color', '#1d2327')); ?>;">
                                                <?php
                                                    $__cic = $child->icon ?? '';
                                                    $__cio = !empty($child->show_only_icon) && $__cic !== '';
                                                    $__cIconHtml = $__cic !== '' ? '<i class="'.e($__cic).'"'.($__cio ? ' title="'.e($child->title).'"' : '').'></i>' : '';
                                                ?>
                                                <span class="flex items-center gap-1.5"><?php echo $__cIconHtml; ?><?php if(!$__cio): ?><?php echo e($child->title); ?><?php endif; ?></span>
                                                <?php if($child->children->count() > 0): ?>
                                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
                                                <?php endif; ?>
                                            </a>
                                            
                                            <?php if($child->children->count() > 0): ?>
                                                <div class="absolute top-0 left-full w-56 shadow-xl opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-200 transform translate-x-2 group-hover/sub:translate-x-0 z-50"
                                                     style="background-color: <?php echo e(get_cms_option('theme_dropdown_bg', '#ffffff')); ?>; border: 1px solid var(--border-color);">
                                                    <ul class="py-2">
                                                        <?php $__currentLoopData = $child->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grandChild): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <a href="<?php echo e($grandChild->url); ?>" target="<?php echo e($grandChild->target ?? '_self'); ?>" class="block px-5 py-2.5 text-[13px] font-medium hover:bg-slate-50 transition-all"
                                                                   style="color: <?php echo e(get_cms_option('theme_dropdown_text_color', '#1d2327')); ?>;">
                                                                    <?php
                                                                        $__gic = $grandChild->icon ?? '';
                                                                        $__gio = !empty($grandChild->show_only_icon) && $__gic !== '';
                                                                        $__gIconHtml = $__gic !== '' ? '<i class="'.e($__gic).' mr-1.5"'.($__gio ? ' title="'.e($grandChild->title).'"' : '').'></i>' : '';
                                                                    ?>
                                                                    <?php echo $__gIconHtml; ?><?php if(!$__gio): ?><?php echo e($grandChild->title); ?><?php endif; ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-5">
                <!-- Language Switcher -->
                <?php echo falcon_lang_dropdown(); ?>


                <!-- Cart Icon -->
                <a href="<?php echo e(route('shop.cart')); ?>" class="relative group hover:text-primary transition-colors" style="color: inherit;">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <?php $count = get_falcon_cart_count(); ?>
                    <span class="cart-count-badge absolute -top-2.5 -right-2.5 bg-primary text-white text-[10px] font-black w-4 h-4 flex items-center justify-center rounded-full ring-2 ring-white <?php echo e($count > 0 ? '' : 'hidden'); ?>">
                        <?php echo e($count); ?>

                    </span>
                </a>

                <button class="hover:text-primary transition-colors" style="color: inherit;" onclick="document.getElementById('search-bar').classList.toggle('hidden')">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                
                <button class="lg:hidden hover:text-primary transition-colors lb-mobile-btn" style="color: inherit;" onclick="document.getElementById('mobile-menu').classList.remove('translate-x-full')">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Dropdown Search Bar -->
    <div id="search-bar" class="hidden absolute top-full left-0 w-full bg-white border-b border-slate-100 p-4 shadow-sm z-40">
        <div class="container-custom">
            <form action="<?php echo e(route('frontend.search')); ?>" method="GET" class="relative max-w-2xl mx-auto">
                <input type="text" name="s" placeholder="Search for stories..." class="w-full bg-slate-50 border-none rounded-full px-6 py-3 text-sm focus:ring-2 focus:ring-primary/20">
                <button type="submit" class="absolute right-2 top-1.5 bottom-1.5 px-4 bg-primary text-white rounded-full text-xs font-bold">SEARCH</button>
            </form>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[200] transform translate-x-full transition-transform duration-300 lg:hidden lb-mobile-menu">
    <div class="absolute right-0 top-0 h-full w-80 bg-white shadow-2xl flex flex-col">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <span class="text-lg font-bold text-slate-900">Navigation</span>
            <button class="text-slate-500 hover:text-primary transition-colors" onclick="document.getElementById('mobile-menu').classList.add('translate-x-full')">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        
        <!-- Mobile Language Switcher -->
        <div class="p-6 border-b border-slate-100 lg:hidden">
            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-wider mb-3">Select Language</p>
            <?php echo lazy_mobile_lang_switcher(); ?>

        </div>
        <div class="flex-grow overflow-y-auto p-6">
            <nav class="space-y-4">
                <?php $__currentLoopData = $menuItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $isActive = (url()->current() == $item->url) || (request()->is(ltrim(parse_url($item->url, PHP_URL_PATH), '/')));
                    ?>
                    <div>
                        <?php
                            $__mic = $item->icon ?? '';
                            $__mio = !empty($item->show_only_icon) && $__mic !== '';
                            $__mIconHtml = $__mic !== '' ? '<i class="'.e($__mic).' mr-2"'.($__mio ? ' title="'.e($item->title).'"' : '').'></i>' : '';
                        ?>
                        <a href="<?php echo e($item->url); ?>" target="<?php echo e($item->target ?? '_self'); ?>" class="text-[15px] font-bold <?php echo e($isActive ? 'text-primary' : 'text-slate-800'); ?> hover:text-primary block mb-2"><?php echo $__mIconHtml; ?><?php if(!$__mio): ?><?php echo e($item->title); ?><?php endif; ?></a>
                        <?php if($item->children->count() > 0): ?>
                            <div class="pl-4 space-y-2 border-l border-slate-100 ml-1">
                                <?php $__currentLoopData = $item->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                        $childActive = (url()->current() == $child->url) || (request()->is(ltrim(parse_url($child->url, PHP_URL_PATH), '/')));
                                    ?>
                                    <?php
                                        $__mcic = $child->icon ?? '';
                                        $__mcio = !empty($child->show_only_icon) && $__mcic !== '';
                                        $__mcIconHtml = $__mcic !== '' ? '<i class="'.e($__mcic).' mr-2"'.($__mcio ? ' title="'.e($child->title).'"' : '').'></i>' : '';
                                    ?>
                                    <a href="<?php echo e($child->url); ?>" target="<?php echo e($child->target ?? '_self'); ?>" class="text-[14px] font-medium <?php echo e($childActive ? 'text-primary' : 'text-slate-600'); ?> hover:text-primary block"><?php echo $__mcIconHtml; ?><?php if(!$__mcio): ?><?php echo e($child->title); ?><?php endif; ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/themes/falcon-theme/partials/header.blade.php ENDPATH**/ ?>