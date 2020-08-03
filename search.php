<?php 
// GC count:
// SELECT id, grna_target_id, target_sequence, 2*LENGTH(target_sequence) - CHAR_LENGTH(REPLACE(target_sequence, "C", "")) - CHAR_LENGTH(REPLACE(target_sequence, "G", "")) AS GC_count FROM cleavage_data HAVING GC_count > 19
include "inc/header.php";
include "inc/plot_offtargetprofile.php";
?>

<script>$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

<?php

$limit = 500;

$querystart = "SELECT *, 2*LENGTH(target_sequence) - CHAR_LENGTH(REPLACE(target_sequence, \"C\", '')) - CHAR_LENGTH(REPLACE(target_sequence, \"G\", '')) AS GC_count";
if (isset($_POST["submit_rna"]) && isset($_POST["guideid"])) {
    $guideid = preg_replace("/[^0-9,.]/", '', $_POST["guideid"]);
    $category = "guide";
    $search = preg_replace("/[^A-Ta-t ]/", '', $_POST["guide"]);
    $result = $conn->query($querystart." FROM cleavage_data WHERE grna_target_id = {$guideid}");
} elseif (isset($_POST["submit_rna"]) && isset($_POST["guide"])) {
    $guide = preg_replace("/[^A-Ta-t ]/", '', $_POST["guide"]);
    $category = "guide";
    $search = $guide;
    $result = $conn->query($querystart." FROM cleavage_data WHERE grna_target_sequence LIKE '%{$guide}%'");
} elseif (isset($_POST["submit_target"]) && isset($_POST["target"])) {
    $target = preg_replace("/[^A-Ta-t ]/", '', $_POST["target"]);
    $category = "target";
    $search = $target;
    $result = $conn->query($querystart." FROM cleavage_data WHERE target_sequence LIKE '%{$target}%'");
} elseif (isset($_POST["submit_geneid"]) && isset($_POST["geneid"])) {
    $geneid = preg_replace("/[^A-Za-z0-9.\- ]/", '', $_POST["geneid"]);
    $category = "gene ID";
    $search = $geneid;
    $result = $conn->query($querystart." FROM cleavage_data WHERE target_geneid LIKE '%{$geneid}%'");
} elseif (isset($_POST["submit_region"]) && isset($_POST["targetregion"])) {
    $targetregion = $_POST["targetregion"];
    if (preg_match("/^(chr)[0-9XVIY]{1,2}:[0-9]{1,10}-[0-9]{1,10}$/", $targetregion)) { // verify string is a proper region
        // split according to chrX:start-end
        $category = "region";
        $search = $targetregion;
        $chr  = explode(":", $targetregion)[0];
        $temp = explode(":", $targetregion)[1];
        $start= explode("-", $temp)[0];
        $end  = explode("-", $temp)[1];
        $result = $conn->query("SELECT *, 2*LENGTH(target_sequence) - CHAR_LENGTH(REPLACE(target_sequence, \"C\", '')) - CHAR_LENGTH(REPLACE(target_sequence, \"G\", '')) AS GC_count FROM cleavage_data WHERE target_chr='{$chr}' AND target_start>={$start} AND target_end<={$end}");
    }
} 
if (isset($result)) {
    // track search result
    $num_results = $result->num_rows;
    echo "<script type='text/javascript'>_paq.push(['trackSiteSearch','".$search."','".$category."',".$num_results."]);</script>\r\n";
    
    // display all matching targets
    if ($num_results > 0) {
        echo "<h2>Targets</h2>";
        echo "<h4 style='display:inline; margin-right:1em;'>matching your search for ".$category." ".$search."</h4><a href='search.php'>Back</a>";
        echo "<p>Your query yielded {$result->num_rows} results: <img src='".plotOfftargetProfile($result->fetch_all(MYSQLI_ASSOC))."' class='offtarget_distr' alt='offtarget_distr' />";
        
        // Help toggle button
        echo "<a class='btn btn-primary' data-toggle='collapse' href='#collapseHelp' role='button' aria-expanded='false' aria-controls='collapseHelp'>Help</a></p>";
        echo "<div class='collapse' id='collapseHelp'>
          <div class='card card-body'>
            <p>Mismatches between guide and target sequences are shown in <strong>bold</strong>. We show the GC count of the target sequence, the target region mapped to the hg19 reference genome (if not shown otherwise) and the GENCODE gene name.<br>
            Cleavage rate refers to the relative indel frequency at the given location.</p>
          </div>
        </div>";
  
        $result->data_seek(0) ;// reset pointer to result set such that we can go through it again below
        echo '<table class="table table-striped sortable targets"><thead class="thead-dark">
              <tr>
                <th scope="col" data-defaultsort="disabled">No.</th>
                <th scope="col">guide sequence</th>
                <th scope="col">target sequence<div class="explain">mismatches in bold</div></th>
                <th scope="col">mismatches</th>
                <th scope="col">target GC</th>
                <th scope="col">target region</th>
                <th scope="col">target gene ID</th>
                <th scope="col">cleavage rate</th>
                <th scope="col">epigenetics markers</th>
                <th scope="col">cell line</th>
                <th scope="col">study</th>
              </tr>
              </thead>
              <tbody>';
        
        // output data of each row
        $i = 0;
        while($row = $result->fetch_assoc()) {
            $i++;
            if ($i > $limit) {
                break;
                echo "<p>Only showing {$limit} results</p>";
            }
            $studies = '';
            $result2 = $conn->query("SELECT DISTINCT experiment_id FROM cleavage_data WHERE id=".$row["id"]);
            while($row2 = $result2->fetch_assoc()) {
                $query2 = $conn->query("SELECT name, pubmed_id FROM cleavage_experiments WHERE id=".$row2["experiment_id"]);
                while ($queryresult2 = $query2->fetch_assoc()) {
                    $studies .= '<a href="https://www.ncbi.nlm.nih.gov/pubmed/'.$queryresult2["pubmed_id"].'" target="_new">'.$queryresult2["name"].'</a> ';
                }
            }
            $epigen_str = '';
            if (strlen($row["epigenetics_ids"]) > 0) {
                $epigen_studies = array_unique(explode(',', $row["epigenetics_ids"]));
                foreach ($epigen_studies as $study_identifier) {
                    if (strlen($study_identifier) > 2) { $epigen_str .= epigenLink($study_identifier, True, $conn).', '; }
                }
                $epigen_str = substr($epigen_str, 0, -2);
            }
            
            // highlight mismatches in target sequence
            $targetseq  = '';
            $mismatches = 0;
            foreach (str_split($row["target_sequence"]) as $pos => $base) {
                if ($row["grna_target_sequence"][$pos] == $base) { $targetseq .= $base; }
                else { $targetseq .= "<b>".$base."</b>"; $mismatches++; }
            }
            
            // if cleavage frequency is in scientific (mantissa) notation, make sure to convert it to float and round
            if (substr_count($row["cleavage_freq"], "e") > 0) { $row["cleavage_freq"] = round($row["cleavage_freq"], 4); }
            
            if ($row["target_geneid"] != '') {
                $geneid = "<a href='https://genome-euro.ucsc.edu/cgi-bin/hgTracks?db=".$row["genome"]."&position=".$row["target_chr"].":".$row["target_start"]."-".$row["target_end"]."' target='_new'>".$row["target_geneid"]."</a>";
            } else { $geneid = ""; }
    
            echo '<tr><th scope="row">'.$i.'</th><td style="font-family:Courier"><form action="search.php" method="post" id="form'.$i.'"><input type="hidden" name="submit_rna" /><input type="hidden" name="guideid" id="sgrnaid" value="'.$row["grna_target_id"].'" /><input type="hidden" name="guide" id="sgrna" value="'.$row["grna_target_sequence"].'" /><a href="#" class="submit-link" onclick="document.getElementById(\'form'.$i.'\').submit();">'.$row["grna_target_sequence"].'</a></form></td><td style="font-family:Courier">'.$targetseq.'</td><td>'.$mismatches.'</td><td>'.$row["GC_count"].'</td><td>'.$row["target_chr"].':'.$row["target_start"].'-'.$row["target_end"].'</td><td>'.$geneid.'</td><td>'.$row["cleavage_freq"].'</td><td>'.$epigen_str.'</td><td>'.$row["cell_line"].'</td><td>'.$studies.'</td></tr>';
        }
        
        echo "</tbody></table><br>";
        
    } else {
        echo '<div class="alert alert-primary" role="alert">Your query did not match any targets in the database.</div>';
    }
} else {
    echo "<h2>Guides</h2>";
    echo "<p>This table shows all guide RNAs with at least two measured off-targets. It can be reordered by clicking on a column title.</p>";
    echo "<p>Clicking on a guide sequence shows all measured off-targets for the respective guide.</p>";
    
    // display all guides
    $species = array("Human"=>"genome='hg19' OR genome='hg38'", "Rodents"=>"genome='rn5' OR genome='mm9' OR genome='mm10'");
    
    foreach ($species as $title => $cond) {
        $result = $conn->query("SELECT id, genome, grna_target_chr, grna_target_start, grna_target_end, grna_target_sequence, grna_target_id, target_chr, target_start, target_end, target_geneid, cell_line FROM cleavage_data WHERE ".$cond." GROUP BY grna_target_id, cell_line, experiment_id ORDER BY IF (grna_target_chr = 'chrX' OR grna_target_chr = 'chrY' OR grna_target_chr='chrMT', 50, CAST(SUBSTRING_INDEX(grna_target_chr, 'chr', -1) AS unsigned)) LIMIT {$limit}");
        if ($result->num_rows > 0) {
            echo "<h4>".$title."</h4><table class='table table-striped sortable'>";
            echo '<thead class="thead-dark">
              <tr>
                <th scope="col" data-defaultsort="disabled">No.</th>
                <th scope="col">sequence</th>
                <th scope="col">region</th>
                <th scope="col">gene ID</th>
                <th scope="col">assembly</th>
                <th scope="col">cell line</th>
                <th scope="col">study</th>
                <th scope="col" data-defaultsort="disabled" data-toggle="tooltip" data-placement="top" data-html="true" title="cleavage frequency histogram: x-axis from<br>chr1 (left) to chrY (right)">target distribution</th>
                <th scope="col">measured off-targets</th>
              </tr>
              </thead>
              <tbody>';
            
            // output data of each row
            $i = 0;
            while($row = $result->fetch_assoc()) {
                $studies = '';
                $result2 = $conn->query("SELECT DISTINCT experiment_id FROM cleavage_data WHERE id=".$row["id"]);
                while($row2 = $result2->fetch_assoc()) {
                    $query2 = $conn->query("SELECT name, pubmed_id, doi FROM cleavage_experiments WHERE id=".$row2["experiment_id"]);
                    while ($queryresult2 = $query2->fetch_assoc()) {
                        if (strlen($queryresult2["doi"]) > 1) { $weblink = $queryresult2["doi"]; } else { $weblink = 'https://www.ncbi.nlm.nih.gov/pubmed/'.$queryresult2["pubmed_id"]; }
                        $studies .= '<a href="'.$weblink.'" target="_new">'.$queryresult2["name"].'</a> ';
                    }
                }
                // get number of off-targets to only include guides with at least two off-targets
                $result3 = $conn->query("SELECT * FROM cleavage_data WHERE grna_target_id=".$row["grna_target_id"]." AND id!=".$row["id"]);
                if ($result3->num_rows > 1) {
                    // fetch all targets for the given guide in order to plot target distribution
                    $result4 = $conn->query("SELECT target_chr, target_start, cleavage_freq, grna_target_chr, grna_target_start FROM cleavage_data WHERE grna_target_id=".$row["grna_target_id"]);
                    $targets = $result4->fetch_all(MYSQLI_ASSOC);
                    
                    // visualise repeated guides, e.g. for different combination of cell line and study TODO: do this using rowspan
                    if ($grna_targetseq_old == $row["grna_target_sequence"]) { 
                        $targetseq = '<div align="center">&mdash; " &mdash;</div>'; // replace both by placeholder
                        $region = $targetseq;
                    } else { 
                        $targetseq = $row["grna_target_sequence"];
                        if (strlen($row["grna_target_chr"]) >= 4) { $region = $row["grna_target_chr"].':'.$row["grna_target_start"].'-'.$row["grna_target_end"]; }
                        else { $region = "<i>n/a</i>"; }
                    }
                    
                    if ($row["target_geneid"] != '') {
                        $geneid = "<a href='https://genome-euro.ucsc.edu/cgi-bin/hgTracks?db=".$row["genome"]."&position=".$row["target_chr"].":".$row["target_start"]."-".$row["target_end"]."' target='_new'>".$row["target_geneid"]."</a>";
                    } else { $geneid = ""; }
                    
                    $i++;
                    echo '<tr><th scope="row">'.$i.'</th><td style="font-family:Courier"><form action="search.php" method="post" id="form'.$i.'"><input type="hidden" name="submit_rna" /><input type="hidden" name="guideid" id="sgrnaid" value="'.$row["grna_target_id"].'" /><input type="hidden" name="guide" id="sgrna" value="'.$row["grna_target_sequence"].'" /><a href="#" class="submit-link" onclick="document.getElementById(\'form'.$i.'\').submit();">'.$targetseq.'</a></form></td><td>'.$region.'</td><td>'.$geneid.'</td><td>'.$row["genome"].'</td><td>'.$row["cell_line"].'</td><td>'.$studies.'</td><td><img src="'.plotOfftargetProfile($targets).'" alt="offtarget distribution" /></td><td>'.$result3->num_rows.'</td></tr>';
                    $grna_targetseq_old = $row["grna_target_sequence"];
                }
            }
            
            echo "</tbody></table><br>";
        }
    }
}
?>


<?php
include "inc/footer.php";

?>