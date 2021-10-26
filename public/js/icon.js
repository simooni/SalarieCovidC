$('.select').click(function() {

    var lastClass = $(this).attr('class').split(' ').pop();
    $('.'+lastClass).html('');
    $(this).html('<i class="fas fa-check ml-2"></i>');

    var choix = $(this).parent( ".choix" ).attr('id');
    $.ajax({
      //url: Routing.generate('update_choix', {code_element: code_element}),
      url: '/update_choix/'+choix,
      success: function (result) {  
        
      }
    });
});