<?php $__env->startSection('title', $title ?? 'Shop'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* WooCommerce-style Pagination Overrides for Laravel Default Tailwind Pagination */
    nav[role="navigation"] { display: flex; align-items: center; justify-content: flex-start; margin-top: 2rem; }
    nav[role="navigation"] > div:first-child { display: none; } /* Hide the 'Showing X to Y...' text from pagination block */
    nav[role="navigation"] > div:last-child { display: flex; width: auto; }
    nav[role="navigation"] .relative.inline-flex { 
        padding: 0; width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px; border-radius: 2px; margin-right: 6px; border: 1px solid #b3d4f0; color: <?php echo e(get_cms_option('theme_primary_color', '#0091ea')); ?>; background: white; text-decoration: none;
    }
    nav[role="navigation"] span[aria-current="page"] > span {
        background-color: <?php echo e(get_cms_option('theme_primary_color', '#0091ea')); ?> !important; color: white !important; border-color: <?php echo e(get_cms_option('theme_primary_color', '#0091ea')); ?> !important; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
    }
    nav[role="navigation"] a.relative:hover { background-color: #f0f6fc; }
    nav[role="navigation"] svg { width: 16px; height: 16px; }
</style>

<div class="bg-white py-12 min-h-screen font-sans">
    <div class="container-custom">
        <h1 class="text-[36px] md:text-[42px] font-normal text-heading mb-8"><?php echo e($title ?? 'Shop'); ?></h1>

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 text-[14px] text-[#777]">
            <div class="mb-4 md:mb-0">
                <?php if($posts->count() > 0): ?>
                    Showing <?php echo e($posts->firstItem()); ?>&ndash;<?php echo e($posts->lastItem()); ?> of <?php echo e($posts->total()); ?> results
                <?php else: ?>
                    Showing all results
                <?php endif; ?>
            </div>
            <div>
                <form action="" method="GET" id="sorting-form">
                    <select name="orderby" class="border border-gray-200 rounded-sm bg-white focus:ring-0 focus:border-gray-300 text-[#777] cursor-pointer text-[14px] font-normal pl-3 pr-8 py-2" onchange="this.form.submit()">
                        <option value="latest" <?php echo e(request('orderby') == 'latest' ? 'selected' : ''); ?>>Default sorting</option>
                        <option value="popularity" <?php echo e(request('orderby') == 'popularity' ? 'selected' : ''); ?>>Sort by popularity</option>
                        <option value="rating" <?php echo e(request('orderby') == 'rating' ? 'selected' : ''); ?>>Sort by average rating</option>
                        <option value="latest" <?php echo e(request('orderby') == 'latest' ? 'selected' : ''); ?>>Sort by latest</option>
                        <option value="price" <?php echo e(request('orderby') == 'price' ? 'selected' : ''); ?>>Sort by price: low to high</option>
                        <option value="price-desc" <?php echo e(request('orderby') == 'price-desc' ? 'selected' : ''); ?>>Sort by price: high to low</option>
                    </select>
                </form>
            </div>
        </div>

        <?php if($posts->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-12">
            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group flex flex-col">
                    <div class="relative mb-4">
                    <a href="<?php echo e(get_falcon_permalink($product)); ?>" class="block relative pt-[100%] overflow-hidden bg-[#eef1f5]">
                        <?php if($product->featured_image): ?>
                            <img src="<?php echo e(str_starts_with($product->featured_image, 'http') ? $product->featured_image : asset('storage/'.$product->featured_image)); ?>" alt="<?php echo e($product->title); ?>" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-90 group-hover:opacity-100 transition-opacity">
                        <?php else: ?>
                            <img src="<?php echo e(asset('assets/images/placeholder.jpg')); ?>" alt="Placeholder" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-70">
                        <?php endif; ?>
                        <?php if(!$product->is_in_stock): ?>
                            <span class="absolute top-3 right-3 bg-red-600 text-white text-[11px] font-bold px-3 py-1 rounded-sm shadow-md uppercase tracking-wider z-10">Out of Stock</span>
                        <?php endif; ?>
                        <?php if($product->shopData && $product->shopData->sale_price): ?>
                            <span class="absolute top-3 left-3 bg-sky-100 text-sky-700 text-[13px] font-bold px-3 py-1 rounded-full shadow uppercase tracking-wide z-10">Sale!</span>
                        <?php endif; ?>
                    </a>
                        <div class="absolute bottom-3 right-3 z-20">
                            <?php echo $__env->make('falcon-cms::themes.falcon-theme.partials.wishlist-button', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                    <div class="flex flex-col flex-grow text-left px-1">
                        <div class="text-[12px] text-[#999] mb-0.5">
                            <?php if($product->taxonomyTerms && $product->taxonomyTerms->count() > 0): ?>
                                <?php echo e($product->taxonomyTerms->first()->name); ?>

                            <?php elseif($product->categories && $product->categories->count() > 0): ?>
                                <?php echo e($product->categories->first()->name); ?>

                            <?php else: ?>
                                Uncategorized
                            <?php endif; ?>
                        </div>
                        <h2 class="text-[15px] font-bold text-heading hover:text-primary transition-colors mb-1 leading-tight">
                            <a href="<?php echo e(get_falcon_permalink($product)); ?>"><?php echo e($product->title); ?></a>
                        </h2>
                        <div class="text-heading font-bold text-[14px] mb-3">
                            <?php if($product->shopData && $product->shopData->sale_price): ?>
                                <span class="line-through text-[#a5a5a5] font-normal mr-1.5"><?php echo e(falcon_price_format($product->shopData->price)); ?></span>
                                <span><?php echo e(falcon_price_format($product->shopData->sale_price)); ?></span>
                            <?php else: ?>
                                <span><?php echo e(falcon_price_format($product->shopData->price ?? 0)); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-auto flex flex-wrap gap-2">
                            <?php if(!$product->is_in_stock): ?>
                                <button disabled class="w-full text-center bg-gray-400 text-white cursor-not-allowed px-4 py-2.5 rounded-[3px] text-[13px] font-semibold uppercase tracking-wider">
                                    Out of stock
                                </button>
                            <?php elseif(falcon_is_variable_product($product)): ?>
                                <a href="<?php echo e(get_falcon_permalink($product)); ?>" class="w-full text-center bg-primary text-white px-4 py-2.5 rounded-[3px] text-[13px] font-semibold hover:bg-primary-hover transition-colors duration-200">
                                    Select Options
                                </a>
                            <?php else: ?>
                                <button onclick="addToCart(<?php echo e($product->id); ?>)" class="flex-1 bg-primary text-white px-4 py-2.5 rounded-[3px] text-[13px] font-semibold hover:bg-primary-hover transition-colors duration-200">
                                    Add to cart
                                </button>
                                <a href="<?php echo e(get_falcon_permalink($product)); ?>" class="flex-1 text-center bg-white text-primary border border-primary px-4 py-2.5 rounded-[3px] text-[13px] font-semibold hover:bg-gray-50 transition-colors duration-200">
                                    See Detail
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 flex justify-start">
            <?php echo e($posts->links()); ?>

        </div>
        <?php else: ?>
        <div class="bg-white p-10 text-center text-[#777]">
            <p class="text-lg mb-4">No products found.</p>
            <a href="<?php echo e(url('/')); ?>" class="inline-block bg-primary text-white px-6 py-2 rounded hover:bg-primary-hover transition">Return to Home</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function addToCart(productId) {
    const loadingSwal = Swal.fire({
        title: 'Adding to cart...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?php echo e(route('shop.cart.add')); ?>', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json', 
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(err => { throw err; });
        }
        return res.json();
    })
    .then(data => {
        loadingSwal.close();
        if(data.success) {
            Swal.fire({
                title: 'Added!',
                text: 'Product added to cart successfully.',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '<?php echo e(get_cms_option('theme_primary_color', '#0091ea')); ?>',
                confirmButtonText: 'View Cart',
                cancelButtonText: 'Continue Shopping',
                background: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo e(route('shop.cart')); ?>';
                }
            });
            updateCartBadge(data.cart_count);
        } else {
            Swal.fire({
                title: 'Error',
                text: data.message || 'Error adding to cart',
                icon: 'error',
                confirmButtonColor: '<?php echo e(get_cms_option('theme_primary_color', '#0091ea')); ?>'
            });
        }
    })
    .catch(err => {
        loadingSwal.close();
        Swal.fire({
            title: 'Error',
            text: 'Could not add product to cart. Please try again.',
            icon: 'error',
            confirmButtonColor: '<?php echo e(get_cms_option('theme_primary_color', '#0091ea')); ?>'
        });
    });
}

function updateCartBadge(count) {
    document.querySelectorAll('.cart-count-badge').forEach(badge => {
        badge.textContent = count;
        if(count > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('falcon-cms::themes.falcon-theme.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/themes/falcon-theme/archive-product.blade.php ENDPATH**/ ?>