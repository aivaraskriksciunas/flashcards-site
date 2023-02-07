<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class TextInput extends Component
{

    public string $type;

    public string $name;

    public string $value;

    public string $class;

    public string $label;

    public string $placeholder;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( $name, $placeholder = '', $value = '', $type = 'text', $class = '', $label = '' )
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->type = $type;
        $this->class = $class;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.text-input');
    }
}
