<? 

$config = [
  'host' => 'localhost',
  'dbname' => 'dstinfo',
  'username' => 'user',
  'password' => 'YjnhLfv2021',
  'charset' => 'utf8'
];

$dsn = 'mysql:host=' . $config['host'] . '; dbname=' . $config['dbname'] . '; charset=' . $config['charset'];
$user = $config['username'];
$password = $config['password'];
$this->link = new PDO($dsn, $user, $password, array(PDO::MYSQL_ATTR_LOCAL_INFILE => true,));

// $sql = "SELECT `nomer` FROM `mashprop` WHERE `2` = 'ТМ10.11ГСТ10'";
// $sth = $this->link->prepare($sql);

// $values = [];
// $sth->execute($values);

// $result = $sth->fetchAll(PDO::FETCH_ASSOC);

echo 134;

?>