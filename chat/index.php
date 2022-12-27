<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
  session_destroy();
  echo "<script>window.location.href = \"//alyocord.funwithalbi.xyz/login/\"</script>";
}

if (isset($_POST['logout'])) {
  session_destroy();
  echo "<script>window.location.href = \"//alyocord.funwithalbi.xyz/login/\"</script>";
} 

$db = new PDO('sqlite:../database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="//alyocord.funwithalbi.xyz/chat/style.css?version=<?php echo time(); ?>">
  <link rel='icon' type='image/x-icon' href='//alyocord.funwithalbi.xyz/cdn-1/favicon.ico'>
  <script src='//alyocord.funwithalbi.xyz/jquery-3.6.1.js'></script>
  <script src='//alyocord.funwithalbi.xyz/chat/script.js?version=<?php echo time(); ?>'></script>
  <title>Alyocord</title>
</head>
<body>

  <div id='alert' style='display:none;'><nav style='background:#0f0645;'><center><button style='border-style:solid;border-color:white;background:none;border-radius:5%;color:white;' onclick='window.location.href = "//alyocord.funwithalbi.xyz/chat/turbo/claim/";'>Claim Turbo </button>&nbsp &nbsp &nbsp<button onclick='$("#alert").empty();' style='background:none;border:none;color:red;'>X</button></center></nav></div>

  <div class='dms'>
    
  </div>
  <?php
    if (isset($_SESSION['loggedin'])) {
      $uslm = $db->prepare("SELECT * FROM users WHERE id=:uid");
      $uslm->bindValue(":uid", $_SESSION['user']['userid']);
      $uslm->execute();
      $usr = $uslm->fetch();
      $uslm->closeCursor();
      $lastTurbo = null;
      $pfp = $usr['pfp'];
      $_SESSION['user']['pfp'] = $usr['pfp'];
      $_SESSION['user']['turbolast'] = $usr['turbolast'];
      $lastTurbo = $usr['turbolast'];
      if ($lastTurbo == 0 || time() - $lastTurbo >= 2592000) {
        if (intval($usr['turbocredit']) > 0)
        {
          $uslt = $db->prepare("UPDATE users SET turbolast=:lt, turbocredit=:tc WHERE id=:uid");
          $uslt->bindValue(":uid", $_SESSION['user']['userid']);
          $uslt->bindValue(":tc", intval($usr['turbocredit'])-1);
          $uslt->bindValue(":lt", time());
          $uslt->execute();
          $uslt->closeCursor();
          $_SESSION['user']['turbo'] = true;
        }
        else
        {
          $_SESSION['user']['turbo'] = false;
        }
      } else {
        $_SESSION['user']['turbo'] = true;
      }
  
      if ($_SESSION['user']['userid'] == null) {
        session_destroy();
        echo "<script>window.location.href = '//alyocord.funwithalbi.xyz/login/';</script>";
      }
    }

    if (isset($_SESSION['admin']) && $_SESSION['user']['turbo'] == false) {
      echo "<script>$(\"#alert\").css('display', 'block')</script>";
    }
  ?>
  <div id='channel-name'>
    <h4 class='gc-text'><b>Global Chat</b> <l style='color:#636060;'>(messages: <?php 
    $res = $db->exec(
                "CREATE TABLE IF NOT EXISTS messages (
                  id INTEGER PRIMARY KEY AUTOINCREMENT,
                  content VARCHAR(4000),
                  userid INT,
                  room VARCHAR(50),
                  time INTEGER
                )"
              );
  
    if (isset($_POST['chat'])) {
        $message = $_POST['message'];
        $message = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $message);
      
        $message = str_replace("<:skull>", '💀', $message);
        $message = str_replace("<:party>", '🥳', $message);
        $message = str_replace("<:fire>", '🔥', $message);
        $message = str_replace("<:gift>", '🎁', $message);
        $message = str_replace("<:scream>", '😱', $message);
        $message = str_replace("<:star>", '✨', $message);
        $message = str_replace("<:sob>", '😭', $message);
        $message = str_replace("<:face_with_money_tongue>", '🤑', $message);
        $message = str_replace("<:blush>", '😳', $message);
      
        if (preg_replace('/\s+/', '', $message) != '') {
          if ($_SESSION['user']['turbo'] == false) {
            if (!(mb_strlen($message) > 2000)) {
              try {
                if ($_SESSION['user']['userid'] == null) {
                  session_destroy();
                  echo "<script>window.location.href = '//alyocord.funwithalbi.xyz/login/';</script>";
                }
                
                $stmt = $db->prepare("INSERT INTO messages (userid, content, room, time) VALUES(:userid, :content, :room, :time)");
                $stmt->bindValue(":userid", $_SESSION['user']['userid']);
                $message = preg_replace('#\*{2}(.*?)\*{2}#', '<strong>$1</strong>', htmlspecialchars($message));
                
                $message = preg_replace('#\*(.*?)\*#', '<i>$1</i>', htmlspecialchars($message));
                $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
                $message = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $message);
                $stmt->bindValue(":content", $message);
                $stmt->bindValue(":room", "global");
                $stmt->bindValue(":time", time());
                $stmt->execute();
                $stmt->closeCursor();
              } catch (PDOException $ex) {
                echo "<p class='c-r'>".$ex->getMessage()."</p>";
              }
          } else {
            echo "<script>alert(\"You can't send messages longer than 2000 characters!\");</script>";
          }
        } else {
            if (!(mb_strlen($message) > 4000)) {
              try {
                if ($_SESSION['user']['userid'] == null) {
                  session_destroy();
                  echo "<script>window.location.href = '//alyocord.funwithalbi.xyz/login/';</script>";
                }
                
                $stmt = $db->prepare("INSERT INTO messages (userid, content, room, time) VALUES(:userid, :content, :room, :time)");
                $stmt->bindValue(":userid", $_SESSION['user']['userid']);
                $message = preg_replace('#\*{2}(.*?)\*{2}#', '<strong>$1</strong>', htmlspecialchars($message));
                $message = preg_replace('#\*(.*?)\*#', '<i>$1</i>', $message);
                $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i'; 
                $message = preg_replace($url, '<a href="$0" target="_blank" title="$0">$0</a>', $message);
                $message = str_replace(htmlspecialchars("<:susface>"), "<img src='//cdn.discordapp.com/emojis/1030796433508540506.webp?size=44&amp;quality=lossless' alt='<:susface>'", $message);
                $stmt->bindValue(":content", $message);
                $stmt->bindValue(":room", "global");
                $stmt->bindValue(":time", time());
                $stmt->execute();
                $stmt->closeCursor();
              } catch (PDOException $ex) {
                echo "<p class='c-r'>".$ex->getMessage()."</p>";
              }
          } else {
            echo "<script>alert(\"You can't send messages longer than 4000 characters!\");</script>";
          }
        }
      }
    }
  
    $sql = "SELECT count(*) FROM messages"; 
    $result = $db->prepare($sql); 
    $result->execute(); 
    $rows = $result->fetchColumn(); 
    $result->closeCursor();
    echo $rows;
    $db = null;
    ?>)</l></h4></div>
  <div id='messages'><br></div>
  <input type='hidden' id='msg-ownr' value='<?php echo $_SESSION['user']['username']; ?>'>
  <input type='hidden' id='msg-ownr-pfp' value='<?php echo $_SESSION['user']['pfp']; ?>'>

  <script>
    if (localStorage.getItem('theme') == 'light') {
      theme();
    }
    updateMessages("global", true);
    mhm = true;
  </script>
  
  <input type='text' name='message' class='message-content' placeholder = 'Message #global' onkeyup="filter()" id='message' autocomplete="off">
  <button onclick="chat()" class='button send-message' id='chat' style='display: none;'>Send</button>

  <form method='post'>
    <button type='submit' style='background:none;border:none;' name='logout'><img src='//cdn0.iconfinder.com/data/icons/thin-line-color-2/21/05_1-512.png' height='25' width='25' id='logout'></button>
  </form>

  <button style='background:transparent;border:none;' onclick='theme()' id='theme-trigger'><img src='//cdn1.iconfinder.com/data/icons/user-interface-16x16-vol-1/16/contrast-circle-512.png' height='20' width='20'></button>

  <button style='background:transparent;border:none;' onclick="location.href = '//alyocord.funwithalbi.xyz/chat/settings/';"><img src='//media.discordapp.net/attachments/1027560810962235392/1029375797825380362/unknown.png' height='25' width='25' id='settings'></button>

  <button style='background:transparent;border:none;' onclick="location.href = '//alyocord.funwithalbi.xyz/chat/turbo/';"><img src='//media.discordapp.net/attachments/1028244276590686218/1030940228514480228/turbo.png' height='25' width='25' id='turbo-man'></button>
</body>
</html>