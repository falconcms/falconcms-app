
<?php if(empty($cart)): ?>
    <?php do_falcon_action('lazy_mini_cart_empty'); ?>
    <div class="flex flex-col items-center justify-center text-center py-20 px-6">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <i data-lucide="shopping-cart" class="w-7 h-7 text-gray-300"></i>
        </div>
        <p class="text-[15px] font-semibold text-heading mb-1">Your cart is empty</p>
        <p class="text-[13px] text-gray-400">Add some products to get started.</p>
    </div>
<?php else: ?>
    <?php do_falcon_action('lazy_before_mini_cart', $cart); ?>
    <ul class="divide-y divide-gray-100">
        <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $linePrice = $item['sale_price'] ?: $item['price']; ?>
            <?php do_falcon_action('lazy_before_mini_cart_item', $item, $key); ?>
            <li class="flex gap-3 py-4 px-5">
                <a href="<?php echo e(get_falcon_permalink(['slug' => $item['slug'], 'type' => 'product'])); ?>" class="shrink-0 block w-16 h-16 rounded-sm overflow-hidden bg-[#eef1f5]">
                    <img src="<?php echo e(get_falcon_image_url($item['thumbnail'])); ?>" alt="<?php echo e($item['name']); ?>" class="w-full h-full object-cover">
                </a>
                <div class="flex-1 min-w-0">
                    <?php echo apply_falcon_filters('lazy_mini_cart_item_name',
                        '<a href="' . get_falcon_permalink(['slug' => $item['slug'], 'type' => 'product']) . '" class="block text-[13px] font-bold text-heading hover:text-primary leading-snug line-clamp-2">' . e($item['name']) . '</a>',
                        $item, $key); ?>

                    <div class="mt-1 text-[12px] text-gray-500">
                        <?php echo e($item['quantity']); ?> &times; <span class="font-semibold text-heading"><?php echo e(falcon_price_format($linePrice)); ?></span>
                    </div>
                    <?php echo falcon_render_item_custom_fields($item, 'mini-cart'); ?>

                    <?php do_falcon_action('lazy_mini_cart_item_meta', $item, $key); ?>
                </div>
                <div class="flex flex-col items-end justify-between shrink-0">
                    <button type="button" onclick="LazyCart.remove('<?php echo e($key); ?>')" title="Remove" class="text-gray-300 hover:text-red-500 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                    <span class="text-[13px] font-bold text-heading"><?php echo e(falcon_price_format($linePrice * $item['quantity'])); ?></span>
                </div>
            </li>
            <?php do_falcon_action('lazy_after_mini_cart_item', $item, $key); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php do_falcon_action('lazy_after_mini_cart', $cart); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/themes/falcon-theme/ecommerce/mini-cart-items.blade.php ENDPATH**/ ?>