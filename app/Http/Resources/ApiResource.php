<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    /**
     * Generic resource for outputting models
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static $wrap = null;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
