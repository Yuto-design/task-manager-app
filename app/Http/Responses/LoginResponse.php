<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LoginResponse
{
    public function toResponse(Request $request): RedirectResponse
    {
        return redirect('/tasks');
    }
}
