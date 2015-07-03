<?php require_once("./check.php"); ?>

<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=export-users.xls");

require_once("../db.php");

try {
  $dbh = new PDO($dsn, $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
  $error = array("error" =>  'Error connection'); //$e->getMessage()
  echo json_encode($error);
  die();
}

if(isset($_GET['date']) && $_GET['date']!='') {
  $date = $_GET['date'];
  $sql = 'SELECT * FROM `user` WHERE `user`.`created_at` > "' . addslashes($date) . ' 00:00:00" AND `user`.`created_at` < "' . addslashes($date) . ' 23:59:59";';
} else {
  $sql = 'SELECT * FROM `user`;';
}

$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetchAll();
?>

<table>
  <tr>
    <th>ID</th>
    <th>Facebook ID</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Phone</th>
    <th>Share</th>
    <th>Device</th>
    <th>Created Date</th>
  </tr>
  <?php
    foreach($tab as $row) :
  ?>
  <tr>
    <td><?php echo $row['user_id']; ?></td>
    <td><?php echo $row['facebook_id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['gender']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['shares']; ?></td>
    <td><?php echo $row['device']; ?></td>
    <td><?php echo $row['created_at']; ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php $dbh = null;?>