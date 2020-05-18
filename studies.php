<?php 
include "inc/header.php";
?>
<h2>Studies</h2>
<p>The following studies are included in crisprSQL:</p>

<?php
$result = $conn->query("SELECT experiment_id, cell_line, COUNT(cleavage_data.id) AS count, cleavage_experiments.name, cleavage_experiments.pubmed_id FROM cleavage_data INNER JOIN cleavage_experiments ON cleavage_data.experiment_id = cleavage_experiments.id GROUP BY experiment_id, cell_line"); // group the data according to assay type and cell line
if ($result->num_rows > 0) {
    echo '<table class="table table-striped">
          <thead class="thead-dark">
          <tr>
            <th scope="col">No.</th>
            <th scope="col">study</th>
            <th scope="col">cell line</th>
            <th scope="col">total guides</th>
            <th scope="col">total targets</th>
            <th scope="col">epigenetically annotated targets</th>
            <th scope="col">cleaved gene IDs (CF > 1%)</th>
          </tr>
          </thead>
          <tbody>';
    
    $sumcount  = 0;
    $sumguides = 0;
    $sumepigen = 0;
    
    while ($row = $result->fetch_assoc()) { // output data of each row
        $i++;
        // TODO: exclude guides without off-targets to comply with search.php
        $guidecount = 0;
        $guides = $conn->query("SELECT DISTINCT(grna_target_id) FROM cleavage_data WHERE experiment_id='{$row["experiment_id"]}' AND cell_line='{$row["cell_line"]}'");
        while ($guide = $guides->fetch_assoc()) {
            // check how many off-targets this guide has
            $offtargets = $conn->query("SELECT id FROM cleavage_data WHERE grna_target_id='{$guide['grna_target_id']}' AND id != grna_target_id");
            if ($offtargets->num_rows > 1) {
                $guidecount++;
            }
        }

        $result2 = $conn->query("SELECT id FROM cleavage_data WHERE experiment_id='{$row["experiment_id"]}' AND epigenetics_ids != '' AND cell_line='{$row["cell_line"]}'");
        $sumcount  += $row["count"];
        $sumguides += $guidecount;
        $sumepigen += $result2->num_rows;
        
        $result2 = $conn->query("SELECT DISTINCT(target_geneid) FROM cleavage_data WHERE experiment_id='{$row["experiment_id"]}' AND cell_line='{$row["cell_line"]}' AND LENGTH(target_geneid) > 1 AND cleavage_freq>0.01 ORDER BY cleavage_freq DESC");
        $geneids = array_column($result2->fetch_all(), 0);
        echo '<tr><th scope="row">'.$i.'</th><td><a href="https://www.ncbi.nlm.nih.gov/pubmed/'.$row["pubmed_id"].'" target="_blank">'.$row["name"].'</a></td><td>'.$row["cell_line"].'</td><td>'.$guidecount.'</td><td>'.$row["count"].'</td><td>'.$result2->num_rows.'</td><td>'.join(", ", $geneids).'</td></tr>';
    }
    echo '<tr><th scope="row">sum</th><td></td><td></td><td>'.$sumguides.'</td><td>'.$sumcount.'</td><td>'.$sumepigen.'</td><td></td></tr>';
    echo "</tbody></table>";
}

include "inc/footer.php";

?>