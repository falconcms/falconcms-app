<?php $__env->startSection('title', 'Order Confirmation'); ?>

<?php $__env->startSection('content'); ?>
    <?php do_falcon_action('lazy_before_order_confirmation', $order); ?>
    <div class="bg-gray-50 py-20 min-h-screen font-sans">
        <div class="container-custom">
            <div class="bg-white rounded-sm shadow-sm border border-gray-100 p-10 text-center mb-10">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-heading mb-2">Thank you for your order!</h1>
                <p class="text-body text-lg mb-6">Your order has been received and is now being processed.</p>
                <a href="<?php echo e(route('shop.track', ['order_number' => $order->order_number, 'email' => $order->customer_email])); ?>"
                   class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-sm font-bold text-sm hover:bg-primary-hover transition uppercase">
                    <i data-lucide="truck" class="w-4 h-4"></i> Track your order
                </a>
            </div>

            <div class="bg-white rounded-sm shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-100 flex flex-wrap justify-between gap-6">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Order
                            Number</span>
                        <strong class="text-heading"><?php echo e($order->order_number); ?></strong>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date</span>
                        <strong class="text-heading"><?php echo e(cms_date($order->created_at, 'M d, Y')); ?></strong>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total</span>
                        <strong class="text-primary font-bold"><?php echo e(falcon_price_format($order->total, $order)); ?></strong>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Payment
                            Method</span>
                        <?php
                            $orderGateways = falcon_enabled_payment_gateways();
                            $pmLabel =
                                $orderGateways[$order->payment_method]['title'] ??
                                ucwords(str_replace('_', ' ', $order->payment_method ?: 'N/A'));
                        ?>
                        <strong class="text-heading"><?php echo e($pmLabel); ?></strong>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Payment
                            Status</span>
                        <?php if($order->paid_at): ?>
                            <strong class="text-green-600">Paid</strong>
                        <?php else: ?>
                            <strong class="text-amber-600">Awaiting Payment</strong>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php if($order->payment_method === 'bank' && get_shop_option('shop_payment_bank_details')): ?>
                    <div class="px-8 pt-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-sm p-5">
                            <h3 class="text-sm font-bold text-heading mb-2 uppercase tracking-wide">Bank Transfer Details
                            </h3>
                            <p class="text-[13px] text-body whitespace-pre-line leading-relaxed">
                                <?php echo e(get_shop_option('shop_payment_bank_details')); ?></p>
                            <p class="text-[12px] text-gray-500 mt-3">Please use your order number
                                <strong><?php echo e($order->order_number); ?></strong> as the payment reference.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="p-8">
                    <h2 class="text-xl font-bold text-heading mb-6 border-b border-gray-100 pb-4 uppercase tracking-tight">
                        Order Details</h2>

                    <table class="w-full text-left mb-8 text-[14px]">
                        <thead>
                            <tr class="text-gray-500 border-b border-gray-100">
                                <th class="pb-3 font-bold uppercase tracking-wider">Product</th>
                                <th class="pb-3 font-bold text-right uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-body">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-gray-50">
                                    <td class="py-4">
                                        <?php echo apply_falcon_filters('lazy_order_confirmation_item_name',
                                            e($item->product_name) . ' <strong class="text-heading">× ' . (int)$item->quantity . '</strong>',
                                            $item, $order); ?>

                                        <?php echo falcon_render_item_custom_fields($item, 'confirmation'); ?>

                                        <?php do_falcon_action('lazy_order_confirmation_item_meta', $item, $order); ?>
                                    </td>
                                    <td class="py-4 text-right font-medium">
                                        <?php echo e(falcon_price_format($item->subtotal, $order)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="text-body">
                            <tr>
                                <td class="pt-6 pb-3 font-bold">Subtotal:</td>
                                <td class="pt-6 pb-3 text-right font-bold"><?php echo e(falcon_price_format($order->subtotal, $order)); ?>

                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 font-bold">Shipping:</td>
                                <td class="py-3 text-right font-bold">
                                    <?php echo e(falcon_price_format($order->shipping_total, $order)); ?></td>
                            </tr>
                            <?php if($order->tax_total > 0): ?>
                                <tr>
                                    <td class="py-3 font-bold">Tax:</td>
                                    <td class="py-3 text-right font-bold">
                                        <?php echo e(falcon_price_format($order->tax_total, $order)); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if($order->coupon_code): ?>
                                <tr>
                                    <td class="py-3 font-bold text-emerald-700 text-sm">Coupons
                                        (<?php echo e($order->coupon_code); ?>):</td>
                                    <td class="py-3 text-right font-bold text-emerald-700">
                                        <?php echo e(falcon_price_format($order->discount_total, $order)); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr class="text-heading text-lg border-t border-gray-100">
                                <td class="pt-4 font-black">Total:</td>
                                <td class="pt-4 text-right font-black text-primary">
                                    <?php echo e(falcon_price_format($order->total, $order)); ?></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-10">
                        <div>
                            <h3
                                class="text-lg font-bold text-heading mb-4 border-b border-gray-100 pb-2 uppercase tracking-tight">
                                Billing Address</h3>
                            <address class="not-italic text-body leading-relaxed">
                                <?php echo e($order->first_name); ?> <?php echo e($order->last_name); ?><br>
                                <?php echo e($order->address_line_1); ?><br>
                                <?php if($order->address_line_2): ?>
                                    <?php echo e($order->address_line_2); ?><br>
                                <?php endif; ?>
                                <?php echo e($order->city); ?>, <?php echo e($order->state); ?> <?php echo e($order->postcode); ?><br>
                                <?php echo e($order->country); ?><br>
                                <div class="mt-4 text-sm">
                                    <span class="block mb-1"><strong class="text-heading">Phone:</strong>
                                        <?php echo e($order->customer_phone); ?></span>
                                    <span class="block"><strong class="text-heading">Email:</strong>
                                        <?php echo e($order->customer_email); ?></span>
                                </div>
                            </address>
                            <?php $confCheckoutMeta = $order->meta['checkout_fields'] ?? []; ?>
                            <?php if(!empty($confCheckoutMeta)): ?>
                                <?php $confLabels = apply_falcon_filters('falcon_checkout_field_labels', []); ?>
                                <div class="mt-4 pt-4 border-t border-gray-100 space-y-1 text-sm text-body">
                                    <?php $__currentLoopData = $confCheckoutMeta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cKey => $cVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($cVal): ?>
                                        <div>
                                            <strong class="text-heading"><?php echo e($confLabels[$cKey] ?? ucwords(str_replace('_', ' ', $cKey))); ?>:</strong>
                                            <?php echo e($cVal); ?>

                                        </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if($order->shipping_address_line_1): ?>
                            <div>
                                <h3
                                    class="text-lg font-bold text-heading mb-4 border-b border-gray-100 pb-2 uppercase tracking-tight">
                                    Shipping Address</h3>
                                <address class="not-italic text-body leading-relaxed">
                                    <?php echo e($order->shipping_first_name); ?> <?php echo e($order->shipping_last_name); ?><br>
                                    <?php echo e($order->shipping_address_line_1); ?><br>
                                    <?php if($order->shipping_address_line_2): ?>
                                        <?php echo e($order->shipping_address_line_2); ?><br>
                                    <?php endif; ?>
                                    <?php echo e($order->shipping_city); ?>, <?php echo e($order->shipping_state); ?>

                                    <?php echo e($order->shipping_postcode); ?><br>
                                    <?php echo e($order->shipping_country); ?>

                                </address>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php $timeline = $order->statusHistory; ?>
            <?php if($timeline->isNotEmpty()): ?>
            <div class="bg-white rounded-sm shadow-sm border border-gray-100 mt-6 p-8">
                <h2 class="text-xl font-bold text-heading mb-8 border-b border-gray-100 pb-4">Timeline</h2>

                <div class="space-y-0">
                <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isLast  = $loop->last;
                        $isBad   = in_array($entry->status, ['cancelled', 'failed']);
                        $label   = \FalconCms\Core\Models\OrderStatusHistory::label($entry->status);
                        $note    = $entry->note ?: \FalconCms\Core\Models\OrderStatusHistory::defaultNote($entry->status);
                        $showTrack = in_array($entry->status, ['delivering', 'shipped'])
                                   && ($order->tracking_number || $order->tracking_url);
                        $trackUrl  = $order->tracking_url
                                   ?: route('shop.track', ['order_number' => $order->order_number, 'email' => $order->customer_email]);
                    ?>
                    <div class="flex items-start gap-0 <?php echo e($isLast ? '' : 'mb-0'); ?>">

                        
                        <div class="hidden sm:flex flex-col items-end justify-start w-36 flex-shrink-0 pt-0.5 pr-4">
                            <span class="text-[13px] text-gray-500 font-medium leading-tight"><?php echo e(cms_date($entry->created_at, 'd M Y')); ?></span>
                            <span class="text-[12px] text-gray-400 mt-0.5"><?php echo e(cms_date($entry->created_at, 'h:i a')); ?></span>
                        </div>

                        
                        <div class="flex flex-col items-center flex-shrink-0">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10"
                                 style="background-color: <?php echo e($isBad ? '#ef4444' : get_cms_option('theme_primary_color','#0d9488')); ?>">
                                <?php if($isBad): ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                <?php endif; ?>
                            </div>
                            <?php if(!$isLast): ?>
                                <div class="w-0.5 flex-1 min-h-[48px]" style="background-color: <?php echo e(get_cms_option('theme_primary_color','#0d9488')); ?>; opacity:.35"></div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-1 pl-4 <?php echo e($isLast ? 'pb-0' : 'pb-8'); ?> pt-0.5">
                            <span class="sm:hidden text-[11px] text-gray-400 block mb-0.5"><?php echo e(cms_date($entry->created_at, 'd M Y, h:i a')); ?></span>
                            <p class="font-bold text-heading text-[15px] leading-snug"><?php echo e($label); ?></p>
                            <p class="text-[14px] text-body mt-1 leading-relaxed"><?php echo e($note); ?></p>
                            <?php if($showTrack): ?>
                                <a href="<?php echo e($trackUrl); ?>" target="_blank"
                                   class="inline-block mt-1.5 text-[14px] font-semibold underline underline-offset-2 hover:opacity-75"
                                   style="color: <?php echo e(get_cms_option('theme_primary_color','#0d9488')); ?>">
                                    Track Order
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    <?php do_falcon_action('lazy_after_order_confirmation', $order); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('falcon-cms::themes.falcon-theme.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/themes/falcon-theme/ecommerce/confirmation.blade.php ENDPATH**/ ?>