@php
    $__layout = $megaMenuData['config']['layout'] ?? [];
@endphp

@if(count($__layout) > 0)
    @foreach($__layout as $container)
        @include('falcon-cms::frontend.builder.container', ['container' => $container])
    @endforeach
@else
    <p style="color:#9ca3af;font-size:13px;text-align:center;padding:20px 0;">This mega menu has no content yet. <a href="{{ route('admin.falcon-builder.mega-menus.builder', $megaMenuData['id']) }}" style="color:#2271b1;">Edit layout</a></p>
@endif
