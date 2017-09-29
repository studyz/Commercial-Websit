jQuery(document).ready(function($){
	"use strict";

	var kt_import_percent = 0, kt_import_percent_increase = 0, kt_import_index_request = 0;
	var kt_arr_import_request_data = new Array();
	var optionid ='';

	$(document).on('click','.kt-button-import',function(){
		$(this).closest('.options').find('.option').addClass('disable');
		$(this).closest('.option').removeClass('disable');
		$(this).closest('.option').find('.progress-wapper').show();
		var id = $(this).data('id');
		optionid = $(this).data('optionid');

		var import_demo_content = false,
			import_theme_options = false,
			import_widget = false,
			import_revslider = false,
			import_config = false;
		if($('#demo-content-'+id).is(':checked')){
			import_demo_content = true;
		}
		if($('#theme-option-'+id).is(':checked')){
			import_theme_options = true;
		}
		if($('#widget-'+id).is(':checked')){
			import_widget = true;
		}
		if($('#revslider-'+id).is(':checked')){
			import_revslider = true;
		}
		if($('#config-'+id).is(':checked')){
			import_config = true;
		}
		
		// Demo content
		if( import_demo_content ){
			var data = {'action' : 'kt_import_content','optionid':optionid};
			kt_arr_import_request_data.push( data );
		}

		if( import_theme_options ){
			kt_arr_import_request_data.push( {'action' : 'kt_import_theme_options','optionid':optionid} );
		}
		
		if( import_widget ){
			kt_arr_import_request_data.push( {'action' : 'kt_import_widget','optionid':optionid} );
		}
		
		if( import_revslider ){
			kt_arr_import_request_data.push( {'action' : 'kt_import_revslider','optionid':optionid} );
		}
		
		if( import_config ){
			kt_arr_import_request_data.push( {'action' : 'kt_import_config','optionid':optionid} );
		}
		
		var total_ajaxs = kt_arr_import_request_data.length;
		
		if( total_ajaxs == 0 ){
			return;
		}
		
		kt_import_percent_increase = (100 / total_ajaxs);
		
		kt_import_ajax_handle();
	})

	function kt_import_ajax_handle(){
		if( kt_import_index_request == kt_arr_import_request_data.length ){
			 $('#option-'+optionid).find('.progress').hide();
			 $('#option-'+optionid).find('.progress-wapper').addClass('complete');
			return;
		}
		$('#option-'+optionid+' .progress-item').find('.'+kt_arr_import_request_data[kt_import_index_request]["action"]).show();

		$.ajax({
			type: 'POST',
			url: ajaxurl,
			async: true,
			data: kt_arr_import_request_data[kt_import_index_request],
			complete: function(jqXHR, textStatus){

				$('#option-'+optionid+' .progress-item').find('.'+kt_arr_import_request_data[kt_import_index_request]["action"]).addClass('complete');
				kt_import_percent += kt_import_percent_increase;
				kt_progress_bar_handle();
				kt_import_index_request++;
				setTimeout(function(){
					kt_import_ajax_handle();
				}, 200);
			}
		});
	}

	function kt_progress_bar_handle(){

		if( kt_import_percent > 100 ){
			kt_import_percent = 100;
		}
		var progress_bar = $('#option-'+optionid).find('.progress-circle .c100');
		var class_percent ='p'+Math.ceil( kt_import_percent );
		progress_bar.addClass(class_percent);
		//progress_bar.css({'width': Math.ceil( kt_import_percent ) + '%'});

		progress_bar.find('.percent').html( Math.ceil( kt_import_percent ) + '%');
	}
});