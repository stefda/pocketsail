<?php

class SolrResponse {

    private $rawResponse;

    public function __construct($res) {
        $this->rawResponse = json_decode($res);
    }

    public function get_raw_response() {
        return $this->rawResponse;
    }
    
    public function status() {
        return $this->rawResponse->responseHeader->status;
    }

    public function num_found() {
        return $this->rawResponse->response->numFound;
    }

    public function docs() {
        return $this->rawResponse->response->docs;
    }
    
    public function num_docs() {
        return count($this->rawResponse->response->docs);
    }

}
