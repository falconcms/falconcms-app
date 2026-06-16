<?php if (isset($component)) { $__componentOriginal3747c22dc85a29a75cf05ad0001919a3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3747c22dc85a29a75cf05ad0001919a3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'falcon-cms::components.layouts.admin','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('falcon-cms::layouts.admin'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Themes - FalconCMS <?php $__env->endSlot(); ?>
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

    <div class="px-2">
        <div class="flex items-center gap-4 mb-6">
            <h1 class="text-[23px] font-normal text-[#1d2327]">Themes</h1>
            <button id="upload-theme-toggle" class="wp-btn-secondary px-3 h-7 text-[13px] font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[18px]">upload</span>
                Upload Theme
            </button>
        </div>

        
        <div id="upload-theme-container" class="hidden bg-white border border-[#dcdcde] p-6 mb-8 shadow-sm">
            <p class="text-[14px] text-[#1d2327] mb-4">If you have a theme in a .zip format, you may install or update it by uploading it here.</p>
            <form action="<?php echo e(route('admin.themes.upload')); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-4">
                <?php echo csrf_field(); ?>
                <input type="file" name="theme_zip" accept=".zip" class="wp-input w-full sm:w-auto h-9 pt-1" required>
                <button type="submit" class="wp-btn-secondary h-9 px-4 font-semibold">Install Now</button>
            </form>
            <?php $__errorArgs = ['theme_zip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-[#d63638] text-[12px] mt-2"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <span class="text-[13px] text-[#1d2327] font-semibold border-b-2 border-[#1d2327] pb-1 cursor-pointer">All <span class="text-[#646970] font-normal">(<?php echo e(count($themes)); ?>)</span></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[#646970] text-[13px]">Search themes...</span>
                <input type="text" class="wp-input w-48 h-7">
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-[#fcf0f1] border-l-4 border-[#d63638] p-3 mb-6 text-[13px] text-[#1d2327]">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="themes-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="theme-card group relative bg-white border <?php echo e($theme['is_active'] ? 'border-[#2271b1] ring-1 ring-[#2271b1]' : 'border-[#dcdcde]'); ?> shadow-sm overflow-hidden flex flex-col h-full">
                    
                    
                    <div class="theme-screenshot relative aspect-[4/3] bg-[#f0f0f1] border-b border-[#dcdcde] overflow-hidden">
                        <?php if($theme['screenshot']): ?>
                            <img src="<?php echo e($theme['screenshot']); ?>" alt="<?php echo e($theme['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-[#dcdcde]">
                                <span class="material-symbols-outlined text-[64px]">palette</span>
                                <span class="text-[12px] mt-2 font-medium">No Screenshot</span>
                            </div>
                        <?php endif; ?>

                        
                        <?php if($theme['is_active']): ?>
                            <div class="absolute top-0 left-0 bg-[#2271b1] text-white px-3 py-1 text-[12px] font-semibold">
                                Active
                            </div>
                        <?php endif; ?>

                        
                        <div class="absolute inset-0 bg-white/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                            <?php if($theme['is_active']): ?>
                                <div class="bg-[#2271b1] text-white px-4 py-1 text-[13px] font-semibold rounded">
                                    Activated
                                </div>
                            <?php else: ?>
                                <?php if($theme['is_activatable']): ?>
                                    <div class="flex items-center justify-center gap-3">
                                        <form action="<?php echo e(route('admin.themes.activate', $theme['slug'])); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="wp-btn-primary px-4 py-1 h-auto text-[13px] font-semibold">Activate</button>
                                        </form>
                                        <a href="<?php echo e(route('admin.themes.preview', $theme['slug'])); ?>" class="wp-btn-secondary bg-white px-4 py-1 h-auto text-[13px] font-semibold" style="display:inline-flex;align-items:center;text-decoration:none;">Live Preview</a>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-[#d63638] text-white px-3 py-2 text-[11px] font-bold rounded shadow-sm text-center mx-4">
                                        Broken Theme: Missing index.blade.php
                                    </div>
                                <?php endif; ?>
                                
                                
                                <?php if($theme['slug'] !== 'falcon-theme'): ?>
                                    <form id="delete-theme-<?php echo e($theme['slug']); ?>" action="<?php echo e(route('admin.themes.destroy', $theme['slug'])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button" onclick="confirmThemeDelete('<?php echo e($theme['slug']); ?>')" class="text-[#d63638] text-[12px] hover:underline mt-2">Delete</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="theme-info p-3 bg-white flex items-center justify-between mt-auto">
                        <div class="truncate">
                            <h2 class="text-[14px] font-bold text-[#1d2327] truncate"><?php echo e($theme['name']); ?></h2>
                        </div>
                        <div class="flex items-center">
                            <?php if($theme['is_active']): ?>
                                <button class="text-[#2271b1] hover:text-[#135e96] transition">
                                    <span class="material-symbols-outlined text-[20px]">info</span>
                                </button>
                            <?php else: ?>
                                <button class="text-[#646970] hover:text-[#2271b1] transition">
                                    <span class="material-symbols-outlined text-[20px]">info</span>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <div class="border-2 border-dashed border-[#dcdcde] bg-[#f6f7f7] hover:bg-[#f0f0f1] hover:border-[#c3c4c7] transition flex flex-col items-center justify-center min-h-[250px] cursor-pointer group">
                <div class="w-12 h-12 rounded-full border-2 border-[#dcdcde] group-hover:border-[#c3c4c7] flex items-center justify-center text-[#dcdcde] group-hover:text-[#c3c4c7] mb-3">
                    <span class="material-symbols-outlined text-[32px]">add</span>
                </div>
                <span class="text-[14px] font-bold text-[#646970]">Add New Theme</span>
            </div>
        </div>
    </div>

    <style>
        /* Add some specific WordPress-like styling for themes page */
        .wp-btn-primary { 
            background: #2271b1; 
            border-color: #2271b1; 
            box-shadow: 0 1px 0 #135e96; 
            color: #fff;
            padding: 4px 12px;
            border-radius: 3px;
            cursor: pointer;
        }
        .wp-btn-primary:hover { 
            background: #135e96; 
            border-color: #135e96; 
        }
        .wp-btn-secondary {
            background: #f6f7f7;
            border: 1px solid #2271b1;
            color: #2271b1;
            padding: 4px 12px;
            border-radius: 3px;
            cursor: pointer;
        }

        /* Grid Fallback if Tailwind fails */
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }
        .theme-card {
            background: #fff;
            border: 1px solid #dcdcde;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .theme-screenshot {
            aspect-ratio: 4/3;
            background: #f0f0f1;
            border-bottom: 1px solid #dcdcde;
            position: relative;
            overflow: hidden;
        }
        .theme-info {
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const uploadToggle = document.getElementById('upload-theme-toggle');
                const uploadContainer = document.getElementById('upload-theme-container');

                if (uploadToggle && uploadContainer) {
                    uploadToggle.addEventListener('click', function() {
                        uploadContainer.classList.toggle('hidden');
                        uploadToggle.classList.toggle('bg-[#dcdcde]');
                    });
                }
            });

            window.confirmThemeDelete = async function(slug) {
                const confirmed = await window.falconConfirm({
                    title: 'Delete Theme',
                    message: 'Are you sure you want to delete this theme? This action cannot be undone and will remove all theme files from the server.',
                    confirmText: 'Delete Theme',
                    isDanger: true
                });

                if (confirmed) {
                    document.getElementById(`delete-theme-${slug}`).submit();
                }
            };
        </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/admin/themes/index.blade.php ENDPATH**/ ?>