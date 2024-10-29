<?php
/**
 * Plugin Name: AS Scroll to top
 * Plugin URI: http://ashik.freeserver.me/as-scroll-to-top
 * Description: This plugin will add a nice scroll to top button to your website. It has a nice option panel. you can change button's color button's icon and button's bacground color.
 * Version: 1.0
 * Author: Ashikur Rahman
 * Author URI: http://ashik.freeserver.me/as-scroll-to-top
 * License: GPL2
 */




function my_scripts_method() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style('plugins-button-css', plugins_url( 'css/font-awesome.min.css' , __FILE__ ));
}

add_action( 'wp_enqueue_scripts', 'my_scripts_method' );


function custom_css_for_as_scroll () { ?>
		
		<style>
			.scrollToTop, .scrollToTop:hover {
				position: fixed;
				bottom: 30px;
				right: 10px;
				background: #00A4E0;
				color: #fff;
				font-size: 40px;
				line-height: 25px;
				padding: 10px;
				border-radius: 10px;
				color: #fff;
				text-decoration: none;
			}
			<?php $o=get_option('as_plug_options'); echo $o['as_plug_custom_css']; ?>
		</style>

	<?php

}

add_action('wp_head', 'custom_css_for_as_scroll');

function custom_js_for_as_scroll () { 
	$yes='';
	$o=get_option('as_plug_options');
	if(!empty( $o['as_plug_button_icon'] )){
		$yes='yes';
	}else{
		$yes='no';
	}
	?>
		<script>
			jQuery(document).ready(function(){
				jQuery('body').append('<a class="scrollToTop fa fa-angle-up <?php echo $yes; ?> <?php echo $o['as_plug_button_icon']; ?>" href="#"></a>');
				//Check to see if the window is top if not then display button
				jQuery(window).scroll(function(){
					if (jQuery(this).scrollTop() > 30) {
						jQuery('.scrollToTop').fadeIn();
					} else {
						jQuery('.scrollToTop').fadeOut();
					}
				});
				orclass = jQuery('a.scrollToTop').hasClass('yes');
				//mainclass = jQuery('a.scrollToTop').hasClass('scrollToTop fa');
				if ( orclass == true) {
					jQuery('a.scrollToTop').removeClass('fa-angle-up');
				}else {
					
				};
				//Click event to scroll to top
				jQuery('.scrollToTop').click(function(){
					jQuery('html, body').animate({scrollTop : 0},800);
					return false;
				});
				
			});
		</script>

	<?php
	
}

add_action('wp_footer', 'custom_js_for_as_scroll');



/*--------------------This is for option page--------------------*/

class AS_Plug_op {

	public $options;

	public function __construct () {
		//delete_option('as_plug_options');
		$this->options = get_option('as_plug_options');
		$this->register_form_and_fields();
	}

	public function create_menu_page()
	{
		add_menu_page( 'As Scroll to Top Options', 'As Scroll To Top Options', 'administrator', __FILE__, array('AS_Plug_op', 'display_option_form')); //page title, Menu name, who is use it?, page ID, functions
	}
	public function display_option_form()
	{
		?>
		
		<div class="wrap">
			<h2>AS Scroll To Top Options</h2>
			<form action="options.php" method="post" ecntype="multipart/form-data">
				<?php settings_fields('as_plug_options'); ?> <!-- option group -->
				<?php do_settings_sections(__FILE__); ?> <!-- page -->
				<p class="submit">
					<input type="submit" name="submit" class="button button-primary" value="Save Changes"> <!-- creating submit button -->
				</p>
			</form>
		</div>

		<?php
	}
	public function register_form_and_fields()
	{
		register_setting( 'as_plug_options', 'as_plug_options' ); // options group, option name, 3rd param is optional cb func
		add_settings_section( 'as_plug_section', 'Main Settings', array($this, 'as_plug_section_cb'), __FILE__ ); //id, title of section, cb, which page?
		add_settings_field('as_plug_custom_css', 'Custom Css:', array($this, 'as_plug_custom_css_cb'), __FILE__, 'as_plug_section'); //id , title of field, cb func, which page?, which section?
		add_settings_field('as_plug_button_icon', 'Button Icon:', array($this, 'as_plug_button_icon_cb'), __FILE__, 'as_plug_section'); //id , title of field, cb func, which page?, which section?
	}
	
	public function as_plug_section_cb()
	{
		//optional
	}
	/**
	 * inputs
	 */

	/**
	 * Custom css
	 */
	public function as_plug_custom_css_cb()
	{
		echo "<textarea name='as_plug_options[as_plug_custom_css]' cols='30' rows='10'>{$this->options['as_plug_custom_css']}</textarea>";
		echo '<p class="description">Use Your Own Custom CSS for this buttons. Buttons Selector is ".scrollToTop"</p>';
	}
	/**
	 * Button Icon
	 */
	public function as_plug_button_icon_cb()
	{
		echo "<input name='as_plug_options[as_plug_button_icon]' type='text' value='{$this->options['as_plug_button_icon']}'>";
		echo '<p class="description">Did You Know About FontAwesome. You Can Use more than 100\'s of Icon. <a href="http://fontawesome.io/icons/#directional" target="_blank">Click here</a></p>';
	}
}



function as_plug_add_menu () {
	AS_Plug_op::create_menu_page();
}

add_action('admin_menu', 'as_plug_add_menu');

function as_plug_res_settings () {
	new AS_Plug_op();
}

add_action('admin_init', 'as_plug_res_settings');

/*--------------------This is end of code option page--------------------*/


 ?>