<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxonController extends Controller
{

    public function getTaxon(Request $request, $id)
    {
        $data = $this->getAuthInfo();
        $data['id'] = $id;
        return view('app', ['data' => $data]);
    }

    public function getKey(Request $request, $id)
    {
        $data = $this->getAuthInfo();
        $data['id'] = $id;
        return view('app', ['data' => $data]);
    }
}
