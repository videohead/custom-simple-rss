<?php
/**
/*
 * Plugin Name:   Custom Simple Rss
 * Plugin URI:    
 * Description:   A plugin to create a Custom Simple RSS Feed according to chosen parameters
 * Version:       1.5
 * Author:        Danny(Danikoo) Haggag 
 * Author URI:    http://www.danikoo.com
 * License: GPLv2 or later
 */


if ( is_admin() ){
    require_once dirname( __FILE__ ) . '/custom-simple-rss-admin.php';

    //============ create settings link at plugins page =================//
    function custom_simple_rss_plugin_action_links( $links, $file ) {
        if ( $file == plugin_basename( dirname(__FILE__).'/custom-simple-rss.php' ) ) {
            $links[] = '<a href="' . admin_url( 'admin.php?page=custom-simple-rss-admin-options' ) . '">'.__( 'Settings' ).'</a>';
            }
            return $links;
        }
        add_filter('plugin_action_links', 'custom_simple_rss_plugin_action_links', 10, 2);
}
 
function call_custom_simple_rss(){
	
   
    $custom_simple_rss_options = get_option('custom_simple_rss_options');
	if(is_array($custom_simple_rss_options)===false){
		//set defaults and return array 
		$custom_simple_rss_options = custom_simple_rss_set_defults();
	}
	
	extract($custom_simple_rss_options);

	if( isset($_GET["csrp_debug"]) ) $csrp_debug = intval($_GET["csrp_debug"]);
	if( isset($_GET["csrp_show_meta"]) ) $csrp_show_meta = intval($_GET["csrp_show_meta"]);
	if( isset($_GET["csrp_cat"]) ) $csrp_cat = sanitize_text_field($_GET["csrp_cat"]);
	if( isset($_GET["csrp_meta_key"]) ) $csrp_meta_key = sanitize_text_field($_GET["csrp_meta_key"]);
	if( isset($_GET["csrp_meta_value"]) ) $csrp_meta_value = sanitize_text_field($_GET["csrp_meta_value"]);
	if( isset($_GET["csrp_meta_compare"]) ){
		$csrp_meta_compare = sanitize_text_field($_GET["csrp_meta_compare"]);	
	}else{
		$csrp_meta_compare = 'IN';	
	} 
	if( isset($_GET["csrp_orderby"]) ) $csrp_orderby = sanitize_text_field($_GET["csrp_orderby"]);
	if( isset($_GET["csrp_order"]) ) $csrp_order = sanitize_text_field($_GET["csrp_order"]);
	if( isset($_GET["csrp_tag"]) ) $csrp_tag = sanitize_text_field($_GET["csrp_tag"]);
	if( isset($_GET["csrp_author_name"]) ) $csrp_author_name = sanitize_text_field($_GET["csrp_author_name"]);
	if( isset($_GET["csrp_author"]) ) $csrp_author = sanitize_text_field($_GET["csrp_author"]);
	
	if( isset($_GET["csrp_post_type"]) ){
		$csrp_post_type = sanitize_text_field($_GET["csrp_post_type"]);	
	}
	
	if( isset($_GET["csrp_post_status"]) ){
		$csrp_post_status = sanitize_text_field($_GET["csrp_post_status"]);	
	}
	
	if( isset($_GET["csrp_posts_per_page"]) ){
		$csrp_posts_per_page = intval($_GET["csrp_posts_per_page"]);	
	}
	
	$args = array(
		'post_type' => $csrp_post_type,
		'showposts' => $csrp_posts_per_page, 
		'post_status'=>$csrp_post_status,
		'ignore_sticky_posts' => true,
	);
	
	if( isset($csrp_cat) && $csrp_cat!='' ){
		$args['cat'] =  $csrp_cat;
	}
	if( isset($csrp_tag) && $csrp_tag!='' ){
		$args['tag'] =  $csrp_tag;
	}	
	if( isset($csrp_author) && $csrp_author!='' ){
		$args['author'] =  $csrp_author;
	}	
	if( isset($csrp_author_name) && $csrp_author_name!='' ){
		$args['author_name'] =  $csrp_author_name;
	}		
	if(  isset($csrp_orderby) && isset($csrp_order) ){
		$args['orderby'] =  $csrp_orderby;
		$args['order'] =  $csrp_order;
	}
	
	if( isset($csrp_meta_key) && isset($csrp_meta_value) ){
		$args['meta_query'] = array(
				array(
					'key'     => $csrp_meta_key,
					'value'   => $csrp_meta_value,
					'compare' => $csrp_meta_compare,
				)
			);		
	}

	
	//print_r($args);
	$the_query = new WP_Query( $args );
	
	$csrp_feed_current = '<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/" >
		<channel>
		<title>'.get_bloginfo("name").'</title>
		<lastBuildDate>'.  mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false) .'</lastBuildDate>';
		
		if(isset($csrp_debug)&& $csrp_debug=='1') $csrp_feed_current .=	'<debug>'.json_encode($args).'</debug>';


			while ( $the_query->have_posts() ) :
			$the_query->the_post();		
			$post_id = get_the_ID();
			$categories = get_the_category();
			$collection = null;
			if($categories){
				foreach($categories as $category) {
					$collection.= '<category>'.$category->term_id.'</category>';
					}
				}
			
			$custom_fields = get_post_custom($post_id);
			$dataset = null;
			foreach ( $custom_fields as $key => $value ) {
				$dataset.= "<".$key."><![CDATA[". $value[0] ."]]></".$key.">";
			}

			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail' );
			$thumb_url = $thumb['0'];
			
			if($csrp_show_content==1){
				$the_content = get_the_content();	
			}
			if($csrp_show_content==2){
				$the_content = apply_filters('the_content',get_the_content());
				//clear content from trash
				$allowed_tags = "<img><a><b><strong><i><li><left><center><right><del><strike><ol><ul><u><sup><pre><code><sub><hr><h1><h2><h3><h4><h5><h6><big><small><font><p><br><span><div><script><video><audio><dd><dl>";
				$the_content = htmlspecialchars_decode($the_content);
				$the_content = strip_tags($the_content,$allowed_tags);
				$the_content = preg_replace("/\r?\n/", "", $the_content);
				$the_content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $the_content);
				$the_content = preg_replace('/\s+/',' ',$the_content); //tabs
				$the_content = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $the_content);
			}
				$csrp_feed_current .='
				<item>
						<title><![CDATA['. get_the_title() .']]></title>
						<link>'. get_permalink() .'</link>
						<pubDate>'. get_the_date() .'</pubDate>
						<dc:creator>'. get_the_author() .'</dc:creator>
						<dc:identifier>'.  $post_id .'</dc:identifier>
						<dc:modified>'. get_the_modified_time('Y-m-d H:i:s').'</dc:modified>
						<dc:created unix="'.  strtotime(get_the_date('Y-m-d H:i:s')).'">'. get_the_date('Y-m-d H:i:s') .'</dc:created>
						<guid isPermaLink="true">'. get_permalink() .'</guid>'
						.$collection.'
						<description><![CDATA['. get_the_excerpt().']]></description>
						';
						if($csrp_show_content!=0){
							$csrp_feed_current .='<content:encoded><![CDATA['. $the_content .']]></content:encoded>';	
						}							
						if($csrp_show_thumbnail==1){
							$csrp_feed_current .='<enclosure url="'. $thumb_url .'"/>';	
						}						
						if($csrp_show_meta==1){
							$csrp_feed_current .='<dc:dataset>'. $dataset .'</dc:dataset>';	
						}
				$csrp_feed_current .='		
				</item>';

			endwhile; 
			/* Restore original Post Data */
			wp_reset_postdata();
		
	$csrp_feed_current .='</channel></rss>';
	header('Content-Type: application/rss+xml; charset=utf-8');
	echo $csrp_feed_current;	 
 }


add_filter( 'query_vars', 'custom_simple_rss_query_vars' );
function custom_simple_rss_query_vars( $query_vars ){
    $query_vars[] = 'call_custom_simple_rss';
    return $query_vars;
}

add_action( 'parse_request', 'custom_simple_rss_parse_request' );
function custom_simple_rss_parse_request( $wp )
{
    if ( array_key_exists( 'call_custom_simple_rss', $wp->query_vars ) ) {
		$call_custom_simple_rss = $wp->query_vars['call_custom_simple_rss'];
		if($call_custom_simple_rss=='1') call_custom_simple_rss();
		die();
    }
}

register_activation_hook(__FILE__, 'custom_simple_rss_activation');
function custom_simple_rss_activation() {
		$custom_simple_rss_options	= array(
				'csrp_post_type'=> 'post',
				'csrp_post_status'=> 'publish',
				'csrp_posts_per_page'=> 10,
				'csrp_show_meta'=> 0,
				'csrp_show_thumbnail'=> 1,
				'csrp_show_content'=> 1,				
		);
		update_option('custom_simple_rss_options',$custom_simple_rss_options);
}

register_deactivation_hook(__FILE__, 'custom_simple_rss_deactivation');
function custom_simple_rss_deactivation() {
	delete_option( 'custom_simple_rss_options' );
}

function custom_simple_rss_set_defults(){
		$custom_simple_rss_options	= array(
				'csrp_post_type'=> 'post',
				'csrp_post_status'=> 'publish',
				'csrp_posts_per_page'=> 10,
				'csrp_show_meta'=> 0,
				'csrp_show_thumbnail'=> 1,	
				'csrp_show_content'=> 1,				
		);
		update_option('custom_simple_rss_options',$custom_simple_rss_options);
    return $custom_simple_rss_options;
}