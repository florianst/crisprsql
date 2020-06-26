<?php 
include "inc/header.php";
?>

<h2>Epigenetics Studies</h2>

<?php
$result = $conn->query("SELECT COUNT(cleavage_data.id) FROM cleavage_data WHERE cell_line != ''");
$num_total = $result->fetch_array()[0];

$result = $conn->query("SELECT assays_per_id, COUNT(assays_per_id) AS count FROM (SELECT COUNT(assay) AS assays_per_id FROM 
                        (SELECT DISTINCT epigenetics_experiments.assay, cleavage_data.id FROM cleavage_data 
                            LEFT JOIN epigenetics_experiments ON cleavage_data.cell_line=epigenetics_experiments.cell_line AND cleavage_data.genome=epigenetics_experiments.genome 
                            WHERE cleavage_data.cell_line != '') AS SubSubQuery 
                        GROUP BY id) AS SubQuery GROUP BY assays_per_id ORDER BY assays_per_id");
$num_coveredbyepigen = $result->fetch_all();
$num_nocover      = $num_coveredbyepigen[0][1];
$num_atleastone   = $num_total - $num_nocover;
$num_atleasttwo   = $num_atleastone - $num_coveredbyepigen[1][1];
$num_atleastthree = $num_atleasttwo - $num_coveredbyepigen[2][1];
$num_allcover     = end($num_coveredbyepigen)[1];

$percentage_atleastone   = round($num_atleastone   / $num_total * 100, 1);
$percentage_atleasttwo   = round($num_atleasttwo   / $num_total * 100, 1);
$percentage_atleastthree = round($num_atleastthree / $num_total * 100, 1);
$percentage_allcover     = round($num_allcover     / $num_total * 100, 1);
?>

<p>We have annotated the cleavage data points with a choice of five epigenetic markers. The respective studies are linked below. <br>
With these studies, we are able to check annotation for <?php echo $percentage_atleasttwo; ?>% of our cell-line data with at least two epigenetic markers, <?php echo $percentage_atleastthree; ?>% with at least three epigenetic markers
and <?php echo $percentage_allcover; ?>% of our data with all five epigenetic markers.</p>


<?php
$result = $conn->query("SELECT id, cell_line, assay, genome, COUNT(id) AS count FROM epigenetics_experiments WHERE file_format = 0 GROUP BY cell_line, assay"); // group the data according to assay type and cell line
if ($result->num_rows > 0) {
    echo '<table class="table table-striped">
          <thead class="thead-dark">
          <tr>
            <th scope="col">No.</th>
            <th scope="col">study</th>
            <th scope="col">assay type</th>
            <th scope="col">cell line</th>
            <th scope="col">total matching targets</th>
            <th scope="col">annotated targets</th>
          </tr>
          </thead>
          <tbody>';
    
    while($row = $result->fetch_assoc()) { // output data of each row
        $i++;
        $studyname = end(explode('/', $row["local_path"]));
        $studyname = explode('.', $studyname)[0]; // gets rid of file ending
        $studyname = explode('_', $studyname)[0]; // gets rid of assay category (if included)
        $assay = strtolower($row["assay"]);
        $result2 = $conn->query("SELECT id FROM cleavage_data WHERE cell_line='{$row["cell_line"]}' AND epigen_{$assay} != '' AND genome='{$row["genome"]}'");
        $result3 = $conn->query("SELECT id FROM cleavage_data WHERE epigenetics_ids LIKE '%EH%' AND cell_line='{$row["cell_line"]}' AND epigen_{$assay} != ''  AND genome='{$row["genome"]}'");
        echo '<tr><th scope="row">'.$i.'</th><td><a href="http://screen.encodeproject.org/" target="_blank">SCREEN ENCODE v4</a></td><td>'.$row["assay"].'</td><td>'.$row["cell_line"].'</td><td>'.$result2->num_rows.'</td><td>'.$result3->num_rows.'</td></tr>';
    }
}
    

$result = $conn->query("SELECT id, cell_line, assay, local_path FROM epigenetics_experiments WHERE file_format != 0");  
while($row = $result->fetch_assoc()) { // output data of each row
    $i++;
    $studyname = end(explode('/', $row["local_path"]));
    $studyname = explode('.', $studyname)[0]; // gets rid of file ending
    $studyname = explode('_', $studyname)[0]; // gets rid of assay category (if included)
    $result2 = $conn->query("SELECT id FROM cleavage_data WHERE cell_line='{$row["cell_line"]}'");
    $result3 = $conn->query("SELECT id FROM cleavage_data WHERE epigenetics_ids LIKE '%{$studyname}%'");
    $epigen_link = epigenLink($studyname);
    echo '<tr><th scope="row">'.$i.'</th><td>'.$epigen_link.'</td><td>'.$row["assay"].'</td><td>'.$row["cell_line"].'</td><td>'.$result2->num_rows.'</td><td>'.$result3->num_rows.'</td></tr>';
}

echo "</tbody>";

?>
</table>

<?php

include "inc/footer.php";

?>