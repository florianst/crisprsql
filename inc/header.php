<?php 
include 'inc/db.php';
include 'inc/functions.php';

$navpages = array("index.php"=>["Home", "fa-home"], "studies.php"=>["Studies", "fa-book"], "search.php"=>["Browse", "fa-search"], "epigen.php"=>["Epigenetics", "fa-dna"], 
                  "download.php"=>["Download", "fa-download"], "submit.php"=>["Submit", "fa-upload"], "contact.php"=>["Contact", "fa-envelope"]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>crisprSQL - CRISPR off-target database</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/design.css">
  <link rel="stylesheet" href="/css/bootstrap-sortable.css">
  <script src="/js/jquery-3.4.1.min.js"></script>
  <script src="/js/popper.min.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/bootstrap-sortable.js"></script>
  <script src="/js/moment.min.js"></script>
  <link rel="stylesheet" href="/css/fontawesome/all.css">
  <!-- Matomo -->
    <script type="text/javascript">
      var _paq = window._paq || [];
      /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//analytics.crisprsql.bplaced.net/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '1']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
  <!-- End Matomo Code -->
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
    foreach ($navpages as $path => $titleicon) {
        $title = $titleicon[0];
        $icon  = $titleicon[1];
        $scriptpath = basename($_SERVER['PHP_SELF']);
        if ($path == $scriptpath) { $active = ' active'; } else { $active = ''; }
        echo '<li class="nav-item'.$active.'">
                 <a class="nav-link" href="'.$path.'"><i class="fa fa-fw '.$icon.'"></i>'.$title.'</a>
              </li>';
    }
    ?>
    </ul>
  </div>  
</nav>
