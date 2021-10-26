
var url_window=window.location.href;

// ******************************************************************************************
// ************************ Ajouter Date rendez-vous  ******************************

$("body").on("submit", "form[name='form_DateRV']", function(ev) {
    ev.preventDefault(); // Prevent browser default submit.
    var formData = new FormData(this);
    var type = 'RendezVous'
    $.ajax({
        url:  $(this).attr('action'),
        type: "POST",
        data: formData, 

        success: function (result) {   
            if(result == 'ok'){
                RefreshRendezVous(type);
            }else{
                for (const property in result) {
                    $('.error-custom').html('');
                    $('.'+property).html('<i class="fas fa-exclamation-triangle"></i>' + result[property]);
                    $('.'+property).show();

                    $('.loader').hide();
                    $('.televerser').show();
                    $('.pagination_document').show();
                    
                }
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });        
});

// ******************************************************************************************
// ************************ Pour actualiser la partie de date rondez-vous **************************
function RefreshRendezVous(type) {
    axios.post(url_window+'?type='+type)
    .then((suc) => {
        if(type=='RendezVous'){
            $(".myDivMessageRV").empty();
            $(".myDivMessageRV").html(suc.data);
        }
        
    })
    .catch((err) => {  
        console.log(err)
    })
}
