<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="../../js/notify.min.js"></script>
<script type="text/javascript"> 
    $.notify.addStyle('ErrorNotify', {
  html: "<div><i class='fa-solid fa-circle-exclamation'></i><span data-notify-text/></div>",
  classes: {
    base: {
      "white-space": "nowrap",
      "color": "white",
      "background-color": "#131313",
      "padding": "10px",
      "font-family": 'Quicksand',
      "border-radius": '6px',
      "border": ' 1px solid #FC9C1A'
    }
  }
});
</script>
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
        $.notify(responsephp, {
                style: 'ErrorNotify'
                });
      }
    };
    xhr.send(formData);
  });
</script>
