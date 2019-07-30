<?php 

function epigenLink($studyname) { // output correctly hyperlinked version of an epigenetics study unique identifier
    $studyname = explode('.', $studyname)[0]; // get rid of possibly remaining file endings in unique identifier
    
    if (substr($studyname, 0, 2) == "GS")     { $url = "https://www.ncbi.nlm.nih.gov/geo/query/acc.cgi?acc=".$studyname; } // GEO
    elseif (substr($studyname, 0, 2) == "EN") { $url = "https://www.encodeproject.org/search/?searchTerm=".$studyname; }   // ENCODE
    elseif (substr($studyname, 0, 2) == "EH") { $url = "http://screen.encodeproject.org/search/?q=".$studyname."&assembly=hg19&uuid=0"; } // SCREEN
    
    return '<a href="'.$url.'" target="_blank">'.$studyname.'</a>';
}

?>