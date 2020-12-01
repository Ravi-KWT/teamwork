(function($) {
    'use strict';
    $(document).ready(function () {
        document.oncontextmenu = document.body.oncontextmenu = function () { return true; }

        $('#login_modal').modal('show');
    });

    

    jQuery(".imgbg").each(function(i, elem) {
        var img = jQuery(elem);
        jQuery(this).hide();
        jQuery(this).parent().css({
            background: "url(" + img.attr("src") + ") no-repeat center top",
        });
    });
    $('body').find('.thmepage').length > 0 ? $('body').addClass('changetheme') : "";
    $('#website').click(function(e){
      var url = $('#website').val();
        if(url.substring(0,7) != "http://")
        {
            if(e.keyCode != 8)
            {
                $(this).val( 'http://'+ url );
            }
        }
    });
    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('table tr th').wrapInner('<span></span>');
    var windowH = $(window).height();
    $('.login_page').height(windowH);
    $( "#website" ).focusin(function(){
        $( "#website" ).trigger( "click" );
    });


    var closingHour ;
    var closingMinutes;
    var openingHour;
    var openingMinutes;

    
    $(document).on('click', '#timepicker_1, #timepicker_2', function() {
            $(this).timepicker('showWidget'); 
    });

    /* work page color code set in background */
    jQuery(".bgcolor").css('background', function() {
        return $(this).data('color')
    });
    var scrollH = $('.mCustomScrollbar').data("height");
    $(".mCustomScrollbar").mCustomScrollbar({
        setHeight: scrollH
    });
    // script for List_view, Grid view and box view
    $('.btn_list_view').click(function(){
        $(this).toggleClass('active');
        $('.list_view').fadeIn();
        $('.list_grid_view').hide();
        $('.list_box_view').hide();
    });
    $('.btn_grid_view').click(function(){
         $(this).toggleClass('active');
        $('.list_view').hide();
        $('.list_grid_view').fadeIn();
        $('.list_box_view').hide();
    });
    $('.btn_box_view').click(function(){
         $(this).toggleClass('active');
        $('.list_view').hide();
        $('.list_grid_view').hide();
        $('.list_box_view').fadeIn();
    });

    $('.registerBtn').click(function() {
       $("#cat_id").val($(this).attr('data-value'));
    });
    $('.task_category').click(function() {
        $('#addNewAppModal').modal('show');
    });
    $('.panel-collapse label').on('click', function(e){
        e.stopPropagation();
    })
    $('.navtogg').click(function(){
        $(this).next('ul').slideToggle();
    });
    $('modal').on('hidden.bs.modal', function () {
        $('modal').modal('data-dismiss');

    });
    // $(document).on('hidden.bs.modal','#logTimeModal', function () {
    //    $('#logTimeModal').trigger('show.bs.modal');
    // });

    // $(document).on('shown.bs.modal','#logTimeModal', function () {
    //          var current_time,current_time2, d;
    //           d = new Date;
    //           if($('#timepicker_1').val()){
    //             current_time = moment($(timepicker_1).val(),'hh::mm A').format('hh:mm A');  
    //           }else{
    //             current_time = moment(d).format('hh:mm A');  
    //           }
    //          $('#timepicker_1').timepicker({
    //             minuteStep:5,
    //             snapToStep:true,
    //             defaultTime:current_time,
    //             forceRoundTime: true 
    //         });
         
    //       // $('#timepicker_1').val(current_time);        


    //       if($('#timepicker_2').val()){
    //         current_time2 = moment($('#timepicker_2').val(),'hh::mm A').format('hh:mm A');  
    //       }else{
    //         current_time2 = moment(d).format('hh:mm A');  
    //       }
           
    //         $('#timepicker_2').timepicker({
    //             minuteStep:5,
    //             snapToStep:true,
    //             defaultTime:current_time2,
    //             forceRoundTime: true 
    //         });
    //       // $('#timepicker_2').val(current_time2);        
    //       // 

    // });
    $('modal').modal({
        keyboard: true
    });
    $(".selectpicker").selectpicker();
    $(function () {
        $("body").tooltip({
            selector: '[data-toggle="tooltip"]',
            container: 'body'
        });
    });
})(window.jQuery);