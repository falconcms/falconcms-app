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
     <?php $__env->slot('title', null, []); ?> Settings - FalconCMS <?php $__env->endSlot(); ?>

    <div class="px-2">
        <h1 class="text-[23px] font-normal text-[#1d2327] mb-4">Settings</h1>
        
        <?php echo $__env->make('falcon-cms::admin.settings.nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(session('success')): ?>
            <div class="bg-[#edfaef] border-l-4 border-[#46b450] p-3 mb-6 text-[13px] text-[#1d2327]">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" class="max-w-[800px]">
            <?php echo csrf_field(); ?>
            <?php echo do_falcon_action('lazy_settings_form_top'); ?>


            <table class="w-full border-separate border-spacing-y-6">
                <!-- Site Title -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="site_title" class="text-[14px] font-semibold text-[#1d2327]">Site Title</label>
                    </th>
                    <td>
                        <input type="text" name="site_title" id="site_title"
                            value="<?php echo e($settings['site_title'] ?? 'FalconCMS'); ?>"
                            class="wp-input w-[400px] h-8 shadow-sm">
                    </td>
                </tr>

                <!-- Tagline -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="tagline" class="text-[14px] font-semibold text-[#1d2327]">Tagline</label>
                    </th>
                    <td>
                        <input type="text" name="tagline" id="tagline" value="<?php echo e($settings['tagline'] ?? ''); ?>"
                            class="wp-input w-[400px] h-8 shadow-sm mb-1">
                        <p class="text-[12px] text-[#646970] italic">In a few words, explain what this site is about.
                            Example: “Just another WordPress site.”</p>
                    </td>
                </tr>

                <!-- Admin Email -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="admin_email" class="text-[14px] font-semibold text-[#1d2327]">Administration Email
                            Address</label>
                    </th>
                    <td>
                        <input type="email" name="admin_email" id="admin_email"
                            value="<?php echo e($settings['admin_email'] ?? auth()->user()->email); ?>"
                            class="wp-input w-[400px] h-8 shadow-sm mb-1">
                        <p class="text-[12px] text-[#646970]">This address is used for admin purposes.</p>
                    </td>
                </tr>

                <!-- Timezone -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="timezone" class="text-[14px] font-semibold text-[#1d2327]">Timezone</label>
                    </th>
                    <td>
                        <?php $selectedTz = $settings['timezone'] ?? config('app.timezone') ?? 'UTC'; ?>
                        <select name="timezone" id="timezone" class="wp-input w-[400px] h-8 py-0 shadow-sm mb-1">
                            <?php $__currentLoopData = lazy_timezone_list(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $region => $zones): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <optgroup label="<?php echo e($region); ?>">
                                    <?php $__currentLoopData = $zones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tzId => $tzLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tzId); ?>" <?php echo e($selectedTz === $tzId ? 'selected' : ''); ?>><?php echo e($tzLabel); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-[12px] text-[#646970]">
                            Choose the timezone for publishing &amp; scheduling. Dates are stored in UTC and shown in this timezone.
                            Current time here: <strong><?php echo e(\Illuminate\Support\Carbon::now($selectedTz)->format('M j, Y H:i')); ?></strong>
                        </p>
                    </td>
                </tr>

                <!-- Homepage Selection -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="home_page_id" class="text-[14px] font-semibold text-[#1d2327]">Select your Home page</label>
                    </th>
                    <td>
                        <select name="home_page_id" id="home_page_id" class="wp-input w-[400px] h-8 py-0 shadow-sm mb-1">
                            <option value="">Latest Blog Posts (Default)</option>
                            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($page->id); ?>" <?php echo e(($settings['home_page_id'] ?? '') == $page->id ? 'selected' : ''); ?>>
                                    <?php echo e($page->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-[12px] text-[#646970]">Choose what to display on your site's home page. If none selected, the latest blog posts will be shown.</p>
                    </td>
                </tr>

                <!-- Blog Page Selection -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="blog_page_id" class="text-[14px] font-semibold text-[#1d2327]">Select your Blog page</label>
                    </th>
                    <td>
                        <select name="blog_page_id" id="blog_page_id" class="wp-input w-[400px] h-8 py-0 shadow-sm mb-1">
                            <option value="">— None —</option>
                            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($page->id); ?>" <?php echo e(($settings['blog_page_id'] ?? '') == $page->id ? 'selected' : ''); ?>>
                                    <?php echo e($page->title); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-[12px] text-[#646970]">The page assigned here will display your blog posts (rendered by the theme's <code>index</code> template) instead of its own content.</p>
                    </td>
                </tr>

                <!-- Membership -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Who can Sign Up</label>
                    </th>
                    <td>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="users_can_register" id="users_can_register"
                                class="w-4 h-4 mr-2"
                                <?php echo e(($settings['users_can_register'] ?? '0') == '1' ? 'checked' : ''); ?>>
                            <span class="text-[14px] text-[#1d2327]">Anyone can register</span>
                        </label>
                    </td>
                </tr>

                <!-- Documentation Access -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Enable Documentation</label>
                    </th>
                    <td>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_documentation" id="enable_documentation"
                                class="w-4 h-4 mr-2"
                                <?php echo e(($settings['enable_documentation'] ?? '1') == '1' ? 'checked' : ''); ?>>
                            <span class="text-[14px] text-[#1d2327]">Show documentation in admin menu and allow access</span>
                        </label>
                        <p class="text-[12px] text-[#646970] mt-1">If unchecked, the documentation link will be hidden and direct access will be forbidden.</p>
                    </td>
                </tr>

                <!-- Themes Group -->
                <tr id="reg-theme-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="registration_theme" class="text-[14px] font-semibold text-[#1d2327]">Choose
                            Registration theme</label>
                    </th>
                    <td>
                        <select name="registration_theme" id="registration_theme" class="wp-input w-[200px] h-8 py-0">
                            <option value="modern"
                                <?php echo e(($settings['registration_theme'] ?? 'modern') == 'modern' ? 'selected' : ''); ?>>Modern</option>
                            <option value="funny"
                                <?php echo e(($settings['registration_theme'] ?? 'breeze') == 'funny' ? 'selected' : ''); ?>>Funny Theme</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="login_theme" class="text-[14px] font-semibold text-[#1d2327]">Choose login
                            theme</label>
                    </th>
                    <td>
                        <select name="login_theme" id="login_theme" class="wp-input w-[200px] h-8 py-0">
                            <option value="modern"
                                <?php echo e(($settings['login_theme'] ?? 'modern') == 'modern' ? 'selected' : ''); ?>>Modern</option>
                            <option value="funny"
                                <?php echo e(($settings['login_theme'] ?? 'breeze') == 'funny' ? 'selected' : ''); ?>>Funny Theme</option>
                        </select>
                    </td>
                </tr>

                <!-- URLs Group -->
                <tr id="reg-url-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="register_url" class="text-[14px] font-semibold text-[#1d2327]">Change registration
                            url</label>
                    </th>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-[#646970] text-[13px]"><?php echo e(url('/')); ?>/</span>
                            <input type="text" name="register_url" id="register_url"
                                value="<?php echo e($settings['register_url'] ?? 'lazy-register'); ?>"
                                class="wp-input w-[280px] h-8 shadow-sm">
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="login_url" class="text-[14px] font-semibold text-[#1d2327]">Change login url</label>
                    </th>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="text-[#646970] text-[13px]"><?php echo e(url('/')); ?>/</span>
                            <input type="text" name="login_url" id="login_url"
                                value="<?php echo e($settings['login_url'] ?? 'lazy-admin'); ?>"
                                class="wp-input w-[280px] h-8 shadow-sm">
                        </div>
                    </td>
                </tr>

                <!-- Default Role -->
                <tr id="default-role-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="default_role" class="text-[14px] font-semibold text-[#1d2327]">New User Default
                            Role</label>
                    </th>
                    <td>
                        <select name="default_role" id="default_role" class="wp-input w-[200px] h-8 py-0">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->slug); ?>"
                                    <?php echo e(($settings['default_role'] ?? 'subscriber') == $role->slug ? 'selected' : ''); ?>>
                                    <?php echo e($role->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                </tr>

                <!-- Multi-device Login -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Multi-device Login</label>
                    </th>
                    <td>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_multi_device" id="allow_multi_device"
                                class="w-4 h-4 mr-2"
                                <?php echo e(($settings['allow_multi_device'] ?? '0') == '1' ? 'checked' : ''); ?>>
                            <span class="text-[14px] text-[#1d2327]">Allow multiple device login</span>
                        </label>
                    </td>
                </tr>

                <!-- Magic Login -->
                <tr>
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label class="text-[14px] font-semibold text-[#1d2327]">Magic Login</label>
                    </th>
                    <td>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="magic_login_enabled" id="magic_login_enabled"
                                class="w-4 h-4 mr-2"
                                <?php echo e(($settings['magic_login_enabled'] ?? '0') == '1' ? 'checked' : ''); ?>>
                            <span class="text-[14px] text-[#1d2327]">Enable passwordless login via magic link (sent to email)</span>
                        </label>
                        <p class="text-[12px] text-[#646970] mt-1">When enabled, users can sign in without a password — both on the shop account page and the admin login form.</p>
                    </td>
                </tr>

                <tr id="max-devices-row">
                    <th scope="row" class="w-[200px] text-left align-top pt-2">
                        <label for="max_devices" class="text-[14px] font-semibold text-[#1d2327]">Max devices allowed</label>
                    </th>
                    <td>
                        <input type="number" name="max_devices" id="max_devices" 
                            value="<?php echo e($settings['max_devices'] ?? '3'); ?>" min="1"
                            class="wp-input w-[100px] h-8 shadow-sm mb-1">
                        <p class="text-[12px] text-[#646970]">Limit the number of concurrent sessions per user. (Default: 3)</p>
                    </td>
                </tr>
            </table>

            <?php echo do_falcon_action('lazy_settings_form_bottom'); ?>


            <div class="pt-6 border-t border-gray-100 mt-6">
                <button type="submit" class="wp-btn-primary px-4 h-8 font-semibold">Save Changes</button>
            </div>
        </form>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const registerCheckbox = document.getElementById('users_can_register');
                const regRows = [
                    document.getElementById('reg-theme-row'),
                    document.getElementById('reg-url-row'),
                    document.getElementById('default-role-row')
                ];

                const multiDeviceCheckbox = document.getElementById('allow_multi_device');
                const maxDevicesRow = document.getElementById('max-devices-row');

                function toggleRegistrationFields() {
                    const isVisible = registerCheckbox.checked;
                    regRows.forEach(row => {
                        if (row) {
                            row.style.display = isVisible ? 'table-row' : 'none';
                        }
                    });
                }

                function toggleMultiDeviceFields() {
                    if (maxDevicesRow) {
                        maxDevicesRow.style.display = multiDeviceCheckbox.checked ? 'table-row' : 'none';
                    }
                }

                // Initial checks
                toggleRegistrationFields();
                toggleMultiDeviceFields();

                // Listen for changes
                registerCheckbox.addEventListener('change', toggleRegistrationFields);
                multiDeviceCheckbox.addEventListener('change', toggleMultiDeviceFields);
                
                // Media Modal for settings
                document.querySelectorAll('.open-media-for-setting').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const target = this.getAttribute('data-target');
                        window.openMediaModal(function(media) {
                            const input = document.getElementById('input-' + target);
                            if (input) input.value = media.path;
                            const preview = document.getElementById('media-preview-' + target);
                            if (preview) {
                                preview.innerHTML = `<img src="/storage/${media.path}" class="max-w-full max-h-full object-contain">`;
                                preview.classList.remove('hidden');
                            }
                        });
                    });
                });

            });
        </script>
    <?php $__env->stopPush(); ?>

    
    <?php $__env->startPush('scripts'); ?>
        <link href="<?php echo e(asset('vendor/falcon-cms/css/tom-select.default.min.css')); ?>" rel="stylesheet">
        <script src="<?php echo e(asset('vendor/falcon-cms/js/tom-select.complete.min.js')); ?>"></script>
        <style>
            .ts-wrapper.wp-input { padding: 0 !important; border: none !important; box-shadow: none !important; height: auto !important; min-height: 32px !important; }
            #timezone + .ts-wrapper { max-width: 400px; }
            .ts-control {
                border: 1px solid #8c8f94 !important; border-radius: 3px !important; padding: 4px 12px !important;
                font-size: 14px !important; color: #1d2327 !important; background-color: #fff !important;
                box-shadow: 0 1px 2px rgba(0,0,0,0.07) inset !important; min-height: 32px !important;
                display: flex !important; align-items: center !important;
            }
            .ts-dropdown { font-size: 13px; }
            .ts-dropdown .optgroup-header { font-weight: 600; color: #646970; }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.TomSelect && document.getElementById('timezone')) {
                    new TomSelect('#timezone', {
                        create: false,
                        maxOptions: null,           // show all timezones; search narrows the list
                        placeholder: 'Search timezone (e.g. Dhaka, +06, Asia)…',
                        searchField: ['text', 'value'],
                    });
                }
            });
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
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/admin/settings/index.blade.php ENDPATH**/ ?>