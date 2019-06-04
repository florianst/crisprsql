<?php 
include "inc/header.php";
?>
<h2>Submit</h2>
<?php 
// check upload
if (isset($_FILES['file'])) {
    if($_FILES['file']['size'] > 10485760) { //10 MB (size is also in bytes)
        // File too big
        echo '<div class="alert alert-danger" role="alert">The uploaded file was too big.</div>';
    } else {
        // check if file is csv
        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(in_array($_FILES['file']['type'], $mimes)) {
            $target_file = $uploadpath.'/'.basename($_FILES["file"]["name"]);
            if (file_exists($target_file)) {
                echo '<div class="alert alert-danger" role="alert">Please choose a different file name.</div>';
            } elseif (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "<div class='alert alert-success' role='alert'>Your file has been uploaded.</div>";
            } else {
                echo '<div class="alert alert-danger" role="alert">There was an error uploading your file. Please try again.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">The uploaded file does not have the right format.</div>';
        }
    }
}

?>
<p>We invite submissions of comma separated text files containing the following columns, to be included in the crisprSQL database:</p>
<table border=0 class="rounded-list">
    <tr>
    <td>
    <ol>
      <li><p>cell line / tissue</p></li>
      <li><p>genome assembly</p></li>
      <li><p>chromosome</p></li>
      <li><p>start base pair of (off-)target locus</p></li>
      <li><p>end base pair of (off-)target locus</p></li>
      <li><p>23bp guide RNA sequence</p></li>
      <li><p>23bp (off-)target strand DNA sequence</p></li>
      <li><p>observed cleavage frequency (0..1)</p></li>
    </ol>
    </td>
    </tr>
</table>
<p>Please also supply metadata about your publication. The maximum file size is 10 MB.</p>

<h3>Upload csv file</h3>
<form action="submit.php" method="post" enctype="multipart/form-data">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="file" name="file">
    <label class="custom-file-label" for="file">Choose file</label>
  </div>
  <div class="form-group" style="padding-top:1em;">
    <textarea class="form-control" id="additional" rows="3" placeholder="metadata: PubMed-ID, publication link, measurement protocol, contact email address, etc."></textarea>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>

<script>
// makes the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<?php
include "inc/footer.php";

?>