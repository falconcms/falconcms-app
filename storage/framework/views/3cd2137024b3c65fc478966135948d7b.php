<?php $paginator = $paginator ?? $paginator; ?>

<?php if($paginator->total() > 0): ?>
    <div class="flex items-center text-[13px] text-[#2c3338] space-x-1">
        <span class="mr-2"><?php echo e($paginator->total()); ?> items</span>
        
        <?php if($paginator->hasPages()): ?>
            <div class="flex items-center space-x-[2px]">
                <?php if($paginator->onFirstPage()): ?>
                    <span class="px-2 py-[2px] border border-[#dcdcde] text-[#a7aaad] bg-[#f6f7f7] rounded-[2px] cursor-default text-[16px] leading-normal">&laquo;</span>
                    <span class="px-2 py-[2px] border border-[#dcdcde] text-[#a7aaad] bg-[#f6f7f7] rounded-[2px] cursor-default text-[16px] leading-normal">&lsaquo;</span>
                <?php else: ?>
                    <a href="<?php echo e($paginator->url(1)); ?>" class="px-2 py-[2px] border border-[#c3c4c7] text-[#2271b1] bg-[#f6f7f7] hover:bg-white rounded-[2px] text-[16px] leading-normal">&laquo;</a>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="px-2 py-[2px] border border-[#c3c4c7] text-[#2271b1] bg-[#f6f7f7] hover:bg-white rounded-[2px] text-[16px] leading-normal">&lsaquo;</a>
                <?php endif; ?>
                
                <span class="mx-2 flex items-center">
                    <input type="text" class="wp-input w-8 h-[28px] text-center p-0 mx-1 text-[13px]" value="<?php echo e($paginator->currentPage()); ?>" 
                           onkeypress="if(event.keyCode==13){ let url = '<?php echo e($paginator->url(1)); ?>'; window.location.href=url.includes('?') ? url.replace('page=1','page='+this.value) : url + '?page='+this.value; return false; }">
                    <span class="text-[#2c3338]">of <?php echo e($paginator->lastPage()); ?></span>
                </span>

                <?php if($paginator->hasMorePages()): ?>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="px-2 py-[2px] border border-[#c3c4c7] text-[#2271b1] bg-[#f6f7f7] hover:bg-white rounded-[2px] text-[16px] leading-normal">&rsaquo;</a>
                    <a href="<?php echo e($paginator->url($paginator->lastPage())); ?>" class="px-2 py-[2px] border border-[#c3c4c7] text-[#2271b1] bg-[#f6f7f7] hover:bg-white rounded-[2px] text-[16px] leading-normal">&raquo;</a>
                <?php else: ?>
                    <span class="px-2 py-[2px] border border-[#dcdcde] text-[#a7aaad] bg-[#f6f7f7] rounded-[2px] cursor-default text-[16px] leading-normal">&rsaquo;</span>
                    <span class="px-2 py-[2px] border border-[#dcdcde] text-[#a7aaad] bg-[#f6f7f7] rounded-[2px] cursor-default text-[16px] leading-normal">&raquo;</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/components/admin/pagination.blade.php ENDPATH**/ ?>