<?php

namespace App\View\Components\Logs;

use App\Models\UserLog;
use Illuminate\View\Component;

class LogEntryContent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( private UserLog $entry )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $payload = $this->entry->payload;
        if ( is_string( $payload ) ) {
            $payload = json_decode( $payload );
        }

        if ( $this->isModelChangePayload( $payload ) ) {
            return $this->renderModelChangePayload( $payload );
        }

        return view('components.logs.log-entry-content', [
            'payload' => $payload
        ]);
    }

    private function isModelChangePayload( array $payload )
    {        
        return array_key_exists( 'old', $payload ) && array_key_exists( 'new', $payload );
    }

    private function renderModelChangePayload( array $payload )
    {
        $rows = collect( array_keys( $payload['old'] ) );
        $rows = $rows->merge( array_keys( $payload['new'] ) );
        $rows = $rows->unique();

        $table = [];
        foreach ( $rows as $row )
        {
            $table[] = [
                $row,
                $payload['old'][$row] ?? '-',
                $payload['new'][$row] ?? '-'
            ];
        }

        return view( 'components.logs.model-change', [
            'table' => $table
        ] );
    }
}
