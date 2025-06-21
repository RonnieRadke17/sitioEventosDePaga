<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class InputText extends Component
{
    public $description;
    public $oldvalue;
    /**
     * Create a new component instance.
     */
    public function __construct($description = '', $oldvalue = ''){
        $this->description = $description;
        $this->oldvalue = $oldvalue;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.forms.input-text');
    }
}
