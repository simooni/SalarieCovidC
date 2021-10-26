$( "#dachboard" ).click(function() {

  if($(".dachboard").hasClass('selectedul')){
    $(".dachboard").removeClass('selectedul');
  }else{
    $(".dachboard").addClass("selectedul");
  }
});
  