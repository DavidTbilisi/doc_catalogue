<?php

namespace App\View\Components;

use Illuminate\View\Component;

class IoPermission extends Component
{
    public $permission;
    public $disabled = false;
    public $perms;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($permission, $perms, $disabled=false)
    {
        $this->permission = $permission;
        $this->disabled = $disabled;
        $this->perms = $perms;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.io-permission');
    }
}
