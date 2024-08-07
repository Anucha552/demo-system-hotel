function _Element(src){
    return document.getElementsByClassName(src);
}

function correct(){
    var input_correct = _Element('input-correct');

    for (var i = 0; i < input_correct.length; i++){
        input_correct[i].style = 'display: block;';
    }

    _Element('box-submid-correct')[0].style = 'display: block;';
    _Element('box-buttom-correct')[0].style = 'display: none;';
}

function  upload_image(){    
    _Element('box-alert-data')[0].style = 'display: block;';
}

function submit_cancel(){

    _Element('box-alert-data')[0].style = "display: none;";
}