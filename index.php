<?php
include "inc/header.php";
?>
  <div class="row">
    <div class="col-sm-4">
      <img src="4un3_small.jpg" style="height:270px"/>
      <p style="font-size:85%">Sp-Cas9 protein bound to a dsDNA (blue) guided by an sgRNA (gold).</p>
      <h3>About crisprSQL</h3>
      <p>crisprSQL is a SQL-based database for CRISPR/Cas9 off-target assays. We provide a benchmark data set for algorithms which predict the cleavage efficiency of gRNA/off-target pairs.</p>
      <hr class="d-sm-none">
    </div>
    <div class="col-sm-8">
      <div class="alert alert-primary" role="alert">
        The database is still in alpha mode.
      </div>
      <h5>Search database</h5>
      <div class="container">
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="sgrna" placeholder="search guide sequence" name="guide">
            <div class="input-group-append">
    			<button class="btn btn-primary" type="submit" name="submit_rna">Search</button>
  			</div>
  		  </div>
  		</form><br>
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="target" placeholder="search target sequence" name="target">
            <div class="input-group-append">
    			<button class="btn btn-primary" type="submit" name="submit_target">Search</button>
  			</div>
  		  </div>  			
  		</form><br>
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="region" placeholder="search target region (chr1:15000-55000)" name="targetregion">
            <div class="input-group-append">
    			<button class="btn btn-primary" type="submit" name="submit_region">Search</button>
  			</div>
  		  </div>
  		</form>
      </div>
      <br>
      <h5>Database statistics</h5>
      <?php 
      // show studies involved, number of guides, number of targets, number of targets with at least 1 epigenetic marker
      $result  = $conn->query("SELECT DISTINCT experiment_id FROM cleavage_data");
      $result2 = $conn->query("SELECT id FROM cleavage_data");
      $result3 = $conn->query("SELECT id FROM cleavage_data WHERE epigenetics_ids != ''");
      echo "<p>crisprSQL contains ".$result->num_rows." <a href='studies.php'>studies</a>, ".$result2->num_rows." total <a href='search.php'>targets</a>, ".$result3->num_rows." of which have at least one <a href='epigen.php'>epigenetic marker</a>.</p>";
      ?>
      <br>
      <h5>Contribute to database</h5>
      <p>crisprSQL invites submissions of Sp-Cas9 off-target indel frequency results, in order to be included in the online database and the benchmark dataset. Please click <a href="submit.php">here</a>.</p>
    </div>
  </div>


<?php
include "inc/footer.php";
?>
