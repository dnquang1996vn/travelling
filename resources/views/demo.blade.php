<html lang="en">
<head>
    <title>Change image on select new image from file input</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
</head>
<body>
<form action="/fuck" method="post" enctype="multipart/form-data">
    {{csrf_field()}} 
    <input type="file" name="avatar" id="profile-img">
    <img src="" id="profile-img-tag" width="200px" />
    <input type="submit" value="Upload" name = "submit">
<form>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#profile-img-tag').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#profile-img").change(function(){
        readURL(this);
    });
</script>

</body>
</html>