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
            <th scope="col">total targets</th>
            <th scope="col">epigenetically annotated targets</th>
          </tr>
          </thead>
          <tbody>';
    
    $sumcount  = 0;
    $sumepigen = 0;
    
    while($row = $result->fetch_assoc()) { // output data of each row
        $i++;
        $result2 = $conn->query("SELECT id FROM cleavage_data WHERE experiment_id='{$row["experiment_id"]}' AND epigenetics_ids != '' AND cell_line='{$row["cell_line"]}'");
        $sumcount  += $row["count"];
        $sumepigen += $result2->num_rows;
        echo '<tr><th scope="row">'.$i.'</th><td><a href="https://www.ncbi.nlm.nih.gov/pubmed/'.$row["pubmed_id"].'" target="_blank">'.$row["name"].'</a></td><td>'.$row["cell_line"].'</td><td>'.$row["count"].'</td><td>'.$result2->num_rows.'</td></tr>';
    }
    echo '<tr><th scope="row">sum</th><td></td><td></td><td>'.$sumcount.'</td><td>'.$sumepigen.'</td></tr>';
    echo "</tbody></table>";
}

include "inc/footer.php";

?>