//1. initialization

var localDB = null;

function onInit(){
    //_('mensagem_notificacao_ok').innerHTML='Conectando...';
    //_('notificacao_ok').style.display='block';
    try {
        if(!window.openDatabase) 
        {            
            _('notificacao_ok').style.display='none';
            _('mensagem_notificacao_erro').innerHTML='Não foi possível buscar as suas configurações! Feche o aplicativo e tente novamente!';
            _('notificacao_erro').style.display='block';
            window.setTimeout(initDB(), 3000);
        }
        else {
            //onDelete();
            //_('mensagem_notificacao_ok').innerHTML='Conectado!';
            //_('notificacao_ok').style.display='block';
            createTables();
            BuscaUsuarioLocal('index');
            //_('conectado').innerHTML='1';
            _('notificacao_erro').style.display='none';
            //if(!(intervalo===undefined))
            //clearInterval(intervalo);
            //intervalo=null;
        }
    } 
    catch (e) {
        //alert('erro');
        if (e == 2) {
            updateStatus("Error: Invalid database version.");
        }
        else {
            updateStatus("Error: Unknown error " + e + ".");
        }
        return;
    }
}

function initDB(){

    //_('mensagem_notificacao_ok').innerHTML='Iniciando...';
    //_('notificacao_ok').style.display='block';
    var shortName = 'paroquia10';
    var version = '1.1';
    var displayName = 'paroquia10';
    var maxSize = 2000; // in bytes
    localDB = window.openDatabase(shortName, version, displayName, maxSize);

    localDB.transaction(function (tx) {
        // here be the transaction
        // do SQL magic here using the tx object
        onInit();
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

function createTables(){
    //_('mensagem_notificacao_ok').innerHTML='Verificando tabelas...';
    //_('notificacao_ok').style.display='block';
    var query = 'CREATE TABLE IF NOT EXISTS usuario(id INTEGER NOT NULL PRIMARY KEY, login TEXT NOT NULL, senha TEXT NOT NULL, id_congregacao INTEGER NOT NULL);';
    try {
        localDB.transaction(function(transaction){
            transaction.executeSql(query, [], nullDataHandler, errorHandler);
            //updateStatus("Tabela usuários criada");
        });
    } 
    catch (e) {
        updateStatus("Erro: Não foi possível criar a tabela 'usuario' " + e + ".");
        return;
    }
}

//2. query db and view update

// event handler start with on*

function onAtualiza(id,login,senha){
    /*var query = "update usuarios set ";
    if(codigo!='')
        query+='codigo=?,';
    if(nome!='')
        query+='nome=?,';
    if(endereco!='')
        query+='endereco=?,';
    if(imagem!='')
        query+='imagem=?,';


    query=query.substing(0,query.length-1)
    query+=" where id=?;";*/
    var query = "update usuario set login=?,senha=? where id=?;";
        try {
            localDB.transaction(function(transaction){
                transaction.executeSql(query, [codigo,id], function(transaction, results){
                    if (!results.rowsAffected) {
                        //updateStatus("Erro: Nenhuma congregacao atualizada com o id="+id);
                        _('mensagem_notificacao_erro').innerHTML='Erro: Não foi possível atualizar!';
                        _('notificacao_erro').style.display='block';
                    }
                    else {
                        //_('mensagem_notificacao_ok').innerHTML='Dados salvos com sucesso!';
                        //_('notificacao_ok').style.display='block';
                        //_("codigo").innerHTML=codigo;
                        //lista_congregacao(codigo);
                        //tab('agenda');
                        //updateForm("", "", "");
                        //updateStatus("Registro atualizado:" + results.rowsAffected);
                        //queryAndUpdateOverview();
                    }
                }, errorHandler);
            });
        } 
        catch (e) {
            updateStatus("Erro: Não foi possível atualizar " + e + ".");
        }
}

function onDelete(){
    try 
    {
        var query = "delete from usuario;";
        localDB.transaction(function(transaction){
            transaction.executeSql(query, [], function(transaction, results){
                if (!results.rowsAffected) 
                {
                    alert("Erro: Nenhum usuario afetado");
                    //_('mensagem_notificacao_erro').innerHTML='Erro: Nenhuma congregação excluída';
                    //_('notificacao_erro').style.display='block';
                }
                else 
                {
                    //_('mensagem_notificacao_ok').innerHTML='Dados excluídos com sucesso!';
                    //_('notificacao_ok').style.display='block';
                    //alert('excluido com sucesso');
                    _('li_entrar').style.display='block';
                    _('li_alterar').style.display='none';
                    _('li_sair').style.display='none';
                    //console.log('excluido com sucesso');
                    //document.getElementById("codigo").innerHTML='';
                    //setTimeout(BuscaUsuarioLocal('index'), 1000);
                }
            }, errorHandler);
        }, 
        function(transaction, error){
            updateStatus(error.message);
        });
    } 
    catch (e) {
        updateStatus("Error: Não foi possível buscar a congregacao " + e + ".");
    }
    //location.reload(); 
}

function onCreate(id,login,senha,id_congregacao){
    //_('mensagem_notificacao_ok').innerHTML='Salvando...';
    //_('notificacao_ok').style.display='block';
    //if (id == "" || codigo == "") {
    //    updateStatus("Erro: 'id' e 'Codigo' são necessários!");
   // }
    //else {
        var query = "insert into usuario (id, login, senha, id_congregacao) VALUES (?, ?, ?, ?);";
        try {
            localDB.transaction(function(transaction){
                transaction.executeSql(query, [id, login, senha, id_congregacao], function(transaction, results){
                    if (!results.rowsAffected) {
                        alert("Erro: Nenhum Registro afetado.");
                        //alert("Erro: Nenhum Registro afetado.");
                        _('mensagem_notificacao_erro').innerHTML='Erro: Não foi possível salvar!';
                        _('notificacao_erro').style.display='block';
                        //_('notificacao_ok').style.display='none';
                        //_('codigo').innerHTML=codigo;
                    }
                    else {
                        //updateForm("", "", "");
                        _('li_entrar').style.display='none';
                        _('li_alterar').style.display='block';
                        _('li_sair').style.display='block';
                        //alert("Inserido com id " + results.insertId);
                        //queryAndUpdateOverview();
                        //document.getElementById("div-login").style.display="none";//location.href='main.html';
                        //document.getElementById("div-main").style.display="block";
                        //document.getElementById("codigo").innerHTML=results.insertId;
                        //BuscaUsuarioLocal('');
                        //logar(email,pin);
                        //buscaTarefas();
                        //location.href="main.html";
                        //_('codigo').innerHTML=codigo;
                        //_('mensagem_notificacao_ok').innerHTML='Dados salvos com sucesso!';
                        //_('notificacao_ok').style.display='none';
                        //BuscaUsuarioLocal('index');
                        //tab('agenda');
                    }
                }, errorHandler);
            });
        } 
        catch (e) {
            updateStatus("Erro: Não foi possivel gravar " + e + ".");
        }
    //}
}

function BuscaUsuarioLocal(pagina){
    //var old_codigo=_("codigo").innerHTML;
    var query = "SELECT * FROM usuario;";
    try {
        localDB.transaction(function(transaction){
            transaction.executeSql(query, [], function(transaction, results){
                if(results.rows.length>0)
                {
                    for (var i = 0; i < results.rows.length; i++) {
                        var row = results.rows.item(i);
                        _('li_entrar').style.display='none';
                        _('li_alterar').style.display='block';
                        _('li_sair').style.display='block';
                        var str=window.location.pathname;
                        if(str.includes('index.html'))
                        {
                            _('entrar_email').value=row['login'];
                            _('entrar_senha').value=row['senha'];
                            entrar();
                        }
                        if(str.includes('admin.html'))
                        {
                            lista_congregacao(row['id_congregacao']);
                        }


                        //_("codigo").innerHTML=row['codigo'];
                        //_('notificacao_ok').style.display='none';
                        /*if(pagina=='index')
                        {
                            try
                            {
                                //if(document.getElementById("div-agenda").style.display=='block')
                                lista_eventos(row['codigo']);
                                //if(document.getElementById("div-main").style.display=='block')
                                //    lista_posts();
                                
                            }catch(e){}
                        }*/
                        
                    }
                }
                else
                {
                    _('li_entrar').style.display='block';
                    _('li_alterar').style.display='none';
                    _('li_sair').style.display='none';
                    /*if(_("codigo").innerHTML=='')
                    {
                        _('mensagem_notificacao_ok').innerHTML='Para visualizar a Agenda, selecione uma congregação, clicando na aba "Localização"!';
                        _('notificacao_ok').style.display='block';
                    }        
                    tab('inicio');*/
                }
            }, function(transaction, error){
                updateStatus("Erro: " + error.code + "<br>Mensagem: " + error.message);
            });
        });

        
    } 
    catch (e) {
        updateStatus("Erro: Não foi possivel buscar a congregacao " + e + ".");
    }
}

// 3. misc utility functions

// db data handler

errorHandler = function(transaction, error){
    //updateStatus("Error: " + error.message);
    //alert("Error: " + error.message);

    return true;
}

nullDataHandler = function(transaction, results){
}

// update view functions

function updateForm(id, amount, name){
    //document.itemForm.id.value = id;
    //document.itemForm.amount.value = amount;
    //document.itemForm.name.value = name;
}

function updateStatus(a){
    //alert(a);
    _('mensagem_notificacao_erro').innerHTML=a;
    _('notificacao_erro').style.display='block';
    //_('notificacao_ok').style.display='none';
}