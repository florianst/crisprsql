<?php 
include 'inc/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>crisprSQL - CRISPR off-target database</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <script src="/inc/jquery-3.4.1.min.js"></script>
  <script src="/inc/popper.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0; background-color:#c1c1c1; background-image:url('4un3_banner.jpg'); background-repeat:no-repeat; color:white;">
  <h1>crisprSQL</h1>
  <p>Database for CRISPR/Sp-Cas9 off-target assays</p> 
</div>
<nav class="bg-primary navbar-dark" style="height:4em;"></nav>

<div class="container">
<nav class="navbar navbar-left navbar-expand-sm bg-primary navbar-dark" style="margin-top:-4em; min-height:4em; margin-bottom: 15px;">
  <a class="navbar-brand" href="index.php">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="search.php">Targets</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="epigen.php">Epigenetics</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="submit.php">Submit</a>
      </li>       
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact</a>
      </li>    
    </ul>
  </div>  
</nav>