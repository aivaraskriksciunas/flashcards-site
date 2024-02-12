<?php 

namespace App\Services\Logs\Payloads;

use Illuminate\Database\Eloquent\Model;

class LoggerPayload 
{
    
    public function __construct(
        protected string $action,
        protected Model|null $model = null
    ) 
    {}

    public function getAction() : string {
        return $this->action;
    }

    public function getModel() : Model|null {
        return $this->model;
    }

    public function toJson() : array {
        return [];
    }
}