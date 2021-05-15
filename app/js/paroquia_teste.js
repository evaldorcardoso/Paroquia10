//1. initialization

var localDB = null;

function initDB(){

    _('mensagem_notificacao_ok').innerHTML='Iniciando...';
    _('notificacao_ok').style.display='block';
    var shortName = 'paroquia10';
    var version = '1.1';
    var displayName = 'paroquia10';
    var maxSize = 2000; // in bytes
    localDB = window.openDatabase(shortName, version, displayName, maxSize);

    localDB.transaction(function (tx) {
        // here be the transaction
        // do SQL magic here using the tx object
        //onInit();
    });

    /*if((localDB===undefined))
    {
        window.setTimeout(function() {
        if(_('conectado').innerHTML=='0'){
            intervalo = window.setInterval(onInit,4000);            
        }
    }, 10);
    }*/
}