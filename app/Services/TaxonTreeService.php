<?php

/*
 * Copyright 2017 Niels Klazenga, Royal Botanic Gardens Victoria.
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

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Description of TaxonTreeModel
 *
 * @author Niels Klazenga <Niels.Klazenga@rbg.vic.gov.au>
 */
class TaxonTreeService {
    protected $nodeNumber;
    protected $highestDescendantNodeNumber;
    protected $tree;
    protected $taxonIds;
    protected $parentIds;

    public function create()
    {
        // delete existing tree
        DB::table('taxon_tree')->delete();
        DB::statement("ALTER SEQUENCE taxon_tree_id_seq RESTART WITH 1");
        
        $this->nodeNumber = 0;
        $this->highestDescendantNodeNumber = 0;
        $this->tree = [];
        $this->taxonIds = [];
        $this->parentIds = [];
        
        $this->init();
        $this->getHighestDescendantNodeNumbers();
        $this->saveTree();
    }
    
    protected function init()
    {
        $this->nodeNumber++;
        $taxonId = DB::table('taxa')
                ->where('rank_id', 1)->value('id');
        $node = [
            'taxon_id' => $taxonId,
            'parent_id' => null,
            'created_by_id' => 1,
            'node_number' => $this->nodeNumber,
            'highest_descendant_node_number' => null,
            'depth' => 0,
            'timestamp_created' => date('Y-m-d H:m:s'),
            'version' => 1
        ];
        $this->tree[] = $node;
        $this->parentIds[] = null;
        $this->taxonIds[] = $taxonId;
        $this->addNode($taxonId, 0);
        
    }
    
    protected function addNode($parentId, $depth)
    {
        $taxa = DB::table('taxa')
                ->where([
                    ['taxonomic_status_id', '=', 1],
                    ['parent_id', '=', $parentId]
                ])
                ->select('id')
                ->get();
        if ($taxa) {
            $depth++;
            foreach ($taxa as $taxon) {
                $this->nodeNumber++;
                $node = [
                    'taxon_id' => $taxon->id,
                    'parent_id' => $parentId,
                    'created_by_id' => 1,
                    'node_number' => $this->nodeNumber,
                    'highest_descendant_node_number' => null,
                    'depth' => $depth,
                    'timestamp_created' => date('Y-m-d H:m:s'),
                    'version' => 1
                ];
                $this->tree[] = $node;
                $this->taxonIds[] = $taxon->id;
                $this->parentIds[] = $parentId;
                $this->addNode($taxon->id, $depth);
            }
        }
        else {
            return false;
        }
    }
    
    protected function getHighestDescendantNodeNumbers()
    {
        foreach ($this->tree as $key => $node) {
            $this->getHighestDescendantNodeNumber($key, $node['taxon_id']);
        }
    }
    
    protected function getHighestDescendantNodeNumber($key, $taxonId)
    {
        $parentIds = array_keys($this->parentIds, $taxonId);
        if ($parentIds) {
            foreach ($parentIds as $parentId) {
                $node = $this->tree[$parentId];
                $this->getHighestDescendantNodeNumber($key, $node['taxon_id']);
            }
        }
        else {
            $skey = array_search($taxonId, $this->taxonIds);
            if ($skey !== FALSE) {
                $node = $this->tree[$skey];
                $this->tree[$key]['highest_descendant_node_number'] = 
                        $node['node_number'];
            }
        }
    }
    
    protected function saveTree()
    {
        foreach ($this->tree as $node) {
            DB::table('taxon_tree')->insert($node);
        }
    }
    
    
    public static function isInTaxonTree($taxonId)
    {
        $test = DB::table('taxon_tree as tr')
                ->join('taxa as t', 'tr.taxon_id', '=', 't.id')
                ->where('t.guid', $taxonId)
                ->value('tr.id');
        return ($test) ? true : false;
    }
    
    /**
     * Add an item or subtree to the taxon tree
     * 
     * @param string $taxonId    UUID of the taxon
     * @param string $parentId   UUID of the parent whereto the item is to be attached
     */
    public static function addItem($taxonId, $parentId)
    {
        $parentNode = DB::table('taxon_tree as tr')
                ->join('taxa as t', 'tr.taxon_id', '=', 't.id')
                ->where('t.guid', $parentId)
                ->select('tr.node_number', 'tr.highest_descendant_node_number', 
                        'tr.depth')
                ->first();
        if ($parentNode) {
            // Raise the node number and highest descendant node numbers of records 
            // for which the node number is higher than that of the parent of the new 
            // taxon by 1
            DB::table('taxon_tree')
                    ->where('node_number', '>', 
                            $parentNode->highest_descendant_node_number)
                    ->update([
                        'node_number' => DB::raw('node_number+1'),
                        'highest_descendant_node_number' => 
                                DB::raw('highest_descendant_node_number+1'),
                    ]);

            // raise the highest descendant node number by 1
            DB::table('taxon_tree')
                    ->where('node_number', '<=', $parentNode->node_number)
                    ->where('highest_descendant_node_number', '>=', 
                            $parentNode->highest_descendant_node_number)
                    ->update([
                        'highest_descendant_node_number' =>
                                DB::raw('highest_descendant_node_number+1')
                    ]);
            
            // insert the new item
            $insert = [
                'taxon_id' => app('em')->getRepository('\App\Entities\Taxon')
                    ->findOneBy(['guid' => $taxonId])->getId(),
                'node_number' => $parentNode->highest_descendant_node_number + 1,
                'highest_descendant_node_number' => 
                        $parentNode->highest_descendant_node_number + 1,
                'depth' => $parentNode->depth + 1,
                'timestamp_created' => date('Y-m-d H:i:s'),
                'version' => 0
            ];
            DB::table('taxon_tree')
                    ->insert($insert);
        }
    }
    
    /**
     * Remove an item from the taxon tree
     * 
     * @param string $taxonId    UUID of the taxon
     */
    public static function deleteItem($taxonId)
    {
        $node = DB::table('taxon_tree as tr')
                ->join('taxa as t', 'tr.taxon_id', '=', 't.id')
                ->where('t.guid', $taxonId)
                ->select('tr.node_number', 'tr.highest_descendant_node_number', 
                        'tr.depth')
                ->first();
        if ($node) {
            DB::table('taxon_tree')
                    ->where('taxon_id', app('em')
                            ->getRepository('\App\Entities\Taxon')
                            ->findOneBy(['guid' => $taxonId])->getId())
                    ->delete();
            
            // decrement higher node numbers
            DB::table('taxon_tree')
                    ->where('node_number', '>', $node->node_number)
                    ->update([
                        'node_number' => DB::raw('node_number-1')
                    ]);
            
            // decrement highest descendant node numbers
            DB::table('taxon_tree')
                    ->where('highest_descendant_node_number', '>', 
                            $node->highest_descendant_node_number)
                    ->update([
                        'highest_descendant_node_number' => 
                                DB::raw('highest_descendant_node_number-1')
                    ]);
        }
    }
    
    /**
     * Move an item or subtree in the taxon tree
     * 
     * @param string $taxonId       UUID of the taxon
     * @param string $newParentId   UUID of the new parent whereto the item is to be attached
     */
    public static function moveItem($taxonId, $newParentId)
    {
        // Get this taxon and its children
        $node = DB::table('taxon_tree')
                ->where('taxon_id', app('em')
                        ->getRepository('\App\Entities\Taxon')
                        ->findOneBy(['guid' => $taxonId])->getId())
                ->select('node_number', 'highest_descendant_node_number')
                ->first();
        $taxonIds = DB::table('taxon_tree as tr')
                ->join('taxa as t', 'tr.taxon_id', '=', 't.id')
                ->where([
                    ['tr.node_number', '>=', $node->node_number],
                    ['tr.highest_descendant_node_number', '<=', 
                        $node->highest_descendant_node_number],
                ])
                ->orderBy('tr.node_number')
                ->pluck('t.guid');
        if ($taxonIds) {
            foreach ($taxonIds as $guid) {
                // First delete the item from the tree
                self::deleteItem($guid);

                // Then insert it into the new position
                self::addItem($guid, $newParentId);
            }
            
            // Return the array with GUIDs, as the SOLR index needs to
            // be updated for all child taxa as well.
            return $taxonIds;
        }
    }
}
