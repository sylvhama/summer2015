<?php session_start(); ?>

<?php
  $co=false;
  require_once("./config.php");
  if(isset($_POST['login']) && isset($_POST['mdp']))
  {
    if(!empty($_POST['login']) && !empty($_POST['mdp']))
    {
      $password_md5 = md5($_POST['mdp']);

      if(($_admin_login == $_POST['login']) && ($password_md5 == $_admin_pass))
      {
        $_SESSION['_login'] = $_admin_login;
        $_SESSION['_pass'] = $password_md5;
        $co=true;
      }
    }
  }
  if($co==false)
  {
?>
    <!DOCTYPE html>
    <html>
    <head>
      <title>Backend Login</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/foundation.min.css" />
      <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.0/css/normalize.css" />
    </head>
    	<body>
    		<div id="home">
 <?php
    echo '<div class="text-center alert-box warning">Connection refused!</div>';
    require_once("./conec.html");
    exit();
    ?>
    </div>
    </body>
<?php
  }else
  {
    echo '<script>window.location.replace("/php/backend/admin.php");</script>';
  }
?>

	
</html>