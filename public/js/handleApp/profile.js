    $(document).on('ready', function() {
      	// slick
        $(".regular").slick({
	        dots: true,
	        infinite: false,
	        slidesToShow: 4,
	        slidesToScroll: 4,
      	});
      	console.log('rudddd');

        // tabs
      	$('.nav-tabs').scrollingTabs();

        //modal update
        $("#updateBtn").click(function() {
            $("#updateModal").modal("show");
            $("#save_update").click(function() {
                var user_id = $(this).val();
                var updateData = {
                    name: $('#name').val(),
                    birthday: $('#birthday').val(),
                    gender: $('#gender').val(),
                    phone: $('#phone').val(),
                    work: $('#work').val(),
                    about: $('#about').val(),
                }
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'accepts': 'application/json',
                }
                });
                $.ajax({
                    url: '/profile/'+user_id+'/update',
                    type: "post",
                    datatType: "json",
                    
                    data: updateData,
                    success: function(data){
                        $("#updateModal").modal("hide");
                        // update detail profile
                        $(".name").html(data.name);
                        $(".birthday").html(data.birthday);
                        $(".work").html(data.work);
                        $(".about").html(data.about);
                        $(".phone").html(data.phone);
                        if(data.gender == 0)
                        {
                            $(".gender").html("male");
                        }
                        else
                        {
                            $(".gender").html("female");
                        }
                        // update modal
                        $("#nameError").html("");
                        $("#birthdayError").html("");
                        $("#workError").html("");
                        $("#aboutError").html("");
                        $("#phoneError").html("");
                    },
                    error: function(data) {
                        var error = JSON.parse(data.responseText);
                        console.log(error);
                        if (error.name) {
                            $("#nameError").html(error.name[0]);
                        }
                        if (error.birthday) {
                            $("#birthdayError").html(error.birthday[0]);
                        }
                        if (error.work) {
                            $("#workError").html(error.work[0]);
                        }
                        if (error.about) {
                            $("$aboutError").html(error.about[0]);
                        }
                        if (error.phone) {
                            
                            $("#phoneError").html(error.phone[0]);
                        }
                    }
                });
            });
        });

        // update avatar
    });
