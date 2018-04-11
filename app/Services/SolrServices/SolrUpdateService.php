<?php

/*
 * Copyright 2018 Royal Botanic Gardens Victoria.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Services\SolrServices;

use Solarium\Client;
use Illuminate\Support\Facades\DB;

/**
 * Description of SolrUpdateService
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class SolrUpdateService {
    protected $client;
    
    public function __construct() {
        $this->client = new Client(config('solarium'));
    }
    
    public function updateAllDocuments()
    {
        $taxa = DB::table('taxa')->pluck('guid');
        foreach ($taxa as $id) {
            $this->updateDocument($id);
        }
    }
    
    public function updateDocument($id)
    {
        $updateQuery = $this->client->createUpdate();
        $this->doc = $updateQuery->createDocument();
        $row = $this->getData($id);
        $this->doc->project = ['HortFlora'];
        $this->doc->id = $id;
        $this->doc->scientific_name_id = $row->scientific_name_id;
        $this->doc->scientific_name = $row->scientific_name;
        $this->doc->scientific_name_authorship = 
                $row->scientific_name_authorship;
        $this->doc->name_type = $row->name_type;
        $this->doc->entry_type = $row->entry_type;
        $this->doc->taxon_remarks = $row->taxon_remarks;
        $this->doc->vernacular_name = $row->vernacular_name;
        if ($row->entry_type == 'taxon') {
            $this->doc->taxon_rank = $row->taxon_rank;
            $this->doc->taxonomic_status = $row->taxonomic_status;
            $this->doc->parent_name_usage_id = $row->parent_name_usage_id;
            $this->doc->parent_name_usage = $row->parent_name_usage;
            $this->doc->accepted_name_usage_id = $row->accepted_name_usage_id;
            $this->doc->accepted_name_usage = $row->accepted_name_usage;
            $this->doc->accepted_name_usage_authorship = 
                    $row->accepted_name_usage_authorship;
            $this->doc->accepted_name_usage_taxon_rank = 
                    $row->accepted_name_usage_taxon_rank;
            if ($row->accepted_rank_id == 220) {
                $this->doc->species_id = $row->accepted_name_usage_id;
            }
            elseif ($row->accepted_rank_id > 220) {
                $this->doc->species_id = 
                        $this->getSpeciesID($row->accepted_name_usage_id);
            }
            if ($row->rank_id > 220) {
                $this->doc->infraspecific_epithet_prefix = $row->text_before;
            }
        }
        else { // cultivar or horticulturalGroup
            if ($row->name_type == 'cultivar') {
                $this->doc->cultivar_epithet = $row->name_part;
            }
            $this->doc->taxon_id = $row->taxon_id;
            $this->doc->taxon_name = $row->taxon_name;
            $this->doc->horticultural_group_id = $row->horticultural_group_id;
            $this->doc->horticultural_group_name = $row->horticultural_group_name;
        }
        if ($row->node_number) {
            if ($this->hasChildren($id)) {
                $this->doc->end_or_higher_taxon = 'higher';
            }
            else {
                $this->doc->end_or_higher_taxon = 'end';
            }
            $ranks = $this->higherClassification($row->node_number);
            if ($ranks) {
                foreach ($ranks as $key => $value) {
                    $this->doc->$key = $value;
                }
            }
        }
        elseif ($row->accepted_node_number) {
            if ($this->hasChildren($row->accepted_name_usage_id)) {
                $this->doc->end_or_higher_taxon = 'higher';
            }
            else {
                $this->doc->end_or_higher_taxon = 'end';
            }
            $ranks = $this->higherClassification($row->accepted_node_number);
            if ($ranks) {
                foreach ($ranks as $key => $value) {
                    $this->doc->$key = $value;
                }
            }
        }
        elseif ($row->taxon_node_number) {
            $ranks = $this->higherClassification($row->taxon_node_number);
            if ($ranks) {
                foreach ($ranks as $key => $value) {
                    $this->doc->$key = $value;
                }
            }
        }
        $updateQuery->addDocuments([$this->doc], $overwrite=true);
        $updateQuery->addCommit();
        $this->client->update($updateQuery);
    }
    
    protected function getData($id) 
    {
        return DB::table('taxa as t')
                ->join('names as n','t.name_id','=','n.id')
                ->leftJoin('name_type_vocab as nt',
                        'n.name_type_id','=','nt.id')
                ->leftJoin('taxon_rank_vocab as td',
                        't.rank_id','=','td.id')
                ->leftJoin('taxa as pt','t.parent_id','=','pt.id')
                ->leftJoin('names as pn','pt.name_id','=','pn.id')
                ->leftJoin('taxa as at','t.accepted_id','=','at.id')
                ->leftJoin('names as an','at.name_id','=','an.id')
                ->leftJoin('taxon_rank_vocab as atd',
                        'at.rank_id','=','atd.id')
                ->leftJoin('taxa as c_t', 't.taxon_id', '=', 'c_t.id')
                ->leftJoin('names as c_n', 'c_t.name_id', '=', 'c_n.id')
                ->leftJoin('taxa as c_cg', 't.horticultural_group_id', '=', 'c_cg.id')
                ->leftJoin('names as c_cgn', 'c_cg.name_id', '=', 'c_cgn.id')
                ->leftJoin('taxon_tree as tt', function($join) {
                    $join->on('t.id','=','tt.taxon_id')
                            ->where('t.taxonomic_status_id', 1);
                })
                ->leftJoin('taxon_tree as att','at.id','=','att.taxon_id')
                ->leftJoin('taxon_tree as c_tt', function($join) {
                    $join->on('c_t.id', '=', 'c_tt.taxon_id');
                })
                ->leftJoin('vernacular_names as v', function($join) {
                    $join->on('t.id','=','v.taxon_id')
                            ->where([
                                ['t.taxonomic_status_id', '=', 1],
                                ['v.is_preferred_name', '=', true]
                            ]);
                })
                ->leftJoin('taxonomic_status_vocab AS ts',
                        't.taxonomic_status_id','=','ts.id')
                
                        
                ->where('t.guid', $id)
                ->select('n.guid as scientific_name_id','n.id as name_id',
                        'n.full_name as scientific_name', 'n.name as name_part',
                        'n.authorship as scientific_name_authorship',
                        'nt.name as name_type', 't.discr as entry_type',
                        'td.name as taxon_rank', 'td.text_before',
                        'td.name as raxon_rank','ts.name as taxonomic_status',
                        't.taxon_remarks',
                        'pt.guid as parent_name_usage_id',
                        'pn.full_name as parent_name_usage',
                        'at.guid as accepted_name_usage_id',
                        'an.full_name as accepted_name_usage',
                        'an.authorship as accepted_name_usage_authorship',
                        'atd.name as accepted_name_usage_taxon_rank',
                        'tt.node_number',
                        'att.node_number as accepted_node_number',
                        'v.vernacular_name', 'td.rank_id',
                        'atd.rank_id as accepted_rank_id', 
                        'c_t.guid as taxon_id', 'c_n.full_name as taxon_name',
                        'c_cg.guid as horticultural_group_id', 
                        'c_cgn.full_name as horticultural_group_name', 
                        'c_tt.node_number as taxon_node_number')
                ->first();
    }
    
    protected function getSpeciesID($id)
    {
        return DB::table('taxa as t')
                ->join('taxa as pt', 't.parent_id', '=', 'pt.id')
                ->where('t.guid', $id)
                ->value('pt.guid');
    }
    
    protected function hasChildren($id)
    {
        $numChildren = DB::table('taxa as t')
                ->join('taxa as ct', 't.id', '=', 'ct.parent_id')
                ->where([
                    ['t.guid', '=', $id],
                    ['ct.taxonomic_status_id', '=', 1]
                ])
                ->count();
        if ($numChildren) {
            return true;
        }
        else {
            return false;
        }
    }
    
    protected function higherClassification($node)
    {
        $ret = [];
        $query = DB::table('taxa as t')
                ->join('taxon_rank_vocab as td',
                        't.rank_id', '=', 'td.id')
                ->join('taxon_tree as tr', 't.id', '=', 'tr.taxon_id')
                ->join('names as n', 't.name_id', '=', 'n.id')
                ->where([
                    ['tr.node_number', '<=', $node],
                    ['tr.highest_descendant_node_number', '>=', $node],
                    ['td.rank_id', '>', 0],
                ])
                ->select('td.name as rank', 'n.name');
        $result = $query->get();
        if ($result) {
            foreach ($result as $row) {
                if ($row->rank == 'species') {
                    $ret['specific_epithet'] = $row->name;
                }
                elseif (in_array($row->rank, 
                        ['subspecies', 'variety', 'forma'])) {
                    $ret['infraspecific_epithet'] = $row->name;
                }
                elseif (in_array($row->rank, ['section'])) {
                    continue;
                }
                else {
                    $ret[$row->rank] = $row->name;
                }
            }
        }
        return $ret;
    }
}
