@php
    $s = $el['settings'] ?? [];
    $sliderId = $s['sliderId'] ?? '';
    $slider = $sliderId ? \FalconSlider\Models\Slider::find($sliderId) : null;
@endphp

@if($slider)
    {!! view('falcon-slider::frontend.render', ['slider' => $slider])->render() !!}
@else
    <div style="padding:28px 20px;text-align:center;color:#94a3b8;border:1px dashed #cbd5e1;border-radius:8px;background:#f8fafc;font-size:14px;">
        <span style="font-size:22px;display:block;margin-bottom:6px;">🖼️</span>
        Choose a slider in the element settings (General → Slider).
    </div>
@endif
