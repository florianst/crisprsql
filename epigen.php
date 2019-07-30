<?php 
include "inc/header.php";
?>

<h2>Epigenetics Studies</h2>
<p>These are the epigenetics studies which are currently included in the database.</p>


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
        $result2 = $conn->query("SELECT id FROM cleavage_data WHERE cell_line='{$row["cell_line"]}' AND epigen_{$assay} != ''");
        $result3 = $conn->query("SELECT id FROM cleavage_data WHERE epigenetics_ids LIKE '%EH%' AND cell_line='{$row["cell_line"]}' AND epigen_{$assay} != ''");
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