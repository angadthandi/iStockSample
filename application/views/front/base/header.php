<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?php echo $title; ?></title>

	<!-- Bootstrap -->
	<link href="<?php echo $this->config->item('base_url'); ?>assets/css/bootstrap.min.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="<?php echo $this->config->item('base_url'); ?>assets/js/jquery-1.11.3.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="<?php echo $this->config->item('base_url'); ?>assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	var errorMsg = 'Some error occurred! Please refresh you web page and try again!';
	function trim_all(strObjName){
		//this function removes the spaces from the variables
		var strObj = strObjName;
		var strRet = "";
		for (i = 0;i < strObj.length;i++) {
			if(strObj.charAt(i) != " " && strObj.charAt(i) != "") {
				strRet = strRet+strObj.charAt(i);
			}
		}
		return strRet;
	}
	</script>

</head>
<body>
	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if($this->uri->segment(1)==''){ ?>class="active"<?php } ?>><a href="<?php echo $this->config->item('base_url'); ?>">Home</a></li>
        <?php if($this->session->userdata('loggeduser')) { ?>
			<li <?php if($this->uri->segment(1)=='users'){ ?>class="active"<?php } ?>><a href="<?php echo $this->config->item('base_url').'users'; ?>">Users</a></li>
		<?php } ?>
      </ul>
	  
      <ul class="nav navbar-nav navbar-right">
        <?php if($this->session->userdata('loggeduser')) { ?>
			<?php $loggeduser = unserialize($this->session->userdata('loggeduser'));
				if($loggeduser['user_type_id'] == 1){ ?>
					<li <?php if($this->uri->segment(1)=='dashboard'){ ?>class="active"<?php } ?>><a href="<?php echo $this->config->item('base_url'); ?>dashboard">Dashboard</a></li>
			<?php } ?>
			<li <?php if($this->uri->segment(1)=='changepassword'){ ?>class="active"<?php } ?>><a href="<?php echo $this->config->item('base_url'); ?>changepassword">Change Password</a></li>
			<li><a href="<?php echo $this->config->item('base_url'); ?>logout">Logout</a></li>
        <?php } else { ?>
			<li <?php if($this->uri->segment(1)=='signup'){ ?>class="active"<?php } ?>><a href="<?php echo $this->config->item('base_url'); ?>signup">Signup</a></li>
			<li <?php if($this->uri->segment(1)=='login'){ ?>class="active"<?php } ?>><a href="<?php echo $this->config->item('base_url'); ?>login">Login</a></li>
        <?php } ?>
	  </ul>
	  
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">