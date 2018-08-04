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
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    $agent = EntityManager::getRepository('\App\Entities\Agent')
            ->findOneBy(['user' => Auth::User()]);
    return response()->json([
            'id' => $agent->getId(),
            'name' => $agent->getName(),
            'email' => $agent->getEmail()
        ]);
});

Route::get('/', function() {
    return view('swagger');
});
Route::get('/swagger.json', 'API\\ApiController@apiDocs');

Route::get('/search', 'API\\SolariumController@search')->name('api.search');
Route::get('ping', 'API\\SolariumController@ping');

/*
 * TaxonController
 */
Route::get('taxa/{taxon}',
        'API\\TaxonController@show')
        ->name('api.taxa.show');
Route::get('/taxa/{taxon}/name',
        'API\\TaxonController@hasScientificName')
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
Route::get('taxa/{taxon}/treatments',
        'API\\TaxonController@hasTreatments')
        ->name('api.taxa.treatments');
Route::middleware('auth:api')->group(function() {
    Route::post('taxa/{taxon}/treatments',
        'API\\TaxonController@storeTreatment')
        ->name('api.taxa.treatments.store');
});
Route::get('taxa/{taxon}/currentTreatment',
        'API\\TaxonController@hasCurrentTreatment')
        ->name('api.taxa.currentTreatment');
Route::get('taxa/{taxon}/vernacularNames',
        'API\\TaxonController@hasVernacularNames')
        ->name('api.taxa.vernacularNames');
Route::get('taxa/{taxon}/changes',
        'API\\TaxonController@hasChanges')
        ->name('api.taxa.changes');
Route::get('taxa/{taxon}/heroImage',
        'API\\TaxonController@hasHeroImage')
        ->name('api.taxa.heroImage');
Route::get('taxa/{taxon}/images',
        'API\\TaxonController@hasImages')
        ->name('api.taxa.images');
Route::get('taxa/{taxon}/creator',
        'API\\TaxonController@hasCreator')
        ->name('api.taxa.creator');
Route::get('taxa/{taxon}/modifiedBy',
        'API\\TaxonController@hasModifiedBy')
        ->name('api.taxa.modifiedBy');
Route::get('taxa/{taxon}/references',
        'API\\TaxonController@hasReferences')
        ->name('api.taxa.references');
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
Route::get('taxa/{taxon}/cultivars',
        'API\\TaxonController@hasCultivars')
        ->name('api.taxa.cultivars');
Route::get('taxa/{taxon}/cultivarGroups',
        'API\\TaxonController@hasHorticulturalGroups')
        ->name('api.taxa.cultivarGroup');
Route::get('taxa/{taxon}/key',
        'API\\TaxonController@hasKey')
        ->name('api.taxa.key');
Route::get('taxa/{taxon}/naturalDistribution',
        'API\\TaxonController@hasRegions')
        ->name('api.taxa.distribution');
Route::get('taxa/{taxon}/distributionMap',
        'API\\TaxonController@hasDistributionMap')
        ->name('api.taxa.distributionMap');
Route::middleware('auth:api')->group(function() {
    Route::post('taxa',
            'API\\TaxonController@store')
            ->name('api.taxa.store');
    Route::match(['PUT', 'PATCH'], 'taxa/{taxon}',
            'API\\TaxonController@update')
            ->name('api.taxa.update');
    Route::delete('taxa/{taxon}',
            'API\\TaxonController@destroy')
            ->name('api.taxa.destroy');
});
Route::get('taxa/{taxon}/regions',
        'API\\TaxonController@showRegions')
        ->name('api.taxa.regions');
Route::middleware('auth.api')->group(function() {
    Route::post('taxa/{taxon}/regions',
            'API\\TaxonController@addRegions')
            ->name('api.taxa.regions.add');
    Route::delete('taxa/{taxon}/regions',
            'API\\TaxonController@removeRegions')
            ->name('api.taxa.regions.remove');
});

/*
 * CultivarController
 */
Route::get('cultivars/{cultivar}',
        'API\\CultivarController@show')
        ->name('api.cultivars.show');
Route::get('/cultivars/{cultivar}/name',
        'API\\CultivarController@hasScientificName')
        ->name('api.cultivars.name');
Route::get('cultivars/{cultivar}/acceptedNameUsage',
        'API\\CultivarController@hasAcceptedNameUsage')
        ->name('api.cultivars.acceptedNameUsage');
Route::get('cultivars/{cultivar}/nameAccordingTo',
        'API\\CultivarController@hasNameAccordingTo')
        ->name('api.cultivars.nameAccordingTo');
Route::get('cultivars/{cultivar}/treatments',
        'API\\CultivarController@hasTreatments')
        ->name('api.cultivars.treatments');
Route::middleware('auth:api')->group(function() {
    Route::post('cultivars/{cultivar}/treatments',
        'API\\CultivarController@storeTreatment')
        ->name('api.cultivars.treatments.store');
});
Route::get('cultivars/{cultivar}/currentTreatment',
        'API\\CultivarController@hasCurrentTreatment')
        ->name('api.cultivars.currentTreatment');
Route::get('cultivars/{cultivar}/vernacularNames',
        'API\\CultivarController@hasVernacularNames')
        ->name('api.cultivars.vernacularNames');
Route::get('cultivars/{cultivar}/changes',
        'API\\CultivarController@hasChanges')
        ->name('api.cultivars.changes');
Route::get('cultivars/{cultivar}/heroImage',
        'API\\CultivarController@hasHeroImage')
        ->name('api.cultivars.heroImage');
Route::get('cultivars/{cultivar}/images',
        'API\\CultivarController@hasImages')
        ->name('api.cultivars.images');
Route::get('cultivars/{cultivar}/creator',
        'API\\CultivarController@hasCreator')
        ->name('api.cultivars.creator');
Route::get('cultivars/{cultivar}/modifiedBy',
        'API\\CultivarController@hasModifiedBy')
        ->name('api.cultivars.modifiedBy');
Route::get('cultivars/{cultivar}/references',
        'API\\CultivarController@hasReferences')
        ->name('api.cultivars.references');
Route::get('cultivars/{cultivar}/taxon',
        'API\\CultivarController@hasTaxon')
        ->name('api.cultivars.taxon');
Route::get('cultivars/{cultivar}/cultivarGroup',
        'API\\CultivarController@hasHorticulturalGroup')
        ->name('api.cultivars.cultivarGroup');

/*
 * HorticulturalGroupController
 */
Route::get('horticultural-groups/{group}',
        'API\\HorticulturalGroupController@show')
        ->name('api.horticultural-groups.show');
Route::get('/horticultural-groups/{group}/name',
        'API\\HorticulturalGroupController@hasScientificName')
        ->name('api.horticultural-groups.name');
Route::get('horticultural-groups/{group}/acceptedNameUsage',
        'API\\HorticulturalGroupController@hasAcceptedNameUsage')
        ->name('api.horticultural-groups.acceptedNameUsage');
Route::get('horticultural-groups/{group}/nameAccordingTo',
        'API\\HorticulturalGroupController@hasNameAccordingTo')
        ->name('api.horticultural-groups.nameAccordingTo');
Route::get('horticultural-groups/{group}/treatments',
        'API\\HorticulturalGroupController@hasTreatments')
        ->name('api.horticultural-groups.treatments');
Route::middleware('auth:api')->group(function() {
    Route::post('horticultural-groups/{group}/treatments',
        'API\\HorticulturalGroupController@storeTreatment')
        ->name('api.horticultural-groups.treatments.store');
});
Route::get('horticultural-groups/{group}/currentTreatment',
        'API\\HorticulturalGroupController@hasCurrentTreatment')
        ->name('api.horticultural-groups.currentTreatment');
Route::get('horticultural-groups/{group}/vernacularNames',
        'API\\HorticulturalGroupController@hasVernacularNames')
        ->name('api.horticultural-groups.vernacularNames');
Route::get('horticultural-groups/{group}/changes',
        'API\\HorticulturalGroupController@hasChanges')
        ->name('api.horticultural-groups.changes');
Route::get('horticultural-groups/{group}/heroImage',
        'API\\HorticulturalGroupController@hasHeroImage')
        ->name('api.horticultural-groups.heroImage');
Route::get('horticultural-groups/{group}/images',
        'API\\HorticulturalGroupController@hasImages')
        ->name('api.horticultural-groups.images');
Route::get('horticultural-groups/{group}/creator',
        'API\\HorticulturalGroupController@hasCreator')
        ->name('api.horticultural-groups.creator');
Route::get('horticultural-groups/{group}/modifiedBy',
        'API\\HorticulturalGroupController@hasModifiedBy')
        ->name('api.horticultural-groups.modifiedBy');
Route::get('horticultural-groups/{group}/references',
        'API\\HorticulturalGroupController@hasReferences')
        ->name('api.horticultural-groups.references');
Route::get('horticultural-groups/{group}/taxon',
        'API\\HorticulturalGroupController@hasTaxon')
        ->name('api.horticultural-groups.taxon');
Route::get('horticultural-groups/{group}/members',
        'API\\HorticulturalGroupController@hasMembers')
        ->name('api.horticultural-groups.members');

/*
 * NameController
 */
Route::get('names/{name}', 'API\\NameController@show')
        ->name('api.taxa.show');
Route::get('names/{name}/namePublishedIn',
        'API\\NameController@showNamePublishedIn')
        ->name('api.names.namePublishedIn');
Route::middleware('auth:api')->group(function() {
    Route::post('names',
            'API\\NameController@store')
            ->name('api.names.store');
    Route::put('names/{name}',
            'API\\NameController@update')
            ->name('api.names.update');
    Route::delete('names/{name}',
            'API\\NameController@destroy')
            ->name('api.names.destroy');
});

/*
 * ReferenceController
 */
Route::get('references/{reference}', 'API\\ReferenceController@show')
        ->name('api.references.show');
Route::middleware('auth:api')->group(function() {
    Route::post('references',
            'API\\ReferenceController@store')
            ->name('api.references.store');
    Route::match(['PUT', 'PATCH'], 'references/{reference}',
            'API\\ReferenceController@update')
            ->name('api.references.update');
    Route::delete('references/{reference}',
            'API\\ReferenceController@destroy')
            ->name('api.references.destroy');
});

/*
 * VernacularNameController
 */
Route::get('vernacular-names/{vernacular_name}',
        'API\\VernacularNameController@show')
        ->name('api.vernacular-names.show');
Route::middleware('auth:api')->group(function() {
    Route::post('vernacular-names',
            'API\\VernacularNameController@store')
            ->name('api.vernacular-names.store');
    Route::put('vernacular-names/{vernacular_name}',
            'API\\VernacularNameController@update')
            ->name('api.vernacular-names.update');
    Route::delete('vernacular-names/{vernacular_name}',
            'API\\VernacularNameController@destroy')
            ->name('api.vernacular-names.destroy');
});

/*
 * TreatmentController
 */
Route::get('treatments/{treatment}', 'API\\TreatmentController@show')
        ->name('api.treatments.show');
Route::get('treatments/{treatment}/forTaxon', "API\\TreatmentController@showForTaxon")
        ->name('api.treatments.forTaxon');
Route::get('treatments/{treatment}/asTaxon', "API\\TreatmentController@showAsTaxon")
        ->name('api.treatments.asTaxon');
Route::get('treatments/{treatment}/versions',
        'API\\TreatmentController@showVersions')
        ->name('api.treatments.versions.list');
Route::middleware('auth:api')->group(function() {
    Route::post('treatments/{treatment}/versions',
            'API\\TreatmentController@storeVersion')
            ->name('api.treatments.versions.store');
});

/*
 * TreatmentVersionController
 */
Route::resource('versions', 'API\\TreatmentVersionController', ['as' => 'api'])
        ->only(['show']);
Route::middleware('auth:api')->group(function() {
    Route::resource('versions', 'API\\TreatmentVersionController',
            ['as' => 'api'])
            ->only(['update', 'destroy']);
});

/*
 * ImageController
 */
Route::get('images', 'API\\ImageController@index')->name('api.images.list');
Route::get('images/{image}', 'API\\ImageController@show')->name('api.images.show');
Route::middleware('auth:api')->group(function() {
    Route::post('images',
            'API\\ImageController@store')
            ->name('api.images.store');
    Route::match(['PUT', 'PATCH'], 'images/{image}',
            'API\\ImageController@update')
            ->name('api.images.update');
    Route::delete('images/{image}',
            'API\\ImageController@destroy')
            ->name('api.images.destroy');
});
Route::get('images/{image}/access-points',
        'API\\ImageController@listAccessPoints')
        ->name('api.images.access-points.list');
Route::get('images/{image}/access-points/{access_point}',
        'API\\ImageController@showAccessPoints')
        ->name('api.images.access-points.show');
Route::middleware('auth:api')->group(function() {
    Route::post('images/{image}/access-points',
            'API\\ImageController@storeAccessPoint')
            ->name('api.images.access-points.store');
    Route::match(['PUT', 'PATCH'], 'images/{image}/access-points/{access_point}',
            'API\\ImageController@updateAccessPoint')
            ->name('api.images.access-points.update');
    Route::delete('images/{image}/access-points/{access_point}',
            'API\\ImageController@destroyAccessPoint')
            ->name('api.images.access-points.destroy');
});
Route::get('images/{image}/features',
        'API\\ImageController@showFeatures')
        ->name('api.images.features');
Route::middleware('auth.api')->group(function() {
    Route::post('images/{image}/features',
            'API\\ImageController@addFeatures')
            ->name('api.images.features.add');
    Route::delete('images/{image}/features',
            'API\\ImageController@removeFeatures')
            ->name('api.images.features.remove');
});
Route::get('images/{image}/occurrence',
        'API\\ImageController@showOccurrence')
        ->name('api.images.occurrence');

/*
 * OccurrenceController
 */
Route::get('occurrences/{occurrence}/event', 'API\\OccurrenceController@showEvent')
        ->name('api.occurrences.event.show');
Route::get('occurrences/{occurrence}', 'API\\OccurrenceController@show')
        ->name('api.occurrences.show');
Route::middleware('auth:api')->group(function() {
    Route::post('occurrences',
            'API\\OccurrenceController@store')
            ->name('api.occurrences.store');
    Route::put('occurrences/{occurrence}',
            'API\\OccurrenceController@update')
            ->name('api.occurrences.update');
    Route::delete('occurrences/{occurrence}',
            'API\\OccurrenceController@destroy')
            ->name('api.occurrences.destroy');
});

/*
 * EventController
 */
Route::get('events/{event}/location', 'API\\EventController@showLocation')
        ->name('api.events.location.show');
Route::resource('events', 'API\\EventController', ['as' => 'api'])
        ->only(['show']);
Route::middleware('auth:api')->group(function() {
    Route::resource('events', 'API\\EventController', ['as' => 'api'])
            ->only(['store', 'update', 'destroy']);
});

/*
 * EventController
 */
Route::resource('locations', 'API\\LocationController', ['as' => 'api'])
        ->only(['show']);
Route::middleware('auth:api')->group(function() {
    Route::resource('locations', 'API\\LocationController', ['as' => 'api'])
            ->only(['store', 'update', 'destroy']);
});

/*
 * VocabularyController
 */
Route::get('vocabularies/{vocabulary}/terms', 'API\\VocabularyController@listTerms')
        ->name('api.vocabularies.terms.list');
Route::get('vocabularies/{vocabulary}/terms/{term}', 'API\\VocabularyController@showTerm')
        ->name('api.vocabularies.terms.show');
Route::middleware('auth:api')->group(function() {
    Route::post('vocabularies/{vocabulary}/terms', 'API\\VocabularyController@storeTerm')
            ->name('api.vocabularies.terms.store');
    Route::match(['PUT', 'PATCH'], 'vocabularies/{vocabulary}/terms/{term}',
            'API\\VocabularyController@updateTerm')
            ->name('api.vocabularies.terms.update');
    Route::delete('vocabularies/{vocabulary}/terms/{term}', 'API\\VocabularyController@destroyTerm')
            ->name('api.vocabularies.terms.destroy');
});

/*
 * ChangeController
 */
Route::resource('changes', 'API\\ChangeController', ['as' => 'api'])
        ->only(['show']);
Route::middleware('auth:api')->resource('changes', 'API\\ChangeController',
        ['as' => 'api'])->only(['update', 'destroy']);

/*
 * Autocomplete
 */
Route::get('autocomplete/name', 'API\\AutocompleteController@autocompleteName')
        ->name('api.autocomplete.name');

/*
 * Agent
 */
Route::resource('agents', 'API\\AgentController', ['as' => 'api'])->only(['show']);

