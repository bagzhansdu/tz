<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZpRequest;
use App\Models\Zp;

class ZpController extends Controller
{

    public function calculate(ZpRequest $request, Zp $zp)
    {
        if ($request->validated()) {
            return $zp->calculate($request);
        }
    }
}
