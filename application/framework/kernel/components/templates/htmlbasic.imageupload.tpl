
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <output id="image_content" class="text-center">
                <img id="{*id*}_img" src="{$theme.images|cat:'user.backend.png'}" width="200" class="img-responsive thumbnail img-circle" style="background-color: #fff;border-radius: 50% !important;margin: 0 auto;" />
            </output>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="fileupload-buttonbar">
                <span class="btn btn-success fileinput-button">
                    <span class="fa fa-file-image-o"></span>
                    <span>{*label*}</span>
                    <input id="{*id*}" type="file" name="{*id*}[]" accept="image/*">
                </span>
            </div>
        </div>
    </div>
</div>


<script>	
$('#{*id*}').change(upload_image);
function upload_image(e) {
      var files = e.target.files; // FileList object     
      // Obtenemos la imagen del campo "file".
      for (var i = 0, f; f = files[i]; i++) {
        //Solo admitimos imágenes.
        if (!f.type.match('image.*')) {
            continue;
        }
        var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
            	$('#{*id*}_img').attr('src',e.target.result);
			};
        })(f);
        reader.readAsDataURL(f);
      }
}
</script>