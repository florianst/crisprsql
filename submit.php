<?php 
include "inc/header.php";
?>
<h2>Submit</h2>
<p>We invite submissions of comma separated text files containing the following columns:</p>
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
<p>Please also supply metadata about your publication.</p>

<h3>Upload csv file</h3>
<form>
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="file">
    <label class="custom-file-label" for="file">Choose file</label>
  </div>
  <div class="form-group">
    <label for="additional">File metadata</label>
    <textarea class="form-control" id="additional" rows="3" placeholder="PubMed-ID, publication link, measurement protocol, etc."></textarea>
  </div>
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