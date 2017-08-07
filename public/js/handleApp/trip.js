$(document).ready(function(){
    $('.nav-tabs').scrollingTabs();
    // load more
    $(".list_user").hide();
    $(".list_user").slice(0,4).show();
    $("#loadMore").on('click', function (e) {
        e.preventDefault();
        $(".list_user:hidden").slice(0, 4).fadeIn();
        if ($(".list_user:hidden").length == 0) {
            $("#loadMore").fadeOut('slow');
        }
        $('html,body').animate({
            scrollTop: $(this).offset().top
        }, 1500);
         $('#scroll_list').animate({
            scrollTop: $(this).offset().top
        }, 1500);
    });

    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('.totop a').fadeIn();
        } else {
            $('.totop a').fadeOut();
        }
    });

    //follow trip
    $("#followBtn").click(function() {
       
        var data = {
            trip_id: $('#trip_id').val(),
            user_id: $('#user_id').val(),
        }
        console.log(data);
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'accepts': 'application/json',
        }
        });
        if ($("#followBtn").val() == 0){
            $.ajax({
                url: '/trip/follow',
                type: "post",
                datatType: "json",
                
                data: data,
                success: function(data){
                    $("#followBtn").prop("value",1);
                    console.log($("#followBtn").val());
                    $("#followBtn").html("Unfollow");
                },
                error: function(data) {

                }
            });
        }
        else {
            $.ajax({
                url: '/trip/unfollow',
                type: "post",
                datatType: "json",
                
                data: data,
                success: function(data){
                    $("#followBtn").prop("value",0);
                    console.log($("#followBtn").val());
                    $("#followBtn").html("Follow");
                },
                error: function(data) {

                }
            });
        }
        
    });


});

    