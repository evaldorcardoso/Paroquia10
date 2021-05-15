<?php
ini_set("memory_limit","100M");
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set('America/Sao_Paulo');
//require_once('config.php');
include "pretty_json.php";

$GLOBALS['id_cliente']='unidosporcristo';
/*
*
*PAROQUIAWS-Desenvolvido por EvaldoRC - 2018
*retorno: json
*
*/
//funcao desejada
$funcao=$_GET['funcao'];
	
switch ($funcao) 
{
	case 'lista_evento':
		lista_evento($_GET['id']);
		break;
	case 'lista_eventos':
		lista_eventos();
		break;
	case 'lista_eventos_congregacao':
		lista_eventos_congregacao($_GET['id_congregacao']);
		break;
	case 'lista_eventos_congregacao_admin':
		lista_eventos_congregacao_admin($_GET['id_congregacao']);
		break;
	case 'lista_posts':
		lista_posts();
		break;
	case 'lista_post':
		lista_post($_GET['id']);
		break;
	case 'lista_congregacao':
		lista_congregacao($_GET['id']);
		break;
	case 'lista_congregacoes':
		lista_congregacoes();
		break;
	case 'login_app':
		login($_GET['login'],$_GET['senha']);
		break;
	case 'reseta_app':
		reseta();
		break;
	case 'esqueci_senha':
		esqueci_senha($_GET['emailtr']);
		break;
	case 'salvar_evento':
		salvar_evento($_GET['id'],$_GET['id_congregacao'],$_GET['data'],$_GET['descricao'],$_GET['titulo'],$_GET['horario'],$_GET['endereco'],$_GET['auxiliares'],$_GET['leituras'],$_GET['musica']);
		break;
	case 'salvar_congregacao':
		salvar_congregacao($_GET['id'],$_GET['nome'],$_GET['localidade'],$_GET['descricao'],$_GET['pastor'],$_GET['lat'],$_GET['lon'],$_GET['ativo'],$_GET['imagem']);
		break;
	case 'exclui_evento':
		excluir_evento($_GET['id']);
		break;
	default:
		echo "Funcao nao encontrada";
		break;
}

function lista_evento($id)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> pesquisarTabela('agenda_admin','id',$id);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nao encontrado...';
	else
	{
		$evento = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$evento["id"]= $array_['id'];
			$evento["id_congregacao"]= $array_['id_congregacao'];
			$evento["data"]= $array_['data'];
			$evento["horario"]= $array_['horario'];
			$evento["endereco"]=html_entity_decode($array_['endereco']);			
			$evento["titulo"]=html_entity_decode($array_['titulo']);
			$evento["descricao"]=html_entity_decode($array_['descricao']);
			$evento["auxiliares"]=html_entity_decode($array_['auxiliares']);
			$evento["leituras"]=html_entity_decode($array_['leituras']);
			$evento["musica"]=html_entity_decode($array_['musica']);
			array_push($info, $evento);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}
				
function lista_eventos()
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> listar('agenda','data');
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhum evento ainda...';
	else
	{
		$evento = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$evento["id"]= $array_['id'];
			$evento["departamento"]= $array_['departamento'];
			$evento["data"]= $array_['data'];
			$evento["horario"]= $array_['horario'];
			$evento["titulo"]=html_entity_decode($array_['titulo']);
			$evento["descricao"]=html_entity_decode($array_['descricao']);
			$evento["endereco"]=html_entity_decode($array_['endereco']);
			$evento["flores"]=$array_['flores'];
			$evento["cafe"]=$array_['cafe'];
			$evento["recepcao"]=$array_['recepcao'];
			$evento["musica"]=$array_['musica'];
			$evento["santaceia"]=$array_['santaceia'];
			$evento["santaceia_distribuicao"]=$array_['santaceia_distribuicao'];
			$evento["leitores"]=$array_['leitores'];
			array_push($info, $evento);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}

function lista_eventos_congregacao($id_congregacao)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass2 = new crudClass();	
	$cClass -> pesquisarTabela('agenda','id_congregacao',$id_congregacao);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhum evento ainda...';
	else
	{
		$evento = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$evento["id"]= $array_['id'];
			$evento["departamento"]= $array_['departamento'];
			$evento["data"]= $array_['data'];
			$evento["horario"]= $array_['horario'];
			$evento["titulo"]=html_entity_decode($array_['titulo']);
			$evento["descricao"]=html_entity_decode($array_['descricao']);
			$evento["endereco"]=($array_['endereco']);
			$evento["flores"]=$array_['flores'];
			$evento["cafe"]=$array_['cafe'];
			$evento["recepcao"]=$array_['recepcao'];
			$evento["musica"]=$array_['musica'];
			$evento["santaceia"]=$array_['santaceia'];
			$evento["santaceia_distribuicao"]=$array_['santaceia_distribuicao'];
			$evento["leitores"]=$array_['leitores'];
			$evento["lat"]='';
			$evento["lon"]='';
			$evento["nome"]='';
			$evento["localidade"]='';
			$cClass2->pesquisarTabela('congregacao','id',$id_congregacao);
			if(0<mysqli_num_rows($cClass2->getconsulta()))
			{
				while($array_2 = mysqli_fetch_array($cClass2->getconsulta()))
				{
					$evento["lat"]=$array_2['lat'];
					$evento["lon"]=$array_2['lon'];
					$evento["nome"]=$array_2['nome'];
					$evento["localidade"]=$array_2['localidade'];
				}
			}
			array_push($info, $evento);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	if(!empty($info))
		print_r(pretty_json(json_encode($info)));
}

function lista_eventos_congregacao_admin($id_congregacao)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass2 = new crudClass();	
	$cClass->eventosCongregacaoAdmin($id_congregacao);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhum evento ainda...';
	else
	{
		$evento = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$evento["id"]= $array_['id'];
			$evento["departamento"]= $array_['departamento'];
			$evento["data"]= $array_['data'];
			$evento["horario"]= $array_['horario'];
			$evento["titulo"]=html_entity_decode($array_['titulo']);
			$evento["descricao"]=html_entity_decode($array_['descricao']);
			$evento["endereco"]=($array_['endereco']);
			$evento["flores"]=$array_['flores'];
			$evento["cafe"]=$array_['cafe'];
			$evento["recepcao"]=$array_['recepcao'];
			$evento["musica"]=$array_['musica'];
			$evento["santaceia"]=$array_['santaceia'];
			$evento["santaceia_distribuicao"]=$array_['santaceia_distribuicao'];
			$evento["leitores"]=$array_['leitores'];
			$evento["lat"]='';
			$evento["lon"]='';
			$evento["nome"]='';
			$evento["localidade"]='';
			$cClass2->pesquisarTabela('congregacao','id',$id_congregacao);
			if(0<mysqli_num_rows($cClass2->getconsulta()))
			{
				while($array_2 = mysqli_fetch_array($cClass2->getconsulta()))
				{
					$evento["lat"]=$array_2['lat'];
					$evento["lon"]=$array_2['lon'];
					$evento["nome"]=$array_2['nome'];
					$evento["localidade"]=$array_2['localidade'];
				}
			}
			array_push($info, $evento);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	if(!empty($info))
		print_r(pretty_json(json_encode($info)));
}

function lista_posts()
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> listar('post','data');
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhuma not�cia ainda...';
	else
	{
		$post = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$post["id"]= $array_['id'];
			$post["data"]= $array_['data'];
			$post["imagem"]= $array_['imagem'];
			$post["titulo"]=html_entity_decode($array_['titulo']);
			$post["nome"]=html_entity_decode($array_['nome']);
			//$post["conteudo"]=html_entity_decode($array_['conteudo']);
			array_push($info, $post);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}

function lista_post($id)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> pesquisarTabela('post','id',$id);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhum post ainda...';
	else
	{
		$post = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$post["id"]= $array_['id'];
			$post["data"]= $array_['data'];
			$post["imagem"]= $array_['imagem'];
			$post["titulo"]=html_entity_decode($array_['titulo']);
			$post["conteudo"]=html_entity_decode($array_['conteudo']);
			array_push($info, $post);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}

function lista_congregacao($id)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> pesquisarTabela('congregacao','id',$id);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nao encontrada...';
	else
	{
		$post = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$post["id"]= $array_['id'];
			$post["nome"]= html_entity_decode($array_['nome']);
			$post["descricao"]= html_entity_decode($array_['descricao']);
			$post["localidade"]=html_entity_decode($array_['localidade']);
			$post["pastor"]= html_entity_decode($array_['pastor']);
			$post["lat"]=$array_['lat'];
			$post["lon"]=$array_['lon'];
			$post["ativo"]=$array_['ativo'];
			$post["imagem"]=$array_['imagem'];
			array_push($info, $post);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}

function msgs_naolidas($id)
{
	//array que guarda os dados
	$msgs=0;
	$info = array();
	include_once '../classes/crudClass.php';
	$cClass = new crudClass();	
	$cClass -> msgs_naolidas($id);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo '';
	else
	{
		$count = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$msgs=$msgs+$array_['cont'];
		}
		$count["count"]=$msgs;
		array_push($info, $count);
	}
	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}

function lista_congregacoes()
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> listar('congregacao','nome');
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhuma congrega��o...';
	else
	{
		$post = array();
		while($array_ = mysqli_fetch_array($cClass->getconsulta()))
		{
			$post["id"]= $array_['id'];
			$post["nome"]= html_entity_decode($array_['nome']);
			$post["pastor"]= html_entity_decode($array_['pastor']);
			$post["localidade"]= html_entity_decode($array_['localidade']);
			$post["lat"]= $array_['lat'];
			$post["lon"]= $array_['lon'];
			array_push($info, $post);
		}
	}

	//chama a fun��o para montar o JSON e mostra na tela
	print_r(pretty_json(json_encode($info)));
}

function login($usuario,$senha)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> pesquisarTabela('users','email',$usuario);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhum cadastro encontrado com esse email...';
	else
	{
		$array_=mysqli_fetch_assoc($cClass->getconsulta());
		if($array_['id_congregacao']>0)
		{
			$cClass2=new crudClass();
			$cClass2 -> pesquisarTabela('congregacao','id',$array_['id_congregacao']);
			$array_2=mysqli_fetch_assoc($cClass2->getconsulta());
			if($array_2['ativo']=='0')
			{
				echo 'Seu cadastro esta inativo, verifique o email informado no cadastro para ativar!';
			}
			else
			{
				if($array_['reseta_senha']=='1')
				{
					$us = array();
					$us['id']=$array_['id'];
					$us['email']=$array_['email'];
					$us['reseta_senha']=$array_['reseta_senha'];
					$us['id_congregacao']=$array_['id_congregacao'];
					array_push($info, $us);
				}
				else
				{
					if($array_['senha']==md5($senha))
					{
						$us = array();
						$us['id']=$array_['id'];
						$us['username']=$array_['username'];
						$us['email']=$array_['email'];
						$us['reseta_senha']=$array_['reseta_senha'];
						$us['id_congregacao']=$array_['id_congregacao'];
						array_push($info, $us);
					}
					else
						echo 'Senha invalida!';//senha invalida
				}
			}
		}
		else
		{
			echo 'Nenhuma Congregacao vinculada a esse email!';
		}
	}
	//chama a função para montar o JSON e mostra na tela
	if(!empty($info))
		print_r(pretty_json(json_encode($info)));
}

function reseta()
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();
	unset($_GET['funcao']);
	if($cClass -> acaoCrud($_GET,'users','update','id',$_GET['id']))
	{
		$cClass -> pesquisarTabela('users','username',$_GET['username']);
		if(0==mysqli_num_rows($cClass->getconsulta()))
			echo 'Nenhum cadastro encontrado com esse email...';
		else
		{
			$array_=mysqli_fetch_assoc($cClass->getconsulta());
			if($array_['id_congregacao']>0)
			{
				$cClass2=new crudClass();
				$cClass2 -> pesquisarTabela('congregacao','id',$array_['id_congregacao']);
				$array_2=mysqli_fetch_assoc($cClass2->getconsulta());
				if($array_2['ativo']=='0')
				{
					echo 'Seu cadastro esta inativo, verifique o email informado no cadastro para ativar!';
				}
				else
				{
					if($array_['reseta_senha']=='1')
					echo 'OK reseta_senha='.$array_['id'];
				else
				{
					if($array_['senha']==md5($_GET['senha']))
					{
						$us = array();
						$us['id']=$array_['id'];
						$us['username']=$array_['username'];
						$us['id_congregacao']=$array_['id_congregacao'];
						array_push($info, $us);
					}
					else
						echo 'Senha invalida!';//senha invalida
				}
				}
			}
			else
			{
				echo 'Nenhuma Congregacao vinculada a esse email!';
			}
		}
	}
	//chama a fun��o para montar o JSON e mostra na tela
	if(!empty($info))
		print_r(pretty_json(json_encode($info)));
}

function salvar_evento($id,$id_congregacao,$data,$descricao,$titulo,$horario,$endereco,$auxiliares,$leituras,$musica)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$titulo=utf8_encode($titulo);
	$descricao=utf8_encode($descricao);
	$endereco=utf8_encode($endereco);
	$auxiliares=utf8_encode($auxiliares);
	$leituras=utf8_encode($leituras);
	$musica=utf8_encode($musica);
	$campos= array('id' => $id,'id_congregacao' => $id_congregacao,'data' => $data,'descricao' => $descricao,'titulo' => $titulo,'horario' => $horario,'endereco' => $endereco,'auxiliares' => $auxiliares,'leituras' => $leituras,'musica' => $musica);		
	if($id=='')
	{
		unset($campos['id']);
		if($cClass->acaoCrud($campos,'agenda','insert','','')==1)	
		{
			print_r(pretty_json(json_encode('Salvo com sucesso!')));
		}
		else
			echo 'erro';
	}
	else
	{
		if($cClass->acaoCrud($campos,'agenda','update','id',$campos['id'])==1)
		{
			print_r(pretty_json(json_encode('Salvo com sucesso!')));
		}
		else
			echo 'erro';
	}
}

function salvar_congregacao($id,$nome,$localidade,$descricao,$pastor,$lat,$lon,$ativo,$imagem)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$nome=utf8_encode($nome);
	$localidade=utf8_encode($localidade);
	$descricao=utf8_encode($descricao);
	$pastor=utf8_encode($pastor);
	if($id=='')
	{
		$cClass -> pesquisarTabela('users','email',$_GET['email']);
		if(mysqli_num_rows($cClass->getconsulta())>0)
		{
			echo 'Ja existe um cadastro com esse E-mail!';
		}
		else
		{
			$campos= array('nome' => $nome,'localidade' => $localidade,'descricao' => $descricao,'pastor' => $pastor,'lat' => $lat,'lon' => $lon,'ativo' => '0','imagem' => $imagem);		
			$idcong=$cClass->inserir_retornando_id($campos,'congregacao');
			if($idcong>0)
			{
				$campos_user= array('email' => $_GET['email'] ,'senha' => $_GET['senha'], 'username' => '-', 'reseta_senha' => 0,'id_congregacao' => $idcong);
				if($cClass->acaoCrud($campos_user,'users','insert','','')==1)
				{					
					print_r(pretty_json(json_encode('Cadastrado com sucesso!')));					
					$to = $_GET['email'];
					$subject = 'Ativacao de Cadastro no Aplicativo Paroquia10!';
					$message = 'Obrigado por se inscrever em nosso aplicativo. Para continuar clique no link abaixo para ativar a sua conta: https://paroquia10.com/ativar/';
					include_once 'testmail.php';
				}
				else
				{
					$cClass -> deletar('congregacao','id',$idcong);
					echo 'erro';
				}
			}
			else
				echo 'erro';
		}
	}
	else	
	{
		$campos= array('id' => $id,'nome' => $nome,'localidade' => $localidade,'descricao' => $descricao,'pastor' => $pastor,'lat' => $lat,'lon' => $lon,'ativo' => $ativo, 'imagem' => $imagem);
		if($cClass->acaoCrud($campos,'congregacao','update','id',$campos['id'])==1)
			print_r(pretty_json(json_encode('Salvo com sucesso!')));
		else
			echo 'erro';
	}
}

function esqueci_senha($emailtr)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();	
	$cClass -> pesquisarTabela('users','email',$emailtr);
	if(0==mysqli_num_rows($cClass->getconsulta()))
		echo 'Nenhum cadastro encontrado com esse email...';
	else
	{
		$array_=mysqli_fetch_assoc($cClass->getconsulta());
		$mcr=randString(10);
		if($cClass -> alterar('chave_reseta',$mcr,'users','id',$array_['id']))
		{		
			ini_set('display_errors', 1);
			error_reporting(E_ALL);
			$from = "suporte@paroquia10.com";
			$to = $emailtr;
			$subject = "Resetar senha de usu�rio do aplicativo Par�quia 10";
			$message = "Para resetar sua senha clique no seguinte link: https://paroquia10.com/resetar_senha/?id=".$array_['id']."&chave_reseta=".$mcr;
			$headers = "De:". $from;
			mail($to, $subject, $message, $headers);
			echo "A mensagem de e-mail foi enviada.";
		}
	}	
}

function excluir_evento($id)
{
	//array que guarda os dados
	$info = array();
	include_once 'crudClass.php';
	$cClass = new crudClass();
	//unset($_GET['funcao']);
	if($cClass -> deletar('agenda','id',$_GET['id']))
		echo 'true';
	else
		echo 'false';	
}

//Essa função gera um valor de String aleatório do tamanho recebendo por parametros
function randString($size){
    //String com valor poss�veis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
    $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $return= "";
    for($count= 0; $size > $count; $count++){
        //Gera um caracter aleatorio
        $return.= $basic[rand(0, strlen($basic) - 1)];
    }

    return $return;
}

?>