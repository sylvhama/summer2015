<?php require_once("./check.php");

$title = 'Finnair Summer 2015';

function resetPDO($dbh) {
  include("../db.php");
  $dbh = null;
  try {
    $dbh = new PDO($dsn, $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
  } catch (PDOException $e) {
    $error = array("error" =>  'Error connection'); //$e->getMessage()
    echo json_encode($error);
    die();
  }
  return $dbh;
}

$dbh = null;

$dbh = resetPDO($dbh);
$sql = 'SELECT COUNT(DISTINCT `user_id`) AS total FROM `user`;';
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$total = $tab['total'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS total FROM `user` WHERE `device` LIKE 'desktop';";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbDesktop = $tab['total'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS total FROM `user` WHERE `device` LIKE 'mobile';";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbMobile = $tab['total'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS total FROM `user` WHERE `phone` IS NOT NULL;";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbPhones = $tab['total'];

$dbh = resetPDO($dbh);
$sql = 'SELECT COUNT(DISTINCT `user_id`) AS shared FROM `user` WHERE `shares` != 0;';
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbShared = $tab['shared'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(*) AS participations FROM `participation`;";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetch(PDO::FETCH_ASSOC);
$nbParticipations = $tab['participations'];

$dbh = resetPDO($dbh);
$sql = "SELECT COUNT(DISTINCT `user_id`) AS players, year(`created_at`) AS year, month(`created_at`)-1 AS month, day(`created_at`) AS day FROM `user` GROUP BY year(`created_at`), month(`created_at`), day(`created_at`);";
$stmt = $dbh->prepare($sql);
$executed = $stmt->execute();
$tab = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/foundation.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/normalize.css" />
  </head>
  <body>
    <div class="row">
      <div class="columns large-12">
        <br>
        <p class="text-right"><a href="./deco.php">Logout</a></p>
        <h1 class="page-header"><?php echo $title;?> - Database overview</h1>
        <br>
        <p style="color:red"><strong>All percentages (%) are based on the total number of players.</strong></p>
        <br>
        <h2>Metrics</h2>
        <table>
          <tr>
            <th>Total number of players</th>
            <td><?php echo $total; ?></td>
            <td><?php echo round(($total/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Total number of phones</th>
            <td><?php echo $nbPhones; ?></td>
            <td><?php echo round(($nbPhones/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Total number of desktop</th>
            <td><?php echo $nbDesktop; ?></td>
            <td><?php echo round(($nbDesktop/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Total number of mobile</th>
            <td><?php echo $nbMobile; ?></td>
            <td><?php echo round(($nbMobile/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of players who have shared</th>
            <td><?php echo $nbShared; ?></td>
            <td><?php echo round(($nbShared/$total) *100, 2); ?>%</td>
          </tr>
          <tr>
            <th>Number of participations</th>
            <td><?php echo $nbParticipations; ?></td>
            <td><?php echo round(($nbParticipations/$total) *100, 2); ?>%</td>
          </tr>
        </table>
        <br>

        <script type="text/javascript">
          google.load('visualization', '1.1', {packages: ['line']});
          google.setOnLoadCallback(drawChart);

          function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('date', 'date');
            data.addColumn('number', 'players');

            data.addRows([
            <?php foreach($tab as $row) :?>
            <?php echo '[new Date('.$row['year'].','.$row['month'].','.$row['day'].'),'.$row['players'].'],'; ?>
            <?php endforeach; ?>
            ]);

            var options = {
              chart: {
                title: 'Number of players',
                subtitle: 'per day'
              },
            };

            var chart = new google.charts.Line(document.getElementById('linechart_material'));

            chart.draw(data, options);
          }
        </script>

        <div id="linechart_material"></div>

        <?php
          $dbh = resetPDO($dbh);
          $sql = "SELECT * FROM `user` ORDER BY `user`.`created_at` DESC LIMIT 10;";
          $stmt = $dbh->prepare($sql);
          $executed = $stmt->execute();
          $tab = $stmt->fetchAll();
        ?>
        <br>
        <hr>
        <br>
        <h2>Last Users</h2>
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
        <br>
        <h2>Download users</h2>
        <form action="export.php" method="get" style="max-width:150px;">
          <input name="date" type="date">
          <input type="submit" value="Download">
        </form>
        <br>
      </div>
    </div>
  </body>
</html>
<?php $dbh = null;?>