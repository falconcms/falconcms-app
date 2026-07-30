<?php

namespace FalconSlider;

use FalconCms\Core\Models\Permission;

class Lifecycle
{
    public function activate(): void
    {
        Permission::updateOrCreate(
            ['slug' => 'manage_sliders'],
            ['name' => 'Manage Sliders', 'description' => 'Create and edit sliders']
        );
    }

    public function uninstall(): void
    {
        Permission::where('slug', 'manage_sliders')->delete();
    }
}
