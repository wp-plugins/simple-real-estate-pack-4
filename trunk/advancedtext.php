<?php
/*
Plugin Name: Advanced Text Widget
Plugin URI: 
Description: Text widget that has extensive conditional options to display content on pages, posts, specific categories etc. It supports regular HTML as well as PHP code. This widget is an extension of Daiko's Text Widget by Rune Fjellheim.
Author: Max Chirkov
Version: 1.1.0
Author URI: http://www.ibsteam.net
*/
                                                                                                                                                        
class advanced_text extends WP_Widget {

	function advanced_text() {
        //parent::WP_Widget(false, $name = 'Simple Sidebar Navigation');	
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'advanced_text', 'description' => __('Advanced text widget. Raw PHP code support.', 'advanced_text'));

		/* Widget control settings. */
		$control_ops = array( 'width' => 400, 'height' => 450, 'id_base' => 'advanced_text' );

		/* Create the widget. */
		$this->WP_Widget( 'advanced_text', __('Advanced Text', 'advanced_text'), $widget_ops, $control_ops );
    }

	function form($instance) {
 
		$title = apply_filters('widget_title', $instance['title']);
		$allSelected = $homeSelected = $postSelected = $postInCategorySelected = $pageSelected = $categorySelected = $blogSelected = false;
		switch ($instance['action']) {
			case "1":
			$showSelected = true;
			break;
			case "0":
			$dontshowSelected = true;
			break;
		}
		switch ($instance['show']) {
			case "all":
			$allSelected = true;
			break;
			case "":
			$allSelected = true;
			break;
			case "home":
			$homeSelected = true;
			break;
			case "post":
			$postSelected = true;
			break;
			case "post_in_category":
			$postInCategorySelected = true;
			break;
			case "page":
			$pageSelected = true;
			break;
			case "category":
			$categorySelected = true;
			break;
			case "blog": //Max' Custom Addition
			$blogSelected = true;
			break;
		}
	?>
				<label for="<?php echo $this->get_field_id('title'); ?>" title="Title above the widget">Title:<input style="width:400px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label> 
				<p>PHP Code (MUST be enclosed in &lt;?php and ?&gt; tags!):</p>
				<label for="<?php echo $this->get_field_id('text'); ?>" title="PHP Code (MUST be enclosed in &lt;?php and ?&gt; tags!):"><textarea id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" cols="20" rows="16" style="width:400px;"><?php echo $instance['text']; ?></textarea></label>
				<label for="<?php echo $this->get_field_id('action'); ?>"  title="Show only on specified page(s)/post(s)/category. Default is All" style="line-height:35px;"><select name="<?php echo $this->get_field_name('action'); ?>"><option value="1" <?php if ($showSelected){echo "selected";} ?>>Show</option><option value="0" <?php if ($dontshowSelected){echo "selected";} ?>>Do NOT show</option></select> only on: <select name="<?php echo $this->get_field_name('show'); ?>" id="<?php echo $this->get_field_id('show'); ?>"><option label="All" value="all" <?php if ($allSelected){echo "selected";} ?>>All</option><option label="Home" value="home" <?php if ($homeSelected){echo "selected";} ?>>Home</option><option label="Post" value="post" <?php if ($postSelected){echo "selected";} ?>>Post(s)</option><option label="Post in Category ID(s)" value="post_in_category" <?php if ($postInCategorySelected){echo "selected";} ?>>Post In Category ID(s)</option><option label="Page" value="page" <?php if ($pageSelected){echo "selected";} ?>>Page(s)</option><option label="Category" value="category" <?php if ($categorySelected){echo "selected";} ?>>Category</option><option label="Blog" value="blog" <?php if ($blogSelected){echo "selected";} ?>>Blog Main Page, Posts and Archives</option></select></label> 
				<label for="<?php echo $this->get_field_id('slug'); ?>"  title="Optional limitation to specific page, post or category. Use ID, slug or title.">Slug/Title/ID: <input type="text" style="width: 250px;" id="<?php echo $this->get_field_id('slug'); ?>" name="<?php echo $this->get_field_name('slug'); ?>" value="<?php echo htmlspecialchars($instance['slug']); ?>" /></label>
				<?php if ($postInCategorySelected) echo "<p>In <strong>Post In Category</strong> add one or more cat. IDs (not Slug or Title) comma separated!</p>" ?>
				<br /><label for="<?php echo $this->get_field_id('suppress_title'); ?>"  title="Do not output widget title in the front-end."><input idx="<?php echo $this->get_field_name('suppress_title'); ?>" name="<?php echo $this->get_field_name('suppress_title'); ?>" type="checkbox" value="1" <?php checked($instance['suppress_title'],'1', true);?> /> Suppress Title Output</label>
				
	<?php
	}
	
	function update($new_instance, $old_instance) {			
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['text'] = $new_instance['text'];
		$instance['action'] = $new_instance['action'];
		$instance['show'] = $new_instance['show'];
		$instance['slug'] = $new_instance['slug'];
		$instance['suppress_title'] = $new_instance['suppress_title'];
		return $instance;
	}
	
	function widget($args, $instance) {
		extract($args);
		if(empty($instance['suppress_title'])){
			$title 	 = $instance['title'];
		}
			
		$text 	 = $text = apply_filters( 'widget_advanced_text', $instance['text'] );
		$action  = $instance['action'];
		$show 	 = $instance['show'];
		$slug 	 = $instance['slug'];				
		?>
		<?php 
 /* Do the conditional tag checks. */
		if($action == "1"){
			switch ($show) {
				case "all": 
					echo $before_widget;
					echo "<div class='AdvancedText'>"; 
					$title ? print($before_title . $title . $after_title) : null;
					eval('?>'.$text);
					echo "</div>"; 
					echo $after_widget."
					";		
					break;
				case "home":
					if (is_home()) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
							";				}
					break;
				case "post":
					$PiD = explode(",",$slug);
					$onPage = false;
					foreach($PiD as $PageID) {
						if (is_single($PageID)) {
							$onPage = true;
						}
					}
					if ($onPage) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				case "post_in_category":
					$PiC = explode(",",$slug);
					$InCategory = false;
					foreach($PiC as $CategoryID) {
						if(is_single() && in_category($CategoryID)){
								$InCategory = true;
						}
						elseif (is_category($CategoryID)) {
							$InCategory = true;
						}
					}
					if ($InCategory) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				case "page":
					$PiD = explode(",",$slug);
					$onPage = false;			
					foreach($PiD as $PageID) {						
						if (is_page($PageID)) {
							$onPage = true;
						}else{
							$onPage = false;
						}
					}
					if (is_page($PiD)) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				case "category":
					if (is_category($slug)) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				//Max' Custom Addition
				case "blog":
					if (is_home($slug) || is_single() || is_archive()) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				
					}
			}
		}else{
			switch ($show) {
				case "all": 					
					break;
				case "home":
					if (!is_home()) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
							";				}
					break;
				case "post":
					$PiD = explode(",",$slug);
					$onPage = false;
					foreach($PiD as $PageID) {
						if (is_single($PageID)) {
							$onPage = true;
						}
					}
					if (!$onPage) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				case "post_in_category":
					$PiC = explode(",",$slug);
					$InCategory = false;
					foreach($PiC as $CategoryID) {
						if(is_single() && in_category($CategoryID)){
							$InCategory = true;
						}
						elseif (is_category($CategoryID)) {
							$InCategory = true;
						}
					}
					if (!$InCategory) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				case "page":
					$PiD = explode(",",$slug);
					$onPage = false;
					foreach($PiD as $PageID) {
						if (is_page($PageID)) {
							$onPage = true;
						}
					}
					if (!$onPage) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				case "category":
					if (!is_category($slug)) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				}
					break;
				//Max' Custom Addition
				case "blog":
					if (!is_home($slug) && !is_single() && !is_archive()) {
						echo $before_widget;
						echo "<div class='AdvancedText'>"; 
						$title ? print($before_title . $title . $after_title) : null;
						eval('?>'.$text);
						echo "</div>"; 
						echo $after_widget."
						";				
					}
			}
		}
		?>
	<?php
	}
		
}

function advanced_text_do_shortcode(){
	if (!is_admin()){
		add_filter('widget_text', 'do_shortcode', SHORTCODE_PRIORITY);
		add_filter('widget_advanced_text', 'do_shortcode', SHORTCODE_PRIORITY);
	}
}
	
// Tell Dynamic Sidebar about our new widget and its control
add_action('widgets_init', create_function('', 'return register_widget("advanced_text");'));
add_action('widgets_init', 'advanced_text_do_shortcode');
?>