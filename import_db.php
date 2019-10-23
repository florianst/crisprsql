<?php 
// import sqlite3 .db file into MySQL database

include 'inc/db.php';

class SQLiteDB extends SQLite3 {
    function __construct($dbFile) {
        $this->open($dbFile);
    }
}

$sqlCreateEpigeneticsData = " CREATE TABLE IF NOT EXISTS epigenetics_experiments (
                                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                cell_line VARCHAR(255),
                                assay VARCHAR(255),
                                genome VARCHAR(255),
                                local_path TEXT,
                                file_format INT,
                                url TEXT
                            ); ";
                                
$sqlCreateCleavageExperiments = " CREATE TABLE IF NOT EXISTS cleavage_experiments (
                                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                name TEXT,
                                url TEXT,
                                pubmed_id INT UNSIGNED,
                                doi INT UNSIGNED
                                ); ";
                                
$sqlCreateCleavageData = " CREATE TABLE IF NOT EXISTS cleavage_data (
                                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                experiment_id INT UNSIGNED,
                                target_chr VARCHAR(255),
                                target_start INT UNSIGNED,
                                target_end INT UNSIGNED,
                                target_strand VARCHAR(255),
                                target_sequence VARCHAR(255),
                                grna_target_id INT UNSIGNED,
                                grna_target_chr VARCHAR(255),
                                grna_target_start INT UNSIGNED,
                                grna_target_end INT UNSIGNED,
                                grna_target_strand VARCHAR(255),
                                grna_target_sequence VARCHAR(255),
                                genome VARCHAR(255),
                                cell_line VARCHAR(255),
                                measured BOOLEAN,
                                cleavage_freq FLOAT,
                                epigenetics_ids TEXT,
                                epigen_ctcf FLOAT,
                                epigen_dnase FLOAT,
                                epigen_rrbs FLOAT,
                                epigen_h3k4me3 FLOAT,
                                epigen_drip FLOAT,
                                energy_1 FLOAT,
                                energy_2 FLOAT,
                                energy_3 FLOAT,
                                energy_4 FLOAT,
                                energy_5 FLOAT
                            ); ";

$result1 = $conn->query($sqlCreateEpigeneticsData); 
$result2 = $conn->query($sqlCreateCleavageExperiments);
$result3 = $conn->query($sqlCreateCleavageData);
$result4 = $conn->query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");       // make sure MySQL starts numbering the first inserted data line with 0, otherwise we will lose line number 2 (ID=1) in the sqlite table due to the primary key constraint!
$result5 = $conn->query("ALTER TABLE cleavage_data AUTO_INCREMENT=0;"); // the effect is not too big because even if we don't do this, we only lose said line but the correspondence of grna_target_id and id stays intact

if ($result1 === TRUE && $result2 === TRUE && $result3 === TRUE && $result4 === TRUE && $result5 === TRUE) {
    echo "Tables created successfully";
    
    // read sqlite .db file - path defined in class
    $db = new SQLiteDB("../haeussler_onandoff_measuredonly.db");
    $tables = array("epigenetics_experiments", "cleavage_experiments", "cleavage_data");
    
    foreach ($tables as $table) {
        $query = $db->query('SELECT * FROM '.$table);
        
        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $columns = implode(", ", array_keys($row));
            $escaped_values = array_map(array($conn, 'real_escape_string'), array_values($row));
            $values  = implode("', '", $escaped_values);
            $mysql = "INSERT INTO `".$table."`($columns) VALUES ('$values')";
            $result4 = $conn->query($mysql);
        }
        if ($result4 === TRUE) { 
            echo "<br> ".$table." inserted successfully"; 
        } else { 
            echo "<br>Error in ".$table.": " . "<br>" . $conn->error; 
        }
    
    }
    
    
    
} else {
    echo "Error: " . "<br>" . $conn->error;
}

$conn->close();

?>
