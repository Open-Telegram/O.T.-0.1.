$(document).ready(function(){
	$('.st_btn').on('click', function(){
		$('.settings_wrap').fadeIn(300);
	});
	

	
	$(document).on('click', function(e) {
		if (!$(e.target).closest(".st_btn, .settings_wrap").length) {
				$('.settings_wrap').fadeOut(300);
			}; 
	});	
});


function changeStyle()
{
	var style = $('#style_select').val();
	$.ajax({
		url: '/?route=users/updateSetting',
		method: 'post',
		dataType: 'html',
		data: {action:'changeStyle', styleName:style},
		success: function(data){
			location.reload();
		}
	});
}