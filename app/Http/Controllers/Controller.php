<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getAuthInfo() {
        $info = ['auth' => Auth::check()];
        if (Auth::check()) {
            $info['user'] = [
                'id' => Auth::user()->getId(),
                'name' => Auth::user()->getName()
            ];
        }
        return $info;
    }

    public function getHomePage()
    {
        return view('app', ['data' => $this->getAuthInfo()]);
    }
}

/*            @if(Auth::check())
            window.user = "{{ Auth::user()->getName() }}";
            @endif
*/
