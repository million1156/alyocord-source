<?php
session_start();

if (isset($_SESSION['loggedin'])) {
  header("Location: //alyocord.funwithalbi.xyz/app/");
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css?version=<?php echo time(); ?>">
  <link rel="stylesheet" href="xavsanims.css">
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <script src='//alyocord.funwithalbi.xyz/script.js?version=<?php echo time(); ?>'></script>
  <title>Alyocord</title>
  <script src="/node_modules/intersection-observer/intersection-observer.js"></script>
  <script defer src="anims.js"></script>
</head>
<body>
  <center>
    <br> <img src='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico' height='75' width='75'> <br>
    <h1>Alyocord</h1>
    <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/app/"'>I'm sold! Give me the app already!</button> <br>
    <br>
    <section class="hidden">
      <h1>Great community</h1>
      <p>Our community is super kind and helpful!</p>
    </section>
    <section class="hidden">
      <h1>Helpful staff</h1>
      <p>Feel free to contact our staff if you have any issues.</p>
    </section>
    <section class="hidden">
      <h1>Little to no lag</h1>
      <p>Read your messages the second they are sent!</p>
    </section>
    <section class="hidden">
      <h1>Clean UI</h1>
      <p>Super clean and responsive UI.</p>
    </section>
  </center>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>
</html>
