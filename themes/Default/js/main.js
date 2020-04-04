$(function() {

	$('input').each(function () {
		if ( $(this).attr('required') ) {
			$(this).after('<span class="astrick">*</span>');
		}
	});
	$('.confirm').click(function () {
		return confirm('Are you sure??');
	});
	$('.options i').click(function () {
		$value = 0;
		if ( $('.options').css('right') == $value + 'px' ) {
			$value = '-200px';
		}
		$('.options').animate({
			right: $value
		}, 800);
	});
});