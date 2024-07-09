<?php
require './class/Database.php';

$dbh=new Database();
$dbh->dbConnect();
$dbh->ExecuteQuery("INSERT INTO dbo.tbUsers(Title,UserName,Password) values('a','b','v')");

echo 'finished';

?>