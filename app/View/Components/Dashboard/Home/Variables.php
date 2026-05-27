<?php

namespace App\View\Components\Dashboard\Home;

use Illuminate\View\Component;

class Variables extends Component
{
    public $values;
    public $withLabel;
    public $class;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($values = null, bool $withLabel = true, string $class = '')
    {
        $this->values = $values;
        $this->withLabel = $withLabel;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dashboard.home.variables', [
            'values' => $this->values,
            'withLabel' => $this->withLabel,
            'class' => $this->class
        ]);
    }
}
