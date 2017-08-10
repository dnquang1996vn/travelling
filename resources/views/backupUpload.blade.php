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
            <form action="/load" method = "post" file = "true" enctype="maltipart/form-data" class="dropzone" id = "image-upload">
             {{ csrf_field() }}
             <input type="hidden" name="quang" value="1">
            <div>
                <h3>Upload Multiple Image By Click On Box</h3>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    Dropzone.options.imageUpload = {
        maxFilesize         :       1,
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,
        init: function() {
            this.on("removedfile", function(file) {
                $.ajax({
                    type: 'POST',
                    url: 'upload/delete',
                    data: {id: file.name},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'html',
                    success: function(data){
                        var rep = JSON.parse(data);
                        console.log(rep);
                    }
                });
            });
        }
    };
</script>

</body>
</html>