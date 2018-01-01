<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user()->getName();
});

Route::get('taxa/{taxon}', 'API\\TaxonController@show')->name('taxa.show');
Route::get('/taxa/{taxon}/name', 'API\\TaxonController@hasScientificName')
        ->name('api.taxa.name');
Route::get('taxa/{taxon}/acceptedNameUsage',
        'API\\TaxonController@hasAcceptedNameUsage')
        ->name('api.taxa.acceptedNameUsage');
Route::get('taxa/{taxon}/nameAccordingTo',
        'API\\TaxonController@hasNameAccordingTo')
        ->name('api.taxa.nameAccordingTo');
Route::get('taxa/{taxon}/parentNameUsage',
        'API\\TaxonController@hasParentNameUsage')
        ->name('api.taxa.parentNameUsage');

Route::get('taxa/{taxon}/classification',
        'API\\TaxonController@classification')
        ->name('api.taxa.classification');
Route::get('taxa/{taxon}/siblings',
        'API\\TaxonController@siblings')
        ->name('api.taxa.siblings');
Route::get('taxa/{taxon}/children',
        'API\\TaxonController@children')
        ->name('api.taxa.children');
Route::get('taxa/{taxon}/synonyms',
        'API\\TaxonController@synonyms')
        ->name('api.taxa.synonyms');

Route::get('taxa/{taxon}/treatments',
        'API\\TaxonController@hasTreatments')
        ->name('api.taxa.treatments');
Route::get('taxa/{taxon}/currentTreatment',
        'API\\TaxonController@hasCurrentTreatment')
        ->name('api.taxa.currentTreatment');

Route::get('taxa/{taxon}/vernacularNames',
        'API\\TaxonController@hasVernacularNames')
        ->name('api.taxa.vernacularNames');

Route::get('taxa/{taxon}/cultivars',
        'API\\TaxonController@hasCultivars')
        ->name('api.taxa.cultivars');
Route::get('taxa/{taxon}/cultivarGroup',
        'API\\TaxonController@hasCultivarGroup')
        ->name('api.taxa.cultivarGroup');

Route::get('taxa/{taxon}/changes',
        'API\\TaxonController@hasChanges')
        ->name('api.taxa.changes');

Route::get('taxa/{taxon}/heroImage',
        'API\\TaxonController@hasHeroImage')
        ->name('api.taxa.heroImage');

Route::get('taxa/{taxon}/images',
        'API\\TaxonController@hasImages')
        ->name('api.taxa.images');


/*Route::get('taxa/{taxon}/features',
        'API\\TaxonController@listFeatures')
        ->name('api.taxa.features.list');
Route::get('taxa/{taxon}/images',
        'API\\TaxonController@images')
        ->name('api.taxa.images');
Route::get('taxa/{taxon}/references',
        'API\\TaxonController@showReferences')
        ->name('api.taxa.references');
Route::get('taxa/{taxon}/key',
        'API\\TaxonController@findKey')
        ->name('api.taxa.key');
Route::get('taxa/{taxon}/creator',
        'API\\TaxonController@showCreator')
        ->name('api.taxa.creator');
Route::get('taxa/{taxon}/modifiedBy',
        'API\\TaxonController@showModifiedBy')
        ->name('api.taxa.modifiedBy');
Route::get('taxa/{taxon}/vernacularNames',
        'API\\TaxonController@listVernacularNames')
        ->name('api.taxa.vernacularNames');
Route::get('taxa/{taxon}/key',
        'API\\TaxonController@hasKey')
        ->name('api.taxa.key');
Route::get('taxa/{taxon}/distribution',
        'API\\TaxonController@showRegions')
        ->name('api.taxa.distribution');
Route::get('taxa/{taxon}/distributionMap',
        'API\\TaxonController@showDistributionMap')
        ->name('api.taxa.distributionMap');
Route::get('taxa/{taxon}/distributionMapUrl',
        'API\\TaxonController@showDistributionMapUrl')
        ->name('api.taxa.distributionMapUrl');
Route::get('taxa/{taxon}/groups', 'API\\TaxonController@hasGroups')
        ->name('api.taxa.groups');
        */
