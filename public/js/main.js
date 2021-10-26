$(document).ready(function() {
    $('.js-example-basic-single').select2({
        placeholder: '',
        width: 'resolve',
        dropdownAutoWidth : true
    });
});

$(document).ready(function() {
    $('#table-data').DataTable({
        
            "language": {
              "search": "Rechercher :",
              "lengthMenu"  : 'Resultat _MENU_ ',
              "info": "Affichage _START_ à _END_ de _TOTAL_ resultat",
              "paginate": {
                "first":      "Premier",
                "last":       "Dernier",
                "next":       "Suivant",
                "previous":   "Précédent"
              }
            },
          
        });

} );

$('tr').click(function() {
  $('#exampleModal').modal('show')
});



$('.fermer-alert-danger').click(function() {
     $('.alert-danger').hide('slow');
});


$('.fermer-alert-success').click(function() {
  $('.alert-success').hide('slow');
});

// $('.select').click(function() {

//     var lastClass = $(this).attr('class').split(' ').pop();
//     $('.'+lastClass).html('');
//     $(this).html('<i class="fas fa-check ml-2"></i>');

//     var choix = $(this).parent( ".choix" ).attr('id');
//     $.ajax({
//       //url: Routing.generate('update_choix', {code_element: code_element}),
//       url: '/update_choix/'+choix,
//       success: function (result) {  
        
//       }
//     });
// });

