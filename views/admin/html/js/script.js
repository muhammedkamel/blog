$(function(){
	

	$(document).on('click', '#edit-post-btn', function(){
		console.log('edit post');
		// get values
		var data 		= {};
		data.title 		= $('#title').val();
		data.body 		= $('#body').val();
		data.summery 	= $('#summery').val();
		data.status_id 	= parseInt($('#status').val());
		data.publish_at = $('#publish-date #date').val();

		$.ajax({
			url: 	'posts.php',
			method: 'POST',
			data: 	{action: 'update', data: data},
		}).done(function(){
			window.location.reload();
		});
	});

	// add an action to add new post
	$(document).on('click', '#add-post-btn', function(){
		console.log('add post');
		// get values
		var data 		= {};
		data.title 		= $('#title').val();
		data.body 		= $('#body').val();
		data.summery 	= $('#summery').val();
		data.status_id 	= parseInt($('#status').val());
		data.publish_at = $('#publish-date #date').val();

		$.ajax({
			url: 	'posts.php',
			method: 'POST',
			data: 	{action: 'add', data: data},
		}).done(function(){
			window.location.reload();
		});
	});

	

	var options = {
		autoclose: true,
	    format: 'dd/mm/yyyy',
	    todayHighlight: true
	};
	
	$(document).on('click', '#publish-date', function(){
		$(this).datetimepicker();
	});

});


function generateFooter(data){
	if(data.footer){
		return '<div class="modal-footer">\
			        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>\
			        <button type="button" class="btn btn-success" id="'+data.id+'-btn">Save</button>\
		      	</div>';
	}
	return '';
}

function generateModal(data){
	console.log('called');
	data.size 	= data.size ? data.size: 'lg';
	data.label 	= data.title.replace(' ', '-');
	data.footer = generateFooter(data);
	var modal 		='<div class="modal fade" id="'+data.id+'" tabindex="-1" role="dialog" aria-labelledby="'+data.label+'">\
					  <div class="modal-dialog modal-'+data.size+'" role="document">\
					    <div class="modal-content">\
					      <div class="modal-header">\
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
					        <h4 class="modal-title" id="'+data.label+'">'+data.title+'</h4>\
					      </div>\
					      <div class="modal-body">\
					      '+data.body+'\
					      </div>\
					      '+data.footer+'\
					    </div>\
					  </div>\
					</div>';
	$('#'+data.id).remove();
	$('body').append(modal);
	$('#'+data.id).modal();
}

