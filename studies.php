<?php 
include "inc/header.php";
?>
<h2>Studies</h2>
<p>The following studies are included in crisprSQL:</p>

<?php
$result = $conn->query("SELECT experiment_id, cell_line, COUNT(*) AS count FROM cleavage_data GROUP BY experiment_id, cell_line"); // group the data according to assay type and cell line
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
    
    while($row = $result->fetch_assoc()) { // output data of each row
        $i++;
        $result2 = $conn->query("SELECT id FROM cleavage_data WHERE experiment_id='{$row["experiment_id"]}' AND epigenetics_ids != ''");
        echo '<tr><th scope="row">'.$i.'</th><td><a href="http://screen.encodeproject.org/" target="_blank">SCREEN ENCODE v4</a></td><td>'.$row["cell_line"].'</td><td>'.$row["count"].'</td><td>'.$result2->num_rows.'</td></tr>';
    }
    echo "</tbody></table>";
}

include "inc/footer.php";

?>