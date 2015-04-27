<?php

class SolrService {

    private static $instance = NULL;
    private $solr;
    
    public static $SERVLET = 'select';

    public function __construct() {
        require_once BASEPATH . 'application/libraries/solr/Apache/Solr/Service.php';
        require_once BASEPATH . 'application/libraries/solr/SolrResponse.php';
        $config = CL_Config::get_instance()->get_config('solr');
        $host = $config['host'];
        $port = $config['port'];
        $path = $config['path'];
        $this->solr = new Apache_Solr_Service($host, $port, $path, self::$SERVLET);
    }

    /**
     * @return SolrService
     */
    public static function get_instance() {
        if (self::$instance == NULL) {
            self::$instance = new SolrService();
        }
        return self::$instance;
    }

    public function query($query, $limit = 10, $offset = 0) {
        $query .= " -subName:Anchorage";
        $query .= " AND -subName:Supermarket";
        $query .= " AND -subName:Minimarket";
        $query .= " AND -subName:\"Mooring buoys\"";
        $query .= " AND -subName:\"Shore mooring\"";
        $query .= " AND -subName:\"Cashpoint\"";
        $query .= " AND -subName:\"Gas station\"";
        $res = $this->solr->search($query . " -subName:Anchorage ", $offset, $limit, array())->getRawResponse();
        return new SolrResponse($res);
    }

    public function fulltext($term, $limit = 10, $offset = 0) {
        $query = $term;
        $res = $this->solr->search($query, $offset, $limit, [
            'q' => $term,
            'spellcheck' => 'true',
            'spellcheck.collate' => 'true',
            'spellcheck.build' => 'true',
            'hl' => 'true'
        ])->getRawResponse();
        return new SolrResponse($res);
    }

    public function reload_cache() {
        $url = 'http://' . $this->solr->getHost() . ':' . $this->solr->getPort() . $this->solr->getPath() . 'reloadCache?wt=json';
        return file_get_contents($url);
    }

    public function ping() {
        return $this->solr->ping();
    }

    public function truncate() {
        $this->solr->deleteByQuery('*:*');
        $this->solr->commit();
    }

}
