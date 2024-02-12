<?php 

namespace App\Services\Logs\Payloads;

use Illuminate\Database\Eloquent\Model;

class ModelChangePayload extends LoggerPayload
{
    /**
     * Store previous and current attributes of the model
     *
     * @param array $oldAttributes
     * @param array $currentAttributes
     */
    public function __construct(
        string $action,
        Model $model
    )
    {
        parent::__construct( $action, $model );
    }

    public function toJson() : array {
        return [
            'type' => 'model_change',
            'old' => $this->model->getOriginal(),
            'new' => $this->model->attributesToArray(),
        ];
    }
}