<?php
require_once("configuration.php");

$sql = new Sql;
$result = $sql->query("select * from tb_produtos");

while($row = mysqli_fetch_array($result)){
    var_dump($row);
}
?>