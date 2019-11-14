/*************************************************************** 

@  

@	From Button JS 

@ 

/**************************************************************/  
$(document).ready(function() {   
	$('#buy_now_toggle').click(function() {

		$('#frombutton_buy .frombutton_buy_content').slideToggle(); 

		$('#frombutton_buy .affiliate_content').slideToggle(); 

		$('#frombutton_buy .buy_now_clear').slideToggle(); 

	});

	

	var overlay = $('<div id="overlay"></div>'); 
	$('.x').click(function(){
		$('.popup').hide();
		overlay.appendTo(document.body).remove();
		return false;
	});

	$('.write-a-review').click(function(){
		overlay.show();
		overlay.appendTo(document.body);
		$('.popup').show();
		return false;
	});

	

	$('.frombutton_reviews_input_range').change(function() {  

		var myID = $(this).attr('id');  

		$('#'+myID+'_value').val($(this).val() );

	});
});





function FrombuttonSurvey(){  

	var faults = $('input').filter(function() { 

		return $(this).data('required') && $(this).val() === "";

	}).addClass('frombuttont_reviews_required');  

	if(faults.length) 

		return false;    

	else

		return(true);

} 

