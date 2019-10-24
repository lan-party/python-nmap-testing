<html>
<head>
    <title>IPv4 Search</title>
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
  <?php if (isset($_COOKIE['pyscannerkey'])) {
    $connection = new mysqli('localhost', 'root', '');
    $user = $connection->query("SELECT * FROM pyscanner.users WHERE loginkey = '".$connection->real_escape_string($_COOKIE['pyscannerkey'])."' AND verified = 1")->fetch_row();

    if (!is_null($user)) {
        $page = "index";
        include "navbar.php"; ?>

  <div class="container">
    <div style="margin:0 auto; position; width: 75%; padding-top: 20px;">
        <div id="searchform">
        <div id="portlist" class="form-group">

          <div class="row ">
          <div class="col-lg-1">
            </br>
          </div>
            <div class="col-lg-2">
              <label >Port</label>
            </div>
            <div class="col-lg-2 ">
              <label>Service</label>
            </div>
            <div class="col-lg-2 ">
              <label>Status</label>
            </div>
            <div class="col-lg-5 ">
              <label>Response Content</label>
            </div>
            <div class="col-lg-1">
              </br>
            </div>
          </div>
          <div class="row " id="port0">


            <div class="col-lg-2 form-group">
              <input type="number" class="form-control" onclick="$(this).val('');" name="port[0]" value="80" />
            </div>
            <div class="col-lg-2 form-group">
              <select name="service[0]" class="form-control">
                <option value="any">Any</option>
                <option value="http">HTTP</option>
                <option value="https">HTTPS</option>
                <option value="telnet">TELNET</option>
                <option value="ssh">SSH</option>
                <option value="ftp">FTP</option>
                <option value="sftp">SFTP</option>
                <option value="smtp">SMTP</option>
                <option value="imap">IMAP</option>
              </select>
            </div>
            <div class="col-lg-2 form-group">
              <select name="status[0]" class="form-control">
                  <option value="open">Open</option>
                  <option value="closed">Closed</option>
                  <option value="filtered">Filtered</option>
              </select>
            </div>
            <div class="col-lg-5 form-group">
              <input type="text" class="form-control" name="resp[0]" />
            </div>
            <div class="col-lg-1">
            <button type="button" class="btn btn-danger" onclick="removeport(0);"><b> - </b></button>
            </div>
        </div>
        </div>
        <div class="row">
          <div class="col-lg-1 col-xs-12">
            <button type="button" id="addport" class="btn btn-primary" ><b>+</b></button>
        </div>
        </div>
<hr>
      <div class="row">
        <div class="col-lg-3 form-group">
          <label>Host</label>
          <input name="host" type="text" class="form-control" />
        </div>
          <div class="col-lg-5 form-group">
            <label>ISP (asn_description)</label>
            <input name="isp" type="text" class="form-control" />
          </div>
          <div class="col-lg-2 form-group">
            <label>Country</label>
            <input name="country" type="text" class="form-control" maxlength="2" />
          </div>
          <div class="col-lg-2">
            <label class="invisible">Submit</label>
                  <input type="button" id="searchbtn" class="btn btn-success" value="Search" />
            </div>
      </div>

  </div>
<div class="card" style="display: none;" id="resultscard">
  <div class="card-header">
    <div class="row">
      (<span id="resultscount"></span>) results found.
    </div>
  </div>
  <div class="card-body" id="searchresults">
  </div>
</div>



  </div>
      <div style="position: fixed; bottom: 0; right: 0; font-size: 14px;">
          <a href="mailto:blueprincesec@gmail.com">blueprincesec@gmail.com</a>
      </div>
  </div>
<script>

<?php if ($user[11] < 1) {
            $connection->query("UPDATE pyscanner.users SET onboard = onboard+1 WHERE loginkey = '".$connection->real_escape_string($_COOKIE['pyscannerkey'])."' AND verified = 1"); ?>
$(document).ready(function() {
	intromsg();
});
<?php

        } ?>

var portcounter = 1;
	$("#addport").click( function(){
		$(".addport").remove();

		$("#portlist").append('<div class="row" id="port'+portcounter+'">'+
      '<div class="col-lg-2 form-group">'+
        '<input type="number" class="form-control" name="port['+portcounter+']" /></div><div class="col-lg-2 form-group">'+
        '<select name="service['+portcounter+']" class="form-control">'+
            '<option value="any">Any</option>'+
            '<option value="http">HTTP</option>'+
            '<option value="https">HTTPS</option>'+
            '<option value="telnet">TELNET</option>'+
            '<option value="ssh">SSH</option>'+
            '<option value="ftp">FTP</option>'+
            '<option value="sftp">SFTP</option>'+
            '<option value="smtp">SMTP</option>'+
            '<option value="imap">IMAP</option>'+
        '</select>'+
        '</div><div class="col-lg-2 form-group">'+
        '<select name="status['+portcounter+']" class="form-control">'+
            '<option value="open">Open</option>'+
            '<option value="closed">Closed</option>'+
            '<option value="filtered">Filtered</option>'+
        '</select></div>'+
        '<div class="col-lg-5 form-group">'+
        '<input type="text" class="form-control" name="resp['+portcounter+']" />'+
        '</div>'+
        '<div class="col-lg-1">'+
        '<button type="button" class="removeport btn btn-danger" onclick="removeport('+portcounter+');"><b> - </b></button>'+
      '</div>'+
        '</div>');

	portcounter += 1;
	});

  function removeport(portno){
    $('#port'+portno).remove();
  }

  $('#searchbtn').on('click', function () {
    search();
    });
  $('#searchform').on('keypress',function(e) {
    if(e.which == 13) {
      search();
    }
  });

    function search(){
        $('#resultscard').attr('stlye', "display: none;");

        var port = [];
        $('[name^="port"]').each(function(){
          port.push($(this).val());
        });
        var service = [];
        $('[name^="service"]').each(function(){
          service.push($(this).val());
        });
        var status = [];
        $('[name^="status"]').each(function(){
          status.push($(this).val());
        });
        var resp = [];
        $('[name^="resp"]').each(function(){
          resp.push($(this).val());
        });

        $.ajax({
          url: '/search.php',
          type: 'POST',
          dataType: 'json',
          data: {
            'port': port,
            'service': service,
            'status': status,
            'resp': resp,
            'host': $('[name^="host"]').val(),
            'isp': $('[name^="isp"]').val(),
            'country': $('[name^="country"]').val()
          },
          success: function(data){
            $('#resultscount').html(data['count']);
            $('#searchresults').html(data['html']);
            $('#resultscard').removeAttr("style");
          }
        })
    }
    function showHost(id){

      $.ajax({
        url: '/services.php',
        type: 'GET',
        data: {
          'id': id
        },
        success: function(data){
          console.log(data);
        Swal.fire({
          html: data
        });
        }
      });
    }


</script>
<?php

    } else {
        header('Location:/login.php');
    }
} else {
    ?>
    <div class="container">
      <div style="margin:0 auto; position; width: 75%; padding-top: 20px; text-align: center;">
<a href="/login.php">
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Glider.svg/600px-Glider.svg.png" style="width: 50px;" />
</a>
</div></div>
  <?php

} ?>
</body>
</html>
