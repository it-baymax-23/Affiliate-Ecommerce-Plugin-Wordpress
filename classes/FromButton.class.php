<?php 

/***************************************************************

@

@	From Button WP Class 

@

/**************************************************************/ 

class FromButton{  

	/***************************************************************

	@

	@	Construct

	@

	/**************************************************************/

	public function __construct($name, $ver) {

		$this->plugin_name 	= $name;

		$this->plugin_version = $ver;

		/***************************************************************

		@

		@	Methods Call

		@

		/**************************************************************/   

		add_action('init',array(&$this,'FromButton_Init'));

		add_action('admin_enqueue_scripts',array(&$this,'FromButton_BackInit'));   

		add_action('add_meta_boxes', array(&$this,'FromButton_Meta')); 

		add_action('save_post', array(&$this,'FromButton_Save')); 

		add_action('init', array(&$this,'FromButton_WYSIWYG'));  

		add_shortcode('TheAffiliateDream', array(&$this,'FromButton_BuyShortcode'));

		add_filter('widget_text', 'do_shortcode');  

		add_action('admin_menu', array(&$this,'FromButton_Manage')); 

		//add_action('wp_enqueue_scripts','AddJquery');
		
		add_action('wp_enqueue_scripts', array($this,'AddJquery'));



	}





/***************************************************************

	@

	@	From Button WYSIWYG 

	@

	/**************************************************************/ 

	/**

 * @return mixed

 */public  function AddJquery(){



    wp_register_script('FromButton-admin',plugins_url('/js/FromButton-admin-6.js',__FILE__));

	wp_localize_script( 'ajaxHandle', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin_ajax.php' ) ) );



 }

	public function FromButton_WYSIWYG(){

		if(!current_user_can('edit_posts') && ! current_user_can('edit_pages')){

			return;

		} 

		if(get_user_option('rich_editing') == 'true'){

			add_filter('mce_external_plugins', 'add_frombutton_tinymce_plugin');

			add_filter('mce_buttons', 'register_frombutton_button');

		} 

		function register_frombutton_button($buttons) {

		   array_push($buttons, "|", "frombutton_button");

		   return $buttons;

		}



		function add_frombutton_tinymce_plugin($plugin_array) {

		   $plugin_array['frombutton_button'] = plugins_url('/js/frombutton_button.js', __FILE__);

		   return $plugin_array;

		}

		function frombutton_refresh_mce($ver) {

			$ver += 3;

			return $ver;

		} 

		add_filter('tiny_mce_version', 'frombutton_refresh_mce'); 

	}



	/***************************************************************

	@

	@	From Button short price order 

	@

	/**************************************************************/ 
function debug_to_console( $data ) {

    if ( is_array( $data ))
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}
	 

public function make_comparer() {

    // Normalize criteria up front so that the comparer finds everything tidy

    $criteria = func_get_args();
    foreach ($criteria as $index => $criterion) {

        $criteria[$index] = is_array($criterion)

            ? array_pad($criterion, 3, null)

            : array($criterion, SORT_ASC, null);
	}



    return function($first, $second) use (&$criteria) {

        foreach ($criteria as $criterion) {

            // How will we compare this round?

            list($column, $sortOrder, $projection) = $criterion;

            $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;



            // If a projection was defined project the values now

            if ($projection) {

                $lhs = call_user_func($projection, $first[$column]);

                $rhs = call_user_func($projection, $second[$column]);

            }

            else {

                $lhs = $first[$column];

                $rhs = $second[$column];

            }



            // Do the actual comparison; do not return if equal

            if ($lhs < $rhs) {

                return -1 * $sortOrder;

            }

            else if ($lhs > $rhs) {

                return 1 * $sortOrder;

            }

        }



        return 0; // tiebreakers exhausted, so $first == $second

    };

} 

    /***************************************************************

	@

	@	From Button Buy Shortcode 

	@

	/**************************************************************/ 



	public function FromButton_BuyShortcode($atts){

		if(is_single() OR is_page()){ 

		

			//echo $default_style = get_option('frontend_default_button');die;

			$default_style = get_option('Frombutton_from_style_id');

			 

			if(empty($default_style)){

				$default_style = '0';

			}

			

			$FromButton = $this->FromButton_Get($default_style); 

			

			$affiliate_group = array('ebay', 'bestbuy','walmart','newegg','clickbank','aliexpress','amazon_us','amazon_uk','amazon_au','amazon_br','amazon_cn','target','rakuten','jet','sears','warriorplus');

			

			$FromButton_GetAffiliate = $this->FromButton_GetAffiliate();

            $index = 0;

			  

			//usort($FromButton_GetAffiliate,  $this->make_comparer('frombutton_affiliate_price'));
			//usort($FromButton_GetAffiliate,  $this->make_comparer('price_me'));

			  

			$power_by = array();			

			$HTML_FromButton_GetAffiliate = '

			<style>



.affiliate_area{

    border-bottom : 1px solid lightgray;

    margin-bottom : 2px;

    padding : 15px;

    height : auto;

}

.frombutton_buy_content .affiliate_area{

    padding : 10px;

    margin : 0px;

    height: auto;

    overflow : hidden;

}

#Overlay .frombutton_icon{

 width: 33%;

 float : left;

 }

 .frombutton_price{

 float : left;

 display : inline;

 padding-left : 30px;

 }

 #Overlay #frombutton_buy{

 float : right;

 }

 [draggable=true] {

    cursor: move;

}







#btnExit {

float:right;

}



</style>



			';
			
			while(list($index, $value) = each($FromButton_GetAffiliate)){





                $HTML_FromButton_GetAffiliate .= '<div id="affiliate_'.$index.'" class="affiliate"><div class="affiliate_area">';

				

					//$HTML_FromButton_GetAffiliate .= '<div class="frombutton_icon"><img src="'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_icon'].'" style="height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;"/></div>';

					

					$HTML_FromButton_GetAffiliate .= '<div style="background:url('.$FromButton_GetAffiliate[$index]['frombutton_affiliate_icon'].') no-repeat scroll center center / 60% auto rgba(0, 0, 0, 0);line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;"  class="frombutton_icon">&nbsp;</div>';

					 

					$HTML_FromButton_GetAffiliate .= '<section class="frombutton_price">';	

					 

					$HTML_FromButton_GetAffiliate .= '<div class="price_me" style="color:'.$FromButton['Frombutton_from_background_'.$default_style].';line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;">';

					

					if($FromButton_GetAffiliate[$index]['frombutton_affiliate_addi_offer'] != ''){	

					$HTML_FromButton_GetAffiliate .= '<ul><li style="line-height:'.($FromButton['Frombutton_from_height_'.$default_style]-15).'px;">$'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_price'].'</li><li class="affiliate_addi_offer">'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_addi_offer'].'</li></ul>';	

					}

					else

					{

						$HTML_FromButton_GetAffiliate .= '<span>'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_price'].'</span>';

					}

					

					$HTML_FromButton_GetAffiliate .= '</div><section> ';



				

					if($default_style==4){

						 

							

							if (strpos($FromButton_GetAffiliate[$index]['frombutton_affiliate_link'],'href=') !== false) 

							{

								$html = $FromButton_GetAffiliate[$index]['frombutton_affiliate_link'];

								$url = preg_match('/href=["\']?([^"\'>]+)["\']?/', $html, $match);

								$info = parse_url($match[1]);

								$href_url = $info["scheme"] . "://" . $info["host"] . $info["path"];

							}

							else

							{

								$href_url =$FromButton_GetAffiliate[$index]['frombutton_affiliate_link'];

							}

								$HTML_FromButton_GetAffiliate .= '<div id="frombutton_buy"  class="buy_now" style="width: '.$FromButton['Frombutton_from_size_'.$default_style].'px;

								height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;"><a style="line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;font-size: '.$FromButton['Frombutton_font_size_'.$default_style].'px;color:'.$FromButton['Frombutton_from_color_'.$default_style].';border-radius:0; background-color:'.$FromButton['Frombutton_from_background_'.$default_style].';margin-top: 10px;width: '.$FromButton['Frombutton_from_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;display:block;" target="_blank" href="'.$href_url.'"> '.$FromButton_GetAffiliate[$index]['frombutton_affiliate_text'].'</a></div>';



					  

					} else {

						

						 					

							if (strpos($FromButton_GetAffiliate[$index]['frombutton_affiliate_link'],'href=') !== false) 

							{

								$html = $FromButton_GetAffiliate[$index]['frombutton_affiliate_link'];

								$url = preg_match('/href=["\']?([^"\'>]+)["\']?/', $html, $match);

								$info = parse_url($match[1]);

								$href_url = $info["scheme"] . "://" . $info["host"] . $info["path"];

							}

							else

							{

								$href_url =$FromButton_GetAffiliate[$index]['frombutton_affiliate_link'];

							}



							 

							$HTML_FromButton_GetAffiliate .= '<div id="frombutton_buy"  class="buy_now" style="width: '.$FromButton['Frombutton_from_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;"><a style="color:'.$FromButton['Frombutton_from_color_'.$default_style].';line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;font-size: '.$FromButton['Frombutton_font_size_'.$default_style].'px;background-color:'.$FromButton['Frombutton_from_background_'.$default_style].';width: '.$FromButton['Frombutton_from_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;display: block;" target="_blank" href="'.$href_url.'">'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_text'].'</a></div>';

							 

						 

					}
					 

					//$HTML_FromButton_GetAffiliate .= '<div style="background:url('.$FromButton_GetAffiliate[$index]['frombutton_affiliate_icon'].') no-repeat scroll center center / 60% auto rgba(0, 0, 0, 0);" class="frombutton_icon"></div>';

				/*	$HTML_FromButton_GetAffiliate .= '<div class="frombutton_icon"><img src="'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_icon'].'" style="height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;"/></div>';

					if($FromButton_GetAffiliate[$index]['frombutton_affiliate_addi_offer'] != ''){	

					$HTML_FromButton_GetAffiliate .= '<div class="frombutton_price" style="line-height:35px;">';

					}else{

					$HTML_FromButton_GetAffiliate .= '<div class="frombutton_price">';	

					}

					$HTML_FromButton_GetAffiliate .= '<span class="price_me" style="color:'.$FromButton['Frombutton_from_background_'.$default_style].';line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;">$'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_price'].'</span>';

					

					if($FromButton_GetAffiliate[$index]['frombutton_affiliate_addi_offer'] != ''){	

					$HTML_FromButton_GetAffiliate .= '<div class="affiliate_addi_offer">'.$FromButton_GetAffiliate[$index]['frombutton_affiliate_addi_offer'].'</div> ';	

					}

					

					$HTML_FromButton_GetAffiliate .= '</div> ';*/

					if (in_array(trim($FromButton_GetAffiliate[$index]['frombutton_affiliate_type']), $affiliate_group)){
						$power_by[] = $FromButton_GetAffiliate[$index]['frombutton_affiliate_type'];
				}
			$HTML_FromButton_GetAffiliate .=  "</div></div>";
		}    

			

			$price = get_post_meta(get_the_ID(), 'frombutton_price'); 

			$h2_background = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID(), array(32,32)));

			

			if($h2_background[0] != '')

			$h2_span_icon = '<span style="background: url('.$h2_background[0].') no-repeat;background-size: 100%;width: 32px;height:32px;display:inline-block;border-radius:5px;">&nbsp;</span>'; 

			else

			$h2_span_icon = ''; 

			

			$HTML_FromButton_Buy = '<div id="frombutton_buy">';

					if($default_style==4){ 

						$HTML_FromButton_Buy .= '<center><div id="frombutton_buy" class="buy_now" style="border-radius:0 ;background-color:'.$FromButton['Frombutton_from_background_'.$default_style].';width: '.$FromButton['Frombutton_from_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;margin-right: 0;">

						<span class="buy_now_open" id="buy_now_toggle" style="color:'.$FromButton['Frombutton_from_color_'.$default_style].';line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;font-size:'.$FromButton['Frombutton_font_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;">'.$FromButton['Frombutton_from_text_'.$default_style].' '.$price[0].'</span> 

					</div></center>';

					}else{

						$HTML_FromButton_Buy .= '<center><div id="frombutton_buy" class="buy_now" style="background-color:'.$FromButton['Frombutton_from_background_'.$default_style].';width: '.$FromButton['Frombutton_from_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;margin-right: 0;">

						<span class="buy_now_open" id="buy_now_toggle" style="color:'.$FromButton['Frombutton_from_color_'.$default_style].';line-height:'.$FromButton['Frombutton_from_height_'.$default_style].'px;font-size:'.$FromButton['Frombutton_font_size_'.$default_style].'px;height: '.$FromButton['Frombutton_from_height_'.$default_style].'px;">'.$FromButton['Frombutton_from_text_'.$default_style].' '.$price[0].'</span> 

					</div></center>';

					}

					$power_by = array_unique( $power_by );

					if(count($power_by) > 0)

					{

					$power_by_str = implode(", ", $power_by);

					$power_by_str = '<div style="font-size: 10px; height: 15px; text-align: right;">Powered by '.$power_by_str.'</div>';

					}

					$HTML_FromButton_Content='<div class="buy_now_clear"></div><div class="frombutton_buy_content"><div class="affiliate"><div class="affiliate_content"><h2>'.$h2_span_icon.'<span>'.__('Shop for', 'FromButton').'</span>'.get_the_title().'</h2>'.$HTML_FromButton_GetAffiliate.$power_by_str.'</div></div></div></div>';
			$HTML_FromButton_Buy .= $HTML_FromButton_Content;



			$pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}

    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") {

        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

        } else {

        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

    }

    $show = 'visibility:hidden;';

    if(get_option('Frombutton_from_social_hidden') == 'yes')

    {

        $show = '';

    }



			$SocialButton =	"

			<script >

        function share_fb(url) {

            window.open('https://www.facebook.com/sharer/sharer.php?u='+url,'facebook-share-dialog',\"width=626,height=436\")

        }

    </script>

<script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\">



    twttr.events.bind('tweet', function(event) {

    });



</script>

<style>



a{

    float: right;

    text-decoration: none;



}

</style>

<div style='".$show."'>

<a href=\"#\" onclick=\"share_fb('".$pageURL."');return false;\" target=\"_blank\">

  <img src =\"https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/F_icon.svg/267px-F_icon.svg.png\" width = 16px height = 16px>

</a>



<a href=\"https://twitter.com/intent/tweet\" target=\"_blank\">

        <img src =\"http://icons.iconarchive.com/icons/limav/flat-gradient-social/512/Twitter-icon.png\" width = 16px height = 16px >

</a>

	</div>		";

            $HTML_FromButton_Buy.="";

			$HTML_FromButton_Buy.='

<script>

var con = document.getElementById("Content");

for(var i = 0; i < 5;i++){

    var btn = $("#Content #affiliate_" + i).find("#frombutton_buy");

    $("#Content #affiliate_" + i).find(".affiliate_area").append(btn);

}

for(var i = 0; i < 5;i++){

    var btn = $("#Content #affiliate_" + i).find("#frombutton_buy");

    $("#Content #affiliate_" + i)

    $("#Content #affiliate_" + i).find(".affiliate_area").append(btn);

    var btn = $(".frombutton_buy_content  #affiliate_" + i).find("#frombutton_buy");

    $(".frombutton_buy_content  #affiliate_" + i).find(".affiliate_area").append(btn);

    $("#Content #affiliate_" + i).attr("class", "");

    $(".frombutton_buy_content #affiliate_" + i).attr("class", "");



}

</script>

			';

			return $HTML_FromButton_Buy;

		} 

	}

    public  function getPopUp($data)

    {

                    $headstring ="

<div id=\"Overlay\">

  <div id=\"Content\" class=\"affiliate_content\">

    ".$data."

    </div>

  <div id = \"Bottom\" style = 'position:static'>

  <Input id=\"btnExit\"

       type=\"button\"

       value=\"Exit\"

       onmousedown=\"DlgHide()\"/>

</div>

  </div>

";

$PopUp = "";

$PopUp .= $headstring;

if(get_option('Frombutton_from_popup_hidden') == 'yes')

{

$PopUp .=  "

<script type = \"text/javascript\"  charset=\"UTF-8\">

window.onload = function(){setTimeout(showPopup,".get_option('Frombutton_from_popup_time').")};

function showPopup()

{

  var specialBox = document.getElementById(\"Overlay\");

  specialBox.style.visibility = \"visible\";

   specialBox.style.display = \"block\";

}

function DlgHide()

{

    var specialBox = document.getElementById(\"Overlay\");

  specialBox.style.visibility = \"hidden\";

   specialBox.style.display = \"none\";



}

</script>".'

<script>



$("body").prepend($("#Overlay"));//, $("body").firstChild);

$("body").css("width","100%");

$("body").css("height","100%");

$("#Overlay").css("left", "25%");

$("#Overlay").css("margin-left","" + (-$("body").width/2));

$("#Overlay").css("top", "25%");

$("#Overlay").css("margin-top","" + (-$(window).height/2));



$(document).ready(function(){



    $(window).scroll(function(){

        if($(window).scrollTop() > 0)

        {

            //$("#Overlay").css("margin-top","" + ($(window).scrollTop() + ($(window)).height/2 - $("#Overlay").height/2));

            //$("#Overlay").css("margin-bottom","" + $("Content").height);

            //$("#Overlay").css("margin-left",($(window)).width/2);

            //$("#Overlay").css("margin","auto");

        }

    })

})



</script>';

}



$PopUp .=  "



 <style>

 #Overlay

{



    max-height: 200px;

  visibility: hidden;

  text-align: center;

  position: fixed;

  font-weight: bold;

  border:10px solid black;

  background-color: ".get_option('Frombutton_from_popup_background_style').";

  width : 50%;

  overflow : scroll;

  z-index : 100;

  padding : 20px;

  overflow-x: hidden;

}

#Overlay .affiliate_area{

    border-bottom : 1px solid lightgray;

    margin-bottom : 2px;

    padding : 15px;

    height : auto;

}

.frombutton_buy_content .affiliate_area{

    padding : 10px;

    margin : 0px;

    height: auto;

    overflow : hidden;

}

#Overlay .frombutton_icon{

 width: 33%;

 float : left;

 }

 .frombutton_price{

 float : left;

 display : inline;

 padding-left : 30px;

 }

 [draggable=true] {

    cursor: move;

}







#btnExit {

float:right;

}



@media only screen and (max-device-width: 480px) {

#Overlay  .affiliate_area{



    border-bottom : 1px solid lightgray;

    height : auto;

}



#Overlay .frombutton_icon{

 width: 25%;

 float : none;

 line-height : auto;

 }

#Overlay .frombutton_price{

 	display: block;

 }



 }







</style>           ";



    return $PopUp;

    }



	/***************************************************************

	@

	@	From Button BackInit

	@

	/**************************************************************/ 

	public function FromButton_BackInit(){   

		wp_register_style('FromButton-admin-style', plugins_url('/css/FromButton-admin.css', __FILE__));

		wp_enqueue_style('FromButton-admin-style');  

		wp_register_script('FromButton-admin-js', plugins_url('/js/FromButton-admin-6.js', __FILE__));

		wp_enqueue_script('FromButton-admin-js');  

		wp_enqueue_style('farbtastic');

		wp_enqueue_script('farbtastic');

		wp_register_script('FromButton-upload-js', plugins_url('/js/frombutton_upload.js', __FILE__));

		wp_enqueue_script('FromButton-upload-js'); 		 		



	}



	

	/***************************************************************

	@

	@	From Button Get Affiliate

	@

	/**************************************************************/ 
function swap($arr,$p1,$p2)
{
    $temp = $arr[$p2];
    $arr[$p2] = $arr[$p1];
    $arr[$p1] = $temp;
    return $arr;
}

function bubble($arr)
{
    $count = count($arr);
    for ($j = 1; $j < $count; $j++)
    {
        for ($i=1; $i < $count-$j+1; $i++)
        {
            $m1 = ltrim($arr[$i-1]['frombutton_affiliate_price'],'$');
            $m2 = ltrim($arr[$i]['frombutton_affiliate_price'],'$');
            $m1 = $m1 * 100;
            $m2 = $m2 * 100;
            if ($m1 > $m2)
            {
                $arr = $this->swap($arr, $i-1, $i);
            }
        }
    }
    return $arr;
}
	public function FromButton_GetAffiliate(){

		$my_affiliate = array();

		for($i=0;$i<5;$i++){   

			$frombutton_affiliate_name = get_post_meta(get_the_ID(), 'frombutton_affiliate_name_'.$i);   

			$frombutton_affiliate_icon = get_post_meta(get_the_ID(), 'frombutton_affiliate_icon_'.$i);   

			$frombutton_affiliate_text = get_post_meta(get_the_ID(), 'frombutton_affiliate_text_'.$i);   

			$frombutton_affiliate_link = get_post_meta(get_the_ID(), 'frombutton_affiliate_link_'.$i  );   

			$frombutton_affiliate_price = get_post_meta(get_the_ID(), 'frombutton_affiliate_price_'.$i);

			$frombutton_affiliate_addi_offer = get_post_meta(get_the_ID(), 'frombutton_affiliate_addi_offer_'.$i); 

			$frombutton_affiliate_type = get_post_meta(get_the_ID(), 'frombutton_affiliate_type_'.$i); 

			if(strlen($frombutton_affiliate_name[0])>0){  

				$my_affiliate[$i]['frombutton_affiliate_name']  = $frombutton_affiliate_name[0];

				$my_affiliate[$i]['frombutton_affiliate_icon']  = $frombutton_affiliate_icon[0];

				$my_affiliate[$i]['frombutton_affiliate_text']  = $frombutton_affiliate_text[0];

				$my_affiliate[$i]['frombutton_affiliate_link'] 	= $frombutton_affiliate_link[0];

				$my_affiliate[$i]['frombutton_affiliate_price'] = $frombutton_affiliate_price[0];

				$my_affiliate[$i]['frombutton_affiliate_addi_offer'] = $frombutton_affiliate_addi_offer[0];

				$my_affiliate[$i]['frombutton_affiliate_type']  = $frombutton_affiliate_type[0]; 

			} 

		} 

		$my_affiliate = $this->bubble($my_affiliate);
		return($my_affiliate);

	}


	  

	

	/***************************************************************

	@

	@	From Button Meta 

	@

	/**************************************************************/ 

	public function FromButton_Init(){ 

		if(!is_admin()){

			wp_register_script('FromButton-jquery', plugins_url('/js/jquery.js', __FILE__));

			wp_enqueue_script('FromButton-jquery' );   

			wp_register_script('FromButton-js', plugins_url('/js/FromButton.js', __FILE__));

			wp_enqueue_script('FromButton-js' );

		}   

		$this->FromButton_Default_Keys();  

		 

		wp_register_style('FromButton-style', plugins_url('/css/FromButton5.css', __FILE__));

		wp_enqueue_style('FromButton-style' );

	}

	 

	/***************************************************************

	@

	@	From Button Get 

	@

	/**************************************************************/ 

 

	

	public function FromButton_Get($id){  

		 

		$FromButton_plugin_options = array();

		$Frombutton_from_text = get_option('Frombutton_from_text_style');

		$Frombutton_from_color = get_option('Frombutton_from_color_style');

		$Frombutton_from_background = get_option('Frombutton_from_background_style');

		$Frombutton_from_size = get_option('Frombutton_from_size_style');

		$Frombutton_from_height = get_option('Frombutton_from_height_style');

		$Frombutton_font_size = get_option('Frombutton_font_size_style');

		

		$FromButton_plugin_options_key = get_option('FromButton_plugin_options');

		$FromButton_plugin_options = array(   

			'Frombutton_from_text_'.$id 				=> $Frombutton_from_text,

			'Frombutton_from_color_'.$id 				=> $Frombutton_from_color,

			'Frombutton_from_background_'.$id 			=> $Frombutton_from_background,

			'Frombutton_from_size_'.$id					=> $Frombutton_from_size,

			'Frombutton_from_height_'.$id				=> $Frombutton_from_height,

			'Frombutton_font_size_'.$id					=> $Frombutton_font_size

		); 

		

		if(strlen($FromButton_plugin_options_key['FromButton_serail'])<10){ 

			$this->FromButton_Set(); 

		}else{

			return($FromButton_plugin_options);

		}

	}

	

	/***************************************************************

	@

	@	From Button Default 

	@

	/**************************************************************/ 

	public function FromButton_Default(){  

		$FromButton_plugin_options = array(   

			'Frombutton_from_text_0' 				=> 'From',

			'Frombutton_from_color_0' 				=> '#FFF',

			'Frombutton_from_background_0' 			=> '#0091C1',

			'Frombutton_from_size_0'				=> '190',

			'Frombutton_from_height_0'				=> '35',

			'Frombutton_font_size_0'				=> '17',

			'Frombutton_from_text_1' 				=> 'From',

			'Frombutton_from_color_1' 				=> '#FFF',

			'Frombutton_from_background_1' 			=> '#00C18A',

			'Frombutton_from_size_1'				=> '190',

			'Frombutton_from_height_1'				=> '35',

			'Frombutton_font_size_1'				=> '17',

			'Frombutton_from_text_2' 				=> 'From',

			'Frombutton_from_color_2' 				=> '#FFF',

			'Frombutton_from_background_2' 			=> '#FF0000',

			'Frombutton_from_size_2'				=> '190',

			'Frombutton_from_height_2'				=> '35',

			'Frombutton_font_size_2'				=> '17',

			'Frombutton_from_text_3' 				=> 'From',

			'Frombutton_from_color_3' 				=> '#FFF',

			'Frombutton_from_background_3' 			=> '#FD5D57',

			'Frombutton_from_size_3'				=> '190',

			'Frombutton_from_height_3'				=> '35',

			'Frombutton_font_size_3'				=> '17',

			'Frombutton_from_text_4' 				=> 'From',

			'Frombutton_from_color_4' 				=> '#FFF',

			'Frombutton_from_background_4' 			=> '#000099',

			'Frombutton_from_size_4'				=> '190',

			'Frombutton_from_height_4'				=> '35',

			'Frombutton_font_size_4'				=> '17',

			'Frombutton_from_text_5' 				=> 'From',

			'Frombutton_from_color_5' 				=> '#FFF',

			'Frombutton_from_background_5' 			=> '#0091C1',

			'Frombutton_from_size_5'				=> '190',

			'Frombutton_from_height_5'				=> '35',

			'Frombutton_font_size_5'				=> '17'

		); 

		return($FromButton_plugin_options);   	

	}

	

	public function FromButton_Default_Keys(){  

		$FromButton_plugin_options = array(  

			'FromButton_name' 						=> $this->plugin_name, 

			'FromButton_version'					=> $this->plugin_version, 

			'FromButton_serail'						=> md5('demo')

		); 

		return($FromButton_plugin_options);   	

	}

	

	/***************************************************************

	@

	@	From Button Set 

	@

	/**************************************************************/ 

	public function FromButton_Set(){  

		add_option('FromButton_plugin_options', $this->FromButton_Default_Keys(), '', 'yes');   	

	}

	

	/***************************************************************

	@

	@	From Button Save Meta 

	@

	/**************************************************************/ 

	public function FromButton_Save($post_id){ 

		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 

			return $post_id;	

		if(!current_user_can('edit_post', $post_id))

			return $post_id; 

		/*	Price */	

		update_post_meta($post_id, 'frombutton_price', esc_attr($_POST['frombutton_price'])); 	  

		global $allowedtags;

	    $ahref = 	array(

					'a' => array(

						'href' => array(),

						'title' => array(),

						'alt' => array()

					),

					'script' => array(

                            'src' => array (),

							'rel' => array ()

					),

					'br' => array(),

					'em' => array(),

					'strong' => array(),

				);

		$allowed_html = array_merge( $allowedtags, $ahref );			

		for($i=0;$i<5;$i++){  

			/*

			*	Affiliate

			*/ 

			update_post_meta($post_id, 'frombutton_affiliate_name_'.$i, esc_attr($_POST['frombutton_affiliate_name_'.$i]));  

			update_post_meta($post_id, 'frombutton_affiliate_icon_'.$i, esc_attr($_POST['frombutton_affiliate_icon_'.$i]));  

			update_post_meta($post_id, 'frombutton_affiliate_text_'.$i, esc_attr($_POST['frombutton_affiliate_text_'.$i])); 

			$ref_link = wp_kses($_POST['frombutton_affiliate_link_'.$i],$allowed_html);

			update_post_meta($post_id, 'frombutton_affiliate_link_'.$i, $ref_link);  

			update_post_meta($post_id, 'frombutton_affiliate_price_'.$i, esc_attr($_POST['frombutton_affiliate_price_'.$i])); 

			update_post_meta($post_id, 'frombutton_affiliate_addi_offer_'.$i, esc_attr($_POST['frombutton_affiliate_addi_offer_'.$i]));  

			update_post_meta($post_id, 'frombutton_affiliate_type_'.$i, esc_attr($_POST['frombutton_affiliate_type_'.$i]));   		

		}     

		return $post_id; 

	}

	

	/***************************************************************

	@

	@	From Button Meta 

	@

	/**************************************************************/ 

	public function FromButton_Meta(){   

		  

		/* Affiliate - Price */

		$FromButton_GetAffiliate = $this->FromButton_GetAffiliate();

		 

		add_meta_box(

			'addons_meta_box_affiliate_price_83542', 

			__('Affiliate - Price', 'FromButton'), 

			'addons_meta_box_affiliate_price_83542', 

			'post',

			'normal', 

			'high', 

			$FromButton_GetAffiliate

		);  



function addons_meta_box_affiliate_price_83542($post, $FromButton_GetAffiliate){ $index = 0;

		 ?>

<div id="frombutton_custom_meta_post">

  <div id="frombutton-tab">

    <div class="tab-nav">

      <ul>

        <li class="active" > <a name="manage_affiliates" href="javascript:void(0)">

          <?php _e('Manage Affiliates', 'FromButton');?>

          </a> </li>

        <li> <a name="manage_price" href="javascript:void(0)">

          <?php _e('Manage Price', 'FromButton');?>

          </a> </li>

      </ul>

    </div>

    <div class="tab-content">

      <div class="tab-panel" id="manage_affiliates" style="display:block">

        <p>

          <input type="hidden" id="get_plugins_url" value="<?php echo plugins_url(); ?>"/>

          <input OnClick="list_affiliates()" class="button button-primary button-large"type="button" id="frombutton_add_reviews" value="<?php _e('Add Offer(s)', 'FromButton');?>" />

        <div class="FromButton_Clear"></div>

        <?php /* 

									while(list($index, $value) = each($FromButton_GetAffiliate['args'])) { */?>

        <?php  //print_r($FromButton_GetAffiliate['args'] );

		

		foreach($FromButton_GetAffiliate['args'] as $key=>$value) {?>

        <?php

		$affiliate_group = array('ebay', 'bestbuy','walmart','newegg','clickbank','aliexpress','amazon_us','amazon_uk','amazon_au','amazon_br','amazon_cn','target','rakuten','jet','sears','warriorplus');

		$disable = '';

		if (in_array(trim($value['frombutton_affiliate_type']), $affiliate_group)) {

    	$disable = 'readonly="readonly"';

		}?>

        <div id="affiliate_<?php echo $index;?>" class="affiliate"> <span>

          <?php _e('Retail Name', 'FromButton');?>

          </span>

          <input type="text" class="frombutton_input frombutton_default" name="frombutton_affiliate_name_<?php echo $index;?>" placeholder="<?php _e('Retail Name', 'FromButton');?>" value="<?php echo $value['frombutton_affiliate_name'];?>" <?php echo $disable; ?> />

          <span>

          <?php _e('Retail Icon', 'FromButton');?>

          </span>

          <input placeholder="<?php _e('Retail Icon', 'FromButton');?>" type="text" class="frombutton_input frombutton_default <?php if($disable == '') echo 'frombutton_affiliate_icon'; ?>" id="frombutton_affiliate_icon_<?php echo $index;?>" name="frombutton_affiliate_icon_<?php echo $index;?>" value="<?php echo $value['frombutton_affiliate_icon'];?>" <?php echo $disable; ?>/>

          <span>

          <?php _e('Call to Action', 'FromButton');?>

          </span>

          <input placeholder="<?php _e('Call to Action', 'FromButton');?>" type="text" class="frombutton_input frombutton_default frombutton_last" name="frombutton_affiliate_text_<?php echo $index;?>" value="<?php echo $value['frombutton_affiliate_text'];?>" />

          <span>

          <?php _e('Unique Affiliate Link', 'FromButton');?>

          </span>

          <input placeholder="<?php _e('Unique Affiliate Link', 'FromButton');?>" type="text" class="frombutton_input frombutton_default" name="frombutton_affiliate_link_<?php echo $index;?>" value="<?php echo  esc_attr($value['frombutton_affiliate_link']);?>" <?php //echo $disable; ?>/>

          <span>

          <?php _e('Retail Price', 'FromButton');?>

          </span>

          <input placeholder="<?php _e('Retail Price', 'FromButton');?>" type="text" class="frombutton_input frombutton_default" name="frombutton_affiliate_price_<?php echo $index;?>" value="<?php echo $value['frombutton_affiliate_price'];?>" <?php echo $disable; ?> />

          <?php if($disable == ''){ ?>

          <span>          

          <?php _e('Additional Offers', 'FromButton');?>

          </span>

          <input placeholder="<?php _e('Additional Offers', 'FromButton');?>" type="text" class=" frombutton_default" name="frombutton_affiliate_addi_offer_<?php echo $index;?>" value="<?php echo $value['frombutton_affiliate_addi_offer'];?>"  />

          <?php } ?>

         

          <input type="hidden" name="frombutton_affiliate_type_<?php echo $index;?>" value="<?php echo $value['frombutton_affiliate_type'];?>" />

          <!-- <a class="button button-primary button-large affiliate_delete" OnClick="affiliate_delete('<?php echo $index;?>')" href="javascript:void(0)"> -->
          <button class="button button-delete button-large affiliate_delete" OnClick="affiliate_delete('<?php echo $index;?>')">

          <?php _e('Delete Offer', 'FromButton');?>

          </button> </div>

        <?php

										$index++;

									}  

								?>

        <div id="frombutton_html_affiliate"></div>

        <div id="LoadingImage" style="display:none;">

          <div id="LoadingImageInner"> <img src="<?php echo plugins_url(); ?>/affiliatedreamplugin/classes/images/loader.gif" /> </div>

        </div>

        <input OnClick="affiliate_delete('ALL')" class="button" type="button" id="affiliate_delete_all" value="<?php _e('Delete All Affiliates', 'FromButton');?>" /> 

      </div>

      <div class="tab-panel" id="manage_price">

        <p>

          <?php

			$frombutton_price = get_post_meta(get_the_ID(), 'frombutton_price');  

			?>

          <span class="price_label">Enter Price from:</span>

          <input style="width:99%" type="text" id="frombutton_price" name="frombutton_price" value="<?php echo $frombutton_price[0];?> " />

        </p>

      </div>

    </div>

  </div>

</div>

<?php

		}	 

	}  	

	

	/***************************************************************

	@

	@	From Button Manage

	@

	/**************************************************************/ 

	public function FromButton_Manage(){   

		add_menu_page(__('The Affiliate Dream Plugin', 'FromButton'),__('The Affiliate Dream Plugin', 'FromButton'), 'manage_options', 'frombutton_config', array($this , 'FromButton_Config'), plugins_url('/affiliatedreamplugin/classes/images/FromButton16.png' ));

		add_submenu_page(

			'frombutton_config',

			__('Design Options', 'FromButton'), 

			__('Design Options', 'FromButton'), 

			'manage_options', 

			'frombutton_config', 

			array($this ,'FromButton_Config') 

		);

		

		add_submenu_page(

			'frombutton_config', 

			__('Management', 'FromButton'), 

			__('Management', 'FromButton'), 

			'manage_options', 

			'frombutton_management', 

			array($this ,'FromButton_Config') 

		);

		   

	}  

	

	/***************************************************************

	@

	@	From Button action  

	@

	/**************************************************************/

	public function FromButton_MCount(){   

		global $wpdb;  

		$table_name = $wpdb->prefix.'frombutton'; 

		$sql = 'select * from '.$table_name.' where confirmed = 0 '; 

		$data =	$wpdb->get_results($sql);

		return(count($data));  

	} 

	

	/***************************************************************

	@

	@	From Button Menu  

	@

	/**************************************************************/

	public function FromButton_menu(){   

		$count = ''; 

		if($this->FromButton_MCount()>0){

			$count = '<span class="update-plugins count-2"><span class="plugin-count"> '.$this->FromButton_MCount().' </span></span>';

		} 



		

		add_menu_page('From Button Reviews', __('Management '.$count.'', 'FromButton'), 'manage_options', 'frombutton_config', 

		array(&$this,'FromButton_Config')

		, plugins_url('/affiliatedreamplugin/classes/images/FromButton16.png' ), 6);

	}  

	

	 

	

	 

	/***************************************************************

	@

	@	From Button Serial

	@

	/**************************************************************/ 

	public function FromButton_serial(){

		$default_style = get_option('frontend_default_button');

			

		if(empty($default_style)){

			$default_style = '0';

		}

		 

		

		$FromButton = $this->FromButton_Get($default_style); 

		if($FromButton['FromButton_serail']!=md5('demo')){

			return(true);

		}else{

			return(false);

		} 

	}

	

	/***************************************************************

	@

	@	From Button Config Page

	@

	/**************************************************************/ 

	public function FromButton_Config(){   

		 

	 $FromButton = $this->FromButton_Default();

			 

		

		?>

<div class="wrap columns-2">

  <div id="FromButton-icon" class="icon32"></div>

  <h2><?php echo $this->plugin_name .' '.$this->plugin_version; ?></h2>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-2">

      <div id="postbox-container-1" class="postbox-container">

        

        <div class="postbox">

          <h3><span>

            <?php _e('Video User Guide', 'FromButton'); ?>

            </span></h3>

          <div class="inside">

            <ul>

              <li>

                <a href="http://www.screencast.com/users/daada2010" target="_blank"><?php _e('Illustration Videos', 'FromButton'); ?></a>

              </li>

               

            </ul>

          </div>

        </div>

        <div class="overlay-bg">

          <div class="overlay-content">

            <?php 

							if($this->FromButton_serial()){

								?>

            <h2 class="best_choice"> <a href="<?php echo bloginfo('url').'/wp-admin/edit.php?post_type=adonide_faq&page=html-faq-page/core/html-faq-page-postType.php&html-faq-page-versionPRO='.md5('OK').''?>">

              <?php _e('Vous avez un problème?', 'html-faq-page'); ?>

              </a> <br/>

              <p>bassem.rabia[at]hotmail.co.uk</p>

              <p><img src="<?php echo plugins_url('/images/rabia-bassem.jpg', __FILE__);?>" /></p>

            </h2>

            <button class="close-btn button">

            <?php _e('Annuler', 'html-faq-page'); ?>

            </button>

            <?php

							}else{

								$items = array(

								__('Quisque non arcu dui. Fusce et turpis justo. ', 'FromButton'),

								__('Suspendisse feugiat molestie volutpat.', 'FromButton'),

								__('Vestibulum dapibus, urna a varius laoreet', 'FromButton'),

								__('Ut aliquam fermentum pharetra. Ut a sem mattis. ', 'FromButton'),

								__('Aliquam tempus nec massa ut porttitor. ', 'FromButton'),

								__('Nullam semper purus a mauris vulputate, non mollis diam laoreet. ', 'FromButton'),

								__('Donec ac fermentum tortor.', 'FromButton')

								); 

								?>

            <table class="widefat" style="width:100%; margin:auto auto 20px;">

              <thead>

                <tr>

                  <th><?php echo $this->plugin_name;?> </th>

                  <th>Demo</th>

                  <th>PRO</th>

                </tr>

              </thead>

              <tbody>

                <?php

										foreach ($items as $index => $val) {

										?>

                <tr>

                  <td><?php echo $val;?></td>

                  <td><?php

													echo ($index<3)?'<img src="'.plugins_url('/images/check.png', __FILE__).'" />':'<img src="'.plugins_url('/images/uncheck.png', __FILE__).'" />';

												?></td>

                  <td><img src="<?php echo plugins_url('/images/uncheck.png', __FILE__);?>" /></td>

                </tr>

                <?php

										}

									?>

              </tbody>

            </table>

            <h2 class="best_choice"> <a href="javascript:void(0)">

              <?php _e('Skip to the PRO version', 'FromButton'); ?>

              </a> </h2>

            <p>

              <?php _e("Note: An email will be sent in your name to the development team.", 'FromButton'); ?>

            </p>

            <button class="close-btn button">

            <?php _e('Annuler', 'FromButton'); ?>

            </button>

            <?php

							}

							?>

          </div>

        </div>

      </div>

      <!-- From Button -->

      <?php

		if(isset($_GET['page']) AND $_GET['page']=='frombutton_config'){

			if(isset($_POST['button_css'])){

				$id = $_POST['button_css'];

				echo $this->FromButton_Update($id);

			}

			?>

      <div id="postbox-container-2" class="postbox-container">

         

         <?php  

		$FromButtonStyle = array(); 

		$FromButtonStyle['Frombutton_from_text_style'] = get_option('Frombutton_from_text_style');

		$FromButtonStyle['Frombutton_from_color_style'] = get_option('Frombutton_from_color_style');

		$FromButtonStyle['Frombutton_from_background_style'] = get_option('Frombutton_from_background_style');

		/*$FromButtonStyle = get_option('Frombutton_from_background_style'); */

		$FromButtonStyle['Frombutton_from_size_style'] = get_option('Frombutton_from_size_style');

		$FromButtonStyle['Frombutton_from_height_style'] = get_option('Frombutton_from_height_style');

		$FromButtonStyle['Frombutton_font_size_style'] = get_option('Frombutton_font_size_style');

		$FromButtonStyle['frontend_default_button_style'] =get_option('frontend_default_button_style');

		 

		 

		  ?>                

        <div class="stuffbox">

        <h3>

          <label>

            <?php _e('Design Option'); ?>

          </label>

        </h3>

        <div class="inside">

        <form action="" method="POST"> 

          <input type="hidden" id="get_plugins_url" value="<?php echo plugins_url(); ?>"/>

          <div class="review"> <span class="help">

            <?php _e('Select Style');  ?>

            </span>

            <select name="Frombutton_from_style" class="frombutton_input frombutton_select" onChange="change_design_options(this.value)" style="height:35px;">

              <option value="0" <?php if(get_option('Frombutton_from_style_id') == "0") echo 'selected="selected"'; ?> >Default</option>

              <option value="1" <?php if(get_option('Frombutton_from_style_id') == "1") echo 'selected="selected"'; ?>>Green</option>

              <option value="2" <?php if(get_option('Frombutton_from_style_id') == "2") echo 'selected="selected"'; ?>>Red</option>

              <option value="3" <?php if(get_option('Frombutton_from_style_id') == "3") echo 'selected="selected"'; ?>>Orange</option>

              <option value="4" <?php if(get_option('Frombutton_from_style_id') == "4") echo 'selected="selected"'; ?>>Blue - Square Style</option>

              <option value="5" <?php if(get_option('Frombutton_from_style_id') == "5") echo 'selected="selected"'; ?>>Custom Style</option>

            </select>

            <br /> 

			<?php 

            if(get_option('Frombutton_from_style_id') != "" && get_option('Frombutton_from_style_id') != "0") 

            $sty_id = get_option('Frombutton_from_style_id'); 

            else 

            $sty_id = '0';

			 

           if($sty_id > 4) {		   

		    ?>

            <br /> 

            <div id="design_options_before_0" class="design_options_before">

              <input type="hidden" name="button_css" id="button_css" value="<?php echo $sty_id;?>" />

              <span class="help">

              <?php _e('Text'); ?>

              </span>

              <input type="text" value="<?php echo $FromButtonStyle['Frombutton_from_text_style']; ?>" name="Frombutton_from_text_<?php echo $sty_id; ?>" class="frombutton_input" placeholder="From Button Text"  />

              

              <div style="width: 100%; height: 50px;margin-top:10px;">

              <span class="help">

              <?php _e('Text Color');?>

              </span>

              <div style="float: right; width: 67%;"><div id="colorSelector1"><div style="background-color:<?php echo $FromButtonStyle['Frombutton_from_color_style']; ?>;" id="color_schema_text_98"  onclick="frombutton_farbtastic(98)" > </div></div>

              <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_color_style']; ?>" name="Frombutton_from_color_<?php echo $sty_id; ?>" class="frombutton_input frombutton_default frombutton_farbtastic" id="frombutton_farbtastic_98"  placeholder="From Button Text Color"  style="background-color:<?php echo $FromButtonStyle['Frombutton_from_color_style']; ?>"  />

              </div>

              </div>

              <div style="width: 100%; height: 50px;">

              <span class="help">

              <?php _e('Text background');?>

              </span>

              <div style="float: right; width: 67%;">

             <div id="colorSelector2"><div style="background-color:<?php echo $FromButtonStyle['Frombutton_from_background_style']; ?>;" id="color_schema_text_99"  onclick="frombutton_farbtastic(99)" id="color_schema_text_99" onclick="frombutton_farbtastic2(99)"> </div></div>

              <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_background_style']; ?>" name="Frombutton_from_background_<?php echo $sty_id; ?>" class="frombutton_input frombutton_default frombutton_farbtastic frombutton_last" id="frombutton_farbtastic_99"  placeholder="From Button Text Background" style="background-color:<?php echo $FromButtonStyle['Frombutton_from_background_style']; ?>"  />

              </div>

              </div>

               

              <span class="help">

              <?php _e('Button width (px)');?>

              </span>

              <input type="text" value="<?php echo $FromButtonStyle['Frombutton_from_size_style']; ?>" name="Frombutton_from_size_<?php echo $sty_id; ?>" class="frombutton_input frombutton_default" placeholder="From Button size" />

              <span class="help">

              <?php _e('Button height (px)');?>

              </span>

              <input type="text" value="<?php echo $FromButtonStyle['Frombutton_from_height_style']; ?>" name="Frombutton_from_height_<?php echo $sty_id; ?>" class="frombutton_input frombutton_default" placeholder="Height (px)" />

              <span class="help">

              <?php _e('Font size');?>

              </span>

              <input type="text" value="<?php echo $FromButtonStyle['Frombutton_font_size_style']; ?>" name="Frombutton_font_size_<?php echo $sty_id; ?>" class="frombutton_input frombutton_default" placeholder="Font size (px)" />

            </div>

            <div id="LoadingImage" style="display:none;">

              <div id="LoadingImageInner"> <img src="<?php echo plugins_url(); ?>/affiliatedreamplugin/classes/images/loader.gif" /> </div>

            </div>

            <br class="design_options_after" />

            <div class="aarproduct_color_picker">

              <div id="color_picker_color1"></div>

            </div>

            <div class="FromButton_Clear"></div>

            <br/>







            <?php } else {?>

            <div id="design_options_before_0" class="design_options_before">

            <input type="hidden" name="button_css" id="button_css" value="<?php echo $sty_id;?>" />

            <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_text_style']; ?>" name="Frombutton_from_text_<?php echo $sty_id; ?>" />

            <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_color_style']; ?>" name="Frombutton_from_color_<?php echo $sty_id; ?>" />

            <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_background_style']; ?>" name="Frombutton_from_background_<?php echo $sty_id; ?>" />

            <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_size_style']; ?>" name="Frombutton_from_size_<?php echo $sty_id; ?>"/>

            <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_from_height_style']; ?>" name="Frombutton_from_height_<?php echo $sty_id; ?>"/>

            <input type="hidden" value="<?php echo $FromButtonStyle['Frombutton_font_size_style']; ?>" name="Frombutton_font_size_<?php echo $sty_id; ?>"/>

            </div>

            <div id="LoadingImage" style="display:none;">

              <div id="LoadingImageInner"> <img src="<?php echo plugins_url(); ?>/affiliatedreamplugin/classes/images/loader.gif" /> </div>

            </div> 

            <br class="design_options_after" />

            <div class="aarproduct_color_picker">

              <div id="color_picker_color1"></div>

            </div>

            <div class="FromButton_Clear"></div>









			<?php } ?>

            

            <?php

				$default_style = get_option('frontend_default_button');

				if(empty($default_style)){

					$default_style = '0';

				}

				?>

         </div>

           

                  <p class="frombutton_submit">

              <input type="hidden" value="e8bbc98601" id="FromButton_noncename" name="FromButton_noncename" />

              <input type="submit" name="submit" value="Save Changes" class="button button-primary" id="frombutton_submit" />

            </p>

            <div style="clear: both;height: 10px;overflow: auto;" class="FromButton_Clear">

</div>

        </form>





       

          

          </div>



<?php

				}elseif(isset($_GET['page']) AND $_GET['page']=='frombutton_management'){

			?>

<div id="postbox-container-2" class="postbox-container">

  <table class="widefat" style="width:100%; margin:auto auto 20px;">

    <thead>

      <tr>

        <th style="width:50%"><?php _e('Post', 'FromButton'); ?></th>

        <th style="width:35%"><?php _e('Affiliates', 'FromButton'); ?></th>

        <th><?php _e('Action', 'FromButton'); ?></th>

      </tr>

    </thead>

    <tbody>

      <?php



						query_posts('showposts=10');

						if(have_posts()){

						  while(have_posts()){

							the_post();



							$frombutton_affiliate_name = get_post_meta(get_the_ID(), 'frombutton_affiliate_name_0');

							if(strlen($frombutton_affiliate_name[0])>0){

								?>

      <tr>

        <td><a href="<?php bloginfo('url');?>/wp-admin/post.php?post=<?php echo get_the_ID();?>&action=edit" target="_blank">

          <?php

											echo get_the_title();

										?>

          </a></td>

        <td><?php

									  echo $frombutton_affiliate_name[0];

									?></td>

        <td><a href="<?php bloginfo('url');?>/wp-admin/post.php?post=<?php echo get_the_ID();?>&action=edit" target="_blank"> <img src="<?php echo plugins_url('/images/edit.png', __FILE__);?>" /> </a></td>

      </tr>

      <?php

						}

					  }

					}

						?>

    </tbody>

  </table>

</div>

<?php

			}

			?>

<!-- From Button -->

</div>

</div>







<?php



	}

	/***************************************************************

	@

	@	From Button Custom

	@

	/**************************************************************/



	

	/***************************************************************

	@

	@	From Button Update Config Page

	@

	/**************************************************************/ 

	public function FromButton_Update($id){

		/* echo "<pre>"; print_r(get_option('Frombutton_from_color_4')); die; 

		   echo "<pre>"; print_r($_POST); die;*/

		update_option('Frombutton_from_style_id', $id);

		update_option('Frombutton_from_text_style', $_POST['Frombutton_from_text_'.$id]);

		update_option('Frombutton_from_color_style', $_POST['Frombutton_from_color_'.$id]);

		update_option('Frombutton_from_background_style', $_POST['Frombutton_from_background_'.$id]);

		/* update_option('Frombutton_from_background_'.$id, '#000'); */

		update_option('Frombutton_from_size_style', $_POST['Frombutton_from_size_'.$id]);

		update_option('Frombutton_from_height_style', $_POST['Frombutton_from_height_'.$id]);

		update_option('Frombutton_font_size_style', $_POST['Frombutton_font_size_'.$id]);

		update_option('frontend_default_button_style', $_POST['default_button_style']);

		

		

		/* Update Affiliate Id 

		update_option('Frombutton_from_amazon_affiliate_id', $_POST['Frombutton_from_amazon_affiliate_id']);

		update_option('Frombutton_from_ebay_affiliate_id', $_POST['Frombutton_from_ebay_affiliate_id']);

		update_option('Frombutton_from_walmart_affiliate_id', $_POST['Frombutton_from_walmart_affiliate_id']);

		update_option('Frombutton_from_bestbuy_affiliate_id', $_POST['Frombutton_from_bestbuy_affiliate_id']);

		update_option('Frombutton_from_newegg_affiliate_id', $_POST['Frombutton_from_newegg_affiliate_id']);

		*/

		?>

<div class="stuffbox" id="frombutton_messgae">

  <h3><span> <?php echo $this->plugin_name; _e(' is Updated successfully', 'FromButton'); ?> </span></h3>

</div>

<script>

			window.location = '<?php echo bloginfo('url').'/wp-admin/admin.php?page=frombutton_config';?>';

		</script>

<?php

	} 

}  