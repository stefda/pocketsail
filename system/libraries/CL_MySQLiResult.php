<?

class CL_MySQLiResult {

    private $result;

    public function __construct($result) {
        $this->result = $result;
    }

    public function isMysqliResult() {
        return gettype($this->result) === 'object' && get_class($this->result) === 'mysqli_result';
    }

    public function numRows() {
        if ($this->isMysqliResult()) {
            return $this->result->num_rows;
        }
        return FALSE;
    }

    public function fetchObject() {
        if ($this->isMysqliResult()) {
            return $this->result->fetch_object();
        }
        return FALSE;
    }

}
