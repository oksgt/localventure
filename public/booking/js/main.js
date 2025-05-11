    $(function(){
        $("#wizard").steps({
        headerTag: "h4",
        bodyTag: "section",
        transitionEffect: "fade",
        enableAllSteps: false, // ✅ Prevent jumping between steps
        enablePagination: true, // ✅ Enable "Next" button navigation
        transitionEffectSpeed: 500,
        labels: {
            current: ""
        }
    });

    // ✅ Disable manual navigation via step headers
        $(".wizard > .steps > ul > li a").on("click", function(event) {
            event.preventDefault(); // ✅ Stops clicking on <h4> headers
        });



    // Custome Button Jquery Step
    // $('.forward').click(function(){
    // 	$("#wizard").steps('next');
    // })

    // Date Picker

    // var dp2 = $('#dp2').datepicker().data('datepicker');
    // dp2.selectDate(new Date());
    // var dp3 = $('#dp3').datepicker().data('datepicker');
    // dp3.selectDate(new Date());
    // var dp4 = $('#dp4').datepicker().data('datepicker');
    // dp4.selectDate(new Date());

    // Select Dropdown
    $('html').click(function() {
        $('.select .dropdown').hide();
    });
    $('.select').click(function(event){
        event.stopPropagation();
    });
    $('.select .select-control').click(function(){
        $(this).parent().next().toggle();
    })
    $('.select .dropdown li').click(function(){
        $(this).parent().toggle();
        var text = $(this).attr('rel');
        $(this).parent().prev().find('div').text(text);
    })


})


