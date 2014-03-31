jQuery(document).ready(function(){

	urlVars 	= getUrlVars();

	var $ 		= jQuery;

	pac_meta_box_show_details(urlVars.post);
	
	$('body').on('change','#postaccesscontroller_ctrl_type', function(){
	
		$dtls = $('.postaccesscontroller-details');
		$dtls.removeClass('hide').html('<img src="'+$dtls.data('spinner-src')+'">');
		pac_meta_box_show_details(urlVars.post);
	
	});
	
	$('body').on('change','#postaccesscontroller_noacs_msg_type', function(){
	
		$dtls = $('.postaccesscontroller-noacs_msg');
		$dtls.removeClass('hide').html('<img src="'+$dtls.data('spinner-src')+'">');
		pac_meta_box_show_msg(urlVars.post);
	
	});

	$('body').on('click','.postaccesscontroller-details .height-toggle',function(){
		
		var toggle = $(this)
		   ,pac_dtls = toggle.closest('.postaccesscontroller-checkbox-well')
		   ,crnt_height = pac_dtls.attr('data-height');
		
		if( crnt_height == 'standard' ){
			pac_dtls.removeClass('height-standard').addClass('height-tall').attr('data-height','tall');
			toggle.text('Show Less');
		}else{
			pac_dtls.removeClass('height-tall').addClass('height-standard').attr('data-height','standard');
			toggle.text('Show More');
		}
		
	});
	
});

function pac_meta_box_show_details( postId ){

	var $ 		= jQuery;
	var type 	= $('#postaccesscontroller_ctrl_type').val();
	
	if( type == 'user' || type == 'group' ){
	
		var data    = {'post_id' : postId};
		
		if( type == 'user' ){
			data.action = 'post-access-controller--meta-user';
		}else if( type == 'group' ){
			data.action = 'post-access-controller--meta-group';
		}
		
		$.post(ajaxurl, data, function(response) {
			$('.postaccesscontroller-details').html( response );
		});	
		
	}else{
	
		$('.postaccesscontroller-details').addClass('hide');
	
	}
	
}

function pac_meta_box_show_msg( postId ){

	var $ 		= jQuery;
	var type 	= $('#postaccesscontroller_noacs_msg_type').val();
	
	if( type == 'none' ){
	
		$('.postaccesscontroller-noacs-std-msg').addClass('hide');
		$('.postaccesscontroller-noacs-custom-msg').addClass('hide');

	}else if( type == 'std' ){

		$('.postaccesscontroller-noacs-std-msg').removeClass('hide');
		$('.postaccesscontroller-noacs-custom-msg').addClass('hide');

	}else if( type == 'custom' ){

		$('.postaccesscontroller-noacs-std-msg').addClass('hide');
		$('.postaccesscontroller-noacs-custom-msg').removeClass('hide');

	}

}


function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}