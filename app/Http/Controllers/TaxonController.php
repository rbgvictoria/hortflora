<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxonController extends Controller
{
    public function getTaxon(Request $request, $id)
    {
        return view('taxon', ['id' => $id]);
    }
}
