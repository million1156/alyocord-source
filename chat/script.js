var mhm = false;
var chatting = false;
var limit = 50;

function ping() {
  sound = new Audio("//alyocord.com/cdn-1/ping.mp3");
  sound.play();
}

function copyText(text) {
  navigator.clipboard.writeText(text);
}

function updateMessages(channel, scrl=false) {
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("messages").innerHTML=this.responseText+"<br>";
      if (scrl == true) {
        var objDiv = document.getElementById("messages");
        objDiv.scrollTop = objDiv.scrollHeight;
      }
    }
  }
  xmlhttp.open("GET","messages.php?limit="+limit+"&channel="+channel,true);
  xmlhttp.send();
  $("#channel-name").load(location.href+" #channel-name>*","");
}

function deleteGift(wid, code) {
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      wip = document.getElementById(wid);
      wip.remove();
    }
  }
  xmlhttp.open("GET","delete.php?gift="+code,true);
  xmlhttp.send();
}

function time() {
  return Date.now()
}

function scrollFunc() {
  if (chatting == true) {
    var objDiv = document.getElementById("messages");
    objDiv.scrollTop = objDiv.scrollHeight;
    chatting = false;
  }
}

function theme() {
  var body = document.body;
  body.classList.toggle("light-mode");
  if (body.classList.contains('light-mode') == true) {
    localStorage.setItem('theme', 'light');
  } else {
    localStorage.setItem('theme', 'dark');
  }
}

function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

var lastSent = time();
var messagesSent = 0;
function chat() {
  var mib = $("#message").val();
  var owner = $("#msg-ownr").val();
  var ownpfp = $("#msg-ownr-pfp").val();
  var msgInput = document.getElementById('message');
  if (time() - lastSent >= 4000) {
    messagesSent = 0;
    lastSent = time();
  } else {
    messagesSent++;
  }
  if (messagesSent >= 50) {
    window.location.href = "//alyocord.com/api/api.php?do=yes";
  } else if (messagesSent > 5) {
    alert("You are being ratelimited");
  } else {
    msgInput.value = '';
    limit = limit + 1;
    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: {
        message: mib,
        chat: "true"
      },  
      success: function(msg) {
        updateMessages("global");
        chatting = true;
      }
    });
    if (ownpfp == undefined) {
      ownpfp = "default.png";
    }
    if (owner == undefined) {
      owner = "Unknown";
    }
    $('#messages').append("<div id=\"0\"><img class='pfp' src='../cdn-2/pfp/"+ownpfp+"' height='50' width='50'>&nbsp "+owner+" <br> <l style='color: #747678;'>"+escapeHtml(mib)+"</l> </div> <br></strong></div>");
    var objDiv = document.getElementById("messages");
    objDiv.scrollTop = objDiv.scrollHeight;
  }
}

function filter() {
  var message = $("#message");
  var messg = document.getElementById("message");
  var msg = message.val();
  messg.value = msg.replace("aepeaeaepapepappapwawfksdjfkafk", "good person");
}

$(document).keypress(function(e) {
  if(e.which == 13) {
    chat();
  }
});

function deleteA(dl) {
  $.ajax({
    url: 'messages.php',
    type: 'POST',
    data: {
      delete: dl
    },  
    success: function(msg) {
      updateMessages("global");
    }
  });
}

function deleteB(doo, dou) {
  $.ajax({
    url: 'messages.php',
    type: 'POST',
    data: {
      delown: doo,
      delownuid: dou
    },  
    success: function(msg) {
      updateMessages("global");
      console.log("sex2");
    }
  });
}

$("document").ready(function() {
  if (mhm != false)
  {
    setInterval(function() {
      updateMessages("global");
      scrollFunc();
    }, 5000)
  
    $('#messages').on('scroll', function() {
      if($("#messages").scrollTop() == 0) {
        limit = limit + 50;
        $("#messages").prepend("<center><h3>Loading messages...</h3></center>");
        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: {
            loadmsg: limit,
          },  
          success: function(msg) {
            updateMessages("global");
          }
        });
      }
    });
  }
});