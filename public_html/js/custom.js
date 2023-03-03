


/**
* Laravel Global Error handler
*/
function laravelError(response, form)
{
    if(response.status == 200)
    {
        console.log(response.status+' received empty response');
        return;
    }
    var error = response.responseJSON;
    var errorList = error.errors;
    
    var text = '';
    var message = '';
    
    if(typeof(error.message) != 'undefined'){
        if(error.message.length < 50){
            message += error.message;
        }else{
            text += error.message+'<br>';
            message += 'Error!';
        }
    }

    if(typeof(error.error) == 'string')
    {
        message += error.error;
    }

if(errorList){
   for (var key of Object.keys(errorList)) {

        var err = errorList[key].join("<br>");
        text += '<li>'+ err +'</li>';
        var input = $(form).find('input[name="'+key+'"]').closest('.field');
        $(input).addClass('error');
          
    }
}

   
    Swal.fire({
          title: message,
          html: '<ul style="text-align:left;color:red">'+text+'</ul>',
          icon: "error",
          //allowOutsideClick: false,
          showConfirmButton: false,
          showCancelButton: true,
          cancelButtonText: 'Close'
        });

}

/**
 * Laravel Handle Success Notices
 */
function laravelSuccess(response, type = 'swal')
{
    if(response.redirect == true)
    {
        redirect(response.redirect_url);
    }
    if(type == 'toast')
    {
        toastr.success(response.message);
        return;
    }
    
   Swal.fire({
                    title: 'Success!',
                    html: '<ul style="text-align:left;color:green">'+response.message+'</ul>',
                    icon: "success",
                    //allowOutsideClick: false,
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Close'
                });
        
}




/**
* Global Ajax Function Helper
*/
function makeAjax(form, beforeSend, complete, success, error)
{
                  
        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
            dataType: 'json',
            contentType: false,
            processData: false,
            data: new FormData(form),            
            beforeSend: function()
            {
                if(typeof(beforeSend) != 'undefined' && beforeSend != null){
                    beforeSend();
                }
            },
            complete: function()
            {
                if(typeof(complete) != 'undefined' && complete != null){
                    complete();
                }
                
            },
            success: function(response)
            {
                if(typeof(success) != 'undefined' && success != null){
                   return success(response);
                }
                laravelSuccess(response);
            },
            error: function(response)
            {
                if(typeof(error) != 'undefined' && error != null){
                    return error(response);
                }
                laravelError(response);
            }

        });
    
}





 /**
  * Button Spinning
  */
 function startSpinner(button)
 {
     //var icons = $(button).find('i').attr('class');
     var text = $(button).html();
     var spinner = '<i class="fas fa-spinner fa-spin">';
     $(button).attr('disabled', true).data('old-text', text).html(spinner);
    //$(button).attr('disabled', true).find('i').attr('class', 'fas fa-spinner fa-spin');
    //keep icons for re-assigning
    //$(button).data('icons', icons);
 }
 
 function stopSpinner(button)
 {
    var text = $(button).data('old-text');
    $(button).attr('disabled', false).html(text);
     //var icons = $(button).data('icons');
    //$(button).attr('disabled', false).find('i').attr('class', icons);
 }