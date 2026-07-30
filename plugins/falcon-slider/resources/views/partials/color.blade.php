{{--
    Color control using Pickr (same as the page builder) — palette + hue +
    opacity/alpha, HEXA output. Vars: $model (JS expr, e.g. 'layer.style.color'), $label.
--}}
@php $model = $model ?? ''; $label = $label ?? 'Color'; @endphp
<div class="fse-field">
    <label class="fse-lbl">{{ $label }}</label>
    <div class="flex items-center gap-2">
        <button type="button" class="fse-swatch"
                :style="`background:${ {{ $model }} || '#000000' }`"
                x-init="$nextTick(() => window.fsPickr && window.fsPickr($el, () => ({{ $model }}), (v) => {{ $model }} = v))"></button>
        <input type="text" x-model="{{ $model }}" placeholder="#000000" class="fse-input" style="flex:1">
    </div>
</div>
