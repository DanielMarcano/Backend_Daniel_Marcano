/*
  Creación de una función personalizada para jQuery que detecta cuando se detiene el scroll en la página
*/
$.fn.scrollEnd = function(callback, timeout) {
  $(this).scroll(function(){
    var $this = $(this);
    if ($this.data('scrollTimeout')) {
      clearTimeout($this.data('scrollTimeout'));
    }
    $this.data('scrollTimeout', setTimeout(callback,timeout));
  });
};
/*
  Función que inicializa el elemento Slider
*/


function inicializarSlider(){
  $("#rangoPrecio").ionRangeSlider({
    type: "double",
    grid: false,
    min: 0,
    max: 100000,
    from: 200,
    to: 80000,
    prefix: "$"
  });
}
/*
  Función que reproduce el video de fondo al hacer scroll, y deteiene la reproducción al detener el scroll
*/
function playVideoOnScroll(){
  var ultimoScroll = 0,
      intervalRewind;
  var video = document.getElementById('vidFondo');
  $(window)
    .scroll((event)=>{
      var scrollActual = $(window).scrollTop();
      if (scrollActual > ultimoScroll){
       video.play();
     } else {
        //this.rewind(1.0, video, intervalRewind);
        video.play();
     }
     ultimoScroll = scrollActual;
    })
    .scrollEnd(()=>{
      video.pause();
    }, 10)
}

inicializarSlider();
playVideoOnScroll();
$(function() {

  $('#mostrar').click(function() {
    mostrar('getAll');
  });

});

function mostrar(action) {

  $.ajax({
    url: 'index.php',
    dataType: 'json',
    data: {
      action: action
    },
    type: 'POST',
    success: function(data) {
      // console.log('success:');
      // console.log(data['message']);
      // console.log(data['json']);
      console.log(data['ciudades']);
      console.log(data['tipos']);
      mostrarArticulos(data['json']);
      inicializarSelects(data['ciudades'], data['tipos']);
    },
    error: function(error) {
      console.log(error);
    }
  })
}

function mostrarArticulos(jsonArticulos) {
  $.each(jsonArticulos, function(key, value) {
    $('.colContenido').append(
      "<div class='card horizontal'>" +
        "<div class='card-image'>" +
          "<img src='img/home.jpg' alt='home'/>" +
        "</div>" +
        "<div class='card-stacked'>" +
          "<div class='card-content'>" +
            "<p>Direccion: " + value.Direccion     + '</p>' +
            "<p>Ciudad: "    + value.Ciudad        + '</p>' +
            "<p>Telefono: "  + value.Telefono      + '</p>' +
            "<p>Zip Code: "  + value.Codigo_Postal + '</p>' +
            "<p>Tipo: "      + value.Tipo          + '</p>' +
            "<p class='precioTexto'>Precio: "    + value.Precio + "</p>" +
            "</p>" +
          "</div>" +
          "<div class='card-action'>" +
            "<a href='#'>VER MAS</a>" +
          "</div>" +
        "</div>" +
      "</div>");
    });
}

function inicializarSelects(ciudades, tipos) {
  var selectTipo   = $('#selectTipo');
  var selectCiudad = $('#selectCiudad');

  $.each(ciudades, function(key, value) {
    selectCiudad.append('<option value="' + value + '">' + value + '</option>');
  });

  $.each(tipos, function(key, value) {
    selectTipo.append('<option value="' + value + '">' + value + '</option>');
  });

  $('#selectTipo').material_select();
  $('#selectCiudad').material_select();
}

function inicializarForm() {

}
