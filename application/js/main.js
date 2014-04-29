
$(function() {
                
    var preventDefault = function(e) {
        e.preventDefault();
        e.stopPropagation();
    }
                
    var map = null;
    google.maps.visualRefresh = true;
    var styledMap = new google.maps.StyledMapType(psMapStyles, {
        name: "PocketSail"
    });
                
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: new google.maps.LatLng(43.5, 17),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoomControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        streetViewControl: false,
        panControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
        }
    });
                
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
                
    var infoMap = null;
    google.maps.visualRefresh = true;
    var styledMap = new google.maps.StyledMapType(psMapStyles, {
        name: "PocketSail"
    });
                
    infoMap = new google.maps.Map(document.getElementById('infoMap'), {
        zoom: 8,
        center: new google.maps.LatLng(43.5, 17),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoomControlOptions: {
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        streetViewControl: false,
        panControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
        }
    });
                
    infoMap.mapTypes.set('map_style', styledMap);
    infoMap.setMapTypeId('map_style');
                
    $('#left-pane').mCustomScrollbar({
        mouseWheelPixels: 40,
        autoHideScrollbar: true,
        scrollInertia: 0,
        advanced:{
            updateOnContentResize: true
        }
    });
                
    $('#left-pane').hover(function() {
        $(window).bind('DOMMouseScroll.myNameSpace', preventDefault);
        $(document).bind('mousewheel.myNameSpace', preventDefault);
    }, function() {
        $(window).unbind('DOMMouseScroll.myNameSpace', preventDefault);
        $(document).unbind('mousewheel.myNameSpace', preventDefault);
    });
                
    var on = true;
    $('#onof').click(function(e) {
                    
        // Leave here!
        e.preventDefault();
                                          
        //        if (on) {
        //            $('#searchres').show();
        //            $('#left-pane').animate({
        //                top: 500
        //            }, 500, function() {
        //                //$(this).css('bottom', 0);
        //                $(this).mCustomScrollbar("update");
        //            });
        //            on = false;
        //        }
        //        else {
        //            $('#searchres').hide();
        //            $('#left-pane').animate({
        //                top: 96
        //            }, 500, function() {
        //                //$(this).css('bottom', 0);
        //                $(this).mCustomScrollbar("update");
        //            });
        //            on = true;
        //        }

        if (on) {
            $('#subMenu').animate({
                height: 'toggle'
            });
            on = false;
        }
        else {
            $('#subMenu').animate({
                height: 'toggle'
            });
            on = true;
        }
    });
                
    $(window).scroll(function(){
        $('#map').css('left','-'+$(window).scrollLeft()+'px');
    });
                
    $('#search-input').focus(function() {
        $(this).css('border-color', '#f9ab30');
    });
                
    $('#search-input').blur(function() {
        $(this).css('border-color', '#ffffff');
    });
                
    $('a[href^=#]').live('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        href = href.substring(1, href.length);
        $(document).scrollTop($('#' + href).position().top-73);
        $('#' + href).closest('.info-block').css('background-color', '#ffdf88');
        $('#' + href).closest('.info-block').animate({
            backgroundColor: '#ffffff'
        }, 800);
    });
    
    /**
     * Info view functions
     */

    var activeID = null;
    var activeMode = '';

    $('.card').click(function() {
        var ID = $(this).attr('poi');
        load_info_view(ID, 'summary');
        activeID = ID;
        $('#infoMenu a').removeClass('active');
        $('#infoMenu a[href=summary]').addClass('active');
    });
    
    function load_info_view(ID, mode, success) {
        Service.place_info(ID, mode, {
            success: function(res) {
                var html = $(res);
                var content = html.find('#content');
                var contact = html.find('#contact');
                var menu = html.find('#menu');
                $('#infoContent').html(content);
                $('#infoContact').html(contact);
                if (menu.length > 0) {
                    $('#navMenu').html(menu);
                }
                show_info_view();
                success !== undefined ? success() : null;
            }
        });
    }
    
    function show_info_view() {
        $('#veil').show();
        $('#infoLeft').show();
        $('#infoRight').show();
        $('textarea').autosize();
        google.maps.event.trigger(infoMap, 'resize');
    }

    function hide_info_view() {
        $('#infoLeft').hide();
        $('#infoRight').hide();
        $('#veil').hide();
    }
    
    $('#infoMenu a').click(function(e) {        
        
        e.preventDefault();
        var mode = $(this).attr('href');
        var clicked = $(this);
        
        load_info_view(activeID, mode, function() {
            // Set correct button active
            $('#infoMenu a').removeClass('active');
            clicked.addClass('active');
            // Show nav-menu if relevant
            if (mode === 'fullview' && activeMode !== 'fullview') {
                $('#navMenu').animate({
                    height: 'toggle'
                });
            }
            else if(mode !== 'fullview' && activeMode === 'fullview') {
                $('#navMenu').animate({
                    height: 'toggle'
                });
            }
            // Show edit menu if relevant
            if (mode === 'edit' && activeMode !== 'edit') {
                $('#editMenu').animate({
                    height: 'toggle'
                });
            }
            else if(mode !== 'edit' && activeMode === 'edit') {
                $('#editMenu').animate({
                    height: 'toggle'
                });
            }
            activeMode = mode;
        });
    });
});

