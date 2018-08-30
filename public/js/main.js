$.notifyDefaults({
	type: 'info'
});

$(function() {
	$('#flashbag .flash').each(function() {
		var message = $(this).data('message');
		var type = $(this).data('type');
		icon = 'info-sign';
		if (type == 'warning') icon = 'exclamation-sign';
		if (type == 'success') icon = 'ok-sign';
		if (type == 'danger') icon = 'exclamation-sign';
		$.notify({
			icon: 'glyphicon glyphicon-'+icon,
			message: message
		}, {
			type: type,
			animate: {
				enter: 'animated bounceInDown',
				exit: 'animated bounceOutUp'
			},
			placement: {
				from: 'top',
				align: 'center'
			}
		});
	});
});	

$('body').on('click', '*[data-role="confirmDeletion"]', function() {
	var text = "Etes-vous sûr de vouloir supprimer cet élément ?";
	if (typeof $(this).data('message') !== "undefined") {
		text = $(this).data('message');
	}
	if (! confirm(text)) {
		return false;
	}
});
