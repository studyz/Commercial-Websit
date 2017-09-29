(function($){
    "use strict"; // Start of use strict
    var easyzoom_api = null;
    
    /* ---------------------------------------------
     Woocommerce Quantily
     --------------------------------------------- */
     function digitalworld_woo_quantily(){
        $('body').on('click','.quantity .quantity-plus',function(){
            var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                val_qty  = parseInt(obj_qty.val()),
                min_qty  = parseInt(obj_qty.data('min')),
                max_qty  = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty = val_qty + step_qty;
            if(max_qty && val_qty > max_qty){ val_qty = max_qty; }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });

        $('body').on('click','.quantity .quantity-minus',function(){
            var obj_qty  = $(this).closest('.quantity').find('input.qty'), 
                val_qty  = parseInt(obj_qty.val()),
                min_qty  = parseInt(obj_qty.data('min')),
                max_qty  = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty = val_qty - step_qty;
            if(min_qty && val_qty < min_qty){ val_qty = min_qty; }
            if(!min_qty && val_qty < 0){ val_qty = 0; }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });
    }
    /* ---------------------------------------------
     MENU REPONSIIVE
     --------------------------------------------- */
     function digitalworld_init_menu_reposive(){
          var kt_is_mobile = (Modernizr.touch) ? true : false;
          if(kt_is_mobile === true){
            $(document).on('click', '.digitalworld-nav .menu-item-has-children >.toggle-submenu', function(e){
              var licurrent = $(this).closest('li');
              var liItem = $('.digitalworld-nav .menu-item-has-children');
              if ( !licurrent.hasClass('show-submenu') ) {
                liItem.removeClass('show-submenu');
                licurrent.parents().each(function (){
                    if($(this).hasClass('menu-item-has-children')){
                     $(this).addClass('show-submenu');   
                    }
                      if($(this).hasClass('main-menu')){
                          return false;
                      }
                })
                licurrent.addClass('show-submenu');
                // Close all child submenu if parent submenu is closed
                if ( !licurrent.hasClass('show-submenu') ) {
                  licurrent.find('li').removeClass('show-submenu');
                }
                  return false;
                  e.preventDefault();
              }else{
                  licurrent.removeClass('show-submenu');
                  // var href = $this.attr('href');
                  // if ( $.trim( href ) == '' || $.trim( href ) == '#' ) {
                  //     licurrent.toggleClass('show-submenu');
                  // }
                  // else{
                  //     window.location = href;
                  // }
              }
              // Close all child submenu if parent submenu is closed
              if ( !licurrent.hasClass('show-submenu') ) {
                  //licurrent.find('li').removeClass('show-submenu');
              }
              e.stopPropagation();
          });
        $(document).on('click', function(e){
              var target = $( e.target );
              if ( !target.closest('.show-submenu').length || !target.closest('.digitalworld-nav').length ) {
                  $('.show-submenu').removeClass('show-submenu');
              }
          }); 
          // On Desktop
          }else{
              $(document).on('mousemove','.digitalworld-nav .menu-item-has-children',function(){
                  $(this).addClass('show-submenu');
                  if( $(this).closest('.digitalworld-nav').hasClass('main-menu')){
                      $('body').addClass('is-show-menu');
                  }
              })

              $(document).on('mouseout','.digitalworld-nav .menu-item-has-children',function(){
                  $(this).removeClass('show-submenu');
                  $('body').removeClass('is-show-menu');
              })
          }
     }

    function digitalworld_clone_main_menu(){
        if( $('#header .clone-main-menu').length > 0 && $('#box-mobile-menu').length >0){
            $( "#header .clone-main-menu" ).clone().appendTo( "#box-mobile-menu .box-inner" );
            $('#box-mobile-menu').find('.clone-main-menu').removeAttr('id');
        }
    }
    /* ---------------------------------------------
     Resize mega menu
     --------------------------------------------- */
     function digitalworld_resizeMegamenu(){
        var window_size = jQuery('body').innerWidth();
        window_size += digitalworld_get_scrollbar_width();
        if( window_size > 767 ){
          if( $('#header .main-menu-wapper').length >0){
            var container = $('#header .main-menu-wapper');
            if( container!= 'undefined'){
              var container_width = 0;
              container_width = container.innerWidth();
              var container_offset = container.offset();
              setTimeout(function(){
                  $('.main-menu .item-megamenu').each(function(index,element){
                      $(element).children('.megamenu').css({'max-width':container_width+'px'});
                      var sub_menu_width = $(element).children('.megamenu').outerWidth();
                      var item_width = $(element).outerWidth();
                      $(element).children('.megamenu').css({'left':'-'+(sub_menu_width/2 - item_width/2)+'px'});
                      var container_left = container_offset.left;
                      var container_right = (container_left + container_width);
                      var item_left = $(element).offset().left;
                      var overflow_left = (sub_menu_width/2 > (item_left - container_left));
                      var overflow_right = ((sub_menu_width/2 + item_left) > container_right);
                      if( overflow_left ){
                        var left = (item_left - container_left);
                        $(element).children('.megamenu').css({'left':-left+'px'});
                      }
                      if( overflow_right && !overflow_left ){
                        var left = (item_left - container_left);
                        left = left - ( container_width - sub_menu_width );
                        $(element).children('.megamenu').css({'left':-left+'px'});
                      }
                  })
              },100);
            }
          }
        }
     }
     function digitalworld_get_scrollbar_width() {
        var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
            $outer = jQuery('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner = $inner[0],
            outer = $outer[0];
        jQuery('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    }
    
    function digitalworld_sticky_menu(){
        var h = $(window).scrollTop();
        var max_h = $('#header').height();
        var vertical_menu_height = 0;
        if( $('.block-nav-categori' ).length > 0 ){
          vertical_menu_height = $('.block-nav-categori .block-content').innerHeight();
        }
        if( h > (max_h + vertical_menu_height)){
            //$('.mid-header ').sticky({ topSpacing: 0 });
        }else{
            //$('.mid-header ').unstick();
        }
    }
    /**==============================
    Auto width Vertical menu
    ===============================**/
    function digitalworld_auto_width_vertical_menu(){
        setTimeout(function(){
            var full_width = parseInt($('.container').innerWidth()) - 30;
            var menu_width = parseInt($('.verticalmenu-content').actual('width'));

            var w = (full_width - menu_width);
            $('.verticalmenu-content').find('.megamenu').each(function(){
                $(this).css('max-width',w+'px');
            });
        },100)

    }
    function digitalworld_show_other_item_vertical_menu(){
        if( $( '.block-nav-categori' ).length > 0 ){

            $('.block-nav-categori').each(function(){
                var all_item = 0;
                var limit_item = $(this).data('items')-1;
                all_item = $(this).find('.vertical-menu>li').length;

                if( all_item > (limit_item + 1) ){
                    $(this).addClass('show-button-all');
                }
                $(this).find('.vertical-menu>li').each(function(i){
                    all_item = all_item + 1;
                    if(i > limit_item){
                        $(this).addClass('link-orther');
                    }
                })
            })
        }
    }
    
    //EQUAL ELEM
    function better_equal_elems() {
        setTimeout(function(){
            $('.equal-container').each(function () {
                var $this = $(this);
                if ($this.find('.equal-elem').length) {
                    $this.find('.equal-elem').css({
                        'height': 'auto'
                    });
                    var elem_height = 0;
                    $this.find('.equal-elem').each(function () {
                        var this_elem_h = $(this).height();
                        if (elem_height < this_elem_h) {
                            elem_height = this_elem_h;
                        }
                    });
                    $this.find('.equal-elem').height(elem_height);
                }
            });
        }, 3000);
    }

    /* ---------------------------------------------
     TAB EFFECT
     --------------------------------------------- */
    function digitalworld_tab_fade_effect(){
        // effect click
        $(document).on('click','.digitalworld-tabs .tabs-link a',function(){
            var tab_id = $(this).attr('href');
            var tab_animated = $(this).data('animate');

            tab_animated = ( tab_animated == undefined || tab_animated =="" ) ? '' : tab_animated;
            if( tab_animated ==""){
                return  false;
            }

            $(tab_id).find('.product-list-grid  .product-item, .owl-item.active ').each(function(i){

                var t = $(this);
                var style = $(this).attr("style");
                style     = ( style == undefined ) ? '' : style;
                var delay  = i * 400;
                t.attr("style", style +
                    ";-webkit-animation-delay:" + delay + "ms;"
                    + "-moz-animation-delay:" + delay + "ms;"
                    + "-o-animation-delay:" + delay + "ms;"
                    + "animation-delay:" + delay + "ms;"
                ).addClass(tab_animated+' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                    t.removeClass(tab_animated+' animated');
                    t.attr("style", style);
                });
            })
        })
    }

    function digitalworld_innit_carousel(){
        $(".owl-carousel").each(function(index, el) {
            var config = $(this).data();
            config.navText = ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>'];
            var animateOut = $(this).data('animateout');
            var animateIn  = $(this).data('animatein');
            var slidespeed = $(this).data('slidespeed');
            if(typeof animateOut != 'undefined' ){
                config.animateOut = animateOut;
            }
            if(typeof animateIn != 'undefined' ){
                config.animateIn = animateIn;
            }
            if(typeof (slidespeed) != 'undefined' ){
                config.smartSpeed = slidespeed;
            }

            if( $('body').hasClass('rtl')){
                config.rtl = true;
            }
            if( digitalworld_fontend_global_script.digitalworld_enable_lazy == '1'){
                config.lazyLoad = true;
            }


            var owl = $(this);
            owl.on('initialized.owl.carousel',function(event){
                var total_active = owl.find('.owl-item.active').length;
                var i            = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function(){
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });

                }, 100);
            })
            owl.on('refreshed.owl.carousel',function(event){
                var total_active = owl.find('.owl-item.active').length;
                var i            = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function(){
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });

                }, 100);
            })
            owl.on('change.owl.carousel',function(event){
                var total_active = owl.find('.owl-item.active').length;
                var i            = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function(){
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if (i == 1) {
                            $(this).addClass('item-first');
                        }
                        if (i == total_active) {
                            $(this).addClass('item-last');
                        }
                    });

                }, 100);


            })
            owl.owlCarousel(config);

        });
    }

    function better_equal_tabs() {
        setTimeout(function(){
            $('.digitalworld-tabs').each(function () {
                var $this = $(this);
                if ($this.find('.tab-panel').length) {
                    $this.find('.tab-panel').css({
                        'height': 'auto'
                    });
                    var elem_height = 0;
                    $this.find('.tab-panel').each(function () {
                        var this_elem_h = $(this).height();
                        if (elem_height < this_elem_h) {
                            elem_height = this_elem_h;
                        }
                    });
                    $this.find('.tab-panel').height(elem_height);
                }
            });
        }, 4000);
    }

    /* ---------------------------------------------
     COUNTDOWN
     --------------------------------------------- */
    function digitalworld__countdown(){
        if($('.digitalworld-countdown').length >0){
            var labels = ['Years', 'Months', 'Weeks', 'Days', 'Hrs', 'Mins', 'Secs'];
            var layout = '<span class="box-count day"><span class="number">{dnn}</span> <span class="text">Days</span></span><span class="dot">:</span><span class="box-count hrs"><span class="number">{hnn}</span> <span class="text">Hrs</span></span><span class="dot">:</span><span class="box-count min"><span class="number">{mnn}</span> <span class="text">Mins</span></span><span class="dot">:</span><span class="box-count secs"><span class="number">{snn}</span> <span class="text">Secs</span></span>';
            $('.digitalworld-countdown').each(function() {
                var austDay = new Date($(this).data('y'),$(this).data('m') - 1,$(this).data('d'),$(this).data('h'),$(this).data('i'),$(this).data('s'));
                $(this).countdown({
                    until: austDay,
                    labels: labels,
                    layout: layout
                });
            });
        }
    }
    function digitalworld_back_to_top() {
        var h = $(window).scrollTop();

        if (h > 100) {
            $('.backtotop').addClass('show');
        }
        else {
            $('.backtotop').removeClass('show');
        }
    };
    /* ---------------------------------------------
     Init popup
     --------------------------------------------- */
    function digitalworld_init_popup(){
        if( digitalworld_fontend_global_script.digitalworld_enable_popup_mobile == 0 ){
            if($(window).width() + digitalworld_get_scrollbar_width() < 768){
                return false;
            }
        }
        var disabled_popup_by_user =  getCookie('digitalworld_disabled_popup_by_user');
        if( disabled_popup_by_user == 'true'){

        }else{
            if($('body').hasClass('home') && digitalworld_fontend_global_script.digitalworld_enable_popup && digitalworld_fontend_global_script.digitalworld_enable_popup =='1'){
                setTimeout(function(){
                    $('#popup-newsletter').modal({
                        keyboard: false
                    })
                }, digitalworld_fontend_global_script.digitalworld_popup_delay_time);

            }
        }
    }
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    function digitalworld_google_maps() {
        if( $('.digitalworld-google-maps').length <= 0 ){
            return;
        }
        $('.digitalworld-google-maps').each(function(){
            var $this = $(this),
                $id = $this.attr('id'),
                $title_maps = $this.attr('data-title_maps'),
                $phone = $this.attr('data-phone'),
                $email = $this.attr('data-email'),
                $zoom = parseInt($this.attr('data-zoom')),
                $latitude = $this.attr('data-latitude'),
                $longitude = $this.attr('data-longitude'),
                $address = $this.attr('data-address'),
                $map_type = $this.attr('data-map-type'),
                $pin_icon = $this.attr('data-pin-icon'),
                $modify_coloring = $this.attr('data-modify-coloring') === "true" ? true : false,
                $saturation = $this.attr('data-saturation'),
                $hue = $this.attr('data-hue'),
                $map_style = $this.data('map-style'),
                $styles;

            if ($modify_coloring == true) {
                var $styles = [
                    {
                        stylers: [
                            { hue: $hue },
                            { invert_lightness: false },
                            { saturation: $saturation  },
                            { lightness: 1 },
                            {
                                featureType: "landscape.man_made",
                                stylers: [{
                                    visibility: "on"
                                }]
                            }
                        ]
                    },{
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [
                            { color: '#46bcec' }
                        ]
                    }
                ];
            }
            var map;
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: $zoom,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                draggable: true,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId[$map_type],
                styles: $styles
            };

            map = new google.maps.Map(document.getElementById($id), mapOptions);
            map.setTilt(45);

            // Multiple Markers
            var markers = [];
            var infoWindowContent = [];

            if ($latitude != '' && $longitude != '') {
                markers[0] = [$address, $latitude, $longitude];
                infoWindowContent[0] = [$address];
            }

            var infoWindow = new google.maps.InfoWindow(), marker, i;

            for (i = 0; i < markers.length; i++)
            {
                var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[i][0],
                    icon: $pin_icon
                });
                if ( $map_style == '1' )
                {

                    if(infoWindowContent[i][0].length > 1) {
                        infoWindow.setContent(
                            '<div style="background-color:#fff; padding:5px; width:350px;line-height: 25px" class="digitalworld-map-info">'+
                            '<h4>'+ $title_maps +'</h4>'+
                            '<div><i class="fa fa-map-marker"></i>&nbsp;'+ $address +'</div>'+
                            '<div><i class="fa fa-phone"></i>&nbsp;'+ $phone +'</div>'+
                            '<div><i class="fa fa-envelope"></i><a href="mailto:'+$email+'">&nbsp;'+$email+'</a></div> '+
                            '</div>'
                        );
                    }

                    infoWindow.open(map, marker);

                }
                if ( $map_style == '2' )
                {
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            if(infoWindowContent[i][0].length > 1) {
                                infoWindow.setContent(
                                    '<div style="background-color:#fff; padding:5px; width:350px;line-height: 25px" class="digitalworld-map-info">'+
                                    '<h4>'+ $title_maps +'</h4>'+
                                    '<div><i class="fa fa-map-marker"></i>&nbsp;'+ $address +'</div>'+
                                    '<div><i class="fa fa-phone"></i>&nbsp;'+ $phone +'</div>'+
                                    '<div><i class="fa fa-envelope"></i><a href="mailto:'+$email+'">&nbsp;'+$email+'</a></div> '+
                                    '</div>'
                                );
                            }

                            infoWindow.open(map, marker);
                        }
                    })(marker, i));
                }

                map.fitBounds(bounds);
            }

            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                this.setZoom($zoom);
                google.maps.event.removeListener(boundsListener);
            });
        });
    }

    /* ---------------------------------------------
     Scripts initialization
     --------------------------------------------- */
    $(window).load(function() {
      digitalworld_innit_carousel();
      digitalworld_clone_main_menu();
      digitalworld_resizeMegamenu();
      better_equal_elems();
      better_equal_tabs();
        digitalworld_init_lazy_load();
    });
    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */
    $(window).on("resize", function() {
        digitalworld_init_menu_reposive();
        digitalworld_resizeMegamenu();
        digitalworld_innit_carousel();
        digitalworld_auto_width_vertical_menu();
        better_equal_elems();
        short_product_name();

    });
    /* ---------------------------------------------
     Scripts scroll
     --------------------------------------------- */
    $(window).scroll(function(){
        digitalworld_back_to_top();
    });

    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function() {
        digitalworld_init_menu_reposive();
        digitalworld_resizeMegamenu();
        digitalworld_woo_quantily();
        digitalworld_tab_fade_effect();
        digitalworld_auto_width_vertical_menu();
        digitalworld_show_other_item_vertical_menu();
        digitalworld__countdown();
        digitalworld_click_open_compare_table();
        digitalworld_simple_product_gallery();
        digitalworld_init_popup();
        digitalworld_google_maps();

        $(document).on('change','.digitalworld_disabled_popup_by_user',function(){
            if( $(this).is(":checked")){
                setCookie("digitalworld_disabled_popup_by_user", 'true', 7);
            }else{
                setCookie("digitalworld_disabled_popup_by_user", '', 0);
            }
        })
        /*  [ All Categorie ]
         - - - - - - - - - - - - - - - - - - - - */
        $(document).on('click', '.open-cate', function () {
            $(this).closest('.block-nav-categori').find('li.link-orther').each(function () {
                $(this).slideDown();
            });
            var closetext = $(this).data('closetext');
            $(this).addClass('close-cate').removeClass('open-cate').html(closetext);
        })
        /* Close Categorie */
        $(document).on('click', '.close-cate', function () {
            $(this).closest('.block-nav-categori').find('li.link-orther').each(function () {
                $(this).slideUp();
            });
            var alltext = $(this).data('alltext');
            $(this).addClass('open-cate').removeClass('close-cate').html(alltext);
            return false;
        })
        $(".block-nav-categori .block-title").on('click', function () {
            $(this).toggleClass('active');
            $(this).parent().toggleClass('has-open');
            $("body").toggleClass("categori-open");
        });


        if( $('.toolbar-products select').length > 0 ){
          $('.toolbar-products select').chosen();
        }
        if( $('.variations_form select').length > 0 ){
            $('.variations_form select').chosen();
        }

        if( $('.categori-search-option').length > 0 ){
            $('.categori-search-option').chosen();
        }

        /*Open search form */
        $(document).on('click','.search-icon',function(){
            $(this).toggleClass('open');
            $(this).closest('.header').find('.block-search').toggleClass('open');
            return false;
        })

        // View grid list product
        $(document).on('click','.display-mode',function(){
            var mode = $(this).data('mode');
            var current_url  = window.location.href;
            current_url = current_url.replace("#", "");
            var data = {
                action: 'fronted_set_products_view_style',
                security : digitalworld_ajax_fontend.security,
                mode: mode
            };
            $.post(digitalworld_ajax_fontend.ajaxurl, data, function(response){
                window.location.replace(current_url);
            })
            return false;
        });
        /*Close widget */
        $(document).on('click','.widgettitle .arow',function(){
          $(this).closest('.widget').toggleClass('widget-close');
        })

        // Category product
        $(document).on('click','.widget_product_categories li .arow',function(){
          var paerent = $(this).closest('li');
          var t = $(this);
          //paerent.find('a').removeClass('open');
          //paerent.find('ul').removeClass('open');
          paerent.toggleClass('open');
          if(paerent.children('ul').length > 0){
              //$(this).toggleClass('open');
              $(this).closest('li').children('ul').slideToggle();
              return false;
          }
        });

        // ZOOM
        
        if( $('.easyzoom').length > 0 ){
            var $easyzoom = $('.easyzoom').easyZoom();
            // Setup thumbnails example
            easyzoom_api = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');
            $('.thumbnails').on('click', 'a', function(e) {
                $(this).closest('.thumbnails').find('a').each(function(){
                    $(this).removeClass('active');
                })
                $(this).addClass('active');
                $(".digitalworld-easyzoom").find('.zoom img').attr({'srcset':''});
                var $this = $(this);
                e.preventDefault();
                // Use EasyZoom's `swap` method
                easyzoom_api.swap($this.data('standard'), $this.attr('href'));
            });
            var default_zoom = $(".digitalworld-easyzoom").find('.zoom').attr('href');
            var default_image = $(".digitalworld-easyzoom").find('.zoom img').attr('src');
            $(document).on('found_variation', 'form.variations_form', function (event, variation) {

                var new_zoom = variation.image_link ? variation.image_link : default_zoom;
                var new_image = variation.image_src ? variation.image_src : default_zoom;
                easyzoom_api.swap(new_image, new_zoom);
            }).on('reset_image', function (event) {
                easyzoom_api.swap(default_image, default_zoom);
            });
        }

        // BOX MOBILE MENU
        $(document).on('click','.mobile-navigation',function(){
            $('#box-mobile-menu').addClass('open');
            return false;
        });
        // Close box menu
        $(document).on('click','#box-mobile-menu .close-menu',function(){
            $('#box-mobile-menu').removeClass('open');
            return false;
        });

        /* Block search */
        $(document).on('click','.search-icon-mobile',function () {
            $('#block-search-mobile').addClass('open');
            $('body').addClass('open-block-serach');
            return false;
        });
        $(document).on('click','.close-block-serach',function () {
            $('#block-search-mobile').removeClass('open');
            $('body').removeClass('open-block-serach');
            return false;
        });
        // Scroll top
        $(document).on('click','.scroll_top',function(){
            $('body,html').animate({scrollTop:0},400);
            return false;
        });

        //
        if( digitalworld_fontend_global_script.digitalworld_enable_sticky_menu == 1){
            var window_size = jQuery('body').innerWidth();
            window_size += digitalworld_get_scrollbar_width();
            if( window_size > 991 ){
                if( $('.header-sticky').length > 0 ){
                    $('.header-sticky').sticky({topSpacing:0});
                }
            }

        }
        //BACK TO TOP
        $('a.backtotop,a.back_to_top').on('click', function () {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });

    });

    // Reinit some important things after ajax
    $(document).ajaxComplete(function (event, xhr, settings) {
        
    });
    
    function digitalworld_click_open_compare_table(){
        $('#digitalworld_open_compare_table').on('click', function(e){
            e.preventDefault();
            $('body').trigger('yith_woocompare_open_popup', { response: digitalworld_yith_add_query_arg('action', yith_woocompare.actionview) + '&iframe=true' });
        });
    }
    
    function digitalworld_yith_add_query_arg(key, value){
        key = escape(key); value = escape(value);

        var s = document.location.search;
        var kvp = key+"="+value;

        var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

        s = s.replace(r,"$1"+kvp);

        if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

        //again, do what you will here
        return s;
    }
    
    function digitalworld_simple_product_gallery(){
        $('.product-innfo .gallery_single_img > img').on('click', function(e){
            $(this).closest('.product_gallery').find('.gallery_single_img').removeClass('selected');
            $(this).closest('.gallery_single_img').addClass('selected');
            e.preventDefault();
            var that = $(this);
            var img_url = that.attr( "data-big_img" );
            var parent_node = that.closest(".product-inner");
            var destince = parent_node.find( ".product-thumb .thumb-inner .thumb-link > img" );
            
            if( destince.length > 0 && img_url != '' ){
                destince.attr( 'src', img_url );
            }
        
            
        });
    }
    
    function digitalworld_init_lazy_load(){
        if( $("img.lazy") .length  > 0 ){
            $("img.lazy").lazyload(
                {
                    effect : "fadeIn"
                }
            );
        }
    }
    function digitalworld_ajax_lazy_load(){
        if( $('img.lazy').length > 0 ){
            $('img.lazy').each(function(){
                if( $(this).data('original') ){
                    $(this).attr('src', $(this).data('original'));
                }
            });
        }
    }


    $(document).ajaxComplete(function (event, xhr, settings) {
        digitalworld_innit_carousel();
        better_equal_elems();
        digitalworld_tab_fade_effect();
    });
    /* ---------------------------------------------
     Ajax Tab
     --------------------------------------------- */

    $(document).on('click','[data-ajax="1"]',function () {
        if( !$(this).hasClass('loaded') ){
            var atts    = $(this).data('shortcode');
            var decode  = atob(atts);
            var tab_id  = $(this).attr('href');

            var t = $(this);
            var data = {
                action      :   'digitalworld_tab_product_via_ajax',
                security    :   digitalworld_ajax_fontend.security,
                decode      :   decode,
            };
            $(tab_id).closest('.tab-container').append('<div class="cssload-wapper"><div class="cssload-square"><div class="cssload-square-part cssload-square-green"></div><div class="cssload-square-part cssload-square-pink"></div><div class="cssload-square-blend"></div></div></div>');
            $.post(digitalworld_ajax_fontend.ajaxurl, data, function(response){
                var items = $( '' + response['html'] + '' );
                $(tab_id).html(items);
                digitalworld_innit_carousel();
                digitalworld_ajax_lazy_load();
                digitalworld_tab_fade_effect();
                $(tab_id).closest('.tab-container').find('.cssload-wapper').remove();
                t.addClass('loaded');


            });
        }
    });

    $(window).bind("load", function() {

    });

})(jQuery); // End of use strict
