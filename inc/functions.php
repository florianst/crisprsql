<?php 

function epigenLink($studyname, $printAssayType=False, $conn=False) { // output correctly hyperlinked version of an epigenetics study unique identifier
    $studyname = explode('.', $studyname)[0]; // get rid of possibly remaining file endings in unique identifier
    
    if (substr($studyname, 0, 2) == "GS")     { $url = "https://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=".$studyname; } // GEO
    elseif (substr($studyname, 0, 2) == "EN") { $url = "https://www.encodeproject.org/search/?searchTerm=".$studyname; }   // ENCODE
    elseif (substr($studyname, 0, 2) == "EH") { $url = "http://screen.encodeproject.org/search/?q=".$studyname."&assembly=hg19&uuid=0"; } // SCREEN
    
    if ($printAssayType) {
        // find assay type for the supplied study name
        $result = $conn->query("SELECT assay FROM epigenetics_experiments WHERE local_path LIKE '%{$studyname}%'");
        if (mysqli_num_rows($result) > 0) { 
            $assay = mysqli_fetch_assoc($result);
            return '<a href="'.$url.'" target="_blank">'.$assay["assay"].'</a>';
        } 
    }
    
    if (substr($studyname, 0, 2) == 'EH') { $studyname = "SCREEN"; } // cannot yet access the SCREEN webpage and find out which epigenetic marker the specific identifier corresponds to
    
    return '<a href="'.$url.'" target="_blank">'.$studyname.'</a>';
}

?>