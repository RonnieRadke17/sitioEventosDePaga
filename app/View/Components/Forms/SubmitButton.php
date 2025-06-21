<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SubmitButton extends Component
{
    /**
     * Create a new component instance.
     */
    public $value;
    public function __construct($value = '')
    {
        //
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.submit-button');
    }
}
