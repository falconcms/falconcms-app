<div class="flex items-center gap-1 border-b border-[#c3c4c7] mb-8">
    <a href="<?php echo e(route('admin.settings.index')); ?>" class="px-4 py-2 text-[14px] <?php echo e(request()->routeIs('admin.settings.index') ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'); ?>">
        General Settings
    </a>
    <a href="<?php echo e(route('admin.settings.seo')); ?>" class="px-4 py-2 text-[14px] <?php echo e(request()->routeIs('admin.settings.seo') ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'); ?>">
        SEO Settings
    </a>
    <a href="<?php echo e(route('admin.settings.activity-logs')); ?>" class="px-4 py-2 text-[14px] <?php echo e(request()->routeIs('admin.settings.activity-logs') ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'); ?>">
        Activity Logs
    </a>
    <a href="<?php echo e(route('admin.settings.api')); ?>" class="px-4 py-2 text-[14px] <?php echo e(request()->routeIs('admin.settings.api') ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'); ?>">
        REST API
    </a>
    <a href="<?php echo e(route('admin.settings.integrations')); ?>" class="px-4 py-2 text-[14px] <?php echo e(request()->routeIs('admin.settings.integrations') ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'); ?>">
        Integrations
    </a>
    <a href="<?php echo e(route('admin.settings.email-templates')); ?>" class="px-4 py-2 text-[14px] <?php echo e(request()->routeIs('admin.settings.email-templates') ? 'text-[#1d2327] font-semibold bg-white -mb-[1px] border-l border-t border-r border-[#c3c4c7] border-b-white' : 'text-[#2271b1] hover:text-[#135e96]'); ?>">
        Email Templates
    </a>
</div>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/admin/settings/nav.blade.php ENDPATH**/ ?>