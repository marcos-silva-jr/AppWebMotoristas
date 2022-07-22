<?php
	
	/* ============ TRATAMENTO DA SESSÃO DO USUÁRIO PARA AUTENTICAÇÃO, SE TIVER PERMISSÃO LIBERA				
	==================================================================
	*/
	
	// ================== PUXA ARQUIVO DO SQL PARA COMUNICAÇÃO 
	
	require_once "conexaoSQL/Conexao.php";
	
	session_start(); // --------------------- INICIA A SESSÃO DO USUARIO
	$operador = $_SESSION['login']; //------ CRIA VARIAVEL COM NOME DE USUARIO 	

	if($operador==""){
		echo "<script type='text/javascript'>alert('ATENCAO :: TEMPO EXPIRADO, LOGAR NOVAMENTE'); window.location='index.php';</script>";
	}
	
	try {
		$Conexao    = Conexao::getConnection(); //SELECT PARA CONTAR QUANTOS USUÁRIOS TEM NO TOTAL
				
		$usuarios2   = $query2->fetchAll();
	
			foreach($usuarios2 as $busca_usuario2) {							
				if($busca_usuario2['TIPO_USUARIO'] == 2 || $busca_usuario2['TIPO_USUARIO'] == 3){
				} else {echo "<script type='text/javascript'>alert('ATENCAO :: ACESSO NÃO PERMITIDO'); window.location='index.php';</script>"; }
			} 
		}	catch (Exception $e){	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<link rel="shortcut icon" type="imagex/png" href="logoOficialIco.png"> <!--ICONE-->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" charset="UTF-8"/>
		<title>Baixa Mobile [Módulo Motorista] &copy; Atuex Express</title>
			<script type="text/javascript" src="javascript/jquery.maskedinput-1.1.4.pack.js"></script>
			<script type="text/javascript" src="javascript/jquery-1.2.6.pack.js"></script>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
			
		</head>

		<!-----------CHAMANDO O ARQUIVO CSS E JAVA SCRIPT-->
		<link rel="stylesheet" type="text/css" href="css/estilo.css" media="screen" />
		<link rel="stylesheet" type="text/javascript" href="javascript/javascript.js" media="screen" />


	<body style="background-color: #EFEFED;">
	
		<div id="cabecalho" style="z-index:1000;background-color: white;   display: flex;  align-items: center; box-shadow: 0 3px 0 rgba(0, 0, 0, .3), 
			0 2px 7px rgba(0, 0, 0, 0.2);    color: white;  height:5.4rem; top:0px; left:0px; 
			margin: 0 auto;     position:fixed;      width: 100%; "> 
		 
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><img src="inicial.png" title="Página Inicial" style="width: 190px; height: 72px; text-align: center;" /></a>
			
			<label class="botaoSair" style=" display: flex;  align-items: center; text-align: center; position:fixed;  width: 26rem;  color: white; font-size: 1.6rem;
				height: 3.4rem;   background-color: gray; border-radius:5px; text-align:center; margin-left:28%;">&nbsp;&nbsp;MÓDULO MOTORISTAS - V5.2.22</label>
		
		
			<a href="index.php"  style="text-decoration: none;">
				<label  style=" display: flex;  align-items: center; text-align: center; position:fixed; right:2.8rem; width: 9rem;  cursor:pointer; color: white; font-size: 1.6rem;
				height: 3.4rem;  margin-top:-1.7rem; background-color: #f24a4a; border-radius:5px; text-align:center; z-index:1000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SAIR</label>
			</a>
				
	</div>
	
	
	<div id="baixa-container" style="width:90%; margin-top:7rem;">	
			
		
	
	<center>
		
		<!-- BOTÃO =========== BAIXAR CT-e -->			
		<br><br><br><br>
		<a href="mobile-enviarDados-ModuloMotorista3.php"  style="text-decoration: none;">
			<label  style=" display: flex;  align-items: center; text-align: center; width: 90%;  cursor:pointer; color: white; font-size: 3.6rem;
			height: 8rem;  border-radius: 50px; background-color: gray; border-radius:30px; margin-left:2.5rem;">
			&nbsp;&nbsp;
			<img src="documento.png" style="width: 80px; height: 80px; " />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BAIXAR CT-e</label>
		</a>
		
		<!-- BOTÃO =========== BAIXAR CT-e -->			
		<br><br><br><br>
		<a href="#"  style="text-decoration: none;">
			<label  style=" display: flex;  align-items: center; text-align: center; width: 90%;  cursor:pointer; color: white; font-size: 3.6rem;
			height: 8rem;  border-radius: 50px; background-color: gray; border-radius:30px; margin-left:2.5rem;">
			&nbsp;&nbsp;
			<img src="caminhao.png" style="width: 80px; height: 80px; " />
			&nbsp;BAIXAR MANIFESTO</label>
		</a>
			
		<br><br><br><br><br><br>
			<div id="rodape">
				<label style="background-color: #042f66;    text-align: center;    font-weight:bold;    width:100%;
				color:white;     position:fixed;     bottom:0px;    left:0px;     font-size: 2rem;    height: 7rem;">
				<br>Atuex Express &copy; Todos os direitos reservados - <?php echo date('Y'); ?></label>
			</div>
		</font>
		</center>
	</body>
</html>
