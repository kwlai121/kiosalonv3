/*!
 * Theme Verify JS
 * Outstock WordPress WooCommerce Theme (https://lion-themes.net/)
 * Copyright 2011-2018 Lionthemes88, Inc.
 * Author: Nguyen Duc Viet
 */
 
(function($) {
	"use strict";
	$(document).on('click', '#hermes-submit-code', function(){
		var $this = $(this);
		var $code = $('#purchased_code').val();
		if (!$code) {
			$('#purchased_code').addClass('empty');
			return false;
		}
		$('.hermes-verify img.loading').show();
		$(this).attr('disabled', 'disabled');
		$.ajax({
			url: 'https://lion-themes.net/api/',
			type: 'POST',
			dataType: 'json',
			data: 'theme=hermes&code=' + $code,
			success: function(result) {
				if (result.success && result.success == 1) {
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: 'action=hermes_save_purchased_code&item_id=' + result.item_id + '&code=' + $code, 
						success: function(data){
							$('#purchased_code').prev('.correct').show();
						}
					});
				}else{
					$('#purchased_code').next('.incorrect').show();
				}
				$('.hermes-verify img.loading').hide();
				$this.removeAttr('disabled');
			}
		});
	});
	$(document).on('change', '#purchased_code', function(){
		$(this).removeClass('empty').next('.incorrect').hide();
	});
})(jQuery);