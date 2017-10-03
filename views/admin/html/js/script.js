$(function(){
	var options = {
		autoclose: true,
	    format: 'dd/mm/yyyy',
	    todayHighlight: true
	};

	$(document).on('click', function(){
		$('#publish-date').datepicker(options);
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

