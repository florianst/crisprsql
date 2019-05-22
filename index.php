<?php
include "inc/header.php";
?>
  <div class="row">
    <div class="col-sm-4">
      <img src="4un3_small.jpg" style="height:270px"/>
      <p>Sp-Cas9 protein bound to a dsDNA (blue) guided by a sgRNA (gold).</p>
      <h3>About crisprSQL</h3>
      <p>crisprSQL is a SQL-based online database for CRISPR/Cas9 off-target assays.</p>
      <hr class="d-sm-none">
    </div>
    <div class="col-sm-8">
      <h5>Search database</h5>
      <div class="container">
        <form action="search.php">
          <div class="form-group">
            <input type="text" class="form-control" id="sgrna" placeholder="search guide sequence" name="sgrna">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="target" placeholder="search target sequence" name="target">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id="region" placeholder="search region (chr1:15000-55000)" name="region">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
      <br>
      <h5>Contribute to database</h5>
      <p>crisprSQL invites submissions of Sp-Cas9 off-target indel frequency results, in order to be included in the online database and the benchmark dataset. Please click <a href="submit.php">here</a>.</p>
    </div>
  </div>


<?php
include "inc/footer.php";
?>
