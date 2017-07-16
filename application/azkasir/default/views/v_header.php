<?php
    $ci =& get_instance();
    $ci->load->helper("az_config");
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url('application/azkasir/default/assets/images/favicon.png');?>" />
        <title>AZ Kasir</title>

        <?php
        	$this->load->helper("az_headscript");
        	echo put_headers();
        ?>
        <script type="text/javascript">
            var base_url = "<?php echo base_url();?>"; 
            var app_url = "<?php echo app_url();?>";
        </script>  
	</head>
	<body>
        <div class="az-loading">
            <div>
                <img src="<?php echo base_url();?>assets/images/loading.gif">
            </div>
        </div>
        <header>
            <div class="container-header">
                <div class="div-table full-width">
                    <div class="div-table-cell va-middle">
                        <div class="logo">
                            <a href="<?php echo app_url();?>"><img src="<?php echo base_url();?>application/azkasir/default/assets/images/logo.png"/></a>
                        </div>
                        <div class="title">
                            <?php echo az_get_config('store_name');?>
                            <div class="address">
                                <?php echo az_get_config('store_description');?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="div-table-cell va-middle">
                        <div class="account">
                            <div class="account-container">
                                <div class="">
                                    <img src="<?php echo base_url();?>application/azkasir/default/assets/images/icon/user_avatar.png"/>
                                </div>
                                <div>
                                    <?php echo $this->session->userdata("name");?>
                                </div>
                            </div>
                            <div class="account-detail">
                                <div class="account-caret">
                                </div>
                                <div class="account-detail-content">
                                    <a href="<?php echo app_url().'user/ubah_password';?>"><button type="button" class="btn btn-info"><i class="fa fa-key"></i> <?php echo azlang('Change Password');?></button></a>
                                    <a href="<?php echo app_url().'login/logout';?>"><button type="button" class="btn btn-default"><i class="fa fa-sign-out"></i> <?php echo azlang('Logout');?></button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </header>
        <menu>
            <div class="hidden-menu">
                <div class="hidden-menu-text">
                    <i class="fa fa-caret-square-o-down"></i>
                    <div></div>
                </div>
            </div>
            <ul>
                <?php
                    $arr_menu = $this->config->item('menu');
                    echo generate_menu($title, $subtitle, '');
                ?>
            <div class="box-language">
                <div class="btn-language">
                    <?php echo azlang('Language');?>&nbsp;
                    <img class="img-btn-language" data-id="id" title='Indonesian' src="<?php echo base_url();?>application/azkasir/default/assets/images/id.png"/>
                    <img class="img-btn-language" data-id="en" title='English' src="<?php echo base_url();?>application/azkasir/default/assets/images/en.png"/>
                </div>
            </div>
            </ul>
        </menu>
        <div class="container-fluid">
            <div class='row'>
                <div class='col-md-12 breadcumb'>
                    <div class="breadcumb-content">
                        <?php echo $title;?>
                    </div>
                    <?php 
                        if (strlen($subtitle) > 0) {
                            echo "&nbsp;>&nbsp;";
                            echo '<div class="breadcumb-content">';
                            echo $subtitle;
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="content">

