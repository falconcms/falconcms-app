<?php

namespace FalconSlider\Http\Controllers;

use FalconSlider\Models\Slider;
use FalconSlider\Models\SliderRevision;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SliderController extends Controller
{
    /**
     * The whole admin needs the capability. The builder itself is free to use — you can
     * design a slider fully — but *persisting* a design (Save / Save-as-template) needs
     * Pro. Pro means an active license OR the freemium grace window (falcon_pro()).
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! auth()->user() || ! auth()->user()->hasPermission('manage_sliders')) {
                abort(403);
            }
            return $next($request);
        });
    }

    /** True when this site may persist slider designs (licensed, or still in grace). */
    protected function canSave(): bool
    {
        return ! function_exists('falcon_pro') || falcon_pro();
    }

    /** JSON/HTTP rejection used by the save endpoints when the site isn't Pro. */
    protected function proRequired(Request $request)
    {
        $msg = 'Saving your slider requires an active FalconCMS Pro subscription.';
        if ($request->wantsJson()) {
            return response()->json(['ok' => false, 'pro_required' => true, 'message' => $msg], 403);
        }
        return redirect()->back()->with('error', $msg);
    }

    public function index()
    {
        return view('falcon-slider::index', [
            'sliders' => Slider::latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120']]);

        $slider = Slider::create(['name' => $data['name']]);

        return redirect()
            ->route('admin.sliders.edit', $slider)
            ->with('success', 'Slider created — start adding slides.')
            ->with('sliderIsNew', true);   // editor opens the ready-made design gallery
    }

    /** Clone a whole slider (settings + all slides) as a new draft. */
    public function duplicate(Slider $slider)
    {
        $copy = $slider->replicate(['slug']);
        $copy->name = $slider->name . ' (copy)';
        $copy->slug = null;   // Slider::booted() regenerates a unique slug
        $copy->save();

        return redirect()
            ->route('admin.sliders.edit', $copy)
            ->with('success', 'Slider duplicated — edit your copy.');
    }

    public function edit(Slider $slider)
    {
        return view('falcon-slider::editor', [
            'slider'        => $slider,
            'demos'         => \FalconSlider\Support\DemoLibrary::all(),
            'icons'         => \FalconSlider\Support\IconLibrary::names(),
            'fontCatalog'   => \FalconSlider\Support\FontLibrary::catalog(),
            'fontCats'      => \FalconSlider\Support\FontLibrary::categories(),
            'userTemplates' => \FalconSlider\Models\SliderTemplate::latest()->get(['id', 'name', 'category', 'settings', 'slides']),
            'isNew'         => (bool) session('sliderIsNew', false),
            // Sliders design for free; saving needs Pro. Pass the state to the editor so it
            // can disable Save and prompt for a subscription when the site isn't Pro.
            'canSave'       => $this->canSave(),
            'upgradeUrl'    => function_exists('falcon_upgrade_url') ? falcon_upgrade_url() : 'https://falconcms.com/#pricing',
        ]);
    }

    /** Save the current design as a reusable pre-built template (called from the editor via fetch). */
    public function saveTemplate(Request $request)
    {
        if (! $this->canSave()) {
            return $this->proRequired($request);
        }

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:60'],
        ]);

        $tpl = \FalconSlider\Models\SliderTemplate::create([
            'name'     => $data['name'],
            'category' => $data['category'] ?: 'My designs',
            'settings' => $this->decodeJson($request->input('settings'), []),
            'slides'   => $this->decodeJson($request->input('slides'), []),
        ]);

        return response()->json(['ok' => true, 'template' => $tpl->only(['id', 'name', 'category', 'settings', 'slides'])]);
    }

    /** Delete a user-saved template. */
    public function deleteTemplate(\FalconSlider\Models\SliderTemplate $template)
    {
        $template->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Standalone slider render for the page-builder canvas iframe. The builder's
     * Vue canvas inserts element HTML with v-html, which never runs the runtime
     * <script>; so the live preview is shown through a same-origin iframe that
     * loads this full HTML document (runtime included) and self-sizes.
     */
    public function preview(Slider $slider)
    {
        return view('falcon-slider::frontend.preview', ['slider' => $slider]);
    }

    /** Save the whole slider (settings + slides JSON) from the editor. */
    public function update(Request $request, Slider $slider)
    {
        if (! $this->canSave()) {
            return $this->proRequired($request);
        }

        // Snapshot the CURRENT (about-to-be-replaced) design first, so every save
        // leaves a restorable point. Skip the very first save of an empty slider.
        $this->snapshot($slider);

        $slider->update([
            'name'     => $request->input('name', $slider->name),
            'settings' => $this->decodeJson($request->input('settings'), Slider::defaultSettings()),
            'slides'   => $this->decodeJson($request->input('slides'), []),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.sliders.index')->with('success', 'Slider saved.');
    }

    /** Store a revision of the slider's current state, keeping only the newest 20. */
    protected function snapshot(Slider $slider): void
    {
        if (empty($slider->slides)) {
            return;   // nothing worth snapshotting yet
        }

        SliderRevision::create([
            'slider_id' => $slider->id,
            'name'      => $slider->name,
            'settings'  => $slider->settings,
            'slides'    => $slider->slides,
        ]);

        // Prune: keep the 20 most recent for this slider.
        $keep = $slider->revisions()->orderByDesc('id')->limit(20)->pluck('id');
        $slider->revisions()->whereNotIn('id', $keep)->delete();
    }

    /** Version history for a slider — JSON list, newest first, with full payloads for one-click restore. */
    public function revisions(Slider $slider)
    {
        $items = $slider->revisions()->orderByDesc('id')->limit(20)->get()
            ->map(fn (SliderRevision $r) => [
                'id'       => $r->id,
                'name'     => $r->name,
                'slides'   => is_array($r->slides) ? count($r->slides) : 0,
                'when'     => $r->created_at?->diffForHumans(),
                'at'       => $r->created_at?->format('M j, Y g:i A'),
                'settings' => $r->settings,
                'slidesData' => $r->slides,
            ]);

        return response()->json(['ok' => true, 'revisions' => $items]);
    }

    /**
     * Full-screen live preview of UNSAVED editor state. The editor POSTs the current
     * settings + slides; we hydrate the model in memory (never save) and render the
     * full slider so autoplay + every animation runs exactly as on the frontend.
     */
    public function previewLive(Request $request, Slider $slider)
    {
        $slider->setRawAttributes(array_merge($slider->getAttributes(), [
            'settings' => json_encode($this->decodeJson($request->input('settings'), $slider->settings ?? Slider::defaultSettings())),
            'slides'   => json_encode($this->decodeJson($request->input('slides'), $slider->slides ?? [])),
        ]));

        return view('falcon-slider::frontend.fullpreview', ['slider' => $slider]);
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted.');
    }

    /** Download a slider as a portable .json file. */
    public function export(Slider $slider)
    {
        $data = [
            'falconSlider' => 1,
            'exportedAt'   => now()->toIso8601String(),
            'name'         => $slider->name,
            'settings'     => $slider->settings,
            'slides'       => $slider->slides,
        ];
        $file = (\Illuminate\Support\Str::slug($slider->name) ?: 'slider') . '.falconslider.json';

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $file . '"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /** Create slider(s) from an uploaded .json — a single export OR a multi-slider bundle. */
    public function import(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'max:16384']]);

        $data = json_decode(file_get_contents($request->file('file')->getRealPath()), true);
        if (!is_array($data)) {
            return redirect()->route('admin.sliders.index')->with('error', 'That is not a valid Falcon Slider file.');
        }

        // Bundle of many sliders (bulk export).
        if (isset($data['sliders']) && is_array($data['sliders'])) {
            $n = 0;
            foreach ($data['sliders'] as $one) {
                if (!is_array($one) || !isset($one['slides']) || !is_array($one['slides'])) continue;
                Slider::create([
                    'name'     => trim(($one['name'] ?? 'Imported slider')) . ' (imported)',
                    'settings' => is_array($one['settings'] ?? null) ? $one['settings'] : Slider::defaultSettings(),
                    'slides'   => $one['slides'],
                ]);
                $n++;
            }
            return redirect()->route('admin.sliders.index')->with('success', $n . ' slider(s) imported.');
        }

        // Single slider export.
        if (!isset($data['slides']) || !is_array($data['slides'])) {
            return redirect()->route('admin.sliders.index')->with('error', 'That is not a valid Falcon Slider export file.');
        }
        $slider = Slider::create([
            'name'     => trim(($data['name'] ?? 'Imported slider')) . ' (imported)',
            'settings' => is_array($data['settings'] ?? null) ? $data['settings'] : Slider::defaultSettings(),
            'slides'   => $data['slides'],
        ]);

        return redirect()->route('admin.sliders.edit', $slider)->with('success', 'Slider imported — review & save.');
    }

    /** Bulk actions from the list: export the selected sliders as one bundle, or delete them. */
    public function bulk(Request $request)
    {
        $ids = array_filter((array) $request->input('ids', []));
        $action = $request->input('action');

        if (empty($ids)) {
            return redirect()->route('admin.sliders.index')->with('error', 'No sliders selected.');
        }

        if ($action === 'delete') {
            $count = Slider::whereIn('id', $ids)->count();
            Slider::whereIn('id', $ids)->delete();
            return redirect()->route('admin.sliders.index')->with('success', $count . ' slider(s) deleted.');
        }

        if ($action === 'export') {
            $sliders = Slider::whereIn('id', $ids)->get()->map(fn ($s) => [
                'name'     => $s->name,
                'settings' => $s->settings,
                'slides'   => $s->slides,
            ])->all();
            $bundle = ['falconSliderBundle' => 1, 'exportedAt' => now()->toIso8601String(), 'sliders' => $sliders];

            return response()->json($bundle, 200, [
                'Content-Disposition' => 'attachment; filename="sliders-bundle.falconslider.json"',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        return redirect()->route('admin.sliders.index')->with('error', 'Choose a bulk action.');
    }

    /** Accept either a JSON string (from the editor) or an already-decoded array. */
    protected function decodeJson($value, array $fallback): array
    {
        if (is_array($value)) {
            return $value;
        }
        $decoded = json_decode((string) $value, true);

        return is_array($decoded) ? $decoded : $fallback;
    }
}
