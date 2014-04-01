<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>

<?php 

$app_id = $_GET["app_id"];
if($app_id){
  $sSql = "insert into ".WP_eemail_TABLE_APP." VALUES ".$app_id; 
  $data = $wpdb->get_results($sSql);
}
//$sSql = "select * from ".WP_eemail_TABLE_APP." where 1=1"; 
//





?>




<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2>Email Newsleter, Now with Readygraph </h2>
  <h3>Active Readygraph features to optimize Email Newsletter functionality</h3>
  <div class="register-left">
  <div class="form-wrap">
      <h3>Free Signup </h3>
      <p>
      <label for="tag-title">Site URL</label>
      <input type="text" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>

      <p>
      <label for="tag-title">Name</label>
      <input type="text" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>

      <p>
      <label for="tag-title">Email</label>
      <input type="text" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>
      <p>
      <label for="tag-title">Password</label>
      <input type="password" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>
      <p>
      <label for="tag-title">Confirm Password</label>
      <input type="password" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>
      <p style="padding-top:10px;">
      <input type="submit" style="width:193px;color:" value="Sign Up!" id="register-app-submit" class="button add-new-h2" name="Submit">
      </p>
  </div>
      
  </div>
  <div class="register-right">
      <div class="form-wrap">
      <p>
      <h3>Already a member?</h3>
      <label for="tag-title">Email</label>
      <input type="text" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>
      <p>
      <label for="tag-title">Password</label>
      <input type="password" id="eemail_on_homepage" name="eemail_on_homepage">
      </p>
      <p style="padding-top:10px;">
      <input type="submit" style="width:193px;color:" value="Sign In" id="register-app-submit" class="button add-new-h2" name="Submit">
      </p>
  </div>
  </div>



<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
$('#register-app-submit').click(function(e){
alert(2132)
});
</script>