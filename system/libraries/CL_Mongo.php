<?php

/**
 * @author David Stefan
 */
class CL_Mongo {

    private static $instance = NULL;
    private $mongo = NULL;
    public $db = NULL;
    public $cursor = NULL;
    private $res = NULL;

    public function __construct() {

        $config = CL_Config::get_instance();

        // Retrieve Mongo config
        $host = $config->get_value('database', 'mongo_server');
        $port = $config->get_value('database', 'mongo_port');
        $user = $config->get_value('database', 'mongo_user');
        $password = $config->get_value('database', 'mongo_password');
        $database = $config->get_value('database', 'mongo_database');

        // Build connection URI
        $uri = 'mongodb://';
        $uri .= $user !== NULL ? $user . ':' . $password . '@' : '';
        $uri .= $host !== NULL ? $host : 'localhost';
        $uri .= $port !== NULL ? ':' . $port : '';

        // Connect to Mongo
        try {
            $this->mongo = new MongoClient($uri);
        } catch (MongoConnectionException $e) {
            error($e->getMessage());
        }

        // Create database shortcut
        $this->db = $this->mongo->{$database};
    }

    /**
     * @return CL_Mongo
     */
    public static function get_instance() {

        if (self::$instance === NULL) {
            self::$instance = new CL_Mongo();
        }

        return self::$instance;
    }

    public function find($collection, $query) {
        return $this->cursor = $this->db->{$collection}->find($query);
    }

    public function find_and_modify($collection, $query, $update) {
        return $this->cursor = $this->db->{$collection}->findAndModify($query, $update);
    }

    /**
     * Fetches the next object from cursor.
     * 
     * @return array
     */
    public function next() {
        try {
            $doc = $this->cursor->getNext();
            if ($doc !== NULL && key_exists('$err', $doc)) {
                throw new MongoCursorException('Mongo ' . $doc['code'] . ": " . $doc['$err']);
            }
            return $doc;
        } catch (MongoCursorException $e) {
            error($e->getMessage());
        }
    }

    /**
     * Inserts a document into the given collection.
     * 
     * @param string $collection
     * @param array $doc
     */
    public function insert($collection, $doc) {
        try {
            $this->res = $this->db->{$collection}->insert($doc);
        } catch (MongoException $e) {
            error($e->getMessage());
        }
        return TRUE;
    }

    /**
     * Fetch and incerase by one the autoincrement seq value for the given
     * collection and insert the document with the fetched seq value.
     * 
     * @param string $collection
     * @param array $doc
     * @return boolean
     */
    public function insert_inc($collection, $doc) {

        // Find and increment corresponding seq
        $temp = $this->db->autoincrement->findAndModify(['_id' => $collection], [
            '$inc' => ['seq' => 1]
        ]);

        // Add/modify _id field with seq value
        $doc['_id'] = $temp['seq'];

        // Proceed with insert
        try {
            $this->res = $this->db->{$collection}->insert($doc);
        } catch (MongoException $e) {
            error($e->getMessage());
        }
        return TRUE;
    }

    /**
     * Update a document.
     * 
     * @param string $collection
     * @param array $query
     * @param array $update
     */
    public function update($collection, $query, $update) {
        try {
            $this->res = $this->db->{$collection}->update($query, $update);
        } catch (MongoException $e) {
            error($e->getMessage());
        }
        return TRUE;
    }

}
