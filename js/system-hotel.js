function _Element(Class){
    return document.getElementsByClassName(Class);
}

function Book_hotel(nameHotel){

    _Element('box-alert-data')[0].style = "display: block;";
    _Element('nameHotel')[0].innerHTML = nameHotel;
    _Element('box-hidden-nameHotel')[0].innerHTML = '<input type="hidden" name="nameHotel" value="' + nameHotel + '">';
}

function submit_cancel(){

    _Element('box-alert-data')[0].style = "display: none;";
}