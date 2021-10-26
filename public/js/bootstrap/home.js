var url_=location.protocol+'//'+location.host;
var documentType = '';

// ******************************************************************************************
// ****************************  show And hide MyDiv  ***************************************
    $("body").on('click', ".div_plus",function(){ 

        if($('> .navigation-icon',this).hasClass('fa-plus')){
            $('> .navigation-icon',this).removeClass('fa-plus')
            $('> .navigation-icon',this).addClass('fa-minus');
            // document.getElementById("pass_sanitaire").style.display = "block";
            
        }else{
            $('> .navigation-icon',this).removeClass('fa-minus')
            $('> .navigation-icon',this).addClass('fa-plus');
            // document.getElementById("pass_sanitaire").style.display = "none";
        }
        $(this).next(".myDiv").slideToggle();
    });

// ******************************************************************************************
// **************************** Ajouter Document Pass Sanitaire  *****************************

    $(document).ready(function(e) {

        $("#upfile_sanitaire").on('change', function() {
        var type='sanitaire';
        var formData = new FormData(document.querySelector('#folder'));
     
        $("#show_loader")[0].removeAttribute("hidden");

        $.ajax({
            type: "POST",
            url: $("[name='tdocument']").attr('action'),
            data: formData,
            success: function (result) {   
                if(result == 'ok'){

                    RefreshAction(type);
                    $("#show_loader").attr("hidden",true);
                    $('.success_sanitaire').show();
                    $('.error_sanitaire').hide();
                    hideAlert();
                    $('.icon_times_sanitaire').removeClass('fa-times');
                    $('.icon_times_sanitaire').addClass('fa-check');
                    
                }else{
                    for (const property in result) {
                        $("#show_loader").attr("hidden",true);
                        $('.error-custom').html('');
                        $('.'+property).html('<i class="fas fa-exclamation-triangle"></i>' + result[property]);
                        $('.'+property).show();
                        

                    }
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        });
    });
// ******************************************************************************************
// ************************ Ajouter Document Certificat COVID  ******************************

    $(document).ready(function(e) {

        $("#file_covid").on('change', function() {
        var type='COVID';
        var formData = new FormData(document.querySelector('#tdocument_covid'));
        $("#show_loader")[0].removeAttribute("hidden");
        
        $.ajax({
            type: "POST",
            url: $("[name='tdocument_covid']").attr('action'),
            data: formData,
            success: function (result) {   
                if(result == 'ok'){
                   
                    RefreshAction(type);
                    $("#show_loader").attr("hidden",true);
                    $('.success_COVID').show();
                    $('.error_COVID').hide();
                    hideAlert();
                    $('.icon_times_covid').removeClass('fa-times');
                    $('.icon_times_covid').addClass('fa-check');
                    
                }else{
                    for (const property in result) {
                        $("#show_loader").attr("hidden",true);
                        $('.error-custom').html('');
                        $('.'+property).html('<i class="fas fa-exclamation-triangle"></i>' + result[property]);
                        $('.'+property).show();
                        
                    }
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
        });
    });

// ******************************************************************************************
// ************************ Ajouter Message  ******************************

    $("body").on("submit", "form[name='form_message']", function(ev) {
        ev.preventDefault(); // Prevent browser default submit.
        var formData = new FormData(this);
        formData.append('_terx-message', $('._terx-message').val());
        var type = 'message';

        $.ajax({
            url:  $(this).attr('action'),
            type: "POST",
            data: formData, 

            success: function (result) {   
                if(result == 'ok'){
                    RefreshAction(type);
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
// ************************ Get type document suppromer  ******************************
    $("body").on('click', ".supprimer_file",function(){ 
         documentType = $('>.type-doc',this).val();
         
    });

// ******************************************************************************************
// ************************ Supprimer Document  ******************************

    $("body").on("submit", "form[name='Tdoc-supprime']", function(ev) {
        ev.preventDefault(); // Prevent browser default submit.
        var type = $('> .type-doc','.supprimer_file').val();
        Swal.fire({
            title: 'Vous êtes sûr? ',
            text: "que vous voulez Supprimer ce document?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Fermer !',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui !'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);
                    $.ajax({
                        url: $(this).attr('action'),
                        type: "POST",
                        data: formData,
                        success: function (result) {
                            if(result == 'ok'){ 
                                RefreshAction(documentType);
                                $("#show_loader").attr("hidden",true);
                                if(documentType == 'COVID'){
                                    $('.success_COVID').html('<i class="fas fa-check"></i> Le document a été Supprimé avec succès');
                                    $('.success_COVID').show();
                                    var countCovid = $('.countCovid').val()
                                    if( countCovid == 1 ){
                                        $('.icon_times_covid').removeClass('fa-check');
                                        $('.icon_times_covid').addClass('fa-times');
                                    }
                                }
                                else if(documentType == 'sanitaire'){
                                    $('.success_sanitaire').html('<i class="fas fa-check"></i> Le document a été Supprimé avec succès');
                                    $('.success_sanitaire').show();

                                    var countSanitaire = $('.countSanitaire').val();
                                    if( countSanitaire == 1 ){
                                        // alert(countSanitaire)
                                        $('.icon_times_sanitaire').removeClass('fa-check');
                                        $('.icon_times_sanitaire').addClass('fa-times');
                                    }
                                }
                                hideAlert();
                            } 
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }  
            })       
    });


// ******************************************************************************************
// ************************ Pour hiede l'alert  **************************
function hideAlert(){
    $('.success-add').fadeOut(2200);
}

// ******************************************************************************************
// ************************ Ajouter Document Certificat COVID  ******************************

// $(document).ready(function(e) {

//     $("#file_covid").on('change', function() {
//     var type='COVID';
//     var formData = new FormData(document.querySelector('#tdocument_covid'));
//     $("#show_loader")[0].removeAttribute("hidden");
    
//     $.ajax({
//         type: "POST",
//         url: $("[name='tdocument_covid']").attr('action'),
//         data: formData,
//         success: function (result) {   
//             if(result == 'ok'){
               
//                 RefreshAction(type);
//                 $("#show_loader").attr("hidden",true);
//                 $('.success_COVID').show();
//                 $('.error_COVID').hide();
//                 hideAlert();
//                 $('.icon_times_covid').removeClass('fa-times');
//                 $('.icon_times_covid').addClass('fa-check');
                
//             }else{
//                 for (const property in result) {
//                     $("#show_loader").attr("hidden",true);
//                     $('.error-custom').html('');
//                     $('.'+property).html('<i class="fas fa-exclamation-triangle"></i>' + result[property]);
//                     $('.'+property).show();
                    
//                 }
//             }
//         },
//         cache: false,
//         contentType: false,
//         processData: false
//     });
//     });
// });

// ******************************************************************************************
// ************************ Pour actualiser la partie de l'action  **************************
function RefreshAction(type) {
    axios.post(url_+'/home?type='+type)
    .then((suc) => {
        if(type=='sanitaire'){
            $(".demo_sanit").empty();
            $(".demo_sanit").html(suc.data);
        }
        else if(type=='COVID'){
            $(".myDivCvid").empty();
            $(".myDivCvid").html(suc.data);
        }
        else if(type=='message'){
            $(".myDivMessage").empty();
            $(".myDivMessage").html(suc.data);
        }
    })
    .catch((err) => {  
        console.log(err)
    })
}
