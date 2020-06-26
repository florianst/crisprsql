<?php
include "inc/header.php";
?>
  <div class="row">
    <div class="col-sm-4">
      <img src="4un3_small.jpg" style="height:270px"/>
      <p style="font-size:85%">Sp-Cas9 protein bound to a dsDNA (blue) guided by an sgRNA (orange).</p>
      <h5>Contribute to database</h5>
      <p>crisprSQL invites submissions of Cas9 off-target indel frequency data, in order to be included in the online database and the benchmark dataset. Please click <a href="submit.php">here</a>.</p>
      <hr class="d-sm-none">
    </div>
    <div class="col-sm-8">
      <?php 
      #<div class="alert alert-primary" role="alert">The database is still in alpha mode.</div>
      ?>
      <h5>Search database</h5>
      <div class="container">
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="sgrna" placeholder="search guide sequence" name="guide">
            <div class="input-group-append">
                <button class="btn btn btn-outline-secondary" type="button" name="ex_rna" onclick="document.getElementById('sgrna').value = 'GAACACAAAGCATAGACTGCGGG';">Example</button>
    			<button class="btn btn-primary" type="submit" name="submit_rna">Search</button>
  			</div>
  		  </div>
  		</form><br>
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="geneid" placeholder="search gene ID" name="geneid">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" name="ex_geneid" onclick="document.getElementById('geneid').value = 'EMX1';">Example</button>
    			<button class="btn btn-primary" type="submit" name="submit_geneid">Search</button>
  			</div>
  		  </div>  			
  		</form><br>
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="target" placeholder="search target sequence" name="target">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" name="ex_target" onclick="document.getElementById('target').value = 'GAACACAAAGCATAGACTGCGGG';">Example</button>
    			<button class="btn btn-primary" type="submit" name="submit_target">Search</button>
  			</div>
  		  </div>  			
  		</form><br>
        <form action="search.php" method="post" enctype="multipart/form-data">
          <div class="input-group">
            <input type="text" class="form-control" id="region" placeholder="search target region (chr1:15000-55000)" name="targetregion">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" name="ex_region" onclick="document.getElementById('region').value = 'chr3:46400000-46420000';">Example</button>
    			<button class="btn btn-primary" type="submit" name="submit_region">Search</button>
  			</div>
  		  </div>
  		</form>
      </div>
      <br>
      <h3>About crisprSQL</h3>
      <p>crisprSQL is a SQL-based database for CRISPR/Cas9 off-target cleavage assays. 
      It is a one-stop source for epigenetically annotated, base-pair resolved cleavage frequency distributions to aid with guide design. 
      Attached gene IDs make this high-resolution data usable for knockout screens, functional genomics and transcriptomics research.</p>
      <br>
      <h5>Database statistics</h5>
      <?php 
      // show studies involved, number of guides, number of targets, number of targets with at least 1 epigenetic marker
      $experiments = $conn->query("SELECT DISTINCT experiment_id FROM cleavage_data");
      $guides      = $conn->query("SELECT DISTINCT grna_target_id FROM cleavage_data");
      $targets     = $conn->query("SELECT id FROM cleavage_data");
      $targets_epi = $conn->query("SELECT id FROM cleavage_data WHERE epigenetics_ids != ''");
      echo "<p>crisprSQL contains ".$experiments->num_rows." <a href='studies.php'>studies</a>, totalling ".$guides->num_rows." <a href='search.php'>guides</a> and ".$targets->num_rows." targets, ".$targets_epi->num_rows." of which have at least one <a href='epigen.php'>epigenetic marker</a> attached.</p>";
      ?>
    </div>
  </div>


<?php
include "inc/footer.php";
?>
