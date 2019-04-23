<?php
/**
 * 自定义方法 类
 * @category WordPress
 * @package  aladdinThemes
 * @author   aladdin
 * @license  MIT
 * */

//移除顶部多余信息
remove_action( 'wp_head', 'feed_links', 2 ); //去除文章feed
remove_action( 'wp_head', 'rsd_link' ); //针对Blog的远程离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' ); //Windows Live Writer接口
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); //移除后面文章的url
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); //移除最开始文章的url
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );//自动生成的短链接
remove_action( 'wp_head', 'wp_generator' ); // 移除版本号
remove_action( 'wp_head', 'index_rel_link' );//当前文章的索引
remove_action( 'wp_head', 'feed_links_extra', 3 );// 额外的feed,例如category, tag页
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // 上、下篇.
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );//rel=pre
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );//rel=shortlink
remove_action( 'wp_head', 'rel_canonical' );
wp_deregister_script( 'l10n' );
remove_action( 'wp_head', 'rsd_link' );//移除head中的rel="EditURI"
remove_action( 'wp_head', 'wlwmanifest_link' );//移除head中的rel="wlwmanifest"
remove_action( 'wp_head', 'rsd_link' );//rsd_link移除XML-RPC
remove_filter( 'the_content', 'wptexturize' );//禁用半角符号自动转换为全角
remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

//Disable the emoji's
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

//禁止emjio
function disable_emojis_tinymce( $plugins ) {
	return array_diff( $plugins, array( 'wpemoji' ) );
}

//禁用页面的评论功能
function disable_page_comments( $posts ) {
	if ( is_page()) {
		$posts[0]->comment_status = 'disabled';
		$posts[0]->ping_status = 'disabled';
	}
	return $posts;
}
add_filter( 'the_posts', 'disable_page_comments' );

// 关闭主题提示
add_filter('pre_site_transient_update_themes',  create_function('$a', "return null;"));

// 禁止 WordPress 更新主题
remove_action('admin_init', '_maybe_update_themes');

//wordpress上传文件重命名
function git_upload_filter($file) {
	$time = date("YmdHis");
	$file['name'] = $time . "" . mt_rand(1, 100) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
	return $file;
}
add_filter('wp_handle_upload_prefilter', 'git_upload_filter');


//文章标题
function website_title() {
	if ( is_home() ) {
		bloginfo( 'name' );
		echo " - ";
		bloginfo( 'description' );
	} elseif ( is_category() ) {
		single_cat_title();
		echo " - ";
		bloginfo( 'name' );
	} elseif ( is_single() || is_page() ) {
		single_post_title();
	} elseif ( is_search() ) {
		echo "搜索结果";
		echo " - ";
		bloginfo( 'name' );
	} elseif ( is_404() ) {
		echo '页面未找到!';
	} else {
		wp_title( '', true );
	}
}

/* 得到page页面别名*/
function get_page_slug($pageId='') {
	global $content,$post;
	if ( is_page() && !$post) {
		$content   = '';
		$content   = $content . get_option( 'display_copyright_text' );
		$post_data = get_post( $post->ID, ARRAY_A );
		$slug      = $post_data['post_name'];
		return $slug;
	}else{
		$post_data = get_post( $pageId, ARRAY_A );
		$slug      = $post_data['post_name'];
		return $slug;
	}
}

/**
 * WordPress 添加面包屑导航
 */
function breadcrumbs() {
	$delimiter = '>'; // 分隔符
	$before = '<span class="current">'; // 在当前链接前插入
	$after = '</span>'; // 在当前链接后插入
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<div itemscope itemtype="http://schema.org/WebPage" id="crumbs">'.__( '', 'cmp' );
		global $post;
		$homeLink = home_url();
		echo ' <a class="link-home" itemprop="breadcrumb" href="' . $homeLink . '">' . __( '<img src='.get_template_directory_uri().'/images/common/header_icon_bread.png >'  , 'cmp' ) . '</a> ' . $delimiter . ' ';
		if ( is_category() ) { // 分类 存档
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} elseif ( is_day() ) { // 天 存档
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) { // 月 存档
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) { // 年 存档
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) { // 文章
			if ( get_post_type() != 'post' ) { // 自定义文章类型
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . get_the_title() . $after;
			} else { // 文章 post
				$cat = get_the_category(); $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) { // 附件
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) { // 页面
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) { // 父级页面
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_search() ) { // 搜索结果
			echo $before ;
			printf( __( 'Search Results for: %s', 'cmp' ),  get_search_query() );
			echo  $after;
		} elseif ( is_tag() ) { //标签 存档
			echo $before ;
			printf( __( 'Tag Archives: %s', 'cmp' ), single_tag_title( '', false ) );
			echo  $after;
		} elseif ( is_author() ) { // 作者存档
			global $author;
			$userdata = get_userdata($author);
			echo $before ;
			printf( __( 'Author Archives: %s', 'cmp' ),  $userdata->display_name );
			echo  $after;
		} elseif ( is_404() ) { // 404 页面
			echo $before;
			echo '404';
			_e( 'Not Found', 'cmp' );
			echo  $after;
		}
		if ( get_query_var('paged') ) { // 分页
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				echo sprintf( __( '( Page %s )', 'cmp' ), get_query_var('paged') );
		}
		echo '</div>';
	}
}

//图片文件夹
function get_pic_uri($path){
	echo get_template_directory_uri().'/images/'.$path;
}

//home_url跳转
function the_home_url($path){
	echo home_url($path);
}