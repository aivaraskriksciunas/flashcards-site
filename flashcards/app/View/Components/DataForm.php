<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataForm extends Component
{
    public string $method;
    
    /**
     * Whether or not to show a cancel button
     *
     * @var bool
     */
    public bool $showCancel;
    
    public string $submit; 

    public ?string $delete;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( string $submit, string $method = 'POST', string $delete = null, bool $showCancel = true )
    {
        $this->method = $method;
        $this->submit = $submit;
        $this->delete = $delete;
        $this->showCancel = $showCancel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view( 'components.forms.data-form' );
    }
}
