jQuery(document).ready(function(){
 jQuery('input.infobar_entries').click(function(e){
 var infobar_name = jQuery('div.inside').find('input[name="nb_name"]').val();
 var infobar_content = jQuery('div.inside').find('textarea[name="nb_content"]').val(); 
 if(infobar_name == "" || infobar_content == ""){
	 if(infobar_name == ""){
		 jQuery('input#nb_name').css({
        "border": "1px solid red"
    });
		 e.preventDefault();
	 }
	 if(infobar_content == ""){
		jQuery('textarea#nb_content').css({
        "border": "1px solid red"
    });
		 e.preventDefault();
	 }
 }
 });
	});