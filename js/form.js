$(document).ready(function() {
    $("#submit_btn").click(function(e) {
		e.preventDefault();
        var proceed = true;
        //simple validation at client's end
        //loop through each field and we simply change border color to red for invalid fields       
        $("#contact_form input").each(function(){
            $(this).css('border-color',''); 
            if(!$.trim($(this).val())){ //if this field is empty 
                $(this).css('border-color','red'); //change border color to red   
                proceed = false; //set do not proceed flag
				$("#contact_results").hide().html('<div class="error">Red border fields are required!</div>').slideDown();
            }
            //check invalid email
            var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/; 
            if($(this).attr("type")=="email" && !email_reg.test($.trim($(this).val()))){
                $(this).css('border-color','red'); //change border color to red   
                proceed = false; //set do not proceed flag              
            }   
        });

        if(proceed) //everything looks good! proceed...
        {
            //get input field values data to be sent to server
            post_data = {
                'fullname' : $('input[name=fullname]').val(), 
                'company' : $('input[name=company]').val(), 
                'phone'  : $('input[name=phone]').val(), 
                'email'  : $('input[name=email]').val(),
                'interest'  : $('input[name=interest]').val()
            };
			
			
			var data = $("#contact_form").serializeArray();
			
			
			console.log(data);
		  
            //Ajax post data to server
            $.post('contact_me.php', data, function(response){  
			
				//	console.log(response);
                if(response.type == 'error'){ //load json data from server and output message     
                    output = '<div class="error">'+response.text+'</div>';
                }else{
                    output = '<div class="success">'+response.text+'</div>';
                    //reset values in all input fields
                    $("#contact_form input").val(''); 
                   // $("#contact_form #contact_body").slideUp(); //hide form after success
                }
                $("#contact_results").hide().html(output).slideDown();
            }, 'json');
        }
    });
    
    //reset previously set border colors and hide all message on .keyup()
    $("#contact_form input").keyup(function() { 
        $(this).css('border-color',''); 
        $("#contact_results").slideUp();
    });
});