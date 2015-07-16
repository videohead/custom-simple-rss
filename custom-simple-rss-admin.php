<?php
define('CUSTOM_SIMPLE_RSS_VERSION', '1');
define('CUSTOM_SIMPLE_RSS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('CUSTOM_SIMPLE_RSS_PLUGIN_ADMIN_FILE', plugin_dir_path(__FILE__)."custom-simple-rss-admin.php" );

//============ wp_enqueue_style and scripts and options page =================//

//register admin menu
function custom_simple_rss_admin_menu() {
	add_options_page( 'Custom Simple Rss Plugin Options', 'Custom Simple Rss Plugin', 'manage_options', 'custom-simple-rss-admin-options', 'custom_simple_rss_options' );
    add_action( 'admin_print_styles-' . $page, 'custom_simple_rss_admin_styles' );
}
add_action( 'admin_menu', 'custom_simple_rss_admin_menu' );

//register styles
function custom_simple_rss_admin_init() {
    wp_register_style( 'custom-simple-rss-admin-css', plugins_url('custom-simple-rss-admin.css', __FILE__),array(),time() );
    wp_enqueue_style( 'custom-simple-rss-admin-css' );
	wp_enqueue_script('custom-simple-rss-js', plugins_url('custom-simple-rss.js', __FILE__), array(), '1.1'.time());
}
add_action( 'admin_init', 'custom_simple_rss_admin_init' );




function custom_simple_rss_options(){

	custom_simple_rss_get_form_data();
   
    $custom_simple_rss_options = get_option('custom_simple_rss_options');
    extract($custom_simple_rss_options);

    //defaults 
    if(!$csra_post_type) $csrp_post_type = 'post';
    if(!$csrp_post_status) $csrp_post_status = 'publish';
	if(!$csrp_posts_per_page) $csrp_posts_per_page = 20;
	if(!$csrp_show_meta) $csrp_show_meta = 0;
	if(!$csrp_show_thumbnail) $csrp_show_thumbnail = 1;	
    ?>

	
    <div class="wrap" id="custom-simple-rss-admin-wrapper" style="direction:ltr;">
		<div class="tabs">		
		  <div class="tab on" id="1"><h2>Examples</h2></div>
		  <div class="tab" id="2"><h2>Parameters</h2></div>
		  <div class="tab" id="3"><h2>Set Defaults</h2></div>
		</div>	
		<div class="postbox on" id="postbox_1">
			<div class="inside ">
				<h2>Examples</h2>
				<h3>see full specs and options in Parameters tab 
				<hr>
				<p>
					<h3>call a simple rss with defaults:</h3>
					<a href="<?php echo site_url() ?>?call_custom_simple_rss=1" target="_blank">
					<?php echo site_url() ?>?call_custom_simple_rss=1
					</a>			
					<ul>
					<li>post type: post</li>
					<li>post status: publish</li>
					<li>posts per page: 20</li>
					</ul>
				</p>
				<p>
					<h3>call 5 items only. order by name descending</h3>
					<a href="<?php echo site_url() ?>?call_custom_simple_rss=1&csrp_posts_per_page=5&csrp_orderby=name&csrp_order=DESC" target="_blank">
					<?php echo site_url() ?>?call_custom_simple_rss=1&csrp_posts_per_page=5&csrp_orderby=name&csrp_order=DESC
					</a>			
					<ul>
					<li>post type: post</li>
					<li>post status: publish</li>
					<li>posts per page: 5</li>
					<li>order by: name</li>
					<li>order: descending</li>
					</ul>
				</p>			

			
			</div>
		</div>
		<div class="postbox" id="postbox_2">
			<div class="inside">
				<h2>Parameters</h2>
				<h3>How to call the URL and what each parameter does... test it don`t be shy :) </h3>
				<hr>
	<pre>
	&middot; csrp_cat (string | optional)
		Display posts that have this category (and any children of that category), using category id
		'?call_custom_simple_rss=1&csrp_cat=4'
		
		Display posts that have these categories, using category id
		'?call_custom_simple_rss=1&csrp_cat=2,6,17,38'
		
		Display all posts except those from a category by prefixing its id with a '-' (minus) sign			
		'?call_custom_simple_rss=1&csrp_cat=-12,-34,-56'
		
	&middot; csrp_author (string | optional)
		Display posts by author, using author id:
		'?call_custom_simple_rss=1&csrp_author=5'
		
		Show Posts From Several Authors:
		'?call_custom_simple_rss=1&csrp_author=2,6,17,38'
		
		Exclude Posts Belonging to an Author:
		'?call_custom_simple_rss=1&csrp_author=-5'
	
	&middot; csrp_author_name (string | optional)
		Display posts by author, using author 'user_nicename':
		'?call_custom_simple_rss=1&csrp_author_name=john'
		
	&middot; csrp_posts_per_page (int | optional) - default 20
		show only 5 posts
		'?call_custom_simple_rss=1&csrp_posts_per_page=5'
	
	&middot; csrp_orderby (string | optional) - default ‘date’
		'ID' - Order by post id. Note the capitalization.
		'author' - Order by author.
		'name' - Order by post name (post slug).
		'date' - Order by date.
		'modified' - Order by last modified date.
		'rand' - Random order.
		
		Display posts order by name:
		'?call_custom_simple_rss=1&csrp_orderby=name'
	
	&middot; csrp_order - (string | optional) - default ‘asc’
		asc
		desc	

		Display posts order by name descending:
		'?call_custom_simple_rss=1&csrp_orderby=name&csrp_order=DESC'		
		
	&middot; csrp_post_status (string | optional) - default ‘publish’
		‘publish’
		'pending' - post is pending review.
		'draft' - a post in draft status.
		'future' - a post to publish in the future.
		'trash' - post is in trashbin 
		'any' - retrieves any status except those from post statuses with 'exclude_from_search' set to true (i.e. trash and auto-draft).
		
		Display only future posts:
		'?call_custom_simple_rss=1&csrp_post_status=future'			
	
	&middot; csrp_post_type (string | optional) - default ‘post’
		post
		page
		any custom post defined by blog
		
		Display Pages not Posts:
		'?call_custom_simple_rss=1&csrp_post_type=page'	

		Display custom post types (if any):
		'?call_custom_simple_rss=1&csrp_post_type=books'		
		

	<u>filter by meta:</u>
	any meta value that exists in post
	&middot; csrp_meta_key (string | optional) - Custom field key.
	&middot; csrp_meta_value (string | optional) Custom field value.<b>!must be specified if meta_key present</b>
	&middot; csrp_meta_compare  (string | optional) default ‘IN’
		'LIKE'
		'NOT LIKE'
		'IN'
		'NOT IN'
		'BETWEEN'
		'NOT BETWEEN'
		'NOT EXISTS'
		
		Display post with meta_key '_thumbnail_id' and meta_value 1448:
		'?call_custom_simple_rss=1&csrp_posts_per_page=5&csrp_show_meta=1&csrp_meta_key=_thumbnail_id&csrp_meta_value=1448'
		
		Display post with meta_key '_thumbnail_id' and meta_value NOT 1448:
		?call_custom_simple_rss=1&csrp_posts_per_page=5&csrp_show_meta=1&csrp_meta_key=_thumbnail_id&csrp_meta_value=1448&csrp_meta_compare=NOT%20IN
	
	<u>show post meta in feed:</u>	
	enables the option to show all custom post fields for post. 
	quite handy if you need the rss as an xml data for external applications or export
	disabled by default.
	&middot; csrp_show_meta (string | optional) - default 0
		'?call_custom_simple_rss=1&csrp_show_meta=1'
	
	<u>show post thumbnail in feed:</u>	
	&middot; csrp_show_thumbnail (string | optional) - default 1 (show)
	
	</pre>
		</div>	
		</div>
		<div class="postbox" id="postbox_3">				
			<div class="inside">
			<form action="" method="POST" id="custom-simple-rss-form" >

				<h2>Set Defaults</h2>
				<hr>
				<h3>
				What ever you set here will effect all rss feeds defaults, UNLESS you choose otherwise by url query Parameters.(see Examples and Parameters tab for more info)
				</h3>
				<div class="custom-simple-rss-admin-row">
					<div class="custom-simple-rss-admin-label">post type:</div>
					<input type="text" name="csrp_post_type" value="<?php echo $csrp_post_type ?>">
				</div>

				<div class="custom-simple-rss-admin-row">
					<div class="custom-simple-rss-admin-label">post_status:</div>
					<input type="text" name="csrp_post_status" value="<?php echo $csrp_post_status ?>">
				</div>
		 
				<div class="custom-simple-rss-admin-row">
					<div class="custom-simple-rss-admin-label">posts_per_page:</div>
					<input type="text" name="csrp_posts_per_page" value="<?php echo $csrp_posts_per_page ?>">
				</div>
				<div class="custom-simple-rss-admin-row">
					<div class="custom-simple-rss-admin-label">show post meta in feed:</div>
					<input type="text" name="csrp_show_meta" value="<?php echo $csrp_show_meta ?>">
				</div>
				<div class="custom-simple-rss-admin-row">
					<div class="custom-simple-rss-admin-label">show post thumbnail in feed:</div>
					<input type="text" name="csrp_show_thumbnail" value="<?php echo $csrp_show_thumbnail ?>">
				</div>
				
				<input type="hidden" name="page" value="custom-simple-rss-admin-options">
				<input type="submit" value="GO">
			</form>				
			</div>		
		</div>
	</div>
   <?php
}

function custom_simple_rss_get_form_data(){
    
    if($_POST){
		
		$custom_simple_rss_options = array(
			
				'csrp_post_type'=> sanitize_text_field( $_POST["csrp_post_type"] ),
				'csrp_post_status'=> sanitize_text_field( $_POST["csrp_post_status"] ),
				'csrp_posts_per_page'=> intval( $_POST["csrp_posts_per_page"] ),
				'csrp_show_meta'=> intval( $_POST["csrp_show_meta"] ),
				'csrp_show_thumbnail'=> intval( $_POST["csrp_show_thumbnail"] ),
		
		);
        update_option('custom_simple_rss_options',$custom_simple_rss_options); 
       
    }
};