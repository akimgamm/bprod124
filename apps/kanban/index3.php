
<?

class Database {
  
  private $DB_DSN = "mysql:host=localhost; dbname=dstinfonew; charset=utf8;";
  private $DB_DSN2 = "mysql:host=localhost; dbname=error_db; charset=utf8;";
  private $DB_DSN3 = "mysql:host=localhost; dbname=bitrixApps; charset=utf8;";
  
  private $DB_USERNAME = "user";
  private $DB_PASSWORD = "YjnhLfv2021";

  public function connect() {
    $this->conn = null;
    $this->conn2 = null;
    $this->conn3 = null;

    $this->conn = new PDO($this->DB_DSN, $this->DB_USERNAME, $this->DB_PASSWORD);
    $this->conn2 = new PDO($this->DB_DSN2, $this->DB_USERNAME, $this->DB_PASSWORD);
    $this->conn3 = new PDO($this->DB_DSN3, $this->DB_USERNAME, $this->DB_PASSWORD);

    $this->db = ["dstinfonew" => $this->conn, "error_db" => $this->conn2,"bitrixApps" => $this->conn3];
    return $this->db;
  }
   
}

$database = new Database;
$db = $database->connect();

$sql = "SELECT * FROM managersPremium WHERE deal_id='123786784'";

$st = $db['bitrixApps']->prepare($sql);
$st->execute();
$query = $st->fetchAll(PDO::FETCH_ASSOC);

print_r($query);