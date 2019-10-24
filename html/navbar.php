<nav class="navbar navbar-expand-lg navbar-dark bg-secondary static-top">
  <div class="container">
    <a class="navbar-brand" href="/">Edgy Deepweb Search</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?= ($page == 'index' ? 'active' : '') ?>">
          <a class="nav-link" href="/">Search
          </a>
        </li>
        <li class="nav-item <?= ($page == 'about' ? 'active' : '') ?>">
          <a class="nav-link" href="/about.php">About</a>
        </li>
        <li class="nav-item <?= ($page == 'scan' ? 'active' : '') ?>" style="display: none;">
          <a class="nav-link" href="#" disabled>Scan</a>
        </li>
        <li class="nav-item <?= ($page == 'profile' ? 'active' : '') ?>">

          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><?= (isset($user[9]) ? $user[9] : $user[1]) ?></a>
          <div class="dropdown-menu-right dropdown-menu">
              <a href="/profile.php" class="dropdown-item">Profile Settings</a>
              <a href="#" class="dropdown-item">Saved Hosts</a>
              <a href="#" onclick="invitelink('<?= $user[8] ?>');return false;" class="dropdown-item">Invite Link (<?= $user[7] ?>)
              <a href="/logout.php" class="dropdown-item">Sign Out</a>
          </div>
        </li>
        <li class="nav-item" style="margin-right: -20px;">
          <span class="nav-link" style="cursor: pointer; font-size: 14px;" onclick="intromsg();">?</span>
      </li>
      </ul>
    </div>
  </div>
</nav>
<script>
function invitelink(key){
  Swal.fire({
    html: "<input typ='text' class='form-control' style='width: 100%;' value='http://<?= $_SERVER['HTTP_HOST'] ?>/login.php?invitekey="+key+"' onclick='this.select()'/>"
  });
}
function intromsg(){
  Swal.fire({
          html: '<div style="text-align: left;"><p>Hello. Welcome to an early version of my ip address search engine.</br>For more information on how it works see the <i>About</i> section in the top navigation bar.</br>Click the green <i>Search</i> button with the default query to see some of the login pages/routers/ip camers most recently discovered. </br></br>This domain (<?= $_SERVER['HTTP_HOST'] ?>) is only temporary. I need help naming this service and would appreciate any suggestions on a better domain name! <a href="mailto:blueprincesec@gmail.com">blueprincesec@gmail.com</a></p></div>'
        });
}
</script>
