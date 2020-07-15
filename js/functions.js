/**
 * Created by Marvin on 12.07.20.
 */

 var BASE_URL = 'localhost/room-booking';

$(document).ready(function () {
    $('#send').click(function () {
        if(checkValidity()){
            console.log('test');
            $('#submit_btn').click();
        }
    });


    $('#booking-table').DataTable();

    $("[data-toggle=tooltip]").tooltip();


    // Error Meldungen löschen
    $('input, select').focus(function () {
        clearErrorLog();
    });
    
    //Neue Buchung Btn
    $('#new_booking').click(function () {
        $('input, select').val('');
        clearErrorLog();
    });

    //Daten löschen Btn
    $('.delete-btn').click(function () {
        $('#booking_id').text($(this).data('id'));
        console.log($(this).data('id'));
    });

});


//Dialog Box Meldung
function showModal(title, text){
    $('#modal_title').text(title);
    $('#modal_text').html(text);
    $('#modal_dialog').modal('show');
}

function  checkValidity() {

    // Init Error
    clearErrorLog();


    var isValid = true;
    var err ="";
    var today = new Date();
    var inputDateFrom = new Date($('#dateFrom').val());
    var inputDateTo = new Date($('#dateTo').val());


    //Name Check
    if ( $('#name').val().length<1){
        isValid=false;
        errorTrigger('#name', 'Bitte Name ausfüllen');
    }

    //Email Check
    if ( $('#email').val().length<1){
        isValid=false;
        errorTrigger('#email', 'Bitte Email ausfüllen');
    }

    //From Date Check
    if ( $('#dateFrom').val().length<1){
        isValid=false;
        errorTrigger('#dateFrom', 'Bitte Datum ausfüllen');
    }
    else if (inputDateFrom.getTime() < today.getTime()){
            isValid=false;
            errorTrigger('#dateFrom', 'Das von Ihnen eingegebene Anfangsdatum liegt in der Vergangenheit','warning');
    }

    //To Date Check
    if ( $('#dateTo').val().length<1){
        isValid=false;
        errorTrigger('#dateTo', 'Bitte Datum ausfüllen');
    }
    else if (today.getTime()> inputDateTo.getTime()){
        isValid=false;
        errorTrigger('#dateTo', 'Das von Ihnen eingegebene Enddatum liegt in der Vergangenheit','warning');
    }
    else if (inputDateTo.getTime() < inputDateFrom.getTime()){
        isValid=false;
        errorTrigger('#dateTo', 'Das von Ihnen eingegebene Enddatum ist kleiner asl der Anfangsdatum','warning');
    }

    //Anfangszeit Check
    if ( $('#timeFrom').val().length<1){
        isValid=false;
        errorTrigger('#timeFrom', 'Bitte Anfangszeit auswählen');
    }
    //Endzeit Check
    if ( $('#timeTo').val().length<1){
        isValid=false;
        errorTrigger('#timeTo', 'Bitte Endzeit auswählen');
    }
    else if(inputDateFrom.getTime() == inputDateTo.getTime()){
        if (($('#timeTo').val().split(':')[0] <= $('#timeFrom').val().split(':')[0])
            || (($('#timeTo').val().split(':')[0] == $('#timeFrom').val().split(':')[0])
            && ($('#timeTo').val().split(':')[1] <= $('#timeFrom').val().split(':')[1]))){
            isValid=false;
            errorTrigger('#timeTo', 'Bitte einen gültigen zeitraum auswählen', 'warning');
        }
    }



    //Bestuhlung Check
    if ( $('#places').val().length<1){
        isValid=false;
        errorTrigger('#places', 'Bitte Bestuhlung auswählen');
    }

    return isValid;
}

//Fehler ausgabe
function errorTrigger(elemID, message, _class) {
    if(!_class){
        _class="danger";
    }
    $(elemID).after('<small  class="form-text error-text text-' + _class+ '">'+message+'</small>');
    $(elemID).addClass('border-'+_class);
}

//Fehlermeldungen löschen
function clearErrorLog() {
    $('.error-text').remove();
    $('input, select').removeClass('border-danger');
    $('input, select').removeClass('border-warning');
}