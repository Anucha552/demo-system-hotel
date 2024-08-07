function _Element(ele){
    return document.getElementById(ele);
}

function reset(){
    var _text = '';

    _Element('firstName').value = _text;
    _Element('surName').value = _text;
    _Element('tel').value = _text;
    _Element('email').value = _text;
    _Element('password').value = _text;
}