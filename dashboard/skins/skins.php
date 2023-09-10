<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<div class="file-upload">
  <div class="input-group">
    <label class="input-group-btn">
      <span class="btn btn-primary">
        <i class='bx bxs-cloud-upload'></i>
        <span>Augšupielādēt attēlu</span>
        <input type="file" style="display:none" accept=".jpg, .jpeg, .png" id="file-input" name="file-input">
      </span>
    </label>
  </div>
</div>

<script>
  var fileInput = document.getElementById('file-input');
  var uploadedFileName = document.getElementById('uploaded-file-name');

  fileInput.addEventListener('change', function() {
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file-input', file);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);

    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var responsephp = xhr.responseText;
        $notConnected = '/No player matching/i';
        console.log(responsephp);
        if (responsephp === "") {
          toastr["success"]("Skins uzlikts veiksmīgi", "Success");
        }
        else {
        toastr["error"](responsephp, "Error");
      }
       }
    };
    xhr.send(formData);
  });
</script>
