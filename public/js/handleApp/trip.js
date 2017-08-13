$(document).ready(function(){
    $('.nav-tabs').scrollingTabs();
    // load more
    $(".list_user").hide();
    if ($(".list_user:hidden").length < 5) {
            $("#loadMore").fadeOut('slow');
        }
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
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'accepts': 'application/json',
        }
    });
    //follow trip
    $("#followBtn").click(function() {
       
        var data = {
            trip_id: $('#trip_id').val(),
            user_id: $('#user_id').val(),
        }
        console.log(data);
        if ($("#followBtn").val() == 0){
            $.ajax({
                url: '/trip/follow',
                type: "post",
                datatType: "json",
                
                data: data,
                success: function(data){
                  //  alert("Followed");
                    $("#followBtn").prop("value",1);
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
                   // alert("Unfollowed");
                    $("#followBtn").prop("value",0);
                    $("#followBtn").html("Follow");
                },
                error: function(data) {

                }
            });
        }
    });

    // join trip
    $("#joinBtn").click(function() {
       
        var data = {
            trip_id: $('#trip_id').val(),
            user_id: $('#user_id').val(),
        }
        var join = $("#joinBtn").val();
        console.log(join);
        switch (join) {
            case '0':
                console.log("join");
                $("#requestModal").modal("show");
                $("#sendRequest").click(function(){
                    $.ajax({
                    url: '/trip/joinTrip',
                    type: "post",
                    datatType: "json",
                    data: {
                        trip_id: $('#trip_id').val(),
                        user_id: $('#user_id').val(),
                        message: $('#messages').val(),
                    },
                    success: function(data){
                        $("#requestModal").modal("hide");
                        console.log(data);
                        $("#joinBtn").prop("value",1);
                        $("#joinBtn").html("Cancel request");
                    },
                    error: function(data) {
                        alert ("Something is wrong");
                    }
                    });
                });
                
                break;

            case '1':
                $.ajax({
                    url: '/trip/cancelRequest',
                    type: "post",
                    datatType: "json",
                    
                    data: data,
                    success: function(data){
                        console.log(data);
                        $("#joinBtn").prop("value",0);
                        $("#joinBtn").html("Join trip");
                    },
                    error: function(data) {
                        alert ("Something is wrong");
                    }
                });
                break;

            case '2':console.log('fuck');
                confirm("Are you sure?"); 
                $.ajax({
                    url: '/trip/outTrip',
                    type: "post",
                    datatType: "json",
                    data: data,
                    success: function(data){
                        alert("outed");
                        $("#joinBtn").prop("value",0);
                        $("#joinBtn").html("Join trip");
                    },
                    error: function(data) {
                        alert ("Something is wrong");
                    }
                });
                break;

                default: console.log('sai');
        }
            
    });

    // handle joining request

    $(".allowJoinBtn").click(function(){
        var data = {
            trip_id: $('#trip_id').val(),
            owner_id: $('#user_id').val(),
            user_id: $(this).siblings("input").val(),
        }
        $(this).parentsUntil('.requestDetail').fadeOut();
    
        $.ajax({
            url: '/trip/acceptRequest',
            type: "post",
            datatType: "json",
            
            data: data,
            success: function(data){
            },
            error: function(data) {
                alert ("Something is wrong");
            }
        });
     });

    $(".denyJoinBtn").click(function(){
        var data = {
            trip_id: $('#trip_id').val(),
            owner_id: $('#user_id').val(),
            user_id: $(this).siblings("input").val(),
        }
        $(this).parentsUntil('.requestDetail').fadeOut();
    
        $.ajax({
            url: '/trip/denyRequest',
            type: "post",
            datatType: "json",
            
            data: data,
            success: function(data){
            },
            error: function(data) {
                alert ("Something is wrong");
            }
        });
    });

    // kick member
    $(".kickBtn").click(function(){
        var data = {
            trip_id: $('#trip_id').val(),
            owner_id: $('#user_id').val(),
            user_id: $(this).val(),
        }
        console.log('fucl');
        if (confirm("Do you really want to kick ?")) {
            $(this).parentsUntil('.memberForm').fadeOut();
        }
        $.ajax({
            url: '/trip/kick',
            type: "post",
            datatType: "json",
            
            data: data,
            success: function(data){
            },
            error: function(data) {
                alert ("Something is wrong");
            }
        });
    });

    // set status
    if ($('#statusBtn').val() == 0){
        $('#finishTripBtn').hide();
    }
    if ($('#statusBtn').val() == 1){
        $('#startTripBtn').hide();
        $('#cancelTripBtn').hide();
    }
    if ($('#statusBtn').val() == 2){
        $('#startTripBtn').hide();
        $('#cancelTripBtn').hide();
        $('#finishTripBtn').hide();
    }
    if ($('#statusBtn').val() == 3){
        $('#startTripBtn').hide();
        $('#cancelTripBtn').hide();
        $('#finishTripBtn').hide();
    }

    $('#startTripBtn').click(function(){
        var data = {
            trip_id: $('#trip_id').val(),
            user_id: $('#user_id').val(),
        }
        console.log(data);

        $.ajax({
            url: '/trip/startTrip',
            type: "post",
            datatType: "json",
            
            data: data,
            success: function(data){
                $('#statusBtn').html("Status: running");
                $('#finishTripBtn').show();
                $('#startTripBtn').hide();
                $('#cancelTripBtn').hide();
                $('#editTripBtn').hide();
            },
            error: function(data) {
                alert ("Something is wrong");
            }
        });
    });

    $('#finishTripBtn').click(function(){
        var data = {
            trip_id: $('#trip_id').val(),
            user_id: $('#user_id').val(),
        }
        console.log(data);

        $.ajax({
            url: '/trip/finishTrip',
            type: "post",
            datatType: "json",
            
            data: data,
            success: function(data){
                $('#statusBtn').html("Status: done");
                $('#finishTripBtn').hide();
                $('#startTripBtn').hide();
                $('#cancelTripBtn').hide();
                $('#editTripBtn').hide();
            },
            error: function(data) {
                alert ("Something is wrong");
            }
        });
    });

    $('#cancelTripBtn').click(function(){
        var data = {
            trip_id: $('#trip_id').val(),
            user_id: $('#user_id').val(),
        }
        console.log(data);

        $.ajax({
            url: '/trip/cancelTrip',
            type: "post",
            datatType: "json",
            
            data: data,
            success: function(data){
                $('#statusBtn').html("Status: canceled");
                $('#finishTripBtn').hide();
                $('#startTripBtn').hide();
                $('#cancelTripBtn').hide();
                
            },
            error: function(data) {
                alert ("Something is wrong");
            }
        });
    });
});

    