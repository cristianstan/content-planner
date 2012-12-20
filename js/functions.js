jQuery(document).ready(function($) {
	$('.content-planner-list li .extended-info').hide();


	function buildABadge(containerObj) {
		var badgeContent = $(containerObj).children('.issue-badge').text();
		if ('0' == badgeContent) {
			badgeContent = '';
			$(containerObj).children('.badger-outter').hide();
		} else {
			$(containerObj).badger(badgeContent);	
		}
		
	}
	
	// Initial build
	$('.content-planner-list li').each(function () {
		buildABadge(this);
	});

	// Integrating badges and highlighting with Front-end Editor
	$(".fee-initialized:contains('[empty]')").text('[fill in this information]').addClass('highlight').bind('edit_save', function() {
		$(this).removeClass('highlight');
		if (('[empty]' != $(this).text()) && ('' !== $(this).text())) {
			var previousCount = $(this).parent().parent().children('.issue-badge').text();
			$(this).parent().parent().children('.issue-badge').text(previousCount-1);
			buildABadge($(this).parent().parent());
		} else {
			$(this).text('[fill in this information]').addClass('highlight');
		}
    });

    $('.content-planner-list li .post-title').click(function() {
		$(this).parent().children('.extended-info').toggle('slow', function() {
		// Animation complete.
		});
	});
});