<?php 
$rss_settings = $this->rss_settings;
$rss_settings['rss_facebook'] = (isset($rss_settings['rss_facebook'])) ? sanitize_text_field($rss_settings['rss_facebook']): '';
$rss_settings['rss_twitter'] = (isset($rss_settings['rss_twitter'])) ? sanitize_text_field($rss_settings['rss_twitter']): ''; 
$rss_settings['rss_pinterest'] = (isset($rss_settings['rss_pinterest'])) ? sanitize_text_field($rss_settings['rss_pinterest']): ''; 
$rss_settings['rss_linkedin'] = (isset($rss_settings['rss_linkedin'])) ? sanitize_text_field($rss_settings['rss_linkedin']): ''; 
$rss_settings['rss_googlePlus'] = (isset($rss_settings['rss_googlePlus'])) ? sanitize_text_field($rss_settings['rss_googlePlus']): ''; 	
$rss_settings['rss_before_post'] = (isset($rss_settings['rss_before_post'])) ? sanitize_text_field($rss_settings['rss_before_post']): '';

$rss_settings['rss_enable_couter'] = (isset($rss_settings['rss_enable_couter'])) ? sanitize_text_field($rss_settings['rss_enable_couter']): '';

$rss_settings['rss_after_post'] = (isset($rss_settings['rss_after_post'])) ? sanitize_text_field($rss_settings['rss_after_post']): '';

$rss_settings['rss_after_page'] = (isset($rss_settings['rss_after_page'])) ? sanitize_text_field($rss_settings['rss_after_page']): '';

$rss_settings['rss_before_page'] = (isset($rss_settings['rss_before_page'])) ? sanitize_text_field($rss_settings['rss_before_page']): '';

$rss_settings['rss_choose_theme'] = (isset($rss_settings['rss_choose_theme'])) ? sanitize_text_field($rss_settings['rss_choose_theme']): '';



?>
<header class="rss-title">
	<H1><?php esc_html_e('Rigorous Social Share','RSS_TEXTDOMAIN');?></H1>
</header>
<div class="rss-main-wrapper">	
	<div class="rss_wrapper">

		<form method="post" action="<?php echo admin_url() .'/admin-post.php'?>">
			<input type="hidden" name="action" value="rss_save_options">

			<div class="rss_share_option">
				<h2><?php esc_html_e( 'Social Media', 'rigorous-social-share' ); ?> </h2>
				<span class="social-text"><?php esc_html_e( 'Please choose the social share button to display social media in post/page.','rigorous-social-share'); ?></span>

				<div class="rss-social-input">
					<ul>
						<li>				
							<input type="checkbox" id="rss_facebook" name="rss_facebook" value="facebook" <?php echo ( $rss_settings['rss_facebook'] == 'facebook' ? 'checked' : '' ) ; ?>>
							<i class="fa fa-facebook"></i><?php esc_html_e('Facebook','RSS_TEXTDOMAIN');?>
						</li>
						<li>
							<input type="checkbox" id="rss_twitter"  name="rss_twitter" value="twitter" <?php echo ( $rss_settings['rss_twitter'] == 'twitter' ? 'checked' : '' ) ; ?>>
							<i class="fa fa-twitter"></i><?php esc_html_e('Twitter','RSS_TEXTDOMAIN');?>
						</li>	
						<li>
							<input type="checkbox" id="rss_pinterest" name="rss_pinterest" value="pinterest" <?php echo ( $rss_settings['rss_pinterest'] == 'pinterest' ? 'checked' : '' ) ; ?>>
							<i class="fa fa-pinterest"></i><?php esc_html_e('Pinterest','RSS_TEXTDOMAIN');?>
						</li>	
						<li>
							<input type="checkbox" id="rss_linkedin" name="rss_linkedin" value="linkedin" <?php echo ( $rss_settings['rss_linkedin'] == 'linkedin' ? 'checked' : '' ) ; ?>>
							<i class="fa fa-linkedin"></i><?php esc_html_e('Linkedin','RSS_TEXTDOMAIN');?>
						</li>	
						<li>
							<input type="checkbox" id="rss_googlePlus" name="rss_googlePlus" value="googleplus" <?php echo ( $rss_settings['rss_googlePlus'] == 'googleplus' ? 'checked' : '' ) ; ?>>
							<i class="fa fa-google-plus"></i><?php esc_html_e('Google Plus','RSS_TEXTDOMAIN');?>
						</li>
					</ul>		
				</div>			

			</div>

			<div class="rss-settings">
				<div class="rss_display_option">
					<h2><?php esc_html_e( 'Display position:', 'rigorous-social-share' ); ?> </h2>
					<span class="social-text"><?php esc_html_e( 'Please choose the option where you want to display the social share:','rigorous-social-share'); ?></span>
					<div class="rss-display-option">
						<ul>
							<li>

								<input type="checkbox" name="rss_before_post" id = "rss_before_post" value="before_post" <?php echo ( $rss_settings['rss_before_post'] == 'before_post' ? 'checked' : '' ) ; ?>>
								<?php esc_html_e( 'Show Before Post', 'rigorousweb-social-media' ); ?>
							</li>
							<li>
								<input type="checkbox" name="rss_after_post" id = "rss_after_post" value="after_post" <?php echo ( $rss_settings['rss_after_post'] == 'after_post' ? 'checked' : '' ) ; ?>>
								<?php esc_html_e( 'Show After Post', 'rigorousweb-social-media' ); ?>
							</li>
							<li>
								<input type="checkbox" name="rss_after_page" id = "rss_after_page" value="after_page" <?php echo ( $rss_settings['rss_after_page'] == 'after_page' ? 'checked' : '' ) ; ?>>
								<?php esc_html_e( 'Show After Page', 'rigorousweb-social-media' ); ?>
							</li>
							<li>
								<input type="checkbox" name="rss_before_page" id = "rss_before_page" value="before_page" <?php echo ( $rss_settings['rss_before_page'] == 'before_page' ? 'checked' : '' ) ; ?>>
								<?php esc_html_e( 'Show Before Page', 'rigorousweb-social-media' ); ?>
							</li>
						</ul>

					</div>			
				</div>

				<div class="rss-miscellaneous-settings">
					<h2><?php _e( 'Miscellaneous settings: ', 'rigorousweb-social-media' ); ?> </h2>	

					<div class="rss-counter-settings">
						<h4><?php _e( 'Social share counter enable?', 'rigorousweb-social-media' ); ?> </h4>
						<input type="checkbox" name="rss_enable_couter" id = "rss_enable_couter" value="enable_couter" <?php echo ( $rss_settings['rss_enable_couter'] == 'enable_couter' ? 'checked' : '' ) ; ?>/>

						<?php esc_html_e( 'Enable Social Counter', 'rigorousweb-social-media' ); ?>
						<div class="rss-note">
							<label for="rss_twitter_counter_option">
								<strong>Note:</strong>
								<br/>
								<?php 
								esc_html_e( 'Use', 'rigorousweb-social-media'); 
								?> 
								<a href='	http://opensharecount.com/' target='_blank'>
									OpenShareCount
								</a>
								<?php esc_html_e( ' to show Twitter share counts', 'rigorous-social-share' ); ?>
							</label>
							<div class="rss_notes_cache_settings">To use opensharecount public API, you have to sign up and register your website url <?php echo site_url(); ?> at their <a href='http://opensharecount.com/' target='_blank'>website.</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			<div class=" rss-icon-sets">
				<h2><?php _e( 'Social Icon Theme: ', 'rigorous-social-share' ); ?> </h2>
				<span><?php _e( 'Please choose any one out of available icon themes:', 'rigorous-social-share' ); ?></span>

				<p><input type="radio" name="rss_choose_theme" value="theme_1" <?php echo ( $rss_settings['rss_choose_theme'] == 'theme_1' ? 'checked' : 'checked' ) ; ?>> 
					<?php esc_html_e( 'Theme 1', 'rigorousweb-social-media' ); ?>
					
					<img src='<?php echo RSS_IMAGE_DIR . "/demo-1.png"; ?>'/>
					
				</p>
				<p><input type="radio" name="rss_choose_theme" value="theme_2" <?php echo ( $rss_settings['rss_choose_theme'] == 'theme_2' ? 'checked' : '' ) ; ?>> 
					<?php esc_html_e( 'Theme 2', 'rigorousweb-social-media' ); ?>
					
					<img src='<?php echo RSS_IMAGE_DIR . "/demo-2.png"; ?>'/>
					
				</p>

				<p><input type="radio" name="rss_choose_theme" value="theme_3" <?php echo ( $rss_settings['rss_choose_theme'] == 'theme_3' ? 'checked' : '' ) ; ?>> 
					<?php esc_html_e( 'Theme 3', 'rigorousweb-social-media' ); ?>

					<img src='<?php echo RSS_IMAGE_DIR . "/demo-3.png"; ?>'/>
					
				</p>



			</div>
			<input type="submit" value="Save all changes" name="rss_settings_submit"/>

		</form>
	</div>

</div>