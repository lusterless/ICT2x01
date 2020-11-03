<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"> Singapore Institute of Technology</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Dashboard</a></li>
      <!--<li><a href="#">Dashboard</a></li>
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>-->
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Profile</a>
          <ul class="dropdown-menu">
            <li><a data-target="#profileModal" data-toggle="modal" href="#profileModal">View Profile</a></li>
          </ul> 
        </li>
      <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<!--Modal-->
<div id="profileModal" class="modal fade" class="modal" style="width:600px; height: 600px; margin: auto;">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close" data-dismiss="modal">&times;</span>
      <h2>Profile <span class="glyphicon glyphicon-user"></span> </h2>
    </div>
    <div class="modal-body">
      <?php
        echo "<p> Name: ".$Details->getName()."</p>";
        echo "<p> Identity No.: ".$Details->getUser()."</p>";
        echo "<p> Phone No.: ".$Details->getTel()."</p>";
        echo "<p> ACC Type: ".$Details->getRole()."</p>";
        echo "<p> Email: ".$Details->getEmail()."</p>";
       // echo "<p>".$Details->getStudentInfo()->get_mod()."</p>";
      ?>
    </div>
    <div class="modal-footer" style="text-align: center;">
      <p>© 2013 - Singapore Institute of Technology</p>
    </div>
  </div>
</div>