/*************************************************************** 
@  
@	From Button JS 
@ 
/**************************************************************/
jQuery(document).ready(function () {
    frombutton_required();
    frombutton_show_popup();
    affiliate_count();
    frombutton_tabs();
    affiliate_icon();
});

/*------------------------------------*\
	Affiliate Icon
\*------------------------------------*/

function affiliate_icon() {
    jQuery('.frombutton_affiliate_icon').focus(function () {
        var name = jQuery(this).attr('name');
        affiliate_upload(name);
    });

}

/*------------------------------------*\
	Affiliate Icon
\*------------------------------------*/

function affiliate_upload(formfield) {
    jQuery('html').addClass('Image');
    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
        if (formfield) {
            fileurl = jQuery('img', html).attr('src');
            jQuery('#' + formfield).val(fileurl);
            tb_remove();
            jQuery('html').removeClass('Image');
        }
    };
    return false;
}


function frombutton_show_popup() {
    jQuery('.show-popup').click(function (event) {
        event.preventDefault();
        jQuery('.overlay-bg').show();
        jQuery('.overlay-bg').css('z-index', '1002');
    });
    jQuery('.close-btn').click(function () {
        jQuery('.overlay-bg').hide();
    });
}

function frombutton_farbtastic(element) {
    //jQuery.farbtastic('#color_picker_color1').linkTo('#frombutton_farbtastic_' + element);
    // jQuery('#color_picker_color1').farbtastic('#frombutton_farbtastic_' + element);
    
 	 jQuery.farbtastic('#color_picker_color1').linkTo(function() { 
	 jQuery('#frombutton_farbtastic_' + element).val(jQuery.farbtastic('#color_picker_color1').color); 
	 jQuery('#color_schema_text_' + element).css("background-color",jQuery.farbtastic('#color_picker_color1').color);
	 }); 
	jQuery('#color_picker_color1').fadeIn();
}

function frombutton_farbtastic2(element) {
    //jQuery.farbtastic('#color_picker_color1').linkTo('#frombutton_farbtastic_' + element);
    // jQuery('#color_picker_color1').farbtastic('#frombutton_farbtastic_' + element);
    
	jQuery.farbtastic('#color_picker_color1').linkTo(function() { 
	 jQuery('#frombutton_farbtastic_' + element).val(jQuery.farbtastic('#color_picker_color1').color); 
	 jQuery('#color_schema_text_' + element).css("background-color",jQuery.farbtastic('#color_picker_color1').color);
	 }); 
	jQuery('#color_picker_color1').fadeIn();
}

function frombutton_required() {

    jQuery('#post').submit(function () {
        var count = jQuery('#addons_meta_box_reviews_83542 .review').size();
        if (count > 0) { 
            var frombutton = true;   
            jQuery('#frombutton_custom_meta_post .frombutton_input').each(function () {
                if (jQuery(this).val() == '') {
                    frombutton = false;
                    jQuery(this).addClass('frombutton_required');
                    jQuery(this).focus();
                    jQuery('#publishing-action .spinner').hide();
                }
            })
        }
        var count = jQuery('#addons_meta_box_affiliate_price_83542 .affiliate').size();
        if (count > 0) {
            var frombutton = true;  
            jQuery('#frombutton_custom_meta_post .frombutton_input').each(function () {
                if (jQuery(this).val() == '') { 
                    frombutton = false;
                    jQuery(this).addClass('frombutton_required');
                    jQuery(this).focus();
                    jQuery('#publishing-action .spinner').hide();
                }
            })
        }
        jQuery('#publish').removeClass('button-primary-disabled');
        return (frombutton);

    });
}

/*------------------------------------*\
	Affiliate Count
\*------------------------------------*/

function affiliate_count() {
    var count = jQuery('#addons_meta_box_affiliate_price_83542 .affiliate').size();
    if (count == 0) {
        jQuery('#affiliate_delete_all').attr("disabled", true);
    } else {
        jQuery('#affiliate_delete_all').attr("disabled", false);
    }
}

/*------------------------------------*\
	Affiliate Delete
\*------------------------------------*/

function frombutton_tabs() {
    jQuery("#frombutton-tab .tab-nav ul li a").click(function () {
        jQuery("#frombutton-tab .tab-nav ul li").removeClass('active');
        jQuery(this).parent().addClass('active');
        jQuery("#frombutton-tab .tab-content .tab-panel").css('display', 'none');
        var myTab = jQuery(this).attr('name');
        jQuery("#" + myTab).css('display', 'block');
    });
}

/*------------------------------------*\
	Affiliate Delete
\*------------------------------------*/

function affiliate_delete(ReviewID) {
    if (ReviewID == 'ALL') {
        jQuery('#frombutton_custom_meta_post .affiliate').remove();
    } else {
        jQuery('#affiliate_' + ReviewID).remove();
        jQuery('#affiliate_list_div_' + ReviewID).remove();
        jQuery('#affiliate_textbox_' + ReviewID).remove();
    }
    affiliate_count();
}

function frombutton_delete(ReviewID) {
    jQuery('#frombutton_' + ReviewID + '').remove();
}
/*------------------------------------*\
	Affiliate Add
\*------------------------------------*/


function Affiliate_add(AffiliateName, AffiliateIcon, AffiliateText, AffiliateLink, AffiliatePrice) {	 
    var count = jQuery('#addons_meta_box_affiliate_price_83542 .affiliate').size();
    html = '<div id="affiliate_' + count + '" class="affiliate"><input placeholder="' + AffiliateName + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_affiliate_name_' + count + '" value="" /><input placeholder="' + AffiliateIcon + '" type="text" class="frombutton_input frombutton_ajax frombutton_upload_image_button" name="frombutton_affiliate_icon_' + count + '" value="" /><input placeholder="' + AffiliateText + '" type="text" class="frombutton_input frombutton_ajax frombutton_last" name="frombutton_affiliate_text_' + count + '" value="" /><input placeholder="' + AffiliateLink + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_affiliate_link_' + count + '" value="" /><input placeholder="' + AffiliatePrice + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_affiliate_price_' + count + '" value="" /><a class="affiliate_delete" OnClick="affiliate_delete(' + count + ')" href="javascript:void(0)">Delete Affiliate</a></div>';
    jQuery('#frombutton_html_affiliate').before(html);
    affiliate_count();
}

function add_affiliate_custom(AffiliateName, AffiliateIcon, AffiliateText, AffiliateLink, AffiliatePrice, count) {
	
	jQuery('#affiliate_textbox_' + count).remove();
	
	jQuery('#affiliate_list_'+count).remove();
	
	jQuery('#affiliate_list_div_'+count).remove();
	
    var html = '<div id="affiliate_' + count + '" class="affiliate"><span>'+AffiliateName+'</span><input placeholder="' + AffiliateName + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_affiliate_name_' + count + '" value="" /><span>'+AffiliateIcon+'</span><input placeholder="' + AffiliateIcon + ' (Like affiliated logo image link.)" type="text" class="frombutton_input frombutton_ajax frombutton_upload_image_button" name="frombutton_affiliate_icon_' + count + '" value="" /><a  title="Like affiliated logo image link.." href="javascript:void(0)"> ?</a><span>'+AffiliateText+'</span><input placeholder="' + AffiliateText + '" type="text" class="frombutton_input frombutton_ajax frombutton_last" name="frombutton_affiliate_text_' + count + '" value="" /><span>'+AffiliateLink+'</span><input placeholder="' + AffiliateLink + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_affiliate_link_' + count + '" value="" /><span>'+AffiliatePrice+'</span><input placeholder="' + AffiliatePrice + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_affiliate_price_' + count + '" value="" /><span>Additional Offers</span><input placeholder="Additional Offers" type="text" class="frombutton_ajax" name="frombutton_affiliate_addi_offer_' + count + '" value="" /><input type="hidden" name="frombutton_affiliate_type_'+count+'"  value="custom" /><a class="affiliate_delete" OnClick="affiliate_delete(' + count + ')" href="javascript:void(0)">Delete Affiliate</a></div>';
	
    jQuery('#frombutton_html_affiliate').before(html);
	
    affiliate_count();
}

function list_affiliates() {

    var count = jQuery('#addons_meta_box_affiliate_price_83542 .affiliate').size();
	
    var html = '<div class="affiliate_list" id="affiliate_list_div_'+count+'"><select name="affiliate_list" id="affiliate_list_'+count+'" onChange="add_affiliate_id(this.value,'+count+')"><option value="">--Please choose affiliate--</option><option value="ebay">Ebay</option><option value="bestbuy">Bestbuy</option><option value="walmart">Walmart</option><option value="newegg">Newegg</option><option value="clickbank">ClickBank</option><option value="aliexpress">Ali Express</option><option value="amazon_us">Amazon US</option><option value="amazon_uk">Amazon UK</option><option value="amazon_au">Amazon Australia</option><option value="amazon_br">Amazon Brazil</option><option value="amazon_cn">Amazon Canada</option><option value="target">Target</option><option value="rakuten">Rakuten</option><option value="jet">Jet</option><option value="sears">Sears</option><option value="custom">Custom</option></select></div>';
	
    jQuery('#frombutton_html_affiliate').before(html);
}

function add_affiliate_id(affiliate,count) {
	
	if(jQuery('#affiliate_textbox_' + count).length){
	
		jQuery('#affiliate_textbox_' + count).remove();
	
	}
    
	if(affiliate=='custom'){
	
		add_affiliate_custom('Affiliate Name', 'Affiliate Icon', 'Affiliate Text', 'Affiliate Link', 'Affiliate Price',count);
	
	} else {
	
		var html = '<div id="affiliate_textbox_' + count + '"><input id="input_id_'+count+'" placeholder="Input Item Id..." type="text" class="frombutton_input frombutton_ajax" name="' + affiliate + '_item_id" value="" /><a class="affiliate_help" title="Put Product\'s Unique ID here.." href="javascript:void(0)"> ?</a><a class="affiliate_check" OnClick="add_affiliate('+count+',\''+affiliate+'\')" href="javascript:void(0)"> + Add</a>&nbsp;&nbsp;<a class="affiliate_delete" OnClick="affiliate_delete(' + count + ')" href="javascript:void(0)">Delete Affiliate</a></div>';
		
		jQuery('#frombutton_html_affiliate').before(html);
	
	}
	
}


function add_affiliate(count,affiliate){
	var plugins_url = jQuery('#get_plugins_url').val();
	var _val = jQuery('#input_id_'+count).val();
	if(_val==''){
		alert('You must enter a product\'s unique id...');
		return false;
	} else {
		jQuery('#affiliate_list_div_'+count).remove();
		jQuery('#affiliate_textbox_' +count).remove();
		jQuery("#LoadingImage").show();
		jQuery.ajax({
			url: plugins_url+'/affiliatedreamplugin/apis/api.php?'+affiliate+'_item_id='+_val+'&count='+count,
			success: function(html){
				jQuery("#LoadingImage").hide();
				/* jQuery('#affiliate_list_'+count).remove(); */
				jQuery('#frombutton_html_affiliate').before(html);
				affiliate_count();
			}
		});
	}
}

function remove_affiliate(count){
	
	jQuery('#affiliate_'+count).remove();
	
	jQuery('#affiliate_list_div_'+count).remove();
	
	return true;
	
}

function frombutton_add(ReviewName, ReviewValue, ReviewColor) {
    var count = jQuery('#custom_meta_box_reviews_83542 .review').size();
    html = '<div id="frombutton_' + count + '" class="review"><input placeholder="' + ReviewName + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_review_name_' + count + '" value="" /><input placeholder="' + ReviewValue + '" type="text" class="frombutton_input frombutton_ajax" name="frombutton_review_value_' + count + '" value="" /><input OnClick="frombutton_farbtastic(' + count + ')" placeholder="' + ReviewColor + '" type="text" id="frombutton_farbtastic_' + count + '" class="frombutton_input frombutton_ajax frombutton_farbtastic frombutton_last" name="frombutton_review_color_' + count + '" value="#FFFFFF" /></div>';
    jQuery('#frombutton_html_content').before(html);
}


function change_design_options(style_id){
	
	var plugins_url = jQuery('#get_plugins_url').val();
	if(style_id == '5')
	jQuery('#color_picker_color1').css("display","block");
	else
	jQuery('#color_picker_color1').css("display","none");
	jQuery('.design_options_before').remove();
	jQuery("#LoadingImage").show();
	jQuery.ajax({
		url: plugins_url+'/affiliatedreamplugin/apis/api.php?style_id='+style_id,
		success: function(html){
			jQuery("#LoadingImage").hide();
			jQuery('.design_options_after').before(html);
		}
	});
	
}