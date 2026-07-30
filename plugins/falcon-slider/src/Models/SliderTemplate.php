<?php

namespace FalconSlider\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A user-saved slider design ("Save as pre-built"). Reused from the editor's
 * Pre-built gallery — its settings + slides are dropped into a slider on demand.
 */
class SliderTemplate extends Model
{
    protected $fillable = ['name', 'category', 'settings', 'slides'];

    protected $casts = [
        'settings' => 'array',
        'slides'   => 'array',
    ];
}
