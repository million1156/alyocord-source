<?php
session_start();

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

if (!isset($_SESSION['loggedin'])) {
  header("location: //alyocord.funwithalbi.xyz/login/");
}

$db = new PDO('sqlite:../../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$qrrr = $db->prepare("SELECT * FROM users WHERE id=:uid");
$qrrr->bindValue(":uid", $_SESSION['user']['userid']);
$qrrr->execute();
$row = $qrrr->fetch();
$qrrr->closeCursor();
$tag = $row['nameid'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/chat/style.css?version=<?php echo time(); ?>">
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/chat/settings/style.css?version=<?php echo time(); ?>">
  <script src='//alyocord.funwithalbi.xyz/chat/script.js?version=<?php echo time(); ?>'></script>
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <title>Settings</title>
</head>
<body>
  <center>
    <br>
    <button class='button' onclick='location.href = "https://alyocord.funwithalbi.xyz/chat/";'>Chat</button>
    <form method='post' class='newuser'>
      Set new username:
      <br><br>

      <input type='text' name='newUsername' autocomplete="off" value='<?php echo $_SESSION['user']['username']; ?>' required><span style='font-size:1cm;'>#</span>
      <input class='tag' type='text' name='newTag' autocomplete="off" value='<?php echo sprintf("%04d", $tag); ?>' <?php echo $_SESSION['user']['turbo'] == true ? null : "disabled=\"\"" ?>>
      &nbsp <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='50' width='50'>
      &nbsp <button class='button' style='background: green;' type='submit' name='changeUsername'>Change</button>
    </form>
    <!--
    <form method='post'>
      Set new tag:
      <br><br>
      <input type='text' name='newTag' autocomplete="off" placeholder='<? /* echo sprintf("%04d", $tag); ?>' required>
      &nbsp <button class='button' style='background: <?php if($_SESSION['user']['turbo'] == false) { echo "grey"; } else {echo "green";} ?>' type='submit' name='changeTag' <?php if($_SESSION['user']['turbo'] == false) { echo "disabled"; } */ ?>>Change</button> &nbsp <img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='50' width='50'>
    </form>
    --->
    <form method='post' class='newpass'>
      Set new password:<br><br>
      <input type='password' name='newPassword' placeholder='12345678' autocomplete="off" required>
      &nbsp <button class='button' style='background: green;' type='submit' name='changePassword'>Change</button>
    </form>
    <form method='post' class='newmail'>
      Set new email:<br><br>
      <input type='text' name='newEmail' placeholder='johndoe@gmail.com' autocomplete="off" required>
      &nbsp <button class='button' style='background: green;' type='submit' name='changeEmail'>Change</button>
    </form>
    <form method="post" enctype="multipart/form-data" class='newpfp'>
    Set profile picture: <br>
    <input type="file" name="fileToUpload" id="fileToUpload"> <br>
    <button type='submit' class='button' style='background: green;' name='setPFP'>Set</button>
    <br><br><br>
    <form method='post'>
      <button type='submit' name='delete' value='<?php echo $_SESSION['user']['userid']; ?>' class='button' style='background:red;'>Delete Account</button>
    </form>
      
    <form method='post' class='biochange'>
      <p>Set new About Me:</p>
      <textarea name='bio' cols="25" rows='6'><?php echo $row['bio']; ?></textarea> <br><br/>
      <button type='submit' name='changebio' class='button' style='background:green;'>Change About Me</button>
    </form>
    <br><br><br><br><br><br><br><br><br><br>
    <?php
        if (isset($_POST['delete'])) {
          if ($_POST['delete'] == $_SESSION['user']['userid']) {
            $stmt = $db->prepare("DELETE FROM users WHERE id=:uid;");
            $stmt->bindValue(":uid", $_POST['delete']);
            $stmt->execute();
            session_destroy();
            echo "<script>window.location.href = '//alyocord.funwithalbi.xyz/login/?del=true';</script>";
          }
        }

        if (isset($_POST['bio'])) {
          $stmt = $db->prepare("UPDATE users SET bio=:bio WHERE id=:uid");
          $stmt->execute([':bio' => htmlspecialchars($_POST['bio']), ':uid' => $_SESSION['user']['userid']]);
          echo "<br><p class='c-g'>Successfully changed About Me!</p>";
        }

      if (isset($_POST['changeUsername'])) {
        $newUsername = $_POST['newUsername'];
        $newUsername = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $newUsername);
        try {
          $res = $db->exec(
            "CREATE TABLE IF NOT EXISTS users (
              id INTEGER PRIMARY KEY AUTOINCREMENT, 
              username VARCHAR(255), 
              email VARCHAR(255),
              password VARCHAR(255),
              pfp VARCHAR(255)
            )"
          );

          $nm = $db->prepare("SELECT * FROM users WHERE username=:user");
          $nm->bindValue(":user", htmlspecialchars($newUsername));
          $nm->execute();
          $nam = $nm->fetchAll();
          $nm->closeCursor();
          $nmid = 0;
          foreach ($nam as $n) {
            $nmid++;
          }

          $newTag = intval($_POST['newTag']);
          $username = htmlspecialchars($newUsername);

          $tago = $db->prepare("SELECT * FROM users WHERE nameid=:nmid AND username=:usnm");
          $tago->execute([':nmid' => $newTag, ':usnm' => $username]);
          $taken = $tago->fetch();
          $tago->closeCursor();
          
          if (!empty($newUsername)) {
            if (!($nmid > 9999)) {            
              $stmt = $db->prepare("UPDATE users SET username=:newuser, nameid=:nmid WHERE id=:uid");
              $stmt->bindValue(":newuser", htmlspecialchars($newUsername));
              $stmt->bindValue(":uid", $_SESSION['user']['userid']);
              if ($_SESSION['user']['turbo'] == true)
              {
                if ($taken || $newTag == 0)
                {
                  $stmt->bindValue(":nmid", $nmid);
                }
                else
                {
                  $stmt->bindValue(":nmid", $newTag);
                }
              }
              else
              {
                if (htmlspecialchars($row['username']) == htmlspecialchars($newUsername)) {
                  $stmt->bindValue(":nmid", $tag);
                }
                else
                {
                  $stmt->bindValue(":nmid", $nmid);
                }
              }
              $stmt->execute();
              $stmt->closeCursor();
              $_SESSION['user']['username'] = htmlspecialchars($newUsername);
              echo "<script>window.location.href = \"https://alyocord.funwithalbi.xyz/chat/settings/\";</script>"; 
            } else {
              echo "<br><p class='c-r'>Too many users have this username!</p>";
            }
          } else {
            echo "<br><p class='c-r'>Username can not be empty!</p>";

            if ($_POST['newTag'] != $row['tag'])
            {
              if ($_SESSION['user']['turbo'] == true) {
                if (intval($_POST['newTag']) != '') {
                  if (strlen(strval($newTag)) < 5) {      
                    if ($taken)
                    {
                      echo "<br><p class='c-r'>Username (with tag) already taken!</p>";
                    }
                    else
                    {
                      $tagop = $db->prepare("UPDATE users SET nameid=:namid WHERE id=:uid");
                      $tagop->bindValue(":namid", $newTag);
                      $tagop->bindValue(":uid", $_SESSION['user']['userid']);
                      $tagop->execute();
                      $tagop->closeCursor();
                    }
                  } else {
                    echo "<br><p class='c-r'>Tag is too long!</p>";
                  }
                } else {
                  echo "<br><p class='c-r'>Tag can not be empty!</p>";
                }
              } else {
                echo "<br><p class='c-r'>You need Turbo to use this feature!</p>";
              }
            }
          }
        } catch (PDOException $ex) {
          echo "<br><p class='c-r'>".$ex->getMessage()."</p>";
        }
      }

      if (isset($_POST['changeEmail'])) {
        $newEmail = $_POST['newEmail'];
        $newEmail = str_replace(" ", "", $newEmail);
        try {
          $res = $db->exec(
            "CREATE TABLE IF NOT EXISTS users (
              id INTEGER PRIMARY KEY AUTOINCREMENT, 
              username VARCHAR(255), 
              email VARCHAR(255),
              password VARCHAR(255),
              pfp VARCHAR(255)
            )"
          );

          $sql = "SELECT * FROM users WHERE email = :email";
          $statement = $db->prepare($sql);
          $statement->bindValue(":email", htmlspecialchars($newEmail));
          $statement->execute();
          $row = $statement->fetch();
          $statement->closeCursor();
          if (!empty($newEmail)) {
            if ($_SESSION['user']['email'] == htmlspecialchars($newEmail)) {
              echo "<br><p class='c-r'>You already have that email!</p>";
            } elseif ($row['email'] == htmlspecialchars($newEmail)) {
              echo "<br><p class='c-r'>A user already has that email!</p>";
            } else {
              $stmt = $db->prepare("UPDATE users SET email=:newmail WHERE id=:uid");
              $stmt->bindValue(":newmail", htmlspecialchars($newEmail));
              $stmt->bindValue(":uid", $_SESSION['user']['userid']);
              $stmt->execute();
              $_SESSION['user']['email'] = $newEmail;
              echo "<br><p class='c-g'>Successfully changed email</p>";
            }
          } else {
             echo "<br><p class='c-r'>Email can not be empty!</p>";
          }
        } catch (PDOException $ex) {
          echo "<br><p class='c-r'>".$ex->getMessage()."</p>";
        }
      }

      if (isset($_POST['changePassword'])) {
        $newPassword = $_POST['newPassword'];
        try {
          $res = $db->exec(
            "CREATE TABLE IF NOT EXISTS users (
              id INTEGER PRIMARY KEY AUTOINCREMENT, 
              username VARCHAR(255), 
              email VARCHAR(255),
              password VARCHAR(255),
              pfp VARCHAR(255)
            )"
        );
          $stmt = $db->prepare("UPDATE users SET password=:newpass WHERE id=:uid");
          $stmt->bindValue(":newpass", password_hash($newPassword, CRYPT_BLOWFISH));
          $stmt->bindValue(":uid", $_SESSION['user']['userid']);
          $stmt->execute();
          echo "<br><p class='c-g'>Successfully changed password!</p>";
        } catch (PDOException $ex) {
          echo "<br><p class='c-r'>".$ex->getMessage()."</p>";
        }
      }

      if (isset($_POST['setPFP'])) {
        $target_dir = "../../cdn-2/pfp/";
        $target_file = $target_dir . $_SESSION['user']['userid'] . "_" . time() . "_" . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        function compress($source, $destination, $quality=45) {
            $info = getimagesize($source);
            if ($info['mime'] == 'image/jpeg') 
                $image = imagecreatefromjpeg($source);
        
            elseif ($info['mime'] == 'image/gif') 
                $image = imagecreatefromgif($source);
        
            elseif ($info['mime'] == 'image/png') 
                $image = imagecreatefrompng($source);
        
            imagejpeg($image, $destination, $quality);
        
            return $destination;
          }
        
        if(isset($_POST["submit"])) {
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
          if($check !== false) {
            $uploadOk = 1;
          } else {
            $uploadOk = 0;
          }
        }
        
        if ($_FILES["fileToUpload"]["size"] > 2*MB) {
          echo "<br><p class='c-r'>Sorry, your file is too large.</p>";
          $uploadOk = 0;
        }
        
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
          echo "<br><p class='c-r'>Sorry, only JPG, JPEG & PNG files are allowed. You can upload gif with <a href='//alyocord.funwithalbi.xyz/chat/turbo'>Turbo</a></p>";
          $uploadOk = 0;
        }
        
        if ($uploadOk == 0) {
          echo "<br><p class='c-r'>Sorry, your file was not uploaded.</p>";
        } else {
          $res = $db->exec(
          "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT, 
            username VARCHAR(255), 
            email VARCHAR(255),
            password VARCHAR(255),
            pfp VARCHAR(255)
          )"
        );
                                
          $stmt = $db->prepare(
            "UPDATE \"users\" SET \"pfp\"=:newpfp WHERE \"id\" = :uid"
          );
          $stmt->bindValue(':newpfp', $_SESSION['user']['userid'] . "_" . time() . "_" . basename($_FILES["fileToUpload"]["name"]) );
          $stmt->bindValue(':uid', $_SESSION['user']['userid']);
          $stmt->execute();
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<br><p class='c-g'>Profile picture successfully changed.</p>";
          } else {
            echo "<br><p class='c-r'>Sorry, there was an error uploading your file.</p>";
          }
        }
      }
      $db = null;
    ?>
  </center>
      <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
  </script>
</body>
</html>