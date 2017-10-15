$(function(){
	
	// delete post
	$('#posts').on('click', '.delete', deletePost);
	// show modal with the post to edit
	$('#posts').on('click', '.edit', showEditPostForm);

	// show add post modal
    $('#add').on('click', showAddPostForm);

	// show edit form with IP
	$('#ips').on('click', '.edit', showEditIPForm);

	// edit ip
	$(document).on('click', '#edit-ip-btn', editIP);

	// add an ip 
	$('#add-ip').on('click', addIP);
	
	$('#ips').on('click', '.delete', showConfirmation);
	$(document).on('click', '#allow-ip-btn', allowIP);

	// check if it's IP
	$(document).on('keyup', '#ip', checkIP);
	// ban ip
	$(document).on('click', '#ban-ip-btn', banIP);

	// delete post
	$(document).on('click', '#delete-post-btn', function(){
		console.log('delete post');
		var postID = $('#delete-post > div > div > div.modal-body > input[type="hidden"]').val();

		$.ajax({
			url: 	'posts.php',
			method: 'POST',
			data: 	{id: postID,action: 'delete'}
		}).done(function(){
			window.location.reload();
		});
	});

	// edit post
	$(document).on('click', '#edit-post-btn', editPost);


	// add an action to add new post
	$(document).on('click', '#add-post-btn', addPost);

	

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
			        <button type="button" class="btn btn-danger" data-dismiss="modal">'+data.footer.cancel+'</button>\
			        <button type="button" class="btn btn-success" id="'+data.id+'-btn">'+data.footer.confirm+'</button>\
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

function addIP(){
	var body  = makeIPForm();
    data = {
        id: 'ban-ip',
        size: 'sm',
        title: 'Ban New IP',
        body:  body,
        footer: {cancel: 'Cancel', confirm: 'Save'},
        autohide: true
    }
    generateModal(data);
}


function makeIPForm(data = null){
	ip = '';
	id = '';
	if(data){
		ip = data.ip;
		id = '<input type="hidden" name="id" value="'+data.id+'">';
	}
	return id+'<div class="form-group">\
                <label for="ip" class="control-label">IP</label>\
                <input type="text" class="form-control" name="ip" id="ip" placeholder="Enter IP here" value="'+ip+'">\
            </div>';
}

function checkIP(){
	var input = $(this);

	var ip = new RegExp('^(([0-1]\d{0,2})|(2[0-5]{0,2}))\.(([0-1]\d{0,2})|(2[0-5]{0,2}))\.(([0-1]\d{0,2})|(2[0-5]{0,2}))\.(([0-1]\d{0,2})|(2[0-5]{0,2}))$');
	console.log(input.val());
	
	if(/^(([0-1]\d{0,2})|(2[0-5]{0,2}))\.(([0-1]\d{0,2})|(2[0-5]{0,2}))\.(([0-1]\d{0,2})|(2[0-5]{0,2}))\.(([0-1]\d{0,2})|(2[0-5]{0,2}))$/.test(input.val())){
		console.log('valid');
		input.parent().children('#error').remove();
		$('#ban-ip-btn, #edit-ip-btn').prop('disabled', false);
	}else{
		console.log('invalid');
		var error = '<div id="error">\
				        <small class="text-danger">\
				          Please Enter a valid ipv4 address.\
				        </small>\
				    </div>';
		$('#ban-ip-btn, #edit-ip-btn').prop('disabled', true);
		input.parent().children('#error').remove().end().append($(error));
	}
}

function banIP(){
	var ip = $('#ip').val();
	$.ajax({
		url: 	'banned-ips.php',
		method: 'POST',
		data: 	{action: 'add', ip: ip}
	}).done(function(data){
		window.location.reload();
	});
}

function showConfirmation(){
	var $this = $(this);
	var id 	  = $this.parent().children('input[name="id"]').val(),
		ip 	  = $this.parents('tr').children('.ip').text();
		
	var body  = '<input type="hidden" value="'+id+'" name="id">'+'<div class="alert alert-warning" role="alert">\
                  <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>\
                  <span class="sr-only">Error:</span>\
                  <strong> Are you sure that you want to allow this IP "'+ip+'" ?</strong>\
                </div>';
    data = {
        id: 'allow-ip',
        size: 'lg',
        title: 'Allow this IP',
        body:  body,
        footer: {cancel: 'No', confirm: 'Yes'},
        autohide: true
    }
    generateModal(data);
}

function allowIP(){
	var id 	= $('#allow-ip > div > div > div.modal-body > input[type="hidden"]').val();

	$.ajax({
		url: 	'banned-ips.php',
		method: 'POST',
		data: 	{action: 'delete', id: id}
	}).done(function(data){
		window.location.reload();
	});
}


function editIP(){

	id = $('#edit-ip > div > div > div.modal-body > input[name="id"]').val(),
	ip = $('#ip').val();

	$.ajax({
		url: 	'banned-ips.php',
		method: 'POST',
		data: 	{action: 'edit', id: id, ip: ip}
	}).done(function(data){
		window.location.reload();
	});
}

function showEditIPForm(){
    var id = $(this).parent().children('input[name="id"]').val();
    var body = '';
    $.ajax({
        url: 'banned-ips.php',
        type: 'POST',
        data: {action: 'get', id: id},
    })
    .done(function(data) {
        body = makeIPForm(data);
        data = {
            id: 'edit-ip',
            size: 'sm',
            title: 'Edit IP',
            body:  body,
            footer: {cancel: 'Cancel', confirm: 'Save'},
            autohide: true
        }
        generateModal(data);
    });
}


function showEditPostForm(){            
    var id   = $(this).parent().children('input[name="id"]').val();
    var body = '';
    $.ajax({
        url:    'posts.php',
        method: 'POST',
        data:   {action: 'get', id: id}
    }).done(function(data){
        body = makePostForm(data);
        data = {
            id: 'edit-post',
            size: 'lg',
            title: 'Edit Post',
            body:  body,
            footer: {cancel: 'Cancel', confirm: 'Save'},
            autohide: true
        }
        generateModal(data);
    });

}


function showAddPostForm(){
    $.ajax({
        url:    'posts.php',
        method: 'POST',
        data:   {action: 'get_statuses'}
    }).done(function(results){
    	var data = [];
        data.statuses = results;
        body = makePostForm(data);
        data = {
            id: 'add-post',
            size: 'lg',
            title: 'Add New Post',
            body:  body,
            footer: {cancel: 'Cancel', confirm: 'Save'},
            autohide: true
        }
        generateModal(data);
    });
}


function makePostForm(data){
    var id          = '',
        title       = '',
        body        = '',
        summery     = '',
        status_id   = '',
        publish_at  = '';
    
    if(data.post){
        id      = '<input type="hidden" name="id" value="'+data.post.id+'">'
        title   = data.post.title;
        body    = data.post.body;
        summery  = data.post.summery;
        status_id  = parseInt(data.post.status_id);
        publish_at = data.post.publish_at;
    }

    return id+'<div class="form-group">\
            <label for="title" class="control-label">Title</label>\
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="'+title+'">\
        </div>\
        <div class="form-group">\
            <label for="body" class="control-label">Body</label>\
            <textarea name="body" id="body" cols="30" rows="10" class="form-control" placeholder="Body here">'+body+'</textarea>\
        </div>\
        <div class="form-group">\
            <label for="summery" class="control-label">Summery</label>\
            <textarea name="summery" id="summery" cols="30" rows="5" class="form-control" placeholder="summery here">'+summery+'</textarea>\
        </div>\
        <div class="form-group">\
            <label for="status" class="control-label">Status</label>\
            <select id="status" name="status">'+ makeStatusesList(data.statuses, status_id) +
            '</select>\
        </div>\
        <div class="container">\
            <div class="row">\
                <div class="col-sm-6">\
                    <div class="form-group">\
                        <div class="input-group date" id="publish-date">\
                            <input type="text" class="form-control" id="date" value="'+publish_at+'"/>\
                            <span class="input-group-addon">\
                            <span class="glyphicon glyphicon-calendar"></span>\
                            </span>\
                        </div>\
                    </div>\
                </div>\
            </div>\
        </div>';        
}

function makeStatusesList(statuses, status_id){
    var options = '';
    for(var i = 0; i<statuses.length; i++){
        if(parseInt(statuses[i].id) === parseInt(status_id)){
           options += '<option value="'+statuses[i].id+'" selected>'+statuses[i].status+'</option>'; 
        }else{
           options += '<option value="'+statuses[i].id+'">'+statuses[i].status+'</option>';
        }
    }
    return options;
}


function deletePost(){
    var postID  = parseInt($(this).parent().children('input[name="id"]').val());
    var body    = '<input type="hidden" value="'+postID+'">'+'<div class="alert alert-warning" role="alert">\
                  <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>\
                  <span class="sr-only">Error:</span>\
                  <strong> Are you sure that you want to delete this post?</strong>\
                </div>';
    data = {
        id: 'delete-post',
        size: 'lg',
        title: 'Delete Post',
        body:  body,
        footer: {cancel: 'No', confirm: 'Yes'},
        autohide: true
    }
    generateModal(data);
}

function editPost(){
	console.log('edit post');
	// get values
	var id 			= parseInt($('#edit-post input[name="id"]').val());
	var data 		= {};
	data.title 		= $('#title').val();
	data.body 		= $('#body').val();
	data.summery 	= $('#summery').val();
	data.status_id 	= parseInt($('#status').val());
	data.publish_at = $('#publish-date #date').val();

	$.ajax({
		url: 	'posts.php',
		method: 'POST',
		data: 	{id: id,action: 'edit', data: data}
	}).done(function(){
		window.location.reload();
	});
}

function addPost(){
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
}