<style>
    .element-image {
        overflow: hidden;
    }
    .element-image img {
        transition: all 0.3s ease-in-out;
        display: block;
    }
    .hover-zoom-in:hover img { transform: scale(1.1); }
    .hover-zoom-out:hover img { transform: scale(0.9); }
    .hover-lift:hover img { transform: translateY(-8px); }
    .hover-shadow:hover img { box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    .hover-opacity:hover img { opacity: 0.7; }

    /* Scroll Entrance Animations */
    .lz-anim { will-change: opacity, transform; }
    .lz-anim-fade-in:not(.lz-animated)    { opacity: 0; }
    .lz-anim-slide-up:not(.lz-animated)   { opacity: 0; transform: translateY(50px); }
    .lz-anim-slide-down:not(.lz-animated) { opacity: 0; transform: translateY(-50px); }
    .lz-anim-slide-left:not(.lz-animated) { opacity: 0; transform: translateX(50px); }
    .lz-anim-slide-right:not(.lz-animated){ opacity: 0; transform: translateX(-50px); }
    .lz-anim-zoom-in:not(.lz-animated)    { opacity: 0; transform: scale(0.8); }
    .lz-anim-zoom-out:not(.lz-animated)   { opacity: 0; transform: scale(1.2); }
    .lz-anim-bounce-in:not(.lz-animated)  { opacity: 0; transform: scale(0.6); }
    .lz-anim.lz-animated { opacity: 1 !important; transform: none !important; }
</style>
<script>
(function(){
    function initLazyAnimations(){
        document.querySelectorAll('.lz-anim:not([data-lz-anim-init])').forEach(function(el){
            el.dataset.lzAnimInit='1';
            var dur=(el.dataset.animDuration||600)+'ms';
            var delay=(el.dataset.animDelay||0)+'ms';
            var ease=el.dataset.animEasing||'ease';
            el.style.transitionProperty='opacity,transform';
            el.style.transitionDuration=dur;
            el.style.transitionDelay=delay;
            el.style.transitionTimingFunction=ease;
            new IntersectionObserver(function(entries,obs){
                if(entries[0].isIntersecting){el.classList.add('lz-animated');obs.disconnect();}
            },{threshold:0.1}).observe(el);
        });
    }
    document.readyState==='loading'
        ?document.addEventListener('DOMContentLoaded',initLazyAnimations)
        :initLazyAnimations();
})();
</script>

<?php
    $rawGlobal = get_cms_option('lazy_global_sections', null);
    $globalSectionsMap = [];
    if ($rawGlobal) {
        $decoded = json_decode($rawGlobal, true);
        if (is_array($decoded)) {
            foreach ($decoded as $gs) { $globalSectionsMap[$gs['id']] = $gs; }
        }
    }
?>

<?php if(!empty($layout)): ?>
    <?php $__currentLoopData = $layout; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $container): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $renderContainer = $container;
            $gsId = $container['settings']['global_id'] ?? null;
            if ($gsId && isset($globalSectionsMap[$gsId]) && !empty($globalSectionsMap[$gsId]['data'])) {
                $renderContainer = $globalSectionsMap[$gsId]['data'];
            }
        ?>
        <?php echo $__env->make('falcon-cms::frontend.builder.container', ['container' => $renderContainer], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\lazy-panda\vendor\tareqcodex\lazy-cms-rebuild\src/../resources/views/frontend/builder/render.blade.php ENDPATH**/ ?>