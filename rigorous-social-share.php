<?php 
 /**
 * Plugin Name: Rigorous Social Share
 * Plugin URI: htpp://rigorousthemes.com
 * Description: This plugin allow to add social share button in post and page of you website.
 * Version: 1.0.1
 * Author: Rigorous Theme
 * Author URI: http://rigorousthemes.com
 * Text Domain: rigorous-social-share
 * License: GPL-2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * languages: /languages
 */

 // Declaration of necessary contants for the plugin.
 // tested on wordpress version 5.7
 if ( !defined( 'RSS_IMAGE_DIR' ) ) {
 	define( 'RSS_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'images' );
 }

 
 if( !defined( 'RSS_CSS_DIR' ) ) {
 	define( 'RSS_CSS_DIR', plugin_dir_url( __FILE__ ) . 'css' );
 }
 
 if( !defined('RSS_LANG_DIR')){
 	define( 'RSS_LANG_DIR', basename(dirname(__FILE__)) . '/languages/');
 }
 
 if( !defined('RSS_VERSION')){
 	define( 'RSS_VERSION','1.0.0');
 }
 
 if( !defined( 'RSS_TEXTDOMAIN')){
 	define( 'RSS_TEXTDOMAIN','rigorous-social-share');
 }
 
 //Declaration of necessary configuration for the plugin
 if( !class_exists('RSS_Class')){
 	class RSS_Class{

 		var $rss_settings;

 		function __construct(){
 			$this->rss_settings = get_option('rss_settings'); //get the plugin varibale;

			add_action( 'admin_menu', array( $this, 'add_rss_menu')); //register the plugin menu in background

			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets'));

			add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );

			add_action('admin_post_rss_save_options', array($this, 'rss_save_options'));// save the option in wordpress option table

			add_filter( 'the_content', array( $this, 'rss_display_social_icon_post' ), 110 );

			add_filter( 'the_content', array( $this, 'rss_display_social_icon_page' ), 110 );

			add_action( 'add_meta_boxes', array( $this, 'social_meta_box' ) ); //for providing the option to disable the social share option in each frontend page
			add_action( 'save_post', array( $this, 'save_meta_values' ) ); //function to save the post meta values of a plugin.
		}
		
		//add plugins menu in backend
		function add_rss_menu() {
			add_menu_page( 'Rigorous Social Share', 'Rigorous Social Share', 'manage_options', 'rigorous-social-share', array( $this, 'main_page' ) );
		}
		
		//add plugin backend assets
		function register_admin_assets(){
			if ( isset( $_GET['page'] ) && $_GET['page'] == 'rigorous-social-share' ) {
				/**
				* Backend Css
				*/
				wp_enqueue_style('rss-admin-css',RSS_CSS_DIR .'/backend.css',false,RSS_VERSION);//register plugin backend css
				wp_enqueue_style( 'fontawesome-css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css', false, RSS_VERSION );		
				

			}
		}

		function register_frontend_assets(){

			wp_enqueue_style('rss-frontend-css',RSS_CSS_DIR .'/frontend.css',RSS_VERSION);

			wp_enqueue_style( 'fontawesome-css', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css', false, RSS_VERSION );

		}


		//Plugin Backend admin page
		function main_page(){
			include('inc/backend/main-page.php');
		}

		
				//Add meta box in post.
		function social_meta_box() {
			add_meta_box( 'rss-share-box', 'Rigorous social share options', array( $this, 'metabox_callback' ), '', 'side', 'core' );
		}

		function metabox_callback( $post ) {
			wp_nonce_field( 'save_meta_values', 'rss_share_meta_nonce' );
			$content_flag = get_post_meta( $post->ID, 'rss_content_flag', true );
			?>
			<label><input type="checkbox" value="1" name="rss_content_flag" <?php checked( $content_flag, true ) ?>/><?php _e( 'Hide share icons in content', 'rigorous-social-share' ); ?></label><br>
			<?php
		}

		/**
		 * Save Share Flags on post save
		 */
		function save_meta_values( $post_id ) {

			/*
			 * We need to verify this came from our screen and with proper authorization,
			 * because the save_post action can be triggered at other times.
			 */

			// Check if our nonce is set.
			if ( !isset( $_POST['rss_share_meta_nonce'] ) ) {
				return;
			}

			// Verify that the nonce is valid.
			if ( !wp_verify_nonce( $_POST['rss_share_meta_nonce'], 'save_meta_values' ) ) {
				return;
			}

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check the user's permissions.
			if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

				if ( !current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( !current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}

			/* OK, it's safe for us to save the data now. */
			// Make sure that it is set.
			$content_flag = (isset( $_POST['rss_content_flag'] ) && $_POST['rss_content_flag'] == 1) ? 1 : 0;

			// Update the meta field in the database.
			update_post_meta( $post_id, 'rss_content_flag', $content_flag );
		}


		function rss_save_options() {
			foreach($_POST as $key=>$val)
			{
				$$key = $val;
			} 
			$rss_settings = array();//array for saving all the settings
			$rss_settings['rss_facebook'] 		= $rss_facebook;
			$rss_settings['rss_twitter'] 		= $rss_twitter;
			$rss_settings['rss_pinterest'] 		= $rss_pinterest;
			$rss_settings['rss_linkedin'] 		= $rss_linkedin;
			$rss_settings['rss_googlePlus'] 	= $rss_googlePlus;	
			$rss_settings['rss_before_post'] 	= $rss_before_post;
			$rss_settings['rss_enable_couter'] 	= $rss_enable_couter;
			$rss_settings['rss_after_post'] 	= $rss_after_post;
			$rss_settings['rss_after_page'] 	= $rss_after_page;
			$rss_settings['rss_before_page'] 	= $rss_before_page;
			$rss_settings['rss_choose_theme'] 	     = $rss_choose_theme;

			update_option('rss_settings', $rss_settings);
			wp_redirect(admin_url().'admin.php?page=rigorous-social-share');
		}

		function rss_the_content_filter(){
			global $post;
			$rss_icon ='';
			$rss_link = '';
			$rss_choose_theme ='';
			$rss_settings = $this->rss_settings;			
			$content_flag = get_post_meta( $post->ID, 'rss_content_flag', true );		
			$rss_share_count =''; 
			$rss_url = get_permalink();
			$rss_title = urlencode( html_entity_decode( get_the_title() ) );  
			$rss_social_image ='';
			$rss_thumb = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'medium' );
			$rss_thumb_url = $rss_thumb['0'];
			
			if($rss_thumb_url == ''){
				$rss_thumb_url = RSS_IMAGE_DIR.'/default.jpg'; 
			}
			if($rss_social_image == ''){
				$rss_social_image = $rss_thumb_url;
			}
			
			$rss_social_image = urlencode($rss_social_image);
			

			$all_social_count = array();
			foreach( $rss_settings as $key => $value ) {
				switch( $value ) :
				case 'facebook' :
				$rss_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $rss_url;
				$rss_icon = '<a class="facebook" href='.$rss_link.' target="_blank"><i class="fa fa-facebook"></i></a>';	
				$rss_share_count = $this->rss_fb_share_count( $rss_url );					
				
				break;
				
				case 'twitter' :
				$rss_icon = '<a class="twitter" href="http://twitter.com/intent/tweet/?text='.$rss_title.'&url='.$rss_url.'"target="_blank"><i class="fa fa-twitter"></i></a>';
				$rss_share_count = $this->rss_twitter_count_display( $rss_url );	
				
				break;				
				case 'pinterest' :
				$rss_icon = '<a class="pinterest" href="http://pinterest.com/pin/create/button/?url='.$rss_url.'&media='.$rss_social_image.'&description='.$rss_title.'" target="_blank"><i class="fa fa-pinterest"></i></a>';	
				$rss_share_count = $this->rss_pinterest_count_display( $rss_url );				
				
				break;
				
				case 'linkedin' :
				$rss_icon = '<a class="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url='.$rss_url.'&title='.$rss_title.'" target="_blank"><i class="fa fa-linkedin"></i></a>';
				$rss_share_count = $this->rss_linkedin_share_count( $rss_url );						
				
				break;
				
				case 'googleplus' :
				$rss_link = 'https://plus.google.com/share?url=' . $rss_url;
				$rss_icon = '<a class="googleplus" href='.$rss_link.' target="_blank"><i class="fa fa-google-plus"></i></a>';
				$rss_share_count = $this->rss_googleplus_share_count( $rss_url );				
				
				break;
				
				default:
				$rss_icon = false;
				break;
				endswitch;

				if($rss_icon ){
					$all_social_count[$value] = array(
						'rss_link'=>$rss_link,
						'rss_icon'=>$rss_icon,
						'rss_share_count'=>$rss_share_count
						);
				}
			}			
			$html='';
			$htmls="";
			$icons_html='';
			$icon_html='';

			if( $rss_settings['rss_choose_theme'] == 'theme_1' ) {				
				$rss_class = 'rss-demo-1';
			} elseif($rss_settings['rss_choose_theme'] == 'theme_2'){
				$rss_class = 'rss-demo-2';
			}
			else{
				$rss_class = 'rss-demo-3';
			}

			foreach ($all_social_count as $key => $value) {

				$htmls .='<div class="icon-wrapper">'.$value['rss_icon'].'<span class="count">'.$value['rss_share_count'].'</span></div>';
				$icons_html .='<div class="icon-wrapper">'.$value['rss_icon'].'</div>';

				$html = '<div class="rss-social-share-wrapper '.$rss_class.'">'.$htmls.'</div>';

				$icon_html = '<div class="rss-social-share-wrapper '.$rss_class.'">'.$icons_html.'</div>';
			}

			if( $rss_settings['rss_enable_couter'] == 'enable_couter' ) {				
				$rss_all_conter = $html;
			} else{
				$rss_all_conter = $icon_html;
			}

			$rss_all_share = $rss_all_conter;		
			if($content_flag != '1'):		
				return $rss_all_share;
			endif;


		}


		 //function to return json values from social media urls
		function get_json_values( $rss_url ) {
			$args            = array( 'timeout' => 10 );
			$response        = wp_remote_get( $rss_url, $args );
			$json_response   = wp_remote_retrieve_body( $response );
			return $json_response;
		}

		// Function to count facebook share
		function rss_fb_share_count( $rss_url ) {
			$json_string    = $this->get_json_values( 'https://graph.facebook.com/?id=' . $rss_url );
			$json           = json_decode( $json_string, true );
			$facebook_share_count = isset( $json['share']['share_count'] ) ? intval( $json['share']['share_count'] ) : 0;
			return $facebook_share_count;
		}

		//function to count twitter share
		function rss_twitter_count_display( $rss_url ) {
			$json_string        = $this->get_json_values( 'http://opensharecount.com/count.json?url=' . $rss_url );
			$json               = json_decode( $json_string, true );
			$twitter_count    = isset( $json['count'] ) ? intval( $json['count'] ) : 0;
			return $twitter_count;
		}

		//function to count pinterest share
		function rss_pinterest_count_display( $rss_url ) {
			$json_string        = $this->get_json_values( 'http://api.pinterest.com/v1/urls/count.json?url=' . $rss_url );	
			$json_string        = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string );
			$json               = json_decode( $json_string, true );
			$pinterest_count    = isset( $json['count'] ) ? intval( $json['count'] ) : 0;
			return $pinterest_count;			
		}

		//function to count linkedin
		function rss_linkedin_share_count( $rss_url ) {
			$json_string    = $this->get_json_values( "https://www.linkedin.com/countserv/count/share?url=$rss_url&format=json" );
			$json           = json_decode( $json_string, true );
			$linkedin_count = isset( $json['count'] ) ? intval( $json['count'] ) : 0;
			return $linkedin_count;
		}

		//function to count social share
		function rss_googleplus_share_count( $rss_url ) {
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_URL, "https://clients6.google.com/rpc" );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . rawurldecode( $rss_url ) . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]' );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Content-type: application/json' ) );
			$curl_results = curl_exec( $curl );
			curl_close( $curl );
			$json = json_decode( $curl_results, true );
			$plusones_count = isset( $json[0]['result']['metadata']['globalCounts']['count'] ) ? intval( $json[0]['result']['metadata']['globalCounts']['count'] ) : 0;
			return $plusones_count;

		}
		function rss_display_social_icon_post( $content ) {			
			$rss_post_content = $content;
			$rss_icon_before = '';			
			$rss_icon_after = '';
			$rss_icon_before_content='';
			$rss_icon_after_content='';
			if( is_single() ) {
				$rss_settings = $this->rss_settings;				
				if( $rss_settings['rss_before_post'] == 'before_post' ) {

					$rss_icon_before = $this->rss_the_content_filter();
					$rss_icon_before_content ='<div class="rss-before-conent">'.$rss_icon_before.'</div>';
				}
				if( $rss_settings['rss_after_post'] == 'after_post' ) {
					$rss_icon_after = $this->rss_the_content_filter();
					$rss_icon_after_content ='<div class="rss-after-conent">'.$rss_icon_after.'</div>';
				}
			}
			$rss_content = $rss_icon_before_content.$rss_post_content.$rss_icon_after_content;
			return $rss_content;
		}

		function rss_display_social_icon_page( $content ) {			
			$rss_post_content = $content;
			$rss_icon_before = '';			
			$rss_icon_after = '';
			if( is_page() ) {
				$rss_settings = $this->rss_settings;				
				if( $rss_settings['rss_before_page'] == 'before_page' ) {
					$rss_icon_before = $this->rss_the_content_filter();
				}
				if( $rss_settings['rss_after_page'] == 'after_page' ) {
					$rss_icon_after = $this->rss_the_content_filter();
				}
			}
			$rss_content = $rss_icon_before.$rss_post_content.$rss_icon_after;
			return $rss_content;
		}

	}

	$GLOBALS['rss_objec'] = new RSS_Class();	
}



