<?php
/*crudClass 
	v. 2.0
*/
//VERSaO DO SISTEMA//
$GLOBALS['versao']='1.0';
$GLOBALS['id_cliente']='unidosporcristo';

class crudClass 
{
	public function db_connect() 
	{

	    //Define connection as a static variable, to avoid connecting more than once
	    static $connection;
	    // Try and connect to the database, if a connection has not been established yet
		if(!isset($connection)) 
	    {
	        // Load configuration as an array. Use the actual location of your configuration file
	        include_once('Ini.class.php');
    		$config = new IniParser( 'config.ini' );
	        $connection = mysqli_connect($config->getValue('server'),$config->getValue('usuario'),$GLOBALS['id_cliente'],$config->getValue('banco'));
	        mysqli_query($connection,"SET time_zone = 'America/Sao_Paulo';");
	   	}
        // If connection was not successful, handle the error
        if($connection === false) {
			// Handle error - notify administrator, log to a file, show an error screen, etc.
			/*print_r($config->getValue('server'));
			print_r($config->getValue('usuario'));
			print_r($GLOBALS['id_cliente']);
			print_r($config->getValue('banco'));*/
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
    		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			//echo 'erro de conexão';
            return mysqli_connect_error();
        }
        //mysqli_close($connection);
        return $connection;
   	}

	public function inserir($formulario,$tabela) 
	{
		unset($formulario['id']);
		$connection = $this->db_connect();
		//print_r($formulario);
		$campo="";	$dado="";	$alterar="";
		foreach($formulario as $cod => $valor){
			$x = substr($cod, 0,2);
			$valor = utf8_decode($valor);
			if($x <> "x_") {
				//if(false===strrpos($campo,$cod))
				//{
					$campo.="$cod,"; 
				//}
				//if(substr_count($valor, "'")>0)
				//	$valor=addslashes($valor);
				$dado.="'$valor',";
			}
		}
		$a = strlen($campo);	 $campos = substr($campo, 0,$a-1);
		$b = strlen($dado); 	 $dados = substr($dado, 0,$b-1);
		//echo $campo;
		//echo $dado;
		//print_r("INSERT INTO $tabela ($campos) VALUES ($dados)");
		$query = mysqli_query($connection,"INSERT INTO $tabela ($campos) VALUES ($dados)") or die (mysqli_error($connection));
		if($query) 
		{ 
			return true;
		}	
		else
		{
			return false;
		}
	}

	public function alterar($campo,$valor,$tabela,$campoId,$id) 
	{		
		$connection = $this->db_connect();
		//echo 'ooopaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa';
		$t="UPDATE $tabela set $campo=$valor where $campoId='$id'";
		//echo $t;
		$query = mysqli_query($connection,"UPDATE $tabela set $campo='$valor' where $campoId='$id'") or die (mysqli_error($connection));
		if($query) 
		{ 
			return true;
		}
		else
			return false;
	}

	public function alterarVarios($id_tarefa,$ids) 
	{
		$connection = $this->db_connect();
		$sql="DELETE FROM fontes_tarefa WHERE id_tarefa=$id_tarefa";
		$query = mysqli_query($connection,$sql);
		$sql="REPLACE INTO fontes_tarefa (id_tarefa, id_fonte, status) VALUES ";
		$campos = explode(",",$ids);
		foreach ($campos as $campo => $valor) 
		{
			$sql.= "($id_tarefa,$valor,1), ";
		}
		$sql=substr($sql, 0,$sql-2);
		$query = mysqli_query($connection,$sql);
		if($query) 
		{ 
			return true;
		}
		else
			return false;
	}

	public  function acaoCrud($formulario,$tabela,$acao,$campoId,$id) 
	{
		$connection = $this->db_connect();
		$campo="";	$dado="";	$alterar="";
		foreach($formulario as $cod => $valor){
			$x = substr($cod, 0,2);
			//if(((substr_count($valor, "'")>0))or(substr_count($valor, '"')>0))
				//$valor=addslashes($valor);
				$valor=mysqli_real_escape_string($connection,$valor);
			$valor = utf8_decode($valor);
			if($x <> "x_") {
				if($cod=='senha')
					$valor=md5($valor);

				$campo.="$cod,"; 
				$dado.="'$valor',";
				
				
				if($valor=='NULL')
					$alterar.="$cod=$valor,";
				else
					$alterar.="$cod='$valor',"; 
			}
		}
		$a = strlen($campo);	 $campos = substr($campo, 0,$a-1);
		$b = strlen($dado); 	 $dados = substr($dado, 0,$b-1);
		$c = strlen($alterar);   $alterar = substr($alterar, 0,$c-1);
		switch($acao) {
			case 'insert': 
				$query = mysqli_query($connection,"INSERT INTO $tabela ($campos) VALUES ($dados)") or die(mysqli_error($connection));
				if($query) 
				{ 
					return true;
				}	
				else
				{	
					return false;
				}
				break;
			case 'update':
				//print_r($alterar);
				$query = mysqli_query($connection,"UPDATE $tabela set $alterar where $campoId='$id'") or die (mysqli_error($connection));
				//print_r($query);
				if($query) 
				{ 
					return true;
				}	
				else
				{	
					return false;
				}
		}
	}

	public  function deletar($tab,$campo,$valorCampo) 
	{
		$connection = $this->db_connect();
		if(!empty($tab) && !empty($campo) && !empty($valorCampo)) 
		{
			$sql="DELETE FROM $tab WHERE $campo='$valorCampo'";
			$query = mysqli_query($connection,$sql) or die (mysqli_error());
			if($query)
				return true;
			else	
				return false;
		}
	}

	public function listar($tab,$orderby)
		{
			$connection = $this->db_connect();
			if($tab=='agenda')
				$sql="SELECT a.*,ac.flores,ac.cafe,ac.recepcao,ac.musica,ac.santaceia,ac.santaceia_distribuicao,ac.leitores FROM agenda a left join agenda_culto ac ON a.id=ac.id_agenda WHERE (CONCAT(date(a.data), 'time(a.horario)')  >= CONCAT(CURDATE(), '23:59:59')) ORDER BY a.data,a.horario";
			elseif($tab=='post')
				$sql="SELECT p.*,c.nome FROM $tab p left join congregacao c on p.id_congregacao=c.id ORDER BY $orderby desc";
			elseif($tab=='congregacao')
				$sql="SELECT * FROM $tab WHERE ativo=1 and id>0 ORDER BY $orderby";
			else
				$sql="SELECT * FROM $tab ORDER BY $orderby";
			$this->resultado=mysqli_query($connection,$sql);
		}

	//retorna o mysqliquery
	public function listar2($tab,$orderby)
	{
		$connection = $this->db_connect();
		$sql="SELECT * FROM $tab ORDER BY $orderby";
		return mysqli_query($connection,$sql);
	}

	public function pesquisaTabela($tab,$campos)//EDITADO
	{
		$search=false;
		$connection = $this->db_connect();
		switch ($tab) {
			case 'fatura':
				$sql="SELECT F.*,CL.fantasia, SE.descricao, CL.email, CL.fone1, CL.fone2, CL.fone3, CL.fone4, CL.contato FROM $tab F LEFT JOIN cliente CL ON CL.id=F.id_cliente LEFT JOIN servico SE ON SE.id=F.id_servico WHERE ";
				//$search=true;
				break;
			case 'cliente':
				$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE ";
				break;
			case 'parceiro':
				$sql="SELECT PA.*,C.nome as cidade FROM $tab PA LEFT JOIN cidade C ON PA.id_cidade = C.id WHERE ";
				break;
			case 'tarefa':
				$sql="SELECT CH.*,CL.fantasia, CL.email, CL.fone1, CL.fone2, CL.fone3, CL.fone4, US.nome, USC.nome as nome_cadastro, SE.tipo as tipo_servico, US.imagem FROM $tab CH LEFT JOIN cliente CL ON CL.id=CH.id_cliente LEFT JOIN servico SE ON SE.id=CH.id_servico LEFT JOIN usuario US ON US.id=CH.id_usuario_executa LEFT JOIN usuario USC ON USC.id=CH.id_usuario_cadastro WHERE ";
				break;
			default:
				$sql="SELECT * FROM $tab WHERE "; 
				break;
		}
		foreach ($campos as $campo => $valor) 
		{
			$search=true;
			$valor = utf8_decode($valor);
			if($campo=="senha")
				$sql.="$campo='".md5($valor)."' AND ";
			else
			{
				switch ($campo) {
					case strripos($campo, '_start')>0:
						$sql.=substr($campo,0,(strripos($campo, '_start'))).">='$valor' AND ";
						break;
					case strripos($campo, '_ends')>0:
						$sql.=substr($campo,0,(strripos($campo, '_ends')))."<'$valor' AND ";
						break;
					case strripos($campo, '_end')>0:
						$sql.=substr($campo,0,(strripos($campo, '_end')))."<='$valor' AND ";
						break;
					case 'fantasia':
						$sql.="LOWER($campo) LIKE LOWER('%".$valor."%')  AND ";
						break;
					case 'razao_social':
						$sql.="LOWER($campo) LIKE LOWER('%".$valor."%')  AND ";
						break;
					case 'cidade':
						$sql.="LOWER(C.nome) LIKE LOWER('%".$valor."%')  AND ";
						break;
					case 'descricao':
						if($tab=='tarefa')
						{
							$sql.="LOWER(convert(CH.$campo using latin1)) LIKE LOWER('%".$valor."%')  AND ";
						}
						break;
					case 'fone':
						if($tab=='parceiro')
							$sql.="LOWER(CONCAT(fone1,fone2,fone3,fone4)) LIKE LOWER('%".$valor."%')  AND ";
						else
							$sql.="$campo=$valor AND ";
						break;
					case 'solucao':
						if($tab=='tarefa')
						{
							$sql.="LOWER(convert(CH.$campo using latin1)) LIKE LOWER('%".$valor."%')  AND ";
						}
						break;
					case 'id':
						switch ($tab) {
							case 'fatura':
								$sql.="F.$campo=$valor AND ";
								break;
							case 'tarefa':
								$sql.="CH.$campo=$valor AND ";
								break;
							case 'parceiro':
								$sql.="PA.$campo=$valor AND ";
								break;
							default:
								$sql.="$campo=$valor AND ";
								break;
						}
						break;
					case 'id_usuario_executa':
						if($tab=='tarefa')
						{
							if($valor=='0')//se for 'minhas tarefas' pega o id do usuario logado
          						$sql.="id_usuario_executa=".$_SESSION["codigo"]." AND ";
							else
							{
								if($valor=='99')
        							$sql.="id_usuario_executa is NULL AND ";
								else
									$sql.="$campo=$valor AND ";
							}
						}
						else
							$sql.="$campo=$valor AND ";
						break;
					case 'tipo':
						if($tab=='tarefa')
							$sql.="SE.$campo=$valor AND ";
						else
							$sql.="$campo=$valor AND ";
						break;
					case 'id_remessa':
						if($valor=='NULL')
							$sql.="F.$campo is NULL AND ";
						else
							$sql.="$campo=$valor AND ";
						break;
					case 'id_tarefa':
						if($valor=='NULL')
							$sql.="$campo is NULL AND ";
						else
							$sql.="$campo=$valor AND ";
						break;
					case 'nosso_numero':
						if($valor=='NULL')
							$sql.="F.$campo is NULL AND ";
						else
							$sql.="$campo=$valor AND ";
						break;
					default:
						$sql.="$campo=$valor AND ";
						break;
				}
			}
		}
		if ($search)
			$sql=substr($sql, 0,$sql-4);
		else
			$sql=substr($sql, 0,$sql-6);	
		if($tab == 'fatura')
			$sql.= ' order by CL.fantasia, dt_vencimento';
		if($tab == 'tarefa')
		{
			$sql.= ' order by id desc';
			$sql.= ' LIMIT 100';
		}
		if($tab == 'versao')
			$sql.= ' order by id desc';
		if($tab == 'remessa')
			$sql.= ' order by id desc';
		
		//echo $sql;
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function pesquisarTabela($tab,$campo,$valorCampo)
	{
		$connection = $this->db_connect();
		switch ($tab) {
			case 'agenda':
				$ddd=date('Y-m-d');
				$hhh=date('H:i');
				$sql="SELECT * FROM agenda WHERE (CONCAT(date(data), time(horario))  >= CONCAT('$ddd', '$hhh')) AND $campo='$valorCampo' ORDER BY data,horario";
				break;
			case 'agenda_admin':
				/*$sql="SELECT * FROM agenda WHERE (CONCAT(date(data), 'time(horario)')  >= CONCAT(CURDATE(), '23:59:59')) AND $campo='$valorCampo' ORDER BY data,horario";*/
				$sql="SELECT * FROM agenda WHERE $campo='$valorCampo' ORDER BY data,horario";
				break;
			case 'congregacao':
				$sql="SELECT * FROM $tab WHERE $campo='$valorCampo'";
				break;
			default:
				$sql="SELECT * FROM $tab WHERE $campo='$valorCampo'";
				break;
		}

		$sql.= ' LIMIT 100';
		//echo $sql;
		$this->resultado=mysqli_query($connection,$sql);
	}

	//retorna o mysqliquery
	public function pesquisarTabela2($tab,$campo,$valorCampo)
	{
		$connection = $this->db_connect();
		switch ($tab) {
			case 'cliente':
				$sql="SELECT CL.*,C.nome as cidade, E.uf as estado FROM $tab CL LEFT JOIN cidade C ON C.id=CL.id_cidade LEFT JOIN estado E ON E.id=C.id_estado  WHERE CL.$campo='$valorCampo'";
				break;
			default:
				$sql="SELECT * FROM $tab WHERE $campo='$valorCampo'";
				break;
		}
		$sql.= ' LIMIT 100';
		return mysqli_query($connection,$sql);
	}

	public function ultimoAcesso($tab,$campo,$valorCampo)
	{
		$connection = $this->db_connect();
		$res = mysqli_query($connection,"SELECT MAX(idAcessoPessoa) FROM $tab WHERE $campo='$valorCampo'");
		$row = mysqli_fetch_array($res);
		return $row[0];
	}

	//pode retornar um array
	public function pesquisarCampo($tab,$campo_pesquisa,$id)
	{
		$connection = $this->db_connect();
		$res = mysqli_query($connection,"SELECT $campo_pesquisa FROM $tab WHERE id=$id");
		return $res;
	}

	public function pegaUltimoIdInserido()
	{
		$connection = $this->db_connect();
		return mysqli_insert_id($connection);
	}

	public function inserir_retornando_id($formulario,$tabela) 
	{
		$connection = $this->db_connect();
		//print_r($formulario);
		$campo="";	$dado="";	$alterar="";
			foreach($formulario as $cod => $valor){
				$x = substr($cod, 0,2);
				$valor = utf8_decode($valor);
				if($x <> "x_") {
					if($cod=='senha')
						$valor=md5($valor);
					if(false===strrpos($campo,$cod))
					{
						$campo.="$cod,"; 
					}
					$dado.="'$valor',";
				}
			}
			$a = strlen($campo);	 $campos = substr($campo, 0,$a-1);
			$b = strlen($dado); 	 $dados = substr($dado, 0,$b-1);
			//echo $campo;
			//echo $dado;
			//print_r($dados);
			//print_r("INSERT INTO $tabela ($campos) VALUES ($dados)");
			$query = mysqli_query($connection,"INSERT INTO $tabela ($campos) VALUES ($dados)") or die(mysqli_error($connection));
			//echo $query;
			//echo mysqli_insert_id($connection);
			if($query) 
			{ 
				return mysqli_insert_id($connection);
			}	
			else
			{
				return false;
			}
	}

	public  function pesquisaCampo($tab,$campo,$id)
	{
		$connection = $this->db_connect();			
		$res = mysqli_query($connection,"SELECT $campo FROM $tab WHERE id=$id");
		if($res)
		{
			$row = mysqli_fetch_array($res);
			return $row[0];
		}
		else
			return '';
	}

	public  function pesquisaCampoEspecifico($tab,$campo,$idPesquisado,$id)
	{
		$connection = $this->db_connect();			
		$res = mysqli_query($connection,"SELECT $campo FROM $tab WHERE $idPesquisado=$id");
		if($res)
		{
			$row = mysqli_fetch_array($res);
			return $row[0];
		}
		else
			return '';
	}

	public function count($tabela)
	{
		$connection = $this->db_connect();
		$sql_query = "SELECT COUNT(id) FROM $tabela";
		$row = mysqli_query($connection,$sql_query);
		$value = mysqli_fetch_array($row);
		return $value[0];
	}

	public  function count2($tabela,$campos)
	{
		$connection = $this->db_connect();
		$sql="SELECT COUNT(id) FROM $tabela WHERE "; 
		foreach ($campos as $campo => $valor) {
			if(!(($_SESSION['tipo']=='4') and ($campo=='id_usuario_executa')))
			{
				$valor = utf8_decode($valor);
				$sql.="$campo='$valor' AND ";
			}
		}
		if($_SESSION['tipo']=='4')
			$sql.= 'id_usuario_executa is null';
		else
			$sql=substr($sql, 0,$sql-4);
		//echo $sql;
		$row = mysqli_query($connection,$sql);
		$value = mysqli_fetch_array($row);
		return $value[0];
	}

	public function pesquisar($tab,$campo,$valor,$start)
	{
		$connection = $this->db_connect();
		switch ($tab) {
			case 'cliente':
				switch ($campo) {
					case 'id':
						$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE CL.$campo =".$valor." LIMIT $start,100";
						break;
					case 'fone':
						$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE LOWER(CONCAT(fone1,fone2,fone3,fone4)) LIKE LOWER('%".$valor."%') LIMIT $start,100";
						break;
					case 'cidade':
						$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE LOWER(C.nome) LIKE LOWER('%".$valor."%') LIMIT $start,100";
						break;
					case 'ativo':
						$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE CL.$campo =".$valor." order by fantasia LIMIT $start,100";
						break;
					case 'parceiro':
						$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id LEFT JOIN parceiro P ON CL.id_parceiro = P.id WHERE (LOWER(P.fantasia) LIKE LOWER('%".$valor."%')) LIMIT $start,100";
						break;
					default:
						$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE LOWER($campo) LIKE LOWER('%".$valor."%') LIMIT $start,100";
						break;
					}
				break;
			case 'tarefa':
				if ($campo == 'id')
					$sql="SELECT CH.* FROM $tab CH WHERE CH.$campo =".$valor." LIMIT $start,100";
				else
					$sql="SELECT CH.* FROM $tab CH WHERE LOWER($campo) LIKE LOWER('%".$valor."%') LIMIT $start,100";
				break;
			case 'parceiro':
				if ($campo == 'id')
					$sql="SELECT PA.*,C.nome as cidade FROM $tab PA LEFT JOIN cidade C ON PA.id_cidade = C.id WHERE PA.$campo =".$valor." LIMIT $start,100";
				else
					$sql="SELECT PA.*,C.nome as cidade FROM $tab PA LEFT JOIN cidade C ON PA.id_cidade = C.id WHERE LOWER($campo) LIKE LOWER('%".$valor."%') LIMIT $start,100";
				break;
			default:
				# code...
				break;
		}
		//echo $sql;
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function listarLimit($tab,$orderby,$start,$desc)
	{
		$connection = $this->db_connect();
		switch ($tab) {
			case 'cliente':
				$sql="SELECT CL.*,C.nome as cidade FROM $tab CL LEFT JOIN cidade C ON CL.id_cidade = C.id ORDER BY $orderby $desc LIMIT $start,100";
				break;
			case 'clientes':
				$sql="SELECT CL.*,C.nome as cidade FROM cliente CL LEFT JOIN cidade C ON CL.id_cidade = C.id WHERE ATIVO = 1 ORDER BY $orderby $desc LIMIT $start,100";
				break;
			case 'fatura':
				$sql="SELECT F.*,CL.FANTASIA AS fantasia, S.DESCRICAO AS descricao, CL.email FROM fatura F LEFT JOIN cliente CL ON F.ID_CLIENTE=CL.ID LEFT JOIN servico S ON F.ID_SERVICO=S.ID WHERE STATUS=0  ORDER BY $orderby LIMIT $start,100";
				break;
			case 'tarefa':
				$sql="SELECT CH.*,CL.FANTASIA AS fantasia,CL.fone1,CL.fone2,CL.fone3,CL.fone4, S.DESCRICAO AS servico FROM tarefa CH LEFT JOIN cliente CL ON CH.ID_CLIENTE=CL.ID LEFT JOIN servico S ON CH.ID_SERVICO=S.ID ORDER BY $orderby LIMIT $start,100";
				break;
			case 'parceiro':
				$sql="SELECT PA.*,C.nome as cidade FROM $tab PA LEFT JOIN cidade C ON PA.id_cidade = C.id ORDER BY $orderby $desc LIMIT $start,100";
				break;
			case 'versao':
				$sql="SELECT * FROM $tab ORDER BY $orderby DESC LIMIT $start,100";
				break;
			case 'remessa':
				$sql="SELECT * FROM $tab ORDER BY $orderby $desc LIMIT $start,100";
				break;
			default:
				$sql="SELECT * FROM $tab ORDER BY $orderby LIMIT $start,100";
				break;
		}
		//echo $sql;
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function pesquisarTotal($tab,$campo,$valor,$start)
	{
		$connection = $this->db_connect();
		if ($tab = 'fatura')
		{
			$sql="SELECT F.*,CL.fantasia as cliente, CL.email , SE.descricao as servico FROM $tab F LEFT JOIN cliente CL ON CL.id=F.id_cliente LEFT JOIN servico SE ON SE.id=F.id_servico WHERE F.$campo LIKE '%".$valor."%'";
		}
		else
		{
			if ($campo == 'id')
				$sql = "SELECT * FROM $tab WHERE $campo =".$valor;
			else	
				$sql = "SELECT * FROM $tab WHERE $campo LIKE '%".$valor."%'";
		}
		$sql.= ' LIMIT 100';
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function clientesEmDia()
	{
		$connection = $this->db_connect();
		$sql = "SELECT * FROM cliente WHERE ativo=1 and tipo_liberacao=3 and id not in (select id_cliente from fatura where((dt_vencimento<current_date) and (status=0)or ((boleto_atualizado=1) and (status=0))) group by id_cliente)";
		//$sql = "SELECT * FROM cliente WHERE ativo=1 and tipo_liberacao=3 and id not in (select id_cliente from fatura where(((dt_vencimento<current_date) and (status=0)) or ((boleto_atualizado=1) and (status=0))) group by id_cliente)or (if(liberacao_temporaria=1,dt_liberacao_start>=now(),dt_liberacao_end<=now()))";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function correcoes_melhorias_versao($id)
	{
		$connection = $this->db_connect();
		$sql = "SELECT t.* FROM versao v LEFT JOIN tarefa t on t.id_versao=v.id LEFT JOIN servico s on t.id_servico=s.id
			WHERE t.situacao=2 and s.tipo=3 and t.id_cliente=1 and t.id_versao=$id order by t.id";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function tarefas_versao($id)
	{
		$connection = $this->db_connect();
		$sql = "SELECT t.*,c.fantasia as cliente FROM versao v LEFT JOIN tarefa t on t.id_versao=v.id LEFT JOIN servico s on t.id_servico=s.id
			LEFT JOIN cliente c on t.id_cliente=c.id 
			WHERE t.situacao=2 and s.tipo=3 and t.id_cliente!=1 and t.id_versao=$id order by c.id";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function tarefas_aberto_versao($id)
	{
		$connection = $this->db_connect();
		$sql = "SELECT t.*,c.fantasia as cliente FROM versao v LEFT JOIN tarefa t on t.id_versao=v.id LEFT JOIN servico s on t.id_servico=s.id
			LEFT JOIN cliente c on t.id_cliente=c.id 
			WHERE t.situacao!=2 and s.tipo=3 and t.id_versao=".$id;
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function tarefas_naoverificadas_versao($id)
	{
		$connection = $this->db_connect();
		$sql = "SELECT t.*,c.fantasia as cliente FROM versao v LEFT JOIN tarefa t on t.id_versao=v.id LEFT JOIN servico s on t.id_servico=s.id
			LEFT JOIN cliente c on t.id_cliente=c.id 
			WHERE t.verificada=0 and s.tipo=3 and t.id_versao=".$id;
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function sobreGroupByVersao()
	{
		$connection = $this->db_connect();
		$sql = "SELECT * FROM sobre group by versao order by versao desc";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function limpar_liberacao_todos()
	{
		$connection = $this->db_connect();
		$query = mysqli_query($connection,"UPDATE cliente set liberacao='',vencimento_liberacao='' ") or die (mysqli_error($connection));
		if($query)
			return true;
		else
			return false;
	}

	public function listar_fontes_livres($id_projeto,$orderby)
	{
		$connection = $this->db_connect();
		$sql="SELECT f.id,f.nome FROM fontes f LEFT JOIN fontes_tarefa ft ON f.id=ft.id_fonte 
		WHERE f.id_projeto=$id_projeto and f.id not in (select id_fonte from fontes_tarefa where status=1 and id_fonte=f.id group by id_fonte) group by f.id,f.nome ORDER BY $orderby";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function listar_fontes_tarefa($id_tarefa,$orderby)
	{
		$connection = $this->db_connect();
		$sql="SELECT f.id,f.nome FROM fontes f LEFT JOIN fontes_tarefa ft ON f.id=ft.id_fonte 
		WHERE f.id_projeto=1 and ft.id_tarefa = $id_tarefa group by f.id,f.nome ORDER BY $orderby";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function atualiza_chat($pagina)//pega a ultima mensagem de cada usu�rio
	{
		$connection = $this->db_connect();
		$sql="SELECT C.id, U.id as id_usuario,U2.id as id_usuario2, U.nome as nome, C.msg, C.envio, (select count(id) from chat where id_usuario=U.id and lido_usuario2=0)as count 
			FROM chat C LEFT JOIN usuario U ON C.id_usuario=U.id LEFT JOIN usuario U2 ON C.id_usuario2=U2.id 
			WHERE C.id IN(
    			SELECT MAX(id)
				FROM chat
				GROUP BY id_usuario
			)
			ORDER BY C.envio";
			
				$sql.= " DESC";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function atualiza_feed()
	{
		$connection = $this->db_connect();
		$sql="SELECT P.id, U.id as id_usuario, U.nome as nome, P.descricao, P.envio, P.imagem, P.setor, P.privado, PR.resposta
			FROM post P LEFT JOIN usuario U ON P.id_usuario=U.id 
			LEFT JOIN post_resposta PR ON P.id=PR.id_post
			ORDER BY P.envio DESC";
		$this->resultado=mysqli_query($connection,$sql);
	}

	public function msgs_naolidas($id)
	{
		$connection = $this->db_connect();
		if($id==1)
		{
			$sql="select count(id) as cont from chat where id_usuario2=1 and lido_usuario2=0 group by id_usuario";
		}
		else
		{
			$sql="select count(id) as cont from chat where id_usuario=".$id." and lido_usuario=0";
		}
		$this->resultado=mysqli_query($connection,$sql);	
	}

	public function eventosCongregacaoAdmin($idcongregcao)
	{
		$connection = $this->db_connect();
		$sql="SELECT * FROM agenda WHERE id_congregacao='$idcongregcao' ORDER BY data,horario";
		//echo $sql;
		$this->resultado=mysqli_query($connection,$sql);
	}


	public function getconsulta()
	{
		return $this->resultado;
	}
}
?>

	

	

		