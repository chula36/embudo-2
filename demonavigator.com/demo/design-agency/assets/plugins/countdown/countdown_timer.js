$(document).ready(function() {
    /*=========================================
    ## Countdown
    =========================================*/
    // Establece el tiempo de cuenta regresiva en 72 horas desde el momento de carga
    var countdownTime = 72 * 60 * 60 * 1000; // 72 horas en milisegundos
    var targetTime = new Date().getTime() + countdownTime;

    $('#counter').countdown(targetTime, function(event) {
        $('#days').html(event.strftime('%D'));
        $('#hours').html(event.strftime('%H'));
        $('#minutes').html(event.strftime('%M'));
        $('#seconds').html(event.strftime('%S'));
    });
});
