$(document).ready(function(){
    
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
});

    