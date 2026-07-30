<x-falcon-cms::layouts.admin active-menu="falcon-slider">
    <x-slot name="title">Sliders</x-slot>
    <x-falcon-cms::admin.delete-modal />


    <div class="mb-4 flex items-center gap-2" x-data="{ showNew: false }" @keydown.escape.window="showNew = false">
        <h1 class="text-[23px] font-normal text-[#1d2327] inline-block mr-2">Sliders</h1>
        <button type="button" @click="showNew = true; $nextTick(() => $refs.sliderName && $refs.sliderName.focus())" class="wp-btn-outline">Add New</button>

        {{-- Import a single slider or a bundle (.json) --}}
        <form action="{{ route('admin.sliders.import') }}" method="POST" enctype="multipart/form-data" class="inline" x-ref="importForm">
            @csrf
            <input type="file" name="file" accept="application/json,.json" class="hidden" x-ref="importFile" @change="$refs.importForm.submit()">
            <button type="button" @click="$refs.importFile.click()" class="wp-btn-outline">Import</button>
        </form>

        {{-- New-slider modal --}}
        <div x-show="showNew" x-cloak x-transition.opacity @click.self="showNew = false"
             class="fixed inset-0 z-[9999] flex items-center justify-center" style="background:rgba(0,0,0,.5)">
            <div class="bg-white rounded-lg shadow-2xl w-[380px] max-w-[92vw] p-5" @click.stop>
                <div class="flex items-center gap-2 mb-4 text-[16px] font-semibold text-[#1d2327]">
                    <span class="material-symbols-outlined text-[#2271b1]">slideshow</span> New slider
                </div>
                <form action="{{ route('admin.sliders.store') }}" method="POST">
                    @csrf
                    <label class="block text-[12px] font-semibold text-[#646970] uppercase tracking-wide mb-1">Slider name</label>
                    <input type="text" name="name" x-ref="sliderName" placeholder="e.g. Home hero slider" required
                           class="wp-input h-[38px] w-full mb-4">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showNew = false" class="wp-btn-outline h-[34px] px-3">Cancel</button>
                        <button type="submit" class="wp-btn-primary h-[34px] px-4 font-semibold">Create slider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-[#fff] border-l-4 border-[#00a32a] shadow-[0_1px_1px_rgba(0,0,0,.04)] p-3 mb-4 rounded-sm text-[13px]">{!! session('success') !!}</div>
    @endif
    @if(session('error'))
        <div class="bg-[#fff] border-l-4 border-[#d63638] shadow-[0_1px_1px_rgba(0,0,0,.04)] p-3 mb-4 rounded-sm text-[13px]">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.sliders.bulk') }}" id="bulk-form">
        @csrf

        {{-- Bulk actions bar --}}
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-1">
                <select name="action" class="wp-input py-0 h-[30px] text-[13px]">
                    <option value="">Bulk actions</option>
                    <option value="export">Export</option>
                    <option value="delete">Delete</option>
                </select>
                <button type="button" class="wp-btn-secondary h-[30px] leading-[1] text-[13px]" onclick="applyBulk()">Apply</button>
            </div>
            <span class="text-[13px] text-[#646970]">{{ $sliders->total() }} item{{ $sliders->total() === 1 ? '' : 's' }}</span>
        </div>

        <table class="w-full bg-[#fff] border border-[#c3c4c7] shadow-[0_1px_1px_rgba(0,0,0,.04)] mb-4">
            <thead>
                <tr>
                    <th class="wp-table-header w-8 text-center pb-0">
                        <input type="checkbox" onclick="document.querySelectorAll('.sl-cb').forEach(c=>c.checked=this.checked)"
                               class="rounded-sm border-[#8c8f94] text-[#2271b1]">
                    </th>
                    <th class="wp-table-header text-left">Name</th>
                    <th class="wp-table-header text-left w-[240px]">Shortcode</th>
                    <th class="wp-table-header text-left w-[90px]">Slides</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliders as $slider)
                    <tr class="{{ $loop->even ? 'bg-[#f6f7f7]' : 'bg-[#fff]' }} group align-top">
                        <td class="wp-table-cell text-center">
                            <input type="checkbox" name="ids[]" value="{{ $slider->id }}" class="sl-cb rounded-sm border-[#8c8f94] text-[#2271b1] mt-1">
                        </td>
                        <td class="wp-table-cell text-[14px]">
                            <strong><a href="{{ route('admin.sliders.edit', $slider) }}" class="text-[#2271b1] hover:text-[#135e96]">{{ $slider->name }}</a></strong>
                            <div class="invisible group-hover:visible mt-1 text-[13px] flex items-center gap-1">
                                <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-[#2271b1] hover:underline">Edit</a>
                                <span class="text-[#c3c4c7]">|</span>
                                <a href="#" onclick="event.preventDefault();document.getElementById('dup-{{ $slider->id }}').submit()" class="text-[#2271b1] hover:underline">Duplicate</a>
                                <form id="dup-{{ $slider->id }}" action="{{ route('admin.sliders.duplicate', $slider) }}" method="POST" class="hidden">@csrf</form>
                                <span class="text-[#c3c4c7]">|</span>
                                <a href="{{ route('admin.sliders.export', $slider) }}" class="text-[#2271b1] hover:underline">Export</a>
                                <span class="text-[#c3c4c7]">|</span>
                                <a href="#" onclick="event.preventDefault();deleteSlider('{{ route('admin.sliders.destroy', $slider) }}')" class="text-[#b32d2e] hover:underline">Delete</a>
                            </div>
                        </td>
                        <td class="wp-table-cell text-[13px]">
                            <code onclick="fsCopyShortcode(this)" title="Click to copy shortcode"
                                  data-shortcode='[falcon_slider id="{{ $slider->id }}"]'
                                  class="cursor-pointer inline-flex items-center gap-1.5 bg-[#f0f0f1] hover:bg-[#e5eefb] px-2 py-1 rounded transition-colors">
                                <span class="fs-sc-text">[falcon_slider id="{{ $slider->id }}"]</span>
                                <span class="material-symbols-outlined" style="font-size:14px;line-height:1;opacity:.5">content_copy</span>
                            </code>
                        </td>
                        <td class="wp-table-cell text-[13px] text-[#50575e]">{{ count($slider->slides ?? []) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="wp-table-cell text-center text-[13px] text-[#646970] py-8">
                            No sliders yet. Click <strong>Add New</strong> to create one, or <strong>Import</strong> a .json export.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($sliders->hasPages())
            <div class="mb-4">{{ $sliders->links('falcon-cms::components.admin.pagination') }}</div>
        @endif
    </form>

    {{-- Single-delete helper form --}}
    <form id="delete-form" method="POST" class="hidden">@csrf @method('DELETE')</form>

    @push('scripts')
    <script>
        window.deleteSlider = async function(url) {
            const ok = await window.falconConfirm({
                title: 'Delete slider',
                message: 'Are you sure you want to delete this slider? This action cannot be undone.',
                confirmText: 'Delete',
                isDanger: true
            });
            if (!ok) return;
            const f = document.getElementById('delete-form'); f.action = url; f.submit();
        };
        window.applyBulk = async function() {
            const form = document.getElementById('bulk-form');
            const action = form.querySelector('[name=action]').value;
            const checked = form.querySelectorAll('.sl-cb:checked').length;
            if (!action) { if (window.showToast) window.showToast('Choose a bulk action.', 'error'); return; }
            if (!checked) { if (window.showToast) window.showToast('Select at least one slider.', 'error'); return; }
            if (action === 'delete') {
                const ok = await window.falconConfirm({
                    title: 'Delete sliders',
                    message: 'Delete ' + checked + ' selected slider(s)? This action cannot be undone.',
                    confirmText: 'Delete',
                    isDanger: true
                });
                if (!ok) return;
            }
            form.submit();
        };
        // Click the shortcode itself to copy it to the clipboard.
        window.fsCopyShortcode = async function(el) {
            const text = (el.dataset.shortcode || el.textContent || '').trim();
            const icon = el.querySelector('.material-symbols-outlined');
            const done = () => {
                if (icon) {
                    const orig = icon.textContent;
                    icon.textContent = 'check';
                    icon.style.opacity = '1';
                    icon.style.color = '#00a32a';
                    setTimeout(() => { icon.textContent = orig; icon.style.opacity = '.5'; icon.style.color = ''; }, 1500);
                }
                if (window.showToast) window.showToast('Shortcode copied to clipboard', 'success');
            };
            try {
                await navigator.clipboard.writeText(text);
                done();
            } catch (e) {
                // Fallback for non-secure contexts / older browsers.
                const t = document.createElement('textarea');
                t.value = text; t.style.position = 'fixed'; t.style.opacity = '0';
                document.body.appendChild(t); t.select();
                try { document.execCommand('copy'); done(); } catch (_) {}
                document.body.removeChild(t);
            }
        };
    </script>
    @endpush
</x-falcon-cms::layouts.admin>
