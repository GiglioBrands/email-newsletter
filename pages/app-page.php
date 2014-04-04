<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<?php 

$app_id = $_GET["app_id"];
if($app_id){
  $sSql = "insert into ".WP_eemail_TABLE_APP." VALUES ('1', '".$app_id."') ";  
  $data = $wpdb->get_results($sSql);
}

$cSql = "select * from ".WP_eemail_TABLE_APP." where 1=1 ";
$result = $wpdb->get_results($cSql);

if(count($result) > 0)
{ 
?>
<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2>Readygraph Setting</h2>
  <h3>Readygraph is activated, to manage your account visit <a href="http://readygraph.com">readygraph.com</a></h3>
  <ul>
  <li>View Subscribers</li>
  <li>Send Emails</li>
  <li>View Insight Graph</li>
  </ul>
  <a class="button add-new-h2" href="http://readygraph.com/application/insights/ ">Account Management</a>
</div>  


<?php }else{ ?>

<div class="wrap">
  <div id="icon-plugins" class="icon32"></div>
  <h2>Email Newsletter, Now with Readygraph </h2>
  <h3>Active Readygraph features to optimize Email Newsletter functionality</h3>
  <p style="display:none;color:red;" id="error"></p>
  <div class="register-left">
  <div class="form-wrap">
      <h3>Free Signup </h3>
      <p>
      <label for="tag-title">Site URL</label>
      <input type="text" id="register-url" name="eemail_on_homepage">
      </p>

      <p>
      <label for="tag-title">Name</label>
      <input type="text" id="register-name" name="eemail_on_homepage">
      </p>

      <p>
      <label for="tag-title">Email</label>
      <input type="text" id="register-email" name="eemail_on_homepage">
      </p>
      <p>
      <label for="tag-title">Password</label>
      <input type="password" id="register-password" name="eemail_on_homepage">
      </p>
      <p>
      <label for="tag-title">Confirm Password</label>
      <input type="password" id="register-password1" name="eemail_on_homepage">
      </p>
      
      <p style="max-width:180px;font-size: 10px;">By signing up, you agree to our <a href="http://www.readygraph.com/tos">Terms of Service</a> and <a href='http://readygraph.com/privacy/'>Privacy Policy</a>.</p>
      <p style="margin-top:10px;">
      <input type="submit" style="width:193px;color:" value="Sign Up!" id="register-app-submit" class="button add-new-h2" name="Submit">
      </p>
  </div>
      
  </div>
  <div class="register-right">
      <div class="form-wrap">
      <p>
      <h3>Already a member?</h3>
      <label for="tag-title">Email</label>
      <input type="text" id="signin-email" name="eemail_on_homepage">
      </p>
      <p>
      <label for="tag-title">Password</label>
      <input type="password" id="signin-password" name="eemail_on_homepage">
      </p>
      <p style="padding-top:10px;">
      <input type="submit" style="width:193px;color:" value="Sign In" id="signin-submit" class="button add-new-h2" name="Submit">
      </p>
  </div>
  </div>
<?php } ?>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">


$('#signin-submit').click(function(e){
  var email = $('#signin-email').val()
  var password = $('#signin-password').val()
  if(!email){
    alert('email is empty!')
    return
  }
  if(!password){
    alert('password is empty')
    return
  }
    $.ajax({
      type: 'GET',
      url: 'https://readygraph.com/api/v1/wordpress-login/',
      data: {
        'email' : email,
        'password' : password
      },
      dataType: 'json',
      success: function(response) {
        if(response.success)
        {
          var pathname = window.location.href;
          window.location = pathname + "&app_id="+response.data.app_id;
        }else{
          $('#error').text(response.error)
          $('#error').show();
        }
      }
  });


});

$('#register-app-submit').click(function(e){
  var email = $('#register-email').val()
  var site_url = $('#register-url').val()
  var first_name = $('#register-name').val()
  var password = $('#register-password').val()
  var password2 = $('#register-password1').val()
  if(!site_url){
    alert('Site Url is empty.')
    return;
  }
  if(!email){
    alert('Email is empty.')
    return;
  }
  if( !password || password != password2 ){
    alert('Password is not matching.')
    return;
  }

  $.ajax({
      type: 'POST',
      url: 'https://readygraph.com/api/v1/wordpress-signup/',
      data: {
        'email' : email,
        'site_url' : site_url,
        'first_name': first_name,
        'password' : password,
        'password2' : password2
      },
      dataType: 'json',
      success: function(response) {
        if(response.success)
        {
          var pathname = window.location.href;
          window.location = pathname + "&app_id="+response.data.app_id;
        }else{
          $('#error').text(response.error)
          $('#error').show();
        }
      }
  });

});
</script>