<?php 
include "inc/header.php";
?>

<h2>Epigenetics Studies</h2>
<p>These are the epigenetics studies which are currently included in the database.</p>

<table class="table table-striped">
<?php
$result = $conn->query("SELECT id, cell_line, assay, local_path FROM epigenetics_experiments");
if ($result->num_rows > 0) {
    echo '<thead class="thead-dark">
          <tr>
            <th scope="col">No.</th>
            <th scope="col">study</th>
            <th scope="col">assay type</th>
            <th scope="col">cell line</th>
            <th scope="col">annotated targets</th>
          </tr>
          </thead>
          <tbody>';
    
    // output data of each row
    $i = 0;
    while($row = $result->fetch_assoc()) {
        $i++;
        $studyname = end(explode('/', $row["local_path"]));
        $studyname = explode('.', $studyname)[0]; // gets rid of file ending
        $studyname = explode('_', $studyname)[0]; // gets rid of assay category (if included)
        $result2 = $conn->query("SELECT id FROM cleavage_data WHERE epigenetics_ids LIKE '%{$studyname}%'");
        echo '<tr><th scope="row">'.$i.'</th><td>'.$studyname.'</td><td>'.$row["assay"].'</td><td>'.$row["cell_line"].'</td><td>'.$result2->num_rows.'</td></tr>';
    }
    
    echo "</tbody>";
}
?>
</table>

<?php

include "inc/footer.php";

?>