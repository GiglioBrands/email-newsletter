<?php
// ReadyGraph Extension
//
function append_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
	global $submenu;
	global $menu;
	global $_wp_real_parent_file;
	global $_wp_submenu_nopriv;
	global $_registered_pages;
	global $_parent_pages;

	$menu_slug = plugin_basename( $menu_slug );
	$parent_slug = plugin_basename( $parent_slug);

	if ( isset( $_wp_real_parent_file[$parent_slug] ) )
		$parent_slug = $_wp_real_parent_file[$parent_slug];

	if ( !current_user_can( $capability ) ) {
		$_wp_submenu_nopriv[$parent_slug][$menu_slug] = true;
		return false;
	}

	// If the parent doesn't already have a submenu, add a link to the parent
	// as the first item in the submenu. If the submenu file is the same as the
	// parent file someone is trying to link back to the parent manually. In
	// this case, don't automatically add a link back to avoid duplication.
	if (!isset( $submenu[$parent_slug] ) && $menu_slug != $parent_slug ) {
		foreach ( (array)$menu as $parent_menu ) {
			if ( $parent_menu[2] == $parent_slug && current_user_can( $parent_menu[1] ) )
				$submenu[$parent_slug][] = $parent_menu;
		}
	}

	$mymenu = array();
	$mymenu[] = array($menu_title, $capability, $menu_slug, $page_title);
	$submenu[$parent_slug] = array_merge($mymenu, $submenu[$parent_slug]);

	$hookname = get_plugin_page_hookname( $menu_slug, $parent_slug);
	if (!empty ( $function ) && !empty ( $hookname ))
		add_action( $hookname, $function );
    
	$_registered_pages[$hookname] = true;
	// backwards-compatibility for plugins using add_management page. See wp-admin/admin.php for redirect from edit.php to tools.php
	if ( 'tools.php' == $parent_slug )
		$_registered_pages[get_plugin_page_hookname( $menu_slug, 'edit.php')] = true;

	// No parent as top level
	$_parent_pages[$menu_slug] = $parent_slug;

	return $hookname;
}

function add_readygraph_plugin_warning() {
  if (get_option('readygraph_access_token', '') != '') return;

  global $hook_suffix, $current_user, $menu_slug;
  if ( $hook_suffix == 'plugins.php' ) {              
    echo '<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">  
      <style type="text/css">  
        .readygraph_activate {
          min-width:825px;
          padding:7px;
          margin:15px 0;
          background:#1b75bb;
          -moz-border-radius:3px;
          border-radius:3px;
          -webkit-border-radius:3px;
          position:relative;
          overflow:hidden;
        }
        .readygraph_activate .aa_button {
          cursor: pointer;
          -moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
          -webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
          box-shadow:inset 0px 1px 0px 0px #ffffff;
          background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #f9f9f9), color-stop(1, #e9e9e9) );
          background:-moz-linear-gradient( center top, #f9f9f9 5%, #e9e9e9 100% );
          filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#f9f9f9", endColorstr="#e9e9e9");
          background-color:#f9f9f9;
          -webkit-border-top-left-radius:3px;
          -moz-border-radius-topleft:3px;
          border-top-left-radius:3px;
          -webkit-border-top-right-radius:3px;
          -moz-border-radius-topright:3px;
          border-top-right-radius:3px;
          -webkit-border-bottom-right-radius:3px;
          -moz-border-radius-bottomright:3px;
          border-bottom-right-radius:3px;
          -webkit-border-bottom-left-radius:3px;
          -moz-border-radius-bottomleft:3px;
          border-bottom-left-radius:3px;
          text-indent:0;
          border:1px solid #dcdcdc;
          display:inline-block;
          color:#333333;
          font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
          font-size:15px;
          font-weight:normal;
          font-style:normal;
          height:40px;
          line-height:40px;
          width:275px;
          text-decoration:none;
          text-align:center;
          text-shadow:1px 1px 0px #ffffff;
        }
        .readygraph_activate .aa_button:hover {
          background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #e9e9e9), color-stop(1, #f9f9f9) );
          background:-moz-linear-gradient( center top, #e9e9e9 5%, #f9f9f9 100% );
          filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#e9e9e9", endColorstr="#f9f9f9");
          background-color:#e9e9e9;
        }
        .readygraph_activate .aa_button:active {
          position:relative;
          top:1px;
        }
        /* This button was generated using CSSButtonGenerator.com */
        .readygraph_activate .aa_description {
          position:absolute;
          top:19px;
          left:285px;
          margin-left:25px;
          color:#ffffff;
          font-size:15px;
          z-index:1000
        }
        .readygraph_activate .aa_description strong {
          color:#FFF;
          font-weight:normal
        }
      </style>                       
      <form name="readygraph_activate" action="'.admin_url( 'admin.php?page=' . $menu_slug).'" method="POST"> 
        <input type="hidden" name="return" value="1"/>
        <input type="hidden" name="jetpack" value="'.(string) class_exists( 'Jetpack' ).'"/>
        <input type="hidden" name="user" value="'.esc_attr( $current_user->user_login ).'"/>
        <div class="readygraph_activate">
          <div class="aa_button" onclick="document.readygraph_activate.submit();">  
            '.__('Connect Your ReadyGraph Account').'
          </div>  
          <div class="aa_description">'.__('<strong>Almost done</strong> - connect your account to start getting users.').'</div>  
        </div>  
      </form>  
    </div>';      
  }
}

function readygraph_client_script_head() {
	global $readygraph_email_subscribe;
	if (get_option('readygraph_access_token', '') != '') {
	?>
<script type='text/javascript'>
var d = top.document;
var h = d.getElementsByTagName('head')[0], script = d.createElement('script');
script.type = 'text/javascript';
script.src = '//readygraph.com/scripts/readygraph.js';
script.onload = function(e) {
	var render = function(delay) {
		var settings = <?php echo str_replace("\\\"", "\"", get_option('readygraph_settings', '{}')) ?>;
		settings['applicationId'] = '<?php echo get_option('readygraph_application_id', '') ?>';
		settings['overrideFacebookSDK'] = true;
		settings['platform'] = 'others';
		settings['enableLoginWall'] = true;
		settings['enableSidebar'] = <?php echo get_option('readygraph_enable_sidebar', 'false') ?>;
		settings['enableNotification'] = <?php echo get_option('readygraph_enable_notification', 'true') ?>;
		settings['inviteFlowDelay'] = delay;
		settings['inviteAutoSelectAll'] = <?php echo get_option('readygraph_auto_select_all', 'true') ?>;
		top.readygraph.setup(settings);
		readygraph.ready(function() {
			function process(userInfo) {
				<?php echo $readygraph_email_subscribe ?>
				subscribe(userInfo.get('email'), userInfo.get('first_name'), userInfo.get('last_name'));
			}
			readygraph.framework.authentication.getUserInfo(function(userInfo) {
				if (userInfo.get('uid') != null) {
					process(userInfo);
				}
				else {
					userInfo.on('change:fb_access_token change:rg_access_token', function() {
						readygraph.framework.authentication.getUserInfo(function(userInfo) {
							process(userInfo);
						});
					});
				}
			}, true);
		});
	}
	
	var $ = readygraph.framework.$;
	<?php if (get_option('readygraph_type', 'delay') == 'delay') { ?>
		render(<?php echo get_option('readygraph_delay', '10000') ?>);
	<?php } else { ?>
		var SCROLL_FRACTION = <?php echo get_option('readygraph_scroll', '50') ?> / 100.0;
		$(document).ready(function() {
			function processOnScroll() {
				var heightFraction = SCROLL_FRACTION;
				var shouldShow = false;

				var windowHeight = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight; 
				var documentHeight = document.body.scrollHeight;
				
				if (windowHeight*1.3 >= documentHeight) shouldShow = true;
				else if ($(window).scrollTop() >= (documentHeight - windowHeight)*(1.0-heightFraction)) shouldShow = true;
				
				if (shouldShow) {
					render(0);
					$(window).unbind('scroll', processOnScroll);
					$(window).unbind('resize', processOnScroll);
				}
			}
			$(window).bind('scroll', processOnScroll);
			$(window).bind('resize', processOnScroll);
			processOnScroll();
		});
	<?php } ?>
}
h.appendChild(script);
</script>
<?php if (get_option('readygraph_show_credit', 'false') == 'false') { ?>
<style type="text/css">
.rgw-credit { display: none !important; }
</style>
<?php } ?>
	<?php
	}
}
?>