<?php

namespace App\Http\Controllers;

use App\Models\DomainOfInterest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DomainOfInterestController extends Controller
{
    /**
     * @param Request $request
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $attributes = $this->validate($request, [
            'name' => 'required|string',
            'language' => 'required|string'
        ]);


        Auth::user()->domainsOfInterest()->save(DomainOfInterest::firstOrNew($attributes));
    }

    public function get(User $user)
    {
        return $user->domainsOfInterest;
    }

    public function remove(DomainOfInterest $domainOfInterest)
    {
        Auth::user()->domainsOfInterest()->detach($domainOfInterest->id);
    }
}
