<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/style.css?version=<?php echo time(); ?>">
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <script src='//alyocord.funwithalbi.xyz/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <title>Alyocord</title>
</head>  <!-- fucking uhh maybe -->
<body> <!-- mind if i make this page /app/ instead of / so i can hottify the landing page -->
  <center>
    <br> <img src='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico' height='75' width='75'> <br>
    <h1>Alyocord</h1>
    <br><br><br><br>
    <?php
    if (!isset($_SESSION['loggedin'])) {
    ?>
    <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/signup/";' name="signupbtn">Signup</button> <br> <br>
    <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/login/";'>Login</button>
    <?php
    } else {
    ?>
    <form method='post'>
      <button class='button' type='submit' name='logout'>Logout</button> <br> <br>
    </form>
    <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/chat/";'>Open Alyocord</button>
    <?php
    }

    if (isset($_SESSION['admin'])) {
      echo "<br><br>";
      echo "<button class='button' onclick='window.location.href = \"//alyocord.funwithalbi.xyz/users/\";'>Users</button>";
    }

    if (isset($_POST['logout'])) {
      session_destroy();
      echo "<script>window.location.href='//alyocord.funwithalbi.xyz/app/'</script>";
    }
    ?>
  </center>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>
</html>