<?php 
include 'inc/db.php';

$navpages = array("index.php"=>"Home", "studies.php"=>"Studies", "search.php"=>"Browse", "epigen.php"=>"Epigenetics", "download.php"=>"Download", "submit.php"=>"Submit", "contact.php"=>"Contact");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>crisprSQL - CRISPR off-target database</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/design.css">
  <script src="/inc/jquery-3.4.1.min.js"></script>
  <script src="/inc/popper.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0; background-color:#c1c1c1; background-image:url('4un3_banner.jpg'); background-repeat:no-repeat; color:white;">
  <h1>crisprSQL</h1>
  <h5>Database for CRISPR/Sp-Cas9 off-target assays</h5> 
</div>
<nav class="bg-primary navbar-dark" style="height:4em;"></nav>

<div class="container">
<nav class="navbar navbar-left navbar-expand-sm bg-primary navbar-dark" style="margin-top:-4em; min-height:4em; margin-bottom: 15px;">
  <a class="navbar-brand" href="index.php">crisprSQL</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
    <?php
    foreach ($navpages as $path => $title) {
        $scriptpath = basename($_SERVER['PHP_SELF']);
        if ($path == $scriptpath) { $active = ' active'; } else { $active = ''; }
        echo '<li class="nav-item'.$active.'">
                 <a class="nav-link" href="'.$path.'">'.$title.'</a>
              </li>';
    }
    ?>
    </ul>
  </div>  
</nav>