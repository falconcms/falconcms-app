<div class="flex flex-wrap items-center gap-1 border-b border-[#c3c4c7] mb-8">
    <?php
        $shopTabs = [
            'general'        => 'General',
            'products'       => 'Product & Inventory',
            'payments'       => 'Payments',
            'shipping'       => 'Shipping',
            'tax'            => 'Tax',
            'coupons'        => 'Coupons',
            'emails_accounts'=> 'Email and Account',
        ];
        $activeNavTab = request('tab', session('active_shop_tab', 'general'));
    ?>

    <?php $__currentLoopData = $shopTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $isActive = $activeNavTab === $key; ?>
    <a href="<?php echo e(route('admin.shop.settings')); ?>?tab=<?php echo e($key); ?>"
       @click="tab = '<?php echo e($key); ?>'; $nextTick(() => window.history.replaceState({}, '', '?tab=<?php echo e($key); ?>')); $event.preventDefault()"
       :class="tab === '<?php echo e($key); ?>' ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'"
       class="px-4 py-2 text-[14px]">
        <?php echo e($label); ?>

    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/admin/shop/nav.blade.php ENDPATH**/ ?>