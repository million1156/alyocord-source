<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/style.css?version=<?php echo time(); ?>">
  <script src='//alyocord.funwithalbi.xyz/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <title>Signup | Alyocord</title>
</head>
<body>
  <center>
    <br> <img src='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico' height='75' width='75'> <br>
    <h1>Alyocord</h1>
    <br><br>
    <button class='button' onclick='window.location.href = "//alyocord.funwithalbi.xyz/app/";'>Home</button> <br> <br>
    <button class='button' onclick='history.back();'>Back</button> <br> <br> <br> <br>
    <div class='signup'>
      <form method='post'>
        <p>Username:</p>
        <input type='text' name='username' required>
        <p>Email:</p>
        <input type='email' name='email' required>
        <p>Password:</p>
        <input type='password' name='password' required> <br> <br>
        <input type='submit' name='signuprequest'>
      </form>
      <?php
        if (isset($_POST['signuprequest'])) {
          $username = $_POST['username'];
          $username = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $username);
          $email = $_POST['email'];
          $password = $_POST['password'];
          if (!empty($username)) {
            if (!empty($email)) {
              if (!empty($password)) {
                if (!(mb_strlen($username) < 3)) {
                  if (!(mb_strlen($username) > 30)) {
                    $db = new PDO('sqlite:../database.sqlite');
                    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $res = $db->exec(
                    "CREATE TABLE IF NOT EXISTS users (
                      id INTEGER PRIMARY KEY AUTOINCREMENT, 
                      username VARCHAR(255), 
                      email VARCHAR(255),
                      password VARCHAR(255),
                      pfp VARCHAR(255),
                      turbolast INTEGER,
                      nameid INTEGER,
                      timesturbo INTEGER,
                      turbocredit INTEGER
                    )"
                  );
                    $sql = "SELECT * FROM users WHERE email = :email";
                        $statement = $db->prepare($sql);
                        $statement->bindValue(":email", htmlspecialchars($email));
                        $nmm = $db->prepare("SELECT * FROM users WHERE username=:unm");
                        $nmm->execute([':unm' => htmlspecialchars($username)]);
                        $nm = $nmm->fetchAll();
                        $nmm->closeCursor();
                        $statement->execute();
                        $row = $statement->fetch();
                        $statement->closeCursor();
                        $nmid = 1;
                        foreach ($nm as $n) {
                          $nmid++;
                        }
                        if ($nmid > 9999) {
                          echo "<p style='color: red;'>Too many users have that username!</p>";
                        } else {
                          if ($row['email'] == $email) {
                            echo "<p style='color: red;'>Email already used! Try another one.</p>";
                          } else {
                            try {  
        
                                $db = new PDO('sqlite:../database.sqlite');
                                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                              
                                $res = $db->exec(
                                  "CREATE TABLE IF NOT EXISTS users (
                                    id INTEGER PRIMARY KEY AUTOINCREMENT, 
                                    username VARCHAR(255), 
                                    email VARCHAR(255),
                                    password VARCHAR(255),
                                    pfp VARCHAR(255),
                                    turbolast INTEGER,
                                    nameid INTEGER,
                                    timesturbo INTEGER,
                                    turbocredit INTEGER
                                  )"
                                );
                                
                                $stmt = $db->prepare(
                                  "INSERT INTO users (username, email, password, pfp, nameid) 
                                    VALUES (:username, :email, :password, \"default.png\", $nmid)"
                                );
                                
                                $stmt->bindValue(':username', htmlspecialchars($username));
                                $stmt->bindValue(':email', htmlspecialchars($email));
                                $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
                                 
                                $stmt->execute();
                                
                                $db = null;
        
                                echo "<p style='color: green;'>Successfully made an account as ".htmlspecialchars($username)."</p>";
                              
                              }  catch (PDOException $ex) {
                              echo "<p style='color: red;'>".$ex->getMessage()."</p>";
                            }
                          }
                        }
                    }
                  } else {
                    echo "<p style='color: red;'>Username must not be less than 3 characters!</p>";
                  }
                } else {
                  echo "<p style='color: red;'>Username must not be longer than 30 characters!</p>";
                }
              } else {
                echo "<p style='color: red;'>Password must not be empty!</p>";
              }
            } else {
              echo "<p style='color: red;'>Email must not be empty!</p>";
            }
          } 
      ?>
    </div>
  </center>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>
</html>