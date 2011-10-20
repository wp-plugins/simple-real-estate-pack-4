<?php
if(!class_exists('Plugin_Admin_Class')){
	require_once ATW_LIB . '/wp_plugin_admin.php';
}

class atw_Admin extends Plugin_Admin_Class {
    var $hook		= 'atw';
    var $longname	= 'Advanced Text Widget Options';
    var $shortname	= 'ATW Plugin';
    var $filename	= 'advanced-text-widget/advancedtext.php';
    var $optionname	= 'atw';
    var $menu		= true;
    var $prefix		= 'atw_';

    var $credits = array(
                    'download_url'  => 'http://wordpress.org/extend/plugins/advanced-text-widget/', //plugin page on wp.org
                    'official_url'  => 'http://www.ibsteam.net/blog/web-development/advanced-text-widget-wordpress', //plugin page on author's website
                    'author_url'    => 'http://wordpress.org/extend/plugins/profile/maxchirkov',
                    'sponsored_by'  => '<a href="http://www.ibsteam.net">ibsTeam.net</a>',
                    'forums_url'    => 'http://wordpress.org/tags/advanced-text-widget?forum_id=10',
                );
    var $default_options = array(
        'condition' => array(
            array(
                'name'  => 'All',
                'code'  => 'true',
                ),
            array(
                'name'  => 'Home Page',
                'code'  => 'is_home()',
                ),
            array(
                'name'  => 'Front Page',
                'code'  => 'is_front_page()',
                ),
            array(
                'name'  => 'Page',
                'code'  => 'is_page($arg)',
                ),
            array(
                'name'  => 'Single Post',
                'code'  => 'is_single($arg)',
                ),
            array(
                'name'  => 'Post in Category',
                'code'  => 'in_category($arg)',
                ),
            array(
                'name'  => 'Category',
                'code'  => 'is_category($arg)',
                ),
            array(
                'name'  => 'Blog',
                'code'  => 'is_home() || is_single() || is_archive()',
                ),
            array(
                'name'  => 'Search Results Page',
                'code'  => 'is_search()',
                ),
            ),
        );
	
    //update from old widgets to new
    function auto_update(){
        if($widgets = get_option('widget_advanced_text')){

            $convert = array(
                'all'               => 0,
                'home'              => 1,
                'post'              => 4,
                'post_in_category'  => 5,
                'page'              => 3,
                'category'          => 6,
                'blog'              => 7,
                'search'            => 8,
                );
                $new_widgets = $widgets;
            //check is any 'show' keys have numeric values 
            $update = false;           
            foreach($new_widgets as $key => $widget){
                if(isset($widget['show']) && !is_numeric($widget['show'])){
                    $new_widgets[$key]['show'] = $convert[$widget['show']];
                    $update = true;
                }
            }                        

            if($update){
                update_option('widget_advanced_text', $new_widgets);
                add_option('widget_advanced_text_old');
                update_option('widget_advanced_text_old', $widgets);
            }
        }
    }

	function settings($key = null){
        
        $add_button = array(
                    //'label'       => __('Add New Condition'),                    
                    'id'        => 'condition-add-new',
                    'type'      => 'input',
                    'attr'      => array('type' => 'button', 'value' => 'Add New Condition', 'class' => 'button'),
                );
    
       $conditions = array(                
                array(
                    'label'     => __('Name 1'),                    
                    'id'        => array('condition', 0, 'name'),
                    'type'      => 'text',
                    'attr'      => array('size' => 30),                    
                ),
                array(
                    'label'     => __('Code 1'),                    
                    'id'        => array('condition', 0, 'code'),
                    'type'      => 'text',
                    'attr'      => array('size' => 60),                
                ),                 
                $add_button          
            );

       if($options = $this->options){        
           if($options['condition'][0]){
               $conditions = array();             
               foreach($options['condition'] as $k => $item){
                    $n = $k + 1;
                   $conditions[] = array(
                        'label'     => __('Name ' . $n),                    
                        'id'        => array('condition', $k, 'name'),
                        'type'      => 'text',
                        'attr'      => array('size' => 30),
                        'default'   => $item['name'],                        
                    );
                    $conditions[] = array(
                        'label'     => __('Code ' . $n),                    
                        'id'        => array('condition', $k, 'code'),
                        'type'      => 'text',
                        'attr'      => array('size' => 60),
                        'default'   => $item['code'],                        
                    );
               }
               $conditions[] = $add_button;
           }
           
       }

		$settings = array(
			'Widget Visibility Conditions'       => $conditions,
		);
		
		if($key){
            return $settings[$key];
        }
        //settings have to return a regular array of fields - no section
        foreach($settings as $section => $fields){
            foreach($fields as $field){
                $settings_array[] = $field;
            }
        }
        return $settings_array;
	}

    function validate_input($input){
        print_r($input);
        foreach($input['condition'] as $k => $v){
            
                if(empty($v['name']) && empty($v['code']))
                    unset($input['condition'][$k]);
            
        }
        return $input;
    }	

	function config_page(){   
		$this->add_column(1, '70%');
		$this->add_box('Widget Visibility Conditions', $this->settings('Widget Visibility Conditions'), 1);
		//Generate Config Page
        $this->_config_page_template();
	}

    function plugin_donate(){
        $content = '<p>If you like this plugin click the PayPal button to buy me a coffe ;)</p>';
        $content .= '<div style="text-align: center">
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JRA6WSKH3MSPG" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!" /><br/>
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"></a>
        </div>';
        return $this->postbox('Donate', $content, $this->hook.'donate');
    }

    function credits_column(){
                    $output = $this->plugin_like();
                    $output .= $this->plugin_donate();
                    $output .= $this->plugin_support();
                    $output .= $this->plugin_credits();                    
                    return $output;
                }
    
    function contextual_help() {                

        $contextual_help = '<H3>Advanced Text Widget Help</H3>
        <p>All code is executed inside this condition: <code>IF( YOUR CODE ){ return TRUE; }else{ return FALSE; }</code>. So make sure your code doesn\'t break the <code>IF</code> section.</p>
        <p>If your condition supports arguments (page id, slug, title), then use variable <code>$arg</code> for a single function, and $argN for multiple functions, where N is a number.<br/>Example 1: <code>is_single($arg)</code>. Example 2: <code>is_single($arg1) && !in_category($arg2)</code>. When using multiple arguments that are assigned to different functions, you can divide their values with pipe symbol "|" when entering into the Slug/Title/ID filed on the widgets page. Multiple arguments that belong to the same function should be delimited by comma.</p>
        <p>Please note that each <code>$arg</code> is exploded and executed as an array. <br>For example: <code>is_single($arg)</code> is executed as <code>is_single(explode(",", $arg))</code>.</p>
        <p>For mor details on functions that you can use read <a href="http://codex.wordpress.org/Conditional_Tags" target="_blank">WP Codex on Conditional Tags</a></p>';
        
        return $contextual_help;
    }     
}	
$atw = new atw_Admin();
$atw->auto_update();