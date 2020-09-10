$(document).ready(function(){
    var maxField = 12; //Input fields increment limitation
    var addButton = $('.add-button'); //Add button selector
    var wrapper = $('.field-wrapper'); //Input field wrapper
    var x = $('.field-custom').length - 1; //Initial field counter is 1
    var wrap = null;

    //Once add button is clicked
    $(addButton).click(function(){

        console.log($(this).parents('.field-wrapper'));


        wrap = $(this).parents('.field-wrapper')[0];
        var appendContent = null;
        if ($(wrap).attr('data-wrap-name') == 'parameters') {
            appendContent = '<div class="field field-custom">' +
                '<input type="text" class="input input-custom" name="parameters['+x+'][name]" value="" placeholder="Parameter name"/>' +
                '<select name="parameters['+x+'][type]" class="select" required="required">' +
                '<option value="">(choose type)</option>' +
                '<option value="string">String</option>' +
                '<option value="array">List</option>' +
                '</select>' +
                '<a href="javascript:void(0);" class="remove-button">' +
                '<img src="/img/remove-icon.png" alt="remove-icon"/>' +
                '</a>' +
                '</div>';
        }else if ($(wrap).attr('data-wrap-name') == 'sections') {

            var cloneSelect = $('.select-sections');

            appendContent = '<div class="field field-custom-sections mb-10">' +
                                '<div class="control">'+
                                    '<div class="select">'+
                                        cloneSelect[0].outerHTML +
                                    '</div>'+
                                '<a href="javascript:void(0);" class="remove-button">' +
                                    '<img src="/img/remove-icon.png" alt="remove-icon"/>' +
                                '</a>' +
                                '</div>'+
                            '</div>';
        }

        //Check maximum number of input fields
        if(x < maxField){
            x++; //Increment field counter
            $(wrap).append(appendContent); //Add field html
        }
    });
    //Once remove button is clicked
    $(wrapper).on('click', '.remove-button', function(e){
        e.preventDefault();
        $(this).parent('div').parent().remove(); //Remove field html
        x--; //Decrement field counter
    });
});
