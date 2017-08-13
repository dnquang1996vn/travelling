<!DOCTYPE html>
<html>
<head>
    <title>Upload Multiple Images using dropzone.js and Laravel</title>
    <meta name="csrf-token" id = "csrf-token" content="{{ csrf_token() }}">
    <script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
    <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet"> -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Upload Multiple Images using dropzone.js and Laravel</h1>
            <button id = "add">add image</button>
            <form action="/load" method = "post" file = "true" enctype="maltipart/form-data" class="dropzone" id = "image-upload" style="display: none">
             {{ csrf_field() }}
            <input type="hidden" name="comment_id" value="1">
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var fileList = new Array;
    var i =0;
    $("#add").click(function(){
        $(this).siblings("form").show();
    });
    Dropzone.options.imageUpload = {
        maxFilesize         :       1,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,  

        
        init: function() {
            var myDropzone = this;
            this.on("success", function(file, serverFileName) {
                file.serverFileName = serverFileName;
                file.id = "fuck";
                console.log(file.id);
                console.log(file.serverFileName);
            });
            
            $.get('/server-images/1', function(data) {
                console.log(data);
                $.each(data.images, function (key, value) {

                    var file = {name: value.original, size: value.size};
                    myDropzone.options.addedfile.call(myDropzone, file);
                    myDropzone.emit("complete", file);
                });
            });

            this.on("removedfile", function(file) {
                console.log(file.serverFileName);
                $.ajax({
                    type: 'POST',
                    url: 'upload/delete',
                    data: {id: file.serverFileName},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'html',
                    success: function(data){
                    }
                });
            });
        }
    };
</script>

</body>
</html>