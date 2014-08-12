<?php

class POI {

    public $id;
    public $name;
    public $label;
    public $url;
    public $lat;
    public $lng;
    public $description;
    public $approach;
    public $contact;
    public $anchoring;
    public $charts;
    public $sources;
    public $exposure;
    public $attractions;
    public $nightlife;

    public static function fromPostData($data) {
        
        CL_Loader::get_instance()->library('PostDataFormatException');

        $poi = new POI();

        $poi->id = (int) $data['id'];
        $poi->name = (string) $data['name'];
        $poi->label = (string) $data['label'];
        $poi->url = (string) $data['url'];
        $poi->lat = (float) $data['lat'];
        $poi->lng = (float) $data['lng'];

        // Description
        if (key_exists("description", $data)) {
            $poi->description = (string) $data['description'];
        }

        // Approach
        if (isset($data['approach'])) {
            if (!isset($data['approach']['text'])
                    || !isset($data['approach']['drying']['value'])
                    || !isset($data['approach']['drying']['details'])) {
                throw new PostDataFormatException("Approach");
            }
            $poi->approach['text'] = (string) $data['approach']['text'];
            $poi->approach['drying']['value'] = (string) $data['approach']['drying']['value'];
            $poi->approach['drying']['details'] = (string) $data['approach']['drying']['details'];
        }

        // Contact
        if (isset($data['contact']['type']) && isset($data['contact']['value'])) {
            // Make sure all attributes have the same length
            if (count($data['contact']['type']) === count($data['contact']['value'])) {
                for ($i = 0; $i < count($data['contact']['type']); $i++) {
                    $poi->contact[] = [
                        'type' => (string) $data['contact']['type'][$i],
                        'value' => (string) $data['contact']['value'][$i]
                    ];
                }
            }
        }

        // Anchoring
        if (key_exists("anchoring", $data)) {

            // Depth
            $poi->anchoring['depth']['from'] = (float) $data['anchoring']['depth']['from'];
            $poi->anchoring['depth']['to'] = (float) $data['anchoring']['depth']['to'];

            // Holding
            $poi->anchoring['holding']['values'] = [];
            foreach ((array) @$data['anchoring']['holding']['values'] AS $value) {
                $poi->anchoring['holding']['values'][] = $value;
            }
            $poi->anchoring['holding']['details'] = (string) $data['anchoring']['holding']['details'];

            // Price
            $poi->anchoring['cost'] = Cost::fromPostData($data['anchoring']['cost']);

            // Soujourn
            $poi->anchoring['tax'] = Tax::fromPostData($data['anchoring']['tax']);
        }

        // Charts
        if (key_exists("charts", $data)) {
            foreach ($data['charts'] AS $chart) {
                if ($chart !== "") {
                    $poi->charts[] = (string) $chart;
                }
            }
        }

        // Sources
        if (key_exists("sources", $data)) {
            foreach ($data['sources'] AS $source) {
                if ($source !== "") {
                    $poi->sources[] = (string) $source;
                }
            }
        }

        // Exposure
        if (key_exists("exposure", $data)) {

            // Wind
            $poi->exposure['wind']['value'] = (string) $data['exposure']['wind']['value'];
            foreach ((array) @$data['exposure']['wind']['dir'] AS $wind) {
                if ($wind !== "") {
                    $poi->exposure['wind']['dir'][] = (string) $wind;
                }
            }

            // Swell
            $poi->exposure['swell']['value'] = (string) $data['exposure']['swell']['value'];
            foreach ((array) @$data['exposure']['swell']['dir'] AS $swell) {
                if ($swell !== "") {
                    $poi->exposure['swell']['dir'][] = (string) $swell;
                }
            }
        }

        // Attractions
        if (key_exists("attractions", $data)) {
            $poi->attractions = (string) $data['attractions'];
        }

        // Nightlife
        if (key_exists("nightlife", $data)) {
            $poi->nightlife = (string) $data['nightlife'];
        }

        return $poi;
    }

    public
            function toDoc() {

        $doc = [];

        // Iterate over all attributes and convert them into doc
        foreach ($this AS $attrName => $attr) {
            if ($attr !== NULL) {
                $doc[$attrName] = $attr;
//                switch (gettype($attr)) {
//                    // If attribute is an object
//                    case 'object': {
//                            $doc[$attrName] = $attr->toDoc();
//                            break;
//                        }
//                    // If attribute is an array
//                    case 'array': {
//                            $doc[$attrName] = [];
//                            foreach ($attr AS $item) {
//                                $doc[$attrName][] = $item->toDoc();
//                            }
//                            break;
//                        }
//                    // ...otherwise
//                    default: {
//                            $doc[$attrName] = $attr;
//                        }
//                }
            }
        }
        return $doc;
    }

}

class Cost {

    public static function fromPostData($data) {

        $cost['value'] = (string) $data['value'];
        $cost['price'] = (float) $data['price'];
        $cost['currency'] = (string) $data['currency'];
        $cost['type'] = (string) $data['type'];
        $cost['details'] = (string) $data['details'];

        return $cost;
    }

}

class Tax {

    public static function fromPostData($data) {

        $tax['value'] = (float) $data['value'];
        $tax['currency'] = (string) $data['currency'];
        $tax['type'] = (string) $data['type'];
        $tax['details'] = (string) $data['details'];

        return $tax;
    }

}
