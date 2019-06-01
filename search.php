<?php 
include "inc/header.php";
// GC count:
// SELECT id, grna_target_id, target_sequence, 2*LENGTH(target_sequence) - CHAR_LENGTH(REPLACE(target_sequence, "C", "")) - CHAR_LENGTH(REPLACE(target_sequence, "G", "")) AS GC_count FROM cleavage_data HAVING GC_count > 19
// TODO: "target gene" column --> get list of gene names
?>

<h2>Targets</h2>

<table class="table table-striped">
<?php
$result = $conn->query("SELECT id, genome, grna_target_chr, grna_target_start, grna_target_end, grna_target_sequence, grna_target_id FROM cleavage_data WHERE grna_target_id=id-1 GROUP BY grna_target_sequence");
if ($result->num_rows > 0) {
    echo '<thead class="thead-dark">
          <tr>
            <th scope="col">No.</th>
            <th scope="col">sequence</th>
            <th scope="col">region</th>
            <th scope="col">assembly</th>
            <th scope="col">study</th>
            <th scope="col">number of off-targets</th>
          </tr>
          </thead>
          <tbody>';
    
    // output data of each row
    $i = 0;
    while($row = $result->fetch_assoc()) {
        $i++;
        $studies = '';
        $result2 = $conn->query("SELECT DISTINCT experiment_id FROM cleavage_data WHERE id=".$row["id"]);
        while($row2 = $result2->fetch_assoc()) { 
            $query2 = $conn->query("SELECT name, pubmed_id FROM cleavage_experiments WHERE id=".$row2["experiment_id"]);
            while ($queryresult2 = $query2->fetch_assoc()) {
                $studies .= '<a href="https://www.ncbi.nlm.nih.gov/pubmed/'.$queryresult2["pubmed_id"].'" target="_new">'.$queryresult2["name"].'</a> ';
            }
        }
        $result3 = $conn->query("SELECT * FROM cleavage_data WHERE grna_target_id=".$row["grna_target_id"]." AND id!=".($row["id"]-1));
        echo '<tr><th scope="row">'.$i.'</th><td style="font-family:Courier">'.$row["grna_target_sequence"].'</td><td>'.$row["grna_target_chr"].':'.$row["grna_target_start"].'-'.$row["grna_target_end"].'</td><td>'.$row["genome"].'</td><td>'.$studies.'</td><td>'.$result3->num_rows.'</td></tr>';
    }
    
    echo "</tbody>";
}
?>
</table>

<?php
include "inc/footer.php";

?>