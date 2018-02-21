jQuery( document ).ready(function($){
    
    do_resize();
    
    $( window ).resize(function() {
        do_resize();
        
    });
    
    $('.sc_team_single_disabled').click( function (e) {
        
       e.preventDefault();
        
    });

    function do_resize() {
        var member_height = $('#sc_our_team.grid .sc_team_member').width();
        $('#sc_our_team.grid .sc_team_member .sc_team_member_inner').each(function(){
            $(this).css({
                height: member_height
            });
        });    

        var member_height = $('#sc_our_team.grid_circles .sc_team_member').width();
        $('#sc_our_team.grid_circles .sc_team_member').each(function(){
            $(this).css({
                height: member_height
            });
        });    

        var member_height = $('#sc_our_team.grid_circles2 .sc_team_member').width();
        $('#sc_our_team.grid_circles2 .sc_team_member').each(function(){
            $(this).css({
                height: member_height
            });
        });          
    }

  
    
    $('#sc_our_team_lightbox .sc_team_icon-close').click( function( event ) {
        
        if( $('#sc_our_team_lightbox').hasClass('show') ){
            
            $('.sc_our_team_lightbox').slideUp(300, function(){
                
                $('#sc_our_team_lightbox').fadeOut(300);
                
            });
            $('#sc_our_team_lightbox').removeClass('show');
            
        }	
        
    });
                
    $('.sc_our_team_panel .sc-right-panel .sc_team_icon-close').click( function () {

        if( $('#sc_our_team_panel').hasClass('show') ){

            $('.sc_our_team_panel').removeClass('slidein');

            $('#sc_our_team_panel').delay(450).fadeOut(300);

            $('#sc_our_team_panel').removeClass('show');
        }            
    });
        
    
    
    $('.sc_team_single_popup').click( function(e){
        
        var item = null;
        
        if( $(this).hasClass('sc_team_member') ){
            item = $(this);
        }else{
            item = $(this).parents('.sc_team_member');
        }
        
        build_popup( item );
        e.stopPropagation();
        e.preventDefault();
        
    });
    
    function build_popup( item ){
        
        // reset
        $('.sc_our_team_lightbox .name').html('');
        $('.sc_our_team_lightbox .skills').html('');
        $('.sc_our_team_lightbox .sc-posts').html('');
        $('.sc_our_team_lightbox .sc-content').html('');
        $('.sc_our_team_lightbox .sc_personal_quote').html('');
        $('.sc_our_team_lightbox .social').html('');
        $('.sc_our_team_lightbox .sc-tags').html('');
        $('.sc_our_team_lightbox .title').html('');
        $('.sc_our_team_lightbox .image').attr('src', '');        
        
        
        $('.sc_our_team_lightbox .name').html($('.sc_team_member_name a', item).html());
        $('.sc_our_team_lightbox .skills').html($('.sc_team_skills', item).html());
        $('.sc_our_team_lightbox .sc-posts').html($('.sc_team_posts', item).html());
        $('.sc_our_team_lightbox .sc-content').html($('.sc_team_content', item).html());
        $('.sc_our_team_lightbox .sc_personal_quote').html($('.sc_personal_quote', item).html());
        $('.sc_our_team_lightbox .social').html($('.icons', item).html());
        $('.sc_our_team_lightbox .sc-tags').html($('.sc_team_tags', item).html());
        $('.sc_our_team_lightbox .title').html($('.sc_team_member_jobtitle', item).html());
        $('.sc_our_team_lightbox .image').attr('src', $('.wp-post-image', item).attr('src'));
        

        $('#sc_our_team_lightbox').fadeIn(350, function () {
            
            $('.sc_our_team_lightbox')
                    .css('opacity', 0)
                    .slideDown('slow')
                    .animate({ opacity : 1 }, { queue : false, duration: 'slow' });
                    
            
            $('#sc_our_team_lightbox').addClass('show');
            
        });
        
    }
    
    
    $('.sc_team_single_panel').click( function(e){
        
        var item = null;
        
        if( $(this).hasClass('sc_team_member') ){
            item = $(this);
        }else{
            item = $(this).parents('.sc_team_member');
        }
        
        build_panel( item );
        e.stopPropagation();
        e.preventDefault();
        
    });    
    function build_panel( item ){
        
        // reset 
        $('.sc_our_team_panel .sc-name').html('');
        $('.sc_our_team_panel .sc-skills').html('');
        $('.sc_our_team_panel .sc_personal_quote').html('');
        $('.sc_our_team_panel .sc-content').html('');
        $('.sc_our_team_panel .sc-social').html('');
        $('.sc_our_team_panel .sc-title').html('');
        $('.sc_our_team_panel .sc-posts').html('');
        $('.sc_our_team_panel .sc-tags').html('');
        $('.sc_our_team_panel .sc-image').attr('src', '');
        
        
        $('.sc_our_team_panel .sc-name').html($('.sc_team_member_name a', item).html());
        $('.sc_our_team_panel .sc-skills').html($('.sc_team_skills', item).html());
        $('.sc_our_team_panel .sc_personal_quote').html($('.sc_personal_quote', item).html());
        $('.sc_our_team_panel .sc-content').html($('.sc_team_content', item).html());
        $('.sc_our_team_panel .sc-social').html($('.icons', item).html());
        $('.sc_our_team_panel .sc-title').html($('.sc_team_member_jobtitle', item).html());
        $('.sc_our_team_panel .sc-posts').html($('.sc_team_posts', item).html());
        $('.sc_our_team_panel .sc-tags').html($('.sc_team_tags', item).html());
        $('.sc_our_team_panel .sc-image').attr('src', $('.wp-post-image', item).attr('src'));


        $('#sc_our_team_panel').fadeIn(350, function () {


            $('.sc_our_team_panel').addClass('slidein');
            $('#sc_our_team_panel').addClass('show');

        });
        
    }    
    
    
    $('#sc_our_team .sc_team_member').hover(function(){
        $('.sc_team_member_overlay',this).stop(true,false).fadeIn(440);
        $('.wp-post-image',this).addClass('zoomIn');
        $('.sc_team_more',this).addClass('show');
        
    },function(){
       $('.sc_team_member_overlay',this).stop(true,false).fadeOut(440)       
       $('.wp-post-image',this).removeClass('zoomIn');
       $('.sc_team_more',this).removeClass('show');
       
    });

});
