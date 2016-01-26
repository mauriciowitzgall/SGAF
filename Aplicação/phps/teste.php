<form enctype="multipart/form-data" method="post" action="upload.php">
    <div class="row">
      <label for="fileToUpload">Select Files to Upload</label><br />
      <input type="file" name="filesToUpload[]" id="filesToUpload" multiple="multiple" />
      <output id="filesInfo"></output>
    </div>
    <div class="row">
      <input type="submit" value="Upload" />
    </div>
</form>