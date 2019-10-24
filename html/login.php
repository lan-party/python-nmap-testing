<?php
    $connection = new mysqli('localhost', 'root', '');
    require "PHPMailer/src/PHPMailer.php";
    require "PHPMailer/src/SMTP.php";
    require "PHPMailer/src/Exception.php";


    if (isset($_POST['email'])) {
        $user = $connection->query("SELECT * FROM pyscanner.users WHERE email = '".$connection->real_escape_string($_POST['email'])."' AND password = PASSWORD('".$connection->real_escape_string($_POST['pass'])."')");

        if (mysqli_num_rows($user) == 1) {
            $user = $user->fetch_row();
            if ($user[10]) {
                $newkey = md5(rand(1, 10000000000));
                setcookie('pyscannerkey', $newkey);
                $connection->query("UPDATE pyscanner.users SET loginkey = '".$newkey."', loginkey_expiration = DATE(DATE_ADD(NOW(), INTERVAL +1 DAY)) WHERE id = ".$user[0]);
                header("Location: /");
            }
        } else {
            if (isset($_POST['invitekey'])) {
                $referrer = $connection->query("SELECT id FROM pyscanner.users WHERE invitekey = '".$connection->real_escape_string($_POST['invitekey'])."' AND invites > 0");
                if (mysqli_num_rows($referrer) == 1) {
                    $loginkey = md5(rand(1, 10000000000));
                    $token = md5(rand(1, 10000000000).date("dmYHis")).date("dmYHis");
                    $invitekey = md5(rand(1, 10000000000).$_POST['name'].$_POST['email']);
                    $connection->query("INSERT IGNORE INTO pyscanner.users (email, password, loginkey, api_token, invitekey, name) VALUES ('".$connection->real_escape_string($_POST['email'])."', PASSWORD('".$connection->real_escape_string($_POST['passconf'])."'), '".$loginkey."', '".$token."', '".$invitekey."', '".$connection->real_escape_string($_POST['name'])."')");
                    $connection->query("UPDATE pyscanner.users SET invites = (invites-1) WHERE id = ".$referrer->fetch_row()[0]);


                    $mail = new PHPMailer\PHPMailer\PHPMailer();
                    $mail->IsSMTP(); // enable SMTP

                    $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
                    $mail->SMTPAuth = true; // authentication enabled
                    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
                    $mail->Host = "smtp.gmail.com";
                    $mail->Port = 465; // or 587
                    $mail->IsHTML(true);
                    $mail->Username = "blueprincesec@gmail.com";
                    $mail->Password = "gurlyctggqszqcpq";
                    $mail->SetFrom("blueprincesec@gmail.com");
                    $mail->Subject = "Account Verification";
                    $mail->Body = "http://".$_SERVER['HTTP_HOST']."/login.php?verification=".$invitekey;
                    $mail->AddAddress($_POST['email']);

                    if (!$mail->Send()) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    } else {
                        echo "You should receive an email from blueprincesec@gmail.com with the subject 'Account Verification'. Follow the link in the message to complete account setup.";exit;
                    }
                }
            }
        }
    }

    if (isset($_GET['verification'])) {
        $user = $connection->query("SELECT id FROM pyscanner.users WHERE invitekey = '".$connection->real_escape_string($_GET['verification'])."'");
        if (mysqli_num_rows($user) == 1) {
            $newkey = md5(rand(1, 10000000000));
            setcookie('pyscannerkey', $newkey);
            $connection->query("UPDATE pyscanner.users SET loginkey = '".$newkey."', loginkey_expiration = DATE(DATE_ADD(NOW(), INTERVAL +1 DAY)), verified = 1 WHERE id = ".$user->fetch_row()[0]);
            header("Location: /");
        }
    }
 ?>
<html>
<head>
    <title>Login</title>
    <meta name="referrer" content="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script
    			  src="https://code.jquery.com/jquery-3.4.1.min.js"
    			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    			  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<style>

  .bgstill {
    background-color: #F0F0F0;
  }
  .bganime {
      animation: pulse 60s infinite;
    }

    @keyframes pulse {
      0% {
        background-color: #F0F0F0;
      }
      50% {
        background-color: #EAEAEA;
      }
      100% {
        background-color: #F0F0F0;
      }
    }
</style>
</head>
<body class="bganime">
  <script>
    $('body').mousemove(function(){

      $('body').removeClass('bganime');
      $('body').addClass('bgstill');

      setTimeout(function() {
        $('body').removeClass('bgstill');
        $('body').addClass('bganime');
    }, 2000);
    });
  </script>
  <div class="container">
    <div style="margin:0 auto; position; width: 75%; padding-top: 20px;">
      <?php if (isset($_GET['invitekey'])) {
     ?>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <h4 class="col-lg-12">Create an account</h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
          <p>Welcome to the private ip search engine for script kiddies and internet weirdos! If you're seeing this message it's probably because you were sent an invitation link by one of those two types of people. Because it's private you'll have to create an account to access the search portal and other things. Do that below.</p>
        </div>
      </div>
      <div class="row text-center">
        <div class="col-lg-6" style="margin: 0 auto;">
          <form method="post" action="/login.php">

          <div class="row">
            <div class="col-lg-12">
              <label style="float:left;">Name</label>
              <input type="text" name="name" class="form-control"/>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <label style="float:left;">Email</label>
              <input type="text" name="email" class="form-control" />
            </div>
          </div>
            <div class="row">
              <div class="col-lg-12">
                <label style="float:left;">Password</label>
                <input type="password" name="pass" class="form-control" />
              </div>
            </div>
              <div class="row">
                <div class="col-lg-12">
                  <label style="float:left;">Confirm Password</label>
                  <input type="password" name="passconf" class="form-control" />
                </div>
              </div>
              <input type="hidden" name="invitekey" value="<?= $_GET['invitekey'] ?>" />
              <div class="row">
                <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Sign Up</button>
              </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
      <?php

 } else {
     ?>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <h4 class="col-lg-12">Login</h4>
      </div>
    </div>
    <div class="card-body">
      <div class="row text-center">
        <div class="col-lg-6" style="margin: 0 auto;">
          <form method="post" action="/login.php">
          <div class="row">
            <div class="col-lg-12">
              <label style="float:left;">Email</label>
              <input type="text" name="email" class="form-control" />
            </div>
          </div>
            <div class="row">
              <div class="col-lg-12">
                <label style="float:left;">Password</label>
                <input type="password" name="pass" class="form-control" />
              </div>
            </div>
              <div class="row">
                <div class="col-lg-12">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
      <?php

 } ?>

  </div>
  </div>
</body>
</html>
