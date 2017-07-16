<script>
	jQuery("body").on("click", ".hidden-menu-text", function() {
		jQuery("menu ul:eq(0)").slideToggle('fast');
		jQuery("menu .hidden-menu-text i").toggleClass("fa-caret-square-o-up fa-caret-square-o-down");
	});

	jQuery("body form").append('<input class="x-hidden" type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">');


	jQuery('.img-btn-language').click(function() {
		var lang = jQuery(this).attr('data-id');
		jQuery.ajax({
			url: app_url+'home/change_language/'+lang,
			success:function(respond){
				location.reload();
			},
		});
	});

	//flag color
	<?php 
		$ci =& get_instance();
		$lang = $ci->session->userdata('azlang');
	?>
	var selected_lang = "<?php echo $lang;?>";
	if (selected_lang == 'indonesian') {
		jQuery('.img-btn-language[data-id="id"]').css('opacity', 1);
		jQuery('.img-btn-language[data-id="en"]').css('opacity', 0.5);
	}
	else {
		jQuery('.img-btn-language[data-id="id"]').css('opacity', 0.5);
		jQuery('.img-btn-language[data-id="en"]').css('opacity', 1);
	}