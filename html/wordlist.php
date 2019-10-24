<?php
$connection = new mysqli('localhost', 'root', '');
if (isset($_GET['text'])) {
    $dorks = $connection->query("SELECT * FROM pyscanner.wordlist WHERE verified = 1");
    if (isset($dorks) && count($dorks) > 0) {
        header("Content-Type: text/plain");
        foreach ($dorks as $dork) {
            echo $dork['dork'];
            echo "\n";
        }
    }
} else {
    ?>
<html>
<head>
    <title>IPv4 Search - Wordlist</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script
    			  src="https://code.jquery.com/jquery-3.4.1.min.js"
    			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    			  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
    <div style="margin:0 auto; position; width: 85%; padding-top: 20px;">
      <div class="card" style="margin-bottom: 10px;">
        <div class="card-header">
          <div class="row ">
            Query String Suggestion
          </div>
        </div>
        <div class="card-body">
          <form action="/wordlist.php" method="post">
            <div class="row form-group">
              <div class="col-lg-10">
                <input type="text" class="form-control" name="suggestion" />
              </div>
              <div class="col-lg-2">
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </div>
          </form>
        </div>
        <?php if (isset($_POST['suggestion'])) {
        if ($connection->query("INSERT INTO pyscanner.wordlist (dork) VALUES ('".$connection->real_escape_string($_POST['suggestion'])."');")) {
            ?>
            <div class="card-footer" style="background-color: #009900;">
                <div class="row">
                  <b style="color: #FFFFFF;">Submission successful!</b>
                </div>
              </div>
        <?php

        }
    } ?>

      </div>
    <div class="card">
      <div class="card-header">
         <div class="row"></div>
      </div>
        <div class="card-body">
          <div class="row">
           <?php
             $dorks = $connection->query("SELECT * FROM pyscanner.wordlist WHERE verified = 1");
    if (isset($dorks) && count($dorks) > 0) {
        foreach ($dorks as $dork) {
            echo '<div class="col-lg-4">'.$dork['dork'].'</div>';
        }
    } ?>
         </div>
      </div>
    </div>
  </div>
  </div>
</body>
</html>
<?php 
} ?>
