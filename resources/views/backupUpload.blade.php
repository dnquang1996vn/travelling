<!DOCTYPE html>
<html>
<head>
    <title>Upload Multiple Images using dropzone.js and Laravel</title>
    <meta name="csrf-token" id = "csrf-token" content="{{ csrf_token() }}">
    <script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>
    <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
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
    $("#add").click(function(){
        $(this).siblings("form").show();
    });
    Dropzone.autoDiscover = false;
    $("#image-upload").dropzone ({
        maxFilesize         :       1,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,  

       
        init: function() {
            this.on("addedfile", function(file) { 
                console.log(file);
                
            });

            this.on("success", function(file, serverFileName) {
                file.serverFileName = serverFileName;
                //console.log(file.id);
                console.log(file.serverFileName);
            });
            
            this.on("removedfile", function(file) {
                console.log(file.serverFileName);
                $.ajax({
                    type: 'POST',
                    url: 'upload/delete',
                    data: {file: file.serverFileName},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'html',
                    success: function(data){
                        console.log(data);
                    }
                });
            });
        }
    });
</script>

</body>
</html>