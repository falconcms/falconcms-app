<?php

namespace FalconSlider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A point-in-time snapshot of a slider's design (name + settings + slides),
 * captured on every save. Powers version history / "restore a previous version".
 */
class SliderRevision extends Model
{
    protected $fillable = ['slider_id', 'name', 'settings', 'slides'];

    protected $casts = [
        'settings' => 'array',
        'slides'   => 'array',
    ];

    public function slider(): BelongsTo
    {
        return $this->belongsTo(Slider::class);
    }
}
