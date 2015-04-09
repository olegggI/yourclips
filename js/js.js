function get_row_number(access_token, user_id) {

	$('.poluchit').remove();


	$.ajax({
		url: 'server.php?call_row_number=' + 1 + '&access_token=' + access_token + '&user_id=' + user_id,
		success: function(data) {
			data = JSON.parse(data);
			go_create_array(data["count"], access_token, user_id);
			//go();
		}
	});
	return false;
}

function go_create_array(e, access_token, user_id) {
	var seq = new Array();
	for (i = 0; i < e; i++) {
		seq.push(i);
	}
	go(seq, access_token, user_id);
}

function go(seq, access_token, user_id) {
	$('.loader').show();
	if (seq.length) {

		$.ajax({
			url: 'server.php?num_of_video=' + seq.shift() * 6 + '&access_token=' + access_token + '&user_id=' + user_id,
			success: function(ata) {
				$('.loader').hide();
				var ata = JSON.parse(ata);
				if (ata['videos'] != '') {
					var clip = ata["packeds"];
					$('.jopa').append(clip);
					$('.piska').slideDown();
				}
				go(seq, access_token, user_id);
			}
		});
	}
	else {
		$('.loader').hide();
	}
}

function Controller($scope) {
	$scope.name = $( "li.clip-main-block" ).first().attr('name');
	$scope.video = $( "li.clip-main-block" ).first().attr('video');
}