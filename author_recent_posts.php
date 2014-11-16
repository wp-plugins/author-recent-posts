<?php
/*
Plugin Name: Author Recent Posts
Plugin URI: http://dev.fellowtuts.com/author-recent-posts-plugin/
Description: Author Recent Posts plugin shows recent posts by an author on his/her posts as a responsive sidebar widget
Version: 1.3
Author: Amit Sonkhiya, Kamal Agrawal
Author URI: http://dev.fellowtuts.com
License: GPLv2 or later
*/

 
 class authorRecentPosts extends WP_Widget {

	// constructor
	function authorRecentPosts() {
		parent::__construct(
		'author_recent_posts', // Base ID
			'Author Recent Posts', // Name
			array( 'description' => __( 'Display recent posts by an author on his/her posts as a responsive sidebar widget', 'text_domain' ) ) // Args
		);
	}
	
	
	// widget form creation
	function form($instance) {	
	// Check values	
	$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
	$numberofposts   = isset( $instance['numberofposts'] ) ? absint( $instance['numberofposts'] ) : 5;
	$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
	$showthumbnail = isset( $instance['showthumbnail'] ) ? (bool) $instance['showthumbnail'] : false;
	$width = isset( $instance['width'] ) ?  esc_attr($instance['width']) : '';
	$height = isset( $instance['height'] ) ? esc_attr( $instance['height']) : '';
	$alternateImg = isset( $instance['alternateImg'] ) ? esc_attr( $instance['alternateImg']) : '';
	
		?>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id('numberofposts'); ?>"><?php _e('Number of posts to show:', 'wp_widget_plugin'); ?></label>
	<input  id="<?php echo $this->get_field_id('numberofposts'); ?>" name="<?php echo $this->get_field_name('numberofposts'); ?>" type="text"  size="3" value="<?php echo $numberofposts; ?>" />
	</p>
    
	<p>
	
	<input class="checkbox showthumbnail" id="<?php echo $this->get_field_id('showthumbnail'); ?>" name="<?php echo $this->get_field_name('showthumbnail'); ?>" type="checkbox" <?php checked( $showthumbnail ); ?> /><label for="<?php echo $this->get_field_id('showthumbnail'); ?>"><?php _e('Show Thumbnail?', 'wp_widget_plugin'); ?></label>
    
	</p>
    <div class="thumbnailAttr">
    <p>
		<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width: ' ); ?></label> 
		<input  size="3" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo $width; ?>" /> px
		</p>
        <p>
		<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height: ' ); ?></label> 
		<input  size="3"  id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $height; ?>" /> px
		</p>
        
          <p>
	<label for="<?php echo $this->get_field_id('alternateImg'); ?>"><?php _e('Alternate image url:', 'wp_widget_plugin'); ?></label>
	<input class="widefat"  id="<?php echo $this->get_field_id('alternateImg'); ?>" name="<?php echo $this->get_field_name('alternateImg'); ?>" type="text"   value="<?php echo $alternateImg; ?>" />
	</p>
        
        </div>
    
    <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
     
    
<?php	}

	// widget update
	function update($new_instance, $old_instance) {
		
		$old_instance['title'] = $new_instance['title'];		
		$old_instance['numberofposts'] = isset($new_instance['numberofposts'])?(int)$new_instance['numberofposts']:'';
		
		$old_instance['showthumbnail'] = isset( $new_instance['showthumbnail'] ) ? (bool) $new_instance['showthumbnail'] : false;
		$old_instance['width'] = isset($new_instance['width'])?$new_instance['width']:'';
		$old_instance['height'] = isset($new_instance['height'])?$new_instance['height']:'';
		$old_instance['alternateImg'] = isset($new_instance['alternateImg'])?$new_instance['alternateImg']:'';
		$old_instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		
		return $old_instance ;
	
	}
	
	
	
	// widget display
	function widget($args, $instance) {
			extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Author Recent Posts' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$number = ( ! empty( $instance['numberofposts'] ) ) ? absint( $instance['numberofposts'] ) : 5;
		$showthumbnail = isset( $instance['showthumbnail'] ) ? $instance['showthumbnail'] : false;
		$width_image = empty($instance['width']) ? '50' : apply_filters('widget_image_width', $instance['width']);
        $height_image = empty($instance['height']) ? '50' : apply_filters('widget_image_height', $instance['height']);
		$alternateImg = !empty($instance['alternateImg']) ? $instance['alternateImg']:'';
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		
	
        if(is_single()){
		 global $authordata, $post;
         
         $authors_posts = get_posts( array( 'author' => $authordata->ID, 
		'post__not_in' => array( $post->ID ), 'posts_per_page' => $number ) );
	if (count($authors_posts) > 0) :
		?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) 
			{
				$author = get_the_author_meta( 'display_name', $authordata->ID );
				$title = str_replace('[author]', $author, $title);
				echo $before_title . $title . $after_title; 
			}
		?>
		<ul class="author_post">
		<?php foreach ( $authors_posts as $authors_post ) { ?>
			<li>
            <?php if($showthumbnail) : ?>
            <div class="author_left" style="width:<?php echo $width_image; ?>px;height:<?php echo $height_image; ?>px;">           
            <?php
			if( $showthumbnail ){
				if( has_post_thumbnail( $authors_post->ID )){
				?>
                 <a href="<?php echo get_permalink( $authors_post->ID ) ; ?>">
                <?php
				echo get_the_post_thumbnail($authors_post->ID, array($width_image,$height_image)); ?>
                </a>
			<?php }elseif($alternateImg != ''){?>
				
				<a href="<?php echo get_permalink( $authors_post->ID ) ; ?>">
                <img src="<?php echo $alternateImg; ?>" width="<?php echo $width_image; ?>" height="<?php echo $height_image; ?>" class="wp-post-image" />
                </a>
				
			<?php } }?>
            
            </div>
            <?php endif; ?>
            <div class="author_right">
            <a href="<?php echo get_permalink( $authors_post->ID ) ; ?>">
            <?php			
            echo apply_filters( 'the_title', $authors_post->post_title, $authors_post->ID ); ?>
            </a>
			<?php if ( $show_date ) : ?>
				<br /><span class="post-date"><small><?php echo date(get_option('date_format'),strtotime($authors_post->post_date)); ?></small></span>
			<?php endif; ?>
            </div>
			</li>
		<?php } ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();		
		endif ;
		} // single page condition ends here
	}// widget function end here

	
 } // class end tag
// register widget
function admin_author_recent_posts_js() {
            wp_register_script('admin_author_script', plugins_url('js/authorrecentposts.js', __FILE__) );
            wp_enqueue_script('admin_author_script');
        }
add_action ('admin_enqueue_scripts','admin_author_recent_posts_js');
add_action('widgets_init', create_function('', 'return register_widget("authorRecentPosts");'));

/* Register the style sheet */

function author_recent_posts_stylesheet() {
            wp_register_style('author_stylesheet', plugins_url('css/authorPostsStyle.css', __FILE__) );
            wp_enqueue_style('author_stylesheet');
        }
add_action('wp_enqueue_scripts', 'author_recent_posts_stylesheet');
 
?>