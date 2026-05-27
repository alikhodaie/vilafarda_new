<?php

namespace App\Http\Requests\Admin\Home\Date;

use App\Http\Requests\Dashboard\Home\StoreCustomDateRequest;

class StoreDateRequest extends StoreCustomDateRequest
{
    public function authorize()
    {
        return $this->user()->can('updateDate', $this->home);
    }
}
