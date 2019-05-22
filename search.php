<?php 
include "inc/header.php";

// GC count:
// SELECT id, grna_target_id, target_sequence, 2*LENGTH(target_sequence) - CHAR_LENGTH(REPLACE(target_sequence, "C", "")) - CHAR_LENGTH(REPLACE(target_sequence, "G", "")) AS GC_count FROM cleavage_data HAVING GC_count > 19 

include "inc/footer.php";

?>