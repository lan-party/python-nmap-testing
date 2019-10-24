<?php
    $connection = new mysqli('localhost', 'root', '');

    if (isset($_COOKIE['pyscannerkey'])) {
        $user = $connection->query("SELECT * FROM pyscanner.users WHERE loginkey = '".$connection->real_escape_string($_COOKIE['pyscannerkey'])."'")->fetch_row();

        if (isset($_POST['name'])) {
            $newpass = "";
            if ($_POST['password'] != '') {
                $newpass = ", password = PASSWORD('".$connection->real_escape_string($_POST['password'])."') ";
            }
            $connection->query("UPDATE pyscanner.users SET name = '".$connection->real_escape_string($_POST['name'])."', email = '".$connection->real_escape_string($_POST['email'])."'".$newpass." WHERE loginkey = '".$connection->real_escape_string($_COOKIE['pyscannerkey'])."'");
            header('Location: /profile.php');
        } ?>
<html>
<head>
    <title>IPv4 Search - Profile</title>
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
  <?php
    $page = "profile";
        include "navbar.php"; ?>


  <div class="container">
    <div style="margin:0 auto; position; width: 75%; padding-top: 20px;">

  <div class="card">
    <div class="card-header">
      <div class="row">
        <h4 class="col-lg-12">Profile Info</h4>
      </div>
    </div>
    <div class="card-body">
          <form method="post" action="/profile.php">
          <div class="row">
            <div class="col-lg-4">
              <label style="float:left;">Name</label>
              <input type="text" name="name" class="form-control" value="<?= $user[9] ?>" onchange="$('#updatebtn').attr('disabled', false);" />
            </div>
              <div class="col-lg-4">
                <label style="float:left;">Email</label>
                <input type="text" name="email" class="form-control" value="<?= $user[1] ?>" onchange="$('#updatebtn').attr('disabled', false);" />
              </div>
              <div class="col-lg-4">
                <label style="float:left;">Password</label>
                <input type="password" name="password" class="form-control" onchange="$('#updatebtn').attr('disabled', false);"/>
              </div>

              </div>
              <div class="row">
                <div class="col-lg-12 text-center">
                <button type="submit" id="updatebtn" class="btn btn-primary" disabled>Update</button>
              </div>
            </div>
            </form>
    </div>
  </div>

  </div>
  </div>

</body>
</html>
<?php

    } ?>
