<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceResponse;

class ErrorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public $errro = "Error occurred!";
    public $statusCode;
    public function __construct($resource, $statusCode = 401, $errro = 'Error occurred!')
    {
        parent::__construct($resource);
        $this->statusCode = $statusCode;
        $this->errro = $errro;
    }
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
    public function toResponse($request)
    {
        return (new ResourceResponse($this))->toResponse($request)->setStatusCode($this->statusCode ?? 401);
    }
}
