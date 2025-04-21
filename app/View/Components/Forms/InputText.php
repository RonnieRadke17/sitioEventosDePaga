<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class InputText extends Component
{
    public $description;
    /**
     * Create a new component instance.
     */
    public function __construct($description = ''){
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.forms.input-text');
    }
}
