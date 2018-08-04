<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutocompleteController extends ApiController
{
    public function autocompleteName(Request $request)
    {
        $term = strtolower($request->input('term'));
        $suggestions = $this->getNameSuggestions($term);
        return response()->json($suggestions);
    }

    public function autocompleteAcceptedName(Request $request)
    {
        $term = strtolower($request->input('term'));
        $suggestions = $this->getAcceptedNameSuggestions($term);
        return response()->json($suggestions);
    }

    protected function getNameSuggestions($term)
    {
        $first = DB::table('vernacular_names')
                ->where(DB::raw('lower(vernacular_name)'), 'like' ,
                        '%' . $term . '%')
                ->select('vernacular_name');
        $result = DB::table('names')
                ->where(DB::raw('lower(full_name)'), 'like',
                        $term . '%')
                ->select('full_name as suggestion')
                ->union($first)
                ->orderBy('suggestion', 'asc')
                ->get();
        $suggestions = [];
        foreach ($result as $row) {
            $suggestions[] = $row->suggestion;
        }
        return $suggestions;
    }

    protected function getAcceptedNameSuggestions($term)
    {
        if(strpos($term, ' ') === false) {
            return DB::table('taxa as t')
                    ->join('flora.names as n', 't.name_id', '=', 'n.id')
                    ->join('flora.taxon_tree_def_items as i',
                            't.taxon_tree_def_item_id', '=', 'i.id')
                    ->where('t.taxonomic_status_id', 1)
                    ->where('i.rank_id', '<=', 180)
                    ->where(DB::raw('lower(n.full_name)'), 'like', $term . '%')
                    ->select('t.guid as id', 'n.full_name as scientificName')
                    ->get();
        }
        else {
            return DB::table('taxa as t')
                    ->join('flora.names as n', 't.name_id', '=', 'n.id')
                    ->where('t.taxonomic_status_id', 1)
                    ->where(DB::raw('lower(n.full_name)'), 'like', $term . '%')
                    ->select('t.guid as id', 'n.full_name as scientificName')
                    ->get();
        }
    }
}
