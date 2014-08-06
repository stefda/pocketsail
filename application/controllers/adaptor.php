<?php

class Adaptor extends CL_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {

        $start = microtime();

        $con = new MongoClient();
        $db = $con->pocketsail;
        $pois = $db->poi;

//        $c = $pois->find([
//            '$where' => 'this.description !== undefined && this.description.length === 0'
//        ]);
//        
//        $c = $pois->find([
//            'contact' => [
//                '$elemMatch' => [
//                    'type' => 'vhf',
//                    'value' => [
//                        '$regex' => ".*\?.*" // ...where value contains '?' sign
//                    ]
//                ]
//            ]
//        ]);
//
//        // Find all * exposed to all of the winds specified
//        $c = $pois->find([
//            'exposure' => [
//                '$exists' => TRUE
//            ],
//            'exposure.wind.value' => "specific",
//            'exposure.wind.dir' => [
//                '$all' => ["N", "NE", "E", "SE", "S", "SW", "W", "NW"]
//            ]
//        ]);
        
        
        $c = $pois->find([
            'exposure' => [
                '$exists' => TRUE
            ],
            '$or' => [
                [
                    'exposure.wind.value' => "specific",
                    'exposure.wind.dir' => [
                        '$nin' => ["N", "NE", "E", "SE", "S", "SW", "W"]
                    ]
                ],
                [
                    'exposure.wind.value' => "protected"
                ]
            ]
        ]);

        echo $c->count();

        foreach ($c->sort(["id" => 1]) AS $doc) {
            var_dump($doc['exposure']['wind']);
        }

        $con->close();

        $end = microtime();

        echo "Processing time: " . ($end - $start);
    }

    function attrs() {

        $this->load->library('POI');
        $this->load->library('geo/*');
        $this->load->model('POIModel');

        $pois = POIModel::loadAll();

        $con = new MongoClient();
        $db = $con->pocketsail;
        $tbl = $db->poi;

        $tbl->remove([]);
        $tbl->ensureIndex([ 'id' => 1, 'lat' => 1, 'lng' => 1, 'contact.type' => 1]);

        foreach ($pois AS $poi) {

//            $id = $poi->id();
//            $url = $poi->url();
//            $name = $poi->name();
//            $label = $poi->label();
//            $nearId = $poi->nearId();
//            $countryId = $poi->countryId();
//            $cat = $poi->cat();
//            $sub = $poi->sub();
//            $userId = $poi->userId();
//            $lat = $poi->lat();
//            $lng = $poi->lng();
//            $border = $poi->border();
//            $rank = $poi->rank();
//            $timestamp = $poi->timestamp();

            $attrs = $poi->attrs();
            $new = [];

            $new['id'] = (int) $poi->id();
            $new['url'] = (string) $poi->url();
            $new['name'] = (string) $poi->name();
            $new['label'] = (string) $poi->label();
            $new['near'] = (string) $poi->nearName();
            $new['country'] = (string) $poi->countryName();
            $new['cat'] = (string) $poi->cat();
            $new['sub'] = (string) $poi->cat();
            $new['lat'] = (float) $poi->lat();
            $new['lng'] = (float) $poi->lng();

            if (gettype($attrs) === 'object') {

                /**
                 * Description
                 */
                if (isset($attrs->description->details)) {
                    $new["description"] = $attrs->description->details;
                }

                /**
                 * Approach
                 */
                if (isset($attrs->approach)) {
                    $new['approach']['text'] = $attrs->approach->details;
                    $new['approach']['drying']['value'] = @$attrs->approach->drying->value;
                    $new['approach']['drying']['details'] = @$attrs->approach->drying->details;
                }

                /**
                 * Hazards
                 */
                if (isset($attrs->hazards->details)) {
                    $new['hazards']['val'] = $attrs->hazards->details;
                }

                /**
                 * Exposure
                 */
                if (isset($attrs->exposure)) {

                    // Wind
                    $winds = explode(",", @$attrs->exposure->wind);
                    if (count($winds) > 0 && $winds[0] != "") {
//                        echo "Specific, yet ";
//                        print_r($winds);
                        $new['exposure']['wind']['value'] = 'specific';
                        foreach ($winds AS $wind) {
                            $new['exposure']['wind']['dir'][] = trim($wind);
                        }
                    } else {
                        $new['exposure']['wind']['value'] = 'na';
                    }

                    // Swell
                    $swells = explode(",", @$attrs->exposure->swell);
                    if (count($swells) > 0) {
                        $new['exposure']['swell']['value'] = 'specific';
                        foreach ($swells AS $swell) {
                            $new['exposure']['swell']['dir'][] = trim($swell);
                        }
                    } else {
                        $new['exposure']['swell']['value'] = 'na';
                    }
                }

                /**
                 * Attractions & Nightlife
                 */
                if (isset($attrs->attractions->details)) {
                    $new['attractions'] = $attrs->attractions->details;
                }

                /**
                 * Nightlife
                 */
                if (isset($attrs->nightlife->details)) {
                    $new['nightlife'] = $attrs->nightlife->details;
                }

                /**
                 * Contact
                 */
                if (isset($attrs->contact->types)) {
                    $new['contact']['type'] = [];
                    $new['contact']['value'] = [];
                    for ($i = 0; $i < count($attrs->contact->types); $i++) {
                        $new['contact']['value'][] = $attrs->contact->values[$i];
                        $new['contact']['type'][] = $attrs->contact->types[$i];
                    }
                }

                /**
                 * Facilities
                 */
                if (isset($attrs->facilities->water->value)) {
                    $new['facilities']['water']['val'] = $attrs->facilities->water->value;
                    $new['facilities']['water']['details']['val'] = @$attrs->facilities->water->details;
                }

                if (isset($attrs->facilities->electricity->value)) {
                    $new['facilities']['electricity']['val'] = $attrs->facilities->electricity->value;
                    $new['facilities']['electricity']['details']['val'] = @$attrs->facilities->electricity->details;
                }

                if (isset($attrs->facilities->showers->value)) {
                    $new['facilities']['showers']['val'] = $attrs->facilities->showers->value;
                    $new['facilities']['showers']['details']['val'] = @$attrs->facilities->showers->details;
                }

                if (isset($attrs->facilities->toilets->value)) {
                    $new['facilities']['toilets']['val'] = $attrs->facilities->toilets->value;
                    $new['facilities']['toilets']['details']['val'] = @$attrs->facilities->toilets->details;
                }

                if (isset($attrs->facilities->waste->value)) {
                    $new['facilities']['waste']['val'] = $attrs->facilities->waste->value;
                    $new['facilities']['waste']['details']['val'] = @$attrs->facilities->waste->details;
                }

                if (isset($attrs->facilities->reception->value)) {
                    $new['facilities']['reception']['val'] = $attrs->facilities->reception->value;
                    $new['facilities']['reception']['details']['val'] = @$attrs->facilities->reception->details;
                }

                if (isset($attrs->facilities->customs->value)) {
                    $new['facilities']['customs']['val'] = $attrs->facilities->customs->value;
                    $new['facilities']['customs']['details']['val'] = @$attrs->facilities->customs->details;
                }

                if (isset($attrs->facilities->enquiries->value)) {
                    $new['facilities']['enquiries']['val'] = $attrs->facilities->enquiries->value;
                    $new['facilities']['enquiries']['details']['val'] = @$attrs->facilities->enquiries->details;
                }

                if (isset($attrs->facilities->laundry->value)) {
                    $new['facilities']['laundry']['val'] = $attrs->facilities->laundry->value;
                    $new['facilities']['laundry']['details']['val'] = @$attrs->facilities->laundry->details;
                }

                if (isset($attrs->facilities->wifi->value)) {
                    $new['facilities']['wifi']['val'] = $attrs->facilities->wifi->value;
                    $new['facilities']['wifi']['details']['val'] = @$attrs->facilities->wifi->details;
                }

                if (isset($attrs->facilities->disability->value)) {
                    $new['facilities']['disability']['val'] = $attrs->facilities->disability->value;
                    $new['facilities']['disability']['details']['val'] = @$attrs->facilities->disability->details;
                }

                if (isset($attrs->facilities->pets->value)) {
                    $new['facilities']['pets']['val'] = $attrs->facilities->pets->value;
                    $new['facilities']['pets']['details']['val'] = @$attrs->facilities->pets->details;
                }

                /**
                 * Sources
                 */
                if (isset($attrs->sources->details)) {
                    $sources = explode("\r\n", $attrs->sources->details);
                    foreach ($sources AS $source) {
                        $new['sources'][] = trim($source);
                    }
                }

                /**
                 * Charts
                 */
                if (isset($attrs->charts->details)) {
                    $chartsStr = trim($attrs->charts->details, " .");
                    $charts = explode(",", $chartsStr);
                    foreach ($charts AS $chart) {
                        $charts_and = $charts = explode(" and ", $chart);
                        foreach ($charts_and AS $chart_and) {
                            $new['charts'][] = trim($chart_and, " .");
                        }
                    }
                }

                /**
                 * Anchoring info
                 */
                if (isset($attrs->anchoring)) {

                    $new['anchoring']['depth']['from'] = $attrs->anchoring->depth->from;
                    $new['anchoring']['depth']['to'] = $attrs->anchoring->depth->to;

                    $new['anchoring']['holding']['value'] = @$attrs->anchoring->holding->values;
                    $new['anchoring']['holding']['details'] = @$attrs->anchoring->holding->details;

                    $new['anchoring']['cost']['value'] = @$attrs->anchoring->price->value !==
                            "";
                    $new['anchoring']['cost']['price'] = @$attrs->anchoring->price->value;
                    $new['anchoring']['cost']['currency'] = @$attrs->anchoring->price->currency;
                    $new['anchoring']['cost']['type'] = @$attrs->anchoring->price->type;
                    $new['anchoring']['cost']['details'] = @$attrs->anchoring->price->details;

                    $new['anchoring']['tax']['value'] = @$attrs->berthing->soujourn->value;
                    $new['anchoring']['tax']['currency'] = @$attrs->berthing->soujourn->currency;
                    $new['anchoring']['tax']['type'] = @$attrs->berthing->soujourn->type;
                    $new['anchoring']['tax']['details'] = @$attrs->berthing->soujourn->details;
                }

                /**
                 * Buoys mooring
                 */
                if (isset($attrs->mooring)) {
                    $new['mooring']['number']['val'] = $attrs->mooring->number->value;
                    $new['mooring']['number']['details']['val'] = @$attrs->mooring->number->details;
                    $new['mooring']['maxdraught']['val'] = $attrs->mooring->maxdraught->value;
                    $new['mooring']['maxdraught']['type']['val'] = $attrs->mooring->maxdraught->type;
                    $new['mooring']['maxdraught']['details']['val'] = @$attrs->mooring->maxdraught->details;
                    $new['mooring']['maxlength']['val'] = $attrs->mooring->maxlength->value;
                    $new['mooring']['maxlength']['type']['val'] = $attrs->mooring->maxlength->type;
                    $new['mooring']['maxlength']['details']['val'] = @$attrs->mooring->maxlength->details;
                    $new['mooring']['price']['val'] = @$attrs->mooring->price->value;
                    $new['mooring']['price']['currency']['val'] = @$attrs->mooring->price->currency;
                    $new['mooring']['price']['type']['val'] = @$attrs->mooring->price->type;
                    $new['mooring']['price']['details']['val'] = @$attrs->mooring->price->details;
                    $new['mooring']['soujourn']['val'] = @$attrs->mooring->soujourn->value;
                    $new['mooring']['soujourn']['currency']['val'] = @$attrs->mooring->soujourn->currency;
                    $new['mooring']['soujourn']['type']['val'] = @$attrs->mooring->soujourn->type;
                    $new['mooring']['soujourn']['details']['val'] = @$attrs->mooring->soujourn->details;
                }

                /**
                 * Berthing, mooring, anchoring
                 */
                if (isset($attrs->mba->details) && $attrs->mba->details !== '') {
                    $new['bma']['val'] = $attrs->mba->details;
                }

                /**
                 * Services
                 */
                if (isset($attrs->services->slipway->value)) {
                    $new['services']['slipway']['val'] = $attrs->services->slipway->value;
                    $new['services']['slipway']['details']['val'] = @$attrs->services->slipway->details;
                }

                if (isset($attrs->services->pumpout->value)) {
                    $new['services']['pumpout']['val'] = $attrs->services->pumpout->value;
                    $new['services']['pumpout']['details']['val'] = @$attrs->services->pumpout->details;
                }

                if (isset($attrs->services->repairs->value)) {
                    $new['services']['repairs']['val'] = $attrs->services->repairs->value;
                    $new['services']['repairs']['details']['val'] = @$attrs->services->repairs->details;
                }

                if (isset($attrs->services->travelift->value)) {
                    $new['services']['travelift']['val'] = $attrs->services->travelift->value;
                    $new['services']['travelift']['maxtonnage']['val'] = @$attrs->services->travelift->maxtonnage;
                    $new['services']['travelift']['details']['val'] = @$attrs->services->travelift->details;
                }

                if (isset($attrs->services->storage->value)) {
                    $new['services']['storage']['val'] = $attrs->services->storage->value;
                    $new['services']['storage']['details']['val'] = @$attrs->services->storage->details;
                }

                if (isset($attrs->services->divers->value)) {
                    $new['services']['divers']['val'] = $attrs->services->divers->value;
                    $new['services']['divers']['details']['val'] = @$attrs->services->divers->details;
                }

                /**
                 * Opening
                 */
                if (isset($attrs->opening)) {
                    $new['opening']['val'] = $attrs->opening->value;
                    $new['opening']['details']['val'] = @$attrs->opening->details;
                    if (isset($attrs->opening->everyday)) {
                        $new['opening']['everyday']['val'] = $attrs->opening->everyday->value;
                        $new['opening']['everyday']['from']['val'] = @$attrs->opening->everyday->from;
                        $new['opening']['everyday']['to']['val'] = @$attrs->opening->everyday->to;
                    }
                    if (isset($attrs->opening->somedays)) {
                        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] AS $day) {
                            $new['opening']['somedays'][$day]['val'] = $attrs->opening->somedays->{$day}->value;
                            $new['opening']['somedays'][$day]['from']['val'] = @$attrs->opening->somedays->{$day}->from;
                            $new['opening']['somedays'][$day]['to']['val'] = @$attrs->opening->somedays->{$day}->to;
                        }
                    }
                }

                /**
                 * Season
                 */
                if (isset($attrs->season)) {
                    $new['season']['val'] = $attrs->season->value;
                    $new['season']['details']['val'] = @$attrs->season->details;
                    $new['season']['from']['val'] = @$attrs->season->from;
                    $new['season']['to']['val'] = @$attrs->season->to;
                }

                /**
                 * Berthing info
                 */
                if (isset($attrs->berthing)) {

                    // Assistance
                    if (isset($attrs->berthing->assistance)) {
                        $new['berthing']['assistance']['val'] = $attrs->berthing->assistance->value;
                    } else {
                        $new['berthing']['assistance']['val'] = 'na';
                    }
                    $new['berthing']['assistance']['details']['val'] = @$attrs->berthing->assistance->details;

                    // Type
                    if (isset($attrs->berthing->type)) {
                        $new['berthing']['type']['val'] = $attrs->berthing->type->values;
                    } else {
                        $new['berthing']['type']['val'] = 'na';
                    }
                    $new['berthing']['type']['details']['val'] = @$attrs->berthing->type->details;

                    // Seaberths
                    if (isset($attrs->berthing->seaberths)) {
                        $new['berthing']['seaberths']['total']['val'] = $attrs->berthing->seaberths->total->value;
                        $new['berthing']['seaberths']['visitor']['val'] = $attrs->berthing->seaberths->total->value;
                    }
                    $new['berthing']['seaberths']['details']['val'] = @$attrs->berthing->seaberths->details;

                    // Dryberths
                    if (isset($attrs->berthing->dryberths)) {
                        $new['berthing']['dryberths']['val'] = $attrs->berthing->dryberths->value;
                        $new['berthing']['dryberths']['details']['val'] = @$attrs->berthing->dryberths->details;
                    }

                    // Maxdraught
                    if (isset($attrs->berthing->maxdraught)) {
                        $new['berthing']['maxdraught']['val'] = $attrs->berthing->maxdraught->value;
                        $new['berthing']['maxdraught']['type']['val'] = $attrs->berthing->maxdraught->type;
                        $new['berthing']['maxdraught']['details']['val'] = @$attrs->berthing->maxdraught->details;
                    }

                    // Maxlength
                    if (isset($attrs->berthing->maxlength)) {
                        $new['berthing']['maxlength']['val'] = $attrs->berthing->maxlength->value;
                        $new['berthing']['maxlength']['type']['val'] = $attrs->berthing->maxlength->type;
                        $new['berthing']['maxlength']['details']['val'] = @$attrs->berthing->maxlength->details;
                    }

                    // Price
                    if (isset($attrs->berthing->price)) {
                        if (isset($attrs->berthing->price->value)) {
                            $new['berthing']['price']['val'] = $attrs->berthing->price->value;
                            $new['berthing']['price']['currency']['val'] = $attrs->berthing->price->currency;
                            $new['berthing']['price']['type']['val'] = $attrs->berthing->price->type;
                        } else {
                            $new['berthing']['price']['val'] = 0;
                            $new['berthing']['price']['currency']['val'] = 'gbp';
                            $new['berthing']['price']['type']['val'] = 'm';
                        }
                        $new['berthing']['price']['details']['val'] = @$attrs->berthing->price->details;
                    }

                    // Soujourn
                    if (isset($attrs->berthing->soujourn)) {
                        if (isset($attrs->berthing->soujourn->value)) {
                            $new['berthing']['soujourn']['val'] = $attrs->berthing->soujourn->value;
                            $new['berthing']['soujourn']['currency']['val'] = $attrs->berthing->soujourn->currency;
                            $new['berthing']['soujourn']['type']['val'] = $attrs->berthing->soujourn->type;
                        } else {
                            $new['berthing']['soujourn']['val'] = 0;
                            $new['berthing']['soujourn']['currency']['val'] = 'gbp';
                            $new['berthing']['soujourn']['type']['val'] = 'person';
                        }
                        $new['berthing']['soujourn']['details']['val'] = @$attrs->berthing->soujourn->details;
                    }
                }
            }

            //$new = json_decode(json_encode($new), TRUE);
            $attributes = POI::fromPostData($new);
//            print_r($attributes->toDoc());
            var_dump($tbl->insert($attributes->toDoc()));
//            print_r($new);
//            echo "<br /><br />";
        }
        $con->close();
    }

}
