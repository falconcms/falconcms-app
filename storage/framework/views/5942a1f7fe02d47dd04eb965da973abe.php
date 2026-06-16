<?php if (isset($component)) { $__componentOriginal3747c22dc85a29a75cf05ad0001919a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3747c22dc85a29a75cf05ad0001919a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'falcon-cms::components.layouts.admin','data' => ['title' => 'Pages']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('falcon-cms::layouts.admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pages']); ?>
    <?php if (isset($component)) { $__componentOriginal4b756fa799d09930f52a34f7fa196de7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4b756fa799d09930f52a34f7fa196de7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'falcon-cms::components.admin.delete-modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('falcon-cms::admin.delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4b756fa799d09930f52a34f7fa196de7)): ?>
<?php $attributes = $__attributesOriginal4b756fa799d09930f52a34f7fa196de7; ?>
<?php unset($__attributesOriginal4b756fa799d09930f52a34f7fa196de7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4b756fa799d09930f52a34f7fa196de7)): ?>
<?php $component = $__componentOriginal4b756fa799d09930f52a34f7fa196de7; ?>
<?php unset($__componentOriginal4b756fa799d09930f52a34f7fa196de7); ?>
<?php endif; ?>
    <div class="flex items-center mb-4">
        <h1 class="text-[23px] font-normal text-[#1d2327] mr-3">Pages</h1>
        <a href="<?php echo e(route('admin.pages.create')); ?>" class="wp-btn-secondary px-2 py-0.5 text-[12px] bg-white hover:bg-[#f6f7f7] border-[#2271b1] text-[#2271b1] leading-normal">Add New</a>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-[#fff] border-l-4 border-[#00a32a] shadow-[0_1px_1px_rgba(0,0,0,.04)] p-3 mb-4 rounded-sm text-[13px] flex justify-between items-center">
            <p><?php echo e(session('success')); ?></p>
            <button type="button" class="text-[#646970] hover:text-black" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-[#fff] border-l-4 border-[#d63638] shadow-[0_1px_1px_rgba(0,0,0,.04)] p-3 mb-4 rounded-sm text-[13px] flex justify-between items-center">
            <p><?php echo e(session('error')); ?></p>
            <button type="button" class="text-[#646970] hover:text-black" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2 gap-4">
        <div class="flex items-center text-[13px] text-[#646970]">
            <a href="<?php echo e(route('admin.pages.index')); ?>" class="<?php echo e(!request('status') ? 'text-black font-semibold' : 'text-[#2271b1]'); ?>">All <span class="text-[#646970]">(<?php echo e($allCount); ?>)</span></a>
            <span class="mx-1 text-[#c3c4c7]">|</span>
            <a href="<?php echo e(route('admin.pages.index', ['status' => 'published'])); ?>" class="<?php echo e(request('status') == 'published' ? 'text-black font-semibold' : 'text-[#2271b1]'); ?>">Published <span class="text-[#646970]">(<?php echo e($publishedCount); ?>)</span></a>
            <span class="mx-1 text-[#c3c4c7]">|</span>
            <a href="<?php echo e(route('admin.pages.index', ['status' => 'scheduled'])); ?>" class="<?php echo e(request('status') == 'scheduled' ? 'text-black font-semibold' : 'text-[#2271b1]'); ?>">Scheduled <span class="text-[#646970]">(<?php echo e($scheduledCount); ?>)</span></a>
            <span class="mx-1 text-[#c3c4c7]">|</span>
            <a href="<?php echo e(route('admin.pages.index', ['status' => 'draft'])); ?>" class="<?php echo e(request('status') == 'draft' ? 'text-black font-semibold' : 'text-[#2271b1]'); ?>">Draft <span class="text-[#646970]">(<?php echo e($draftCount); ?>)</span></a>
            <?php if($trashCount > 0): ?>
                <span class="mx-1 text-[#c3c4c7]">|</span>
                <a href="<?php echo e(route('admin.pages.index', ['status' => 'trash'])); ?>" class="<?php echo e(request('status') == 'trash' ? 'text-black font-semibold' : 'text-[#2271b1]'); ?>">Trash <span class="text-[#646970]">(<?php echo e($trashCount); ?>)</span></a>
            <?php endif; ?>
        </div>
        
        <form action="<?php echo e(route('admin.pages.index')); ?>" method="GET" class="flex items-center space-x-1 w-full md:w-auto">
            <?php if(request('status')): ?> <input type="hidden" name="status" value="<?php echo e(request('status')); ?>"> <?php endif; ?>
            <input type="text" name="s" value="<?php echo e(request('s')); ?>" class="wp-input h-[30px] flex-grow md:w-48" placeholder="">
            <button type="submit" class="wp-btn-secondary h-[30px] leading-[1]">Search Pages</button>
        </form>
    </div>

    <form id="filter-form" action="<?php echo e(route('admin.pages.index')); ?>" method="GET" class="hidden">
        <?php if(request('status')): ?> <input type="hidden" name="status" value="<?php echo e(request('status')); ?>"> <?php endif; ?>
        <?php if(request('s')): ?> <input type="hidden" name="s" value="<?php echo e(request('s')); ?>"> <?php endif; ?>
    </form>

    <form id="pages-filter" method="POST" action="<?php echo e(route('admin.pages.bulk')); ?>">
    <?php echo csrf_field(); ?>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2 gap-2">
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center space-x-1">
                <select name="action" class="wp-input py-0 h-[30px] text-[13px]">
                    <option value="-1">Bulk actions</option>
                    <?php if(request('status') === 'trash'): ?>
                        <option value="restore">Restore</option>
                        <option value="delete">Delete Permanently</option>
                    <?php else: ?>
                        <option value="draft">Move to Draft</option>
                        <option value="published">Publish</option>
                        <option value="trash">Move to Trash</option>
                    <?php endif; ?>
                </select>
                <button type="button" onclick="handleBulkAction('pages-filter')" class="wp-btn-secondary h-[30px] leading-[1] text-[13px]">Apply</button>
            </div>

            <?php if(request('status') !== 'trash'): ?>
            <div class="flex items-center space-x-1 ml-0 md:ml-4">
                <select name="m" form="filter-form" class="wp-input py-0 h-[30px] text-[13px]">
                    <option value="-1">All dates</option>
                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php 
                            $val = $date->year . str_pad($date->month, 2, '0', STR_PAD_LEFT);
                            $name = date("F Y", mktime(0, 0, 0, $date->month, 1, $date->year));
                        ?>
                        <option value="<?php echo e($val); ?>" <?php echo e(request('m') == $val ? 'selected' : ''); ?>><?php echo e($name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" form="filter-form" class="wp-btn-secondary h-[30px] leading-[1] text-[13px]">Filter</button>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if (isset($component)) { $__componentOriginal7296aa65b1c936852cacbe9cc211f856 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7296aa65b1c936852cacbe9cc211f856 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'falcon-cms::components.admin.pagination','data' => ['paginator' => $pages]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('falcon-cms::admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pages)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7296aa65b1c936852cacbe9cc211f856)): ?>
<?php $attributes = $__attributesOriginal7296aa65b1c936852cacbe9cc211f856; ?>
<?php unset($__attributesOriginal7296aa65b1c936852cacbe9cc211f856); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7296aa65b1c936852cacbe9cc211f856)): ?>
<?php $component = $__componentOriginal7296aa65b1c936852cacbe9cc211f856; ?>
<?php unset($__componentOriginal7296aa65b1c936852cacbe9cc211f856); ?>
<?php endif; ?>
    </div>

    <table class="w-full bg-[#fff] border border-[#c3c4c7] shadow-[0_1px_1px_rgba(0,0,0,.04)] mb-4">
        <thead>
            <tr>
                <th class="wp-table-header w-8 text-center pb-0"><input type="checkbox" id="cb-select-all-1" class="rounded-sm border-[#8c8f94] text-[#2271b1] focus:ring-[#2271b1]"></th>
                <th class="wp-table-header text-left">Title</th>
                <th class="wp-table-header text-left">Author</th>
                <th class="wp-table-header text-center w-8"><svg class="w-4 h-4 mx-auto text-[#8c8f94]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg></th>
                <th class="wp-table-header text-left">SEO</th>
                <th class="wp-table-header text-left">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($idx % 2 === 0 ? 'bg-[#f6f7f7]' : 'bg-[#fff]'); ?> group">
                    <td class="wp-table-cell text-center"><input type="checkbox" name="post_ids[]" value="<?php echo e($item->id); ?>" class="cb-select-item rounded-sm border-[#8c8f94] text-[#2271b1]"></td>
                    <td class="wp-table-cell align-top text-[14px] text-left">
                        <strong>
                            <?php if($item->parent_id): ?> <span class="text-[#c3c4c7]">—</span> <?php endif; ?>
                            <a href="<?php echo e($item->trashed() ? '#' : route('admin.pages.edit', $item)); ?>" class="text-[#2271b1] hover:text-[#135e96]"><?php echo e($item->title); ?></a>
                            <?php if($item->status === 'draft' && !$item->trashed()): ?> <span class="font-normal text-[#646970]"> — Draft</span> <?php endif; ?> 
                            <?php if($item->status === 'scheduled' && !$item->trashed()): ?> <span class="font-normal text-[#646970]"> — Scheduled</span> <?php endif; ?>
                            <?php if(is_lazy_homepage($item)): ?> <span class="font-normal text-[#646970]"> — Front Page</span> <?php endif; ?>
                            <?php if(is_lazy_shop_page($item)): ?> <span class="font-normal text-[#646970]"> — Shop Page</span> <?php endif; ?>
                            <?php if(is_falcon_cart_page($item)): ?> <span class="font-normal text-[#646970]"> — Cart Page</span> <?php endif; ?>
                            <?php if(is_lazy_checkout_page($item)): ?> <span class="font-normal text-[#646970]"> — Checkout Page</span> <?php endif; ?>
                            <?php if(is_lazy_account_page($item)): ?> <span class="font-normal text-[#646970]"> — Account Page</span> <?php endif; ?>
                            <?php if($item->trashed()): ?> <span class="font-normal text-[#646970]"> — Trash</span> <?php endif; ?>
                        </strong>
                        <div class="invisible group-hover:visible mt-1 text-[13px] space-x-1">
                            <?php if($item->trashed()): ?>
                                <button form="restore-form-<?php echo e($item->id); ?>" type="submit" class="text-[#2271b1] hover:underline cursor-pointer">Restore</button>
                                <span class="text-[#c3c4c7]">|</span>
                                <button type="button" onclick="confirmForceDelete(<?php echo e($item->id); ?>)" class="text-[#b32d2e] hover:text-[#8a2424] hover:underline cursor-pointer">Delete Permanently</button>
                            <?php else: ?>
                                <a href="<?php echo e(route('admin.pages.edit', $item)); ?>" class="text-[#2271b1] hover:underline">Edit</a> <span class="text-[#c3c4c7]">|</span>
                                <button type="button" onclick="moveToTrash(<?php echo e($item->id); ?>)" class="text-[#b32d2e] hover:text-[#8a2424] hover:underline cursor-pointer">Trash</button> <span class="text-[#c3c4c7]">|</span>
                                <a href="<?php echo e(get_falcon_permalink($item)); ?>" target="_blank" class="text-[#2271b1] hover:underline">View</a>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="wp-table-cell text-[#2271b1] text-left"><?php echo e($item->user?->username ?? $item->user?->name ?? 'admin'); ?></td>
                    <td class="wp-table-cell text-center text-[#646970]">-</td>
                    <td class="wp-table-cell text-left">
                        <?php $score = $item->getSeoScore(); ?>
                        <div class="flex items-center gap-1.5" title="SEO Score: <?php echo e($score); ?>%">
                            <div class="w-2.5 h-2.5 rounded-full <?php echo e($score >= 70 ? 'bg-[#00a32a]' : ($score >= 40 ? 'bg-[#dba617]' : 'bg-[#d63638]')); ?>"></div>
                            <span class="text-[12px] font-medium"><?php echo e($score); ?>%</span>
                        </div>
                    </td>
                    <td class="wp-table-cell text-[#2c3338] text-left">
                        <?php if($item->trashed()): ?>
                            Last Modified<br>
                            <span class="text-[#646970] text-[12px]"><?php echo e($item->updated_at ? cms_date($item->updated_at, 'Y/m/d \a\t g:i a') : ''); ?></span>
                        <?php else: ?>
                            <?php echo e(ucfirst($item->status)); ?><br>
                            <span class="text-[#646970] text-[12px]"><?php echo e($item->created_at ? cms_date($item->created_at, 'Y/m/d \a\t g:i a') : ''); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr class="bg-[#fff]">
                    <td colspan="5" class="wp-table-cell text-center py-4">No pages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="wp-table-header w-8 text-center pb-0 border-t"><input type="checkbox" id="cb-select-all-2" class="rounded-sm border-[#8c8f94] text-[#2271b1] focus:ring-[#2271b1]"></th>
                <th class="wp-table-header text-left border-t">Title</th>
                <th class="wp-table-header text-left border-t">Author</th>
                <th class="wp-table-header text-center w-8 border-t"><svg class="w-4 h-4 mx-auto text-[#8c8f94]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg></th>
                <th class="wp-table-header text-left border-t">SEO</th>
                <th class="wp-table-header text-left border-t">Date</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-2">
        <div class="flex items-center space-x-2">
            <select name="action2" class="wp-input py-0 h-[30px] text-[13px]">
                <option value="-1">Bulk actions</option>
                <?php if(request('status') === 'trash'): ?>
                    <option value="restore">Restore</option>
                    <option value="delete">Delete Permanently</option>
                <?php else: ?>
                    <option value="draft">Move to Draft</option>
                    <option value="published">Publish</option>
                    <option value="trash">Move to Trash</option>
                <?php endif; ?>
            </select>
            <button type="button" onclick="handleBulkAction('pages-filter', 'action2')" class="wp-btn-secondary h-[30px] leading-[1] text-[13px]">Apply</button>
        </div>
        
        <?php if (isset($component)) { $__componentOriginal7296aa65b1c936852cacbe9cc211f856 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7296aa65b1c936852cacbe9cc211f856 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'falcon-cms::components.admin.pagination','data' => ['paginator' => $pages]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('falcon-cms::admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pages)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7296aa65b1c936852cacbe9cc211f856)): ?>
<?php $attributes = $__attributesOriginal7296aa65b1c936852cacbe9cc211f856; ?>
<?php unset($__attributesOriginal7296aa65b1c936852cacbe9cc211f856); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7296aa65b1c936852cacbe9cc211f856)): ?>
<?php $component = $__componentOriginal7296aa65b1c936852cacbe9cc211f856; ?>
<?php unset($__componentOriginal7296aa65b1c936852cacbe9cc211f856); ?>
<?php endif; ?>
    </div>
    </form>

    <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <form id="delete-form-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.pages.destroy', $item)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        </form>
        <form id="restore-form-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.pages.restore', $item)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
        </form>
        <form id="force-delete-form-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.pages.force-delete', $item)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        </form>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <script>
        document.querySelectorAll('#cb-select-all-1, #cb-select-all-2').forEach(function(master) {
            master.addEventListener('change', function() {
                let isChecked = this.checked;
                document.querySelectorAll('.cb-select-item').forEach(function(item) {
                    item.checked = isChecked;
                });
                document.getElementById('cb-select-all-1').checked = isChecked;
                document.getElementById('cb-select-all-2').checked = isChecked;
            });
        });

        window.handleBulkAction = async function(formId, selectName = 'action') {
            const form = document.getElementById(formId);
            const action = form.querySelector(`select[name="${selectName}"]`).value;
            const selected = form.querySelectorAll('.cb-select-item:checked');

            if (action === '-1' || action === 'none') return;
            if (selected.length === 0) {
                window.showToast('Please select at least one item.', 'warning');
                return;
            }

            if (action === 'delete') {
                const confirmed = await window.falconConfirm({
                    title: 'Delete Permanently',
                    message: `Are you sure you want to permanently delete ${selected.length} items? This cannot be undone.`,
                    confirmText: 'Delete',
                    isDanger: true
                });

                if (!confirmed) return;
            }

            // If it was action2, we need to sync it to the main action field before submitting
            if (selectName === 'action2') {
                form.querySelector('select[name="action"]').value = action;
            }
            form.submit();
        };

        window.moveToTrash = function(id) {
            document.getElementById(`delete-form-${id}`).submit();
        };

        window.confirmForceDelete = async function(id) {
            const confirmed = await window.falconConfirm({
                title: 'Delete Permanently',
                message: 'Are you sure you want to permanently delete this page? This action cannot be undone.',
                confirmText: 'Delete',
                isDanger: true
            });
            if (confirmed) {
                document.getElementById(`force-delete-form-${id}`).submit();
            }
        };
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3747c22dc85a29a75cf05ad0001919a3)): ?>
<?php $attributes = $__attributesOriginal3747c22dc85a29a75cf05ad0001919a3; ?>
<?php unset($__attributesOriginal3747c22dc85a29a75cf05ad0001919a3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3747c22dc85a29a75cf05ad0001919a3)): ?>
<?php $component = $__componentOriginal3747c22dc85a29a75cf05ad0001919a3; ?>
<?php unset($__componentOriginal3747c22dc85a29a75cf05ad0001919a3); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/admin/pages/index.blade.php ENDPATH**/ ?>