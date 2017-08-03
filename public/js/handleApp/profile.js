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
                url: '/profile/update',
                type: "post",
                datatType: "json",
                
                data: updateData,
                success: function(data){
                    
                    console.log(data);

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
                },
                error: function(data) {
                    console.log(data);
                    console.log(typeof(data.responseText));
                    console.log(JSON.parse(data.responseText).birthday);
                }
            });
        });

        // update avatar
    });
