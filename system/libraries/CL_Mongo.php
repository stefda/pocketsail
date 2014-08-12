<?php

/**
 * @author David Stefan
 */
class CL_Mongo {

    private static $instance = NULL;
    private $m = NULL;
    public $db = NULL;
    public $cursor = NULL;

    public function __construct() {

        $config = CL_Config::get_instance();

        // Retrieve MongoDB config
        $host = $config->get_item('database', 'mongo_server');
        $port = $config->get_item('database', 'mongo_port');
        $user = $config->get_item('database', 'mongo_user');
        $password = $config->get_item('database', 'mongo_password');
        $database = $config->get_item('database', 'mongo_database');

        // Build connection URI
        $uri = 'mongodb://';
        $uri .= $user !== NULL ? $user . ':' . $password . '@' : '';
        $uri .= $host !== NULL ? $host : 'localhost';
        $uri .= $port !== NULL ? ':' . $port : '';

        // Connect to MongoDB
        try {
            $this->m = new MongoClient($uri);
        } catch (MongoConnectionException $e) {
            throwErrorAndExit($e->getMessage());
        }

        // Use database
        $this->db = $this->m->{$database};
    }

    /**
     * @return CL_Mongo
     */
    public static function getInstance() {

        if (self::$instance === NULL) {
            self::$instance = new CL_Mongo();
        }

        return self::$instance;
    }

    public function find($collect, $query) {
        $collect = $this->db->{$collect};
        $this->cursor = $collect->find($query);
    }
    
    /**
     * Fetches next object from the cursor created during the last query.
     * 
     * @return array
     */
    public function fetchObject() {
        return $this->cursor->getNext();
    }
    
    /**
     * Inserts a document into given collection.
     * 
     * @param string $collect
     * @param array $doc
     */
    public function insert($collect, $doc) {
        $collect = $this->db->{$collect};
        $collect->insert($doc);
    }

}
