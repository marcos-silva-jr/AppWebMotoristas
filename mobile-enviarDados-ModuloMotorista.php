<!-- =======================================================-->
<!--------------------- COMEÇANDO CÓDIGO PHP ----------------->
<!-- =======================================================-->

<?php

	// ================== PUXA ARQUIVO DO SQL PARA COMUNICAÇÃO 
	
	require_once "conexaoSQL/Conexao.php";

	date_default_timezone_set('America/Sao_Paulo');
	$hoje = date('m/d/Y'); //PEGA DATA ATUAL PARA PREENCHER AUTOMATICO NA BAIXA
	$hojeconsulta = date('Y.m.d'); //PEGA DATA ATUAL PARA PREENCHER AUTOMATICO NA BAIXA
	$hora = date('H:i:s'); //PEGA HORA ATUAL PARA PREENCHER AUTOMATICO NA BAIXA

	// ==================== ********************* ==================
	// ================== MANIPULANDO VARIAVEL CTE =================
	// ==================== ********************* ==================

	$pegaCTE=substr($_SERVER['REQUEST_URI'], -44); 
	
	// ==================== ********************* ==================
	// ================== SALVA O NUMERO DOS CTES =================
	// ==================== ********************* ==================
	
	$numeroCTE = substr($pegaCTE, -7, -1);
	
	// ==================== ********************* ==================
	// ================== SALVA O NOME DAS FILIAIS =================
	// ==================== ********************* ==================

	$filial = substr($pegaCTE, 6, 14);

	if($filial=="00026360000162"){			$filial="ATU"; 	}
	else if ($filial=="04100709000110") { 	$filial="SER";	}
	else if ($filial=="00026360000596") { 	$filial="ATS";	}
	else if ($filial=="24905397000129") { 	$filial="ATX";	}
	else if ($filial=="00026360000405") {	$filial="ATR";	}
	else if ($filial=="01152739000181") {	$filial="MTZ";	}
	else{ 	$filial = ".";	}

	/* ============ TRATAMENTO DA SESSÃO DO USUÁRIO PARA AUTENTICAÇÃO, SE TIVER PERMISSÃO LIBERA				
	==================================================================
	*/
	
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
			<script language="JavaScript">
			$(document).keydown(function(event){
   			 if(event.keyCode==123){
        		return false;
    		 }
   			 else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
             	return false;
    		 }
			 });

			$(document).on("contextmenu",function(e){        
   			e.preventDefault();
			});
    		function protegercodigo() {
    			if (event.button==2||event.button==3){
        		alert('ATENÇÃO :: AÇÃO BLOQUEADA');}
    		}
    		document.onmousedown=protegercodigo
		</script>
		</head>

		<!-----------CHAMANDO O ARQUIVO CSS E JAVA SCRIPT-->
		<link rel="stylesheet" type="text/css" href="css/estilo.css" media="screen" />
		<link rel="stylesheet" type="text/javascript" href="javascript/javascript.js" media="screen" />

		<script type="text/javascript">
		// ==================== ********************* ==================
		// ==================== TEMPORIZADOR DE LOGIN ==================
		// ==================== ********************* ==================
		var startTime;
		
		//===================== FUNÇÃO PARA VALIDAR AS ENTREGAS AFIM DE MOSTRAR CAMPO DE OCORRENCIA===================
		function mostra() {				
			if ($('#option-2').is(':checked')) {
				$('#ocorrencia').show();
				$('#botao-ocorren').show();
				let alterar = document.getElementById('unico'); //PEGA O ID DO NAO ENTREGUE
				let alterar2 = document.getElementById('naoentregue'); //PEGA O SPAN NAO ENTREGUE
				alterar2.style.color = 'black'; //ALTERA A COR DO TEXTO "NAO ENTREGUE"
				alterar.style.background = 'white'; //ALTERA O FUNDO PARA AZUL
			} 
			else if ($('#option-1').is(':checked')) {
				$('#ocorrencia').hide();
				let alterar = document.getElementById('unico'); //PEGA O ID DO NAO ENTREGUE
				let alterar2 = document.getElementById('naoentregue'); //PEGA O SPAN NAO ENTREGUE
				alterar2.style.color = 'black'; //ALTERA A COR DO TEXTO "NAO ENTREGUE"
				alterar.style.background = 'white'; //ALTERA O FUNDO PARA AZUL
			}
		}
		function mostraOcorren() {	
					$('#ocorren').show();
					$('#ocorrencia').hide();
		}
		function escondeOcorren() {	
					$('#botao-ocorren').hide();	
    				$('#ocorren').hide();
					$('#ocorrencia').hide();
		}
		function escondebotao() {	
					$('#botao-ocorren').hide();	
    				$('#ocorren').hide();				
		}
		function clicarCTE() {		
					let alterar = document.getElementById('coletaL'); //PEGA O ID DO NAO ENTREGUE
					let alterar2 = document.getElementById('coleta'); //PEGA O SPAN NAO ENTREGUE
					alterar2.style.color = 'black'; //ALTERA A COR DO TEXTO "NAO ENTREGUE"
					alterar.style.background = 'white'; //ALTERA O FUNDO PARA AZUL				   
					$('#filial').hide();
					$('#numeroDoc').hide();  
					$('#scanner').show();  				
		}
		function clicarMinuta() {		
					let alterar = document.getElementById('coletaL'); //PEGA O ID DO NAO ENTREGUE
					let alterar2 = document.getElementById('coleta'); //PEGA O SPAN NAO ENTREGUE
					alterar2.style.color = 'black'; //ALTERA A COR DO TEXTO "NAO ENTREGUE"
					alterar.style.background = 'white'; //ALTERA O FUNDO PARA AZUL				   
					$('#scanner').hide();  
					$('#filial').show();  					
					$('#numeroDoc').show(); 			
		}
		function clicarColeta() {	
					let alterar = document.getElementById('coletaL'); //PEGA O ID DO NAO ENTREGUE
					let alterar2 = document.getElementById('coleta'); //PEGA O SPAN NAO ENTREGUE
					alterar2.style.color = 'white'; //ALTERA A COR DO TEXTO "NAO ENTREGUE"
					alterar.style.background = '#072eac'; //ALTERA O FUNDO PARA AZUL
					$('#scanner').hide();  
					$('#filial').show();  					
					$('#numeroDoc').show();  
		}
		// =============== Codigo para contar os ENTERS DIGITADOS
		var tarea = document.querySelector('#ta')
		var input = document.querySelector('#rt')
		function update() {
			var res = (tarea.value.match(/\n/g)) ? tarea.value.match(/\n/g).length : 0;
			input.value = res;
		} tarea.addEventListener('input', update)  

		// ================= SOMENTE NUMEROS NA CHAVE CT-e e no CPF
		jQuery(function($) {
			$(document).on('keypress', 'input.somente-numero', function(e) {
			var square = document.getElementById("sonumero");
			var key = (window.event)?event.keyCode:e.which;
			if((key > 47 && key < 58)) {
				sonumero.style.backgroundColor = "#ffffff"; //cor fundo
			return true; 	
			} else {
				sonumero.style.backgroundColor = "#ffffff"; //cor fundo
 		    return (key == 8 || key == 0)?true:false;	
			}
			});
		}); 
		function limitText(limitField, limitNum) { //FUNÇÃO PARA TRAVAR OS 11 CARACTERES DO CPF E OS 44 DO CONHECIMENTO
			if (limitField.value.length > limitNum) {
				limitField.value = limitField.value.substring(0, limitNum);
			}
		}		
		function ApenasLetras(e, t) {
			try {
				if (window.event) {
					var charCode = window.event.keyCode;
				} else if (e) {
					var charCode = e.which;
				} else {
					return true;
				}
				if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
					return true;
				else
					return false;
			} catch (err) {
			alert(err.Description);
			}
		}
		</script>

	<!-- ====================== ********************* ================== -->
	<!-- =================== COMEÇANDO CORPO DA PÁGINA ================= -->
	<!-- ====================== ********************* ================== -->

	<body style="background-color: #EFEFED;">
	
		<div id="cabecalho" style="z-index:1000;background-color: white;   display: flex;  align-items: center; box-shadow: 0 3px 0 rgba(0, 0, 0, .3), 
	0 2px 7px rgba(0, 0, 0, 0.2);    color: white;  height:5.4rem; top:0px; left:0px; 
	margin: 0 auto;     position:fixed;      width: 100%; "> 
		 
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><img src="inicial.png" title="Página Inicial" style="width: 190px; height: 72px; text-align: center;" /></a>
			
			<label class="botaoSair" style=" display: flex;  align-items: center; text-align: center; position:fixed;  width: 26rem;  color: white; font-size: 1.6rem;
				height: 3.4rem;   background-color: gray; border-radius:5px; text-align:center; margin-left:28%;">&nbsp;&nbsp;MÓDULO MOTORISTAS - V1.3.22</label>
		
		
			<a href="index.php"  style="text-decoration: none;">
				<label  style=" display: flex;  align-items: center; text-align: center; position:fixed; right:2.8rem; width: 9rem;  cursor:pointer; color: white; font-size: 1.6rem;
				height: 3.4rem;  margin-top:-1.7rem; background-color: #f24a4a; border-radius:5px; text-align:center; z-index:1000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SAIR</label>
			</a>
				
	</div>
	
	
	<div id="baixa-container" style="width:90%; margin-top:7rem;">	
			
	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== QUANTIDADE DE BAIXAS HOJE ============================= --!-->		
	<!--!===================================== ************************************** ================== --!-->
	<div style="margin-top:-1.3rem;">	
	<?php
		try {
			$Conexao = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
			
			$consulta   = $queryConsultaBaixas->fetchAll();		//EXECUTA O SELECT
			
			//============================= ********************** ======================
			//============================= ENTRA NO FOR PARA PROCURAR ==================
			//============================= ********************** ======================

			foreach($consulta as $busca_usuario) { 
					$contadorBaix = $busca_usuario['CONSULTAR'];			
			}							
		}
		catch (Exception $e){	 }				
	?>
	</div>
	
	<center>
	<img src="foto-user-1.png" style="width: 200px; height: 200px; " />
		
	<!-- =========================================== ********************************** ==============================
	====================================================== NOME DO PARCEIRO ============================================
	=========================================== ********************************======================================-->
	
	<label style="margin-top:-1.2rem; text-align: center;  color: white; font-size: 1.8rem;	 height: 4rem;   background-color: gray;
	border-radius:5px; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2);  width: 64%; display: flex;  align-items: center; z-index:1000;">
	<?php echo "<label style='text-align:center; color:white; font-size:1.8rem;'>".$operador."</label>"; ?></label>
	
	<!-- =========================================== ********************************** ==============================
	====================================================== CONTADOR DE BAIXAS ============================================
	=========================================== ********************************======================================-->
	
	
	<label style=" text-align: center;  color: white; font-size: 1.8rem;	 height: 4rem;   background-color: #c4c4c4;
	border-radius:5px; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2);  width: 50%; display: flex;  align-items: center; z-index:1000;">
	<?php echo "<label style='text-align:center; color:white; font-size:1.8rem;'>BAIXAS REALIZADAS HOJE: ".$contadorBaix."</label>"; ?></label>
	</center>
																																	  	
	<!--!===================================== ************************************** ================== --!-->	
	<!--!========================================= SEÇÃO ESCOLHER OPÇÃO INICIAL ======================== --!-->		
	<!--!===================================== ************************************** ================== --!-->

	<form action="verificaDadosRecebidos-ModuloMotorista.php" method="post" enctype="multipart/form-data"> 
			
			<div name="opcaoInicial" id="opcaoInicial" class="wrapper" style="font-size: 2.5rem;color: white; text-align:center; border-radius:50px; height:6rem; width:90%;"> 
			<input style="display:none;" value="3" type="radio" name="tipo_doc" id="option-3" checked>
			<input style="display:none;" value="4" type="radio" name="tipo_doc" id="option-4" >	
			<input style="display:none;" value="5" type="radio" name="tipo_doc" id="option-5" >	
	
	<!--!==================================== ************************************** ================== --!-->	
	<!--!========================================== ----------- CTE ---------- ======================== --!-->		
	<!--!==================================== ************************************** ================== --!-->
		
		<label for="option-3" class="option option-3" onclick="clicarCTE()"> 
				<div class="dot" ></div>
					<span style='font-size:2rem;'>CT-E</span>
			</label>
			<label for="option-4" class="option option-4" onclick="clicarMinuta()">
				<div class="dot"></div>
					<span style='font-size:2rem;'>MINUTA</span>
			</label>
			<label for="option-5" class="option option-5" onclick="clicarColeta()" id="coletaL">
				<div class="dot"></div>
					<span id="coleta" style='font-size:2rem;'>COLETA</span>
			</label>								
		</div>

	<!--!==================================== ************************************** =================== --!-->	
	<!--!========================================== CAMPO DIGITAR FILIAL =============================== --!-->		
	<!--!===================================== ************************************** ================== --!-->
	<center>
		<div id="filial" style="display: none; font-size: 2.5rem;">
			<label for="siglafil" style="font-size: 2.5rem; padding: 15px; text-align:center">Sigla da Filial</label>
			<input  type="text"  required onKeyDown="limitText(this,3);" onKeyUp="limitText(this,3);" value=<?php echo $filial ?> name="filial" style="font-size: 2.5rem; width: 500px; height: 50px; text-align: center; border-radius: 50px;text-transform: uppercase"> 
		</div>

	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== CAMPO DIGITAR NUMERO DOC ================================== --!-->		
	<!--!===================================== ************************************** ================== --!-->

		<div id="numeroDoc" style="display: none; font-size: 2.5rem;">
			<label for="siglanr" style="font-size: 2.5rem;padding: 15px; text-align:center">Numero Documento</label>
			<input type="number" onKeyDown="limitText(this,7);" onKeyUp="limitText(this,7);" value=<?php echo $numeroCTE ?> class="ao_cpf" name="numerodoc" style="font-size: 2.5rem; width: 500px; height: 50px; text-align: center; border-radius: 50px; border-width:1px;"> 
		</div>	
	</center>	
		
		<input type="hidden" id="sonumero" value=<?php if($pegaCTE=="/mobile-enviarDados-ModuloMotorista.php") //VALOR VAI VIR ENDEREÇO DA PAGINA, ENTÃO..
					echo "CHAVE-ELETRONICA"; //ALTERO PARA CHAVE-ELETRONICA
				else //PARA DEPOIS QUANDO BUSCAR O NUMERO DA CTE SCANEADA JOGA AQUI
					echo $pegaCTE ?> name="chavecte" class="somente-numero" title="Chave CT-e" onKeyDown="limitText(this,44);" onKeyUp="limitText(this,44);" placeholder="CHAVE CT-e" style="font-size: 2.5rem; width: 900px; height: 100px; text-align: center; border-radius: 50px 10px; border-color:#FFD40D; border-width:10px;" value="">

	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== CAMPO NOME RECEBEDOR ================================= --!-->		
	<!--!===================================== ************************************** ================== --!-->
	<center>
		
	<br><input type="text" placeholder="NOME RECEBEDOR" required onkeypress="return ApenasLetras(event,this);" title="Nome Recebedor" name="nomeRecebedor" style="font-size: 2.5rem; width: 90%; height: 50px; text-align: center; border-radius: 50px 10px; border-color:#1b223c; border-width:1px;">
				
	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== CAMPO CPF RECEBEDOR ================================== --!-->		
	<!--!===================================== ************************************** ================== --!-->
				
	<br><input type="number"  onKeyDown="limitText(this,11);" onKeyUp="limitText(this,11);" placeholder="CPF/RG RECEBEDOR" required id="validaCPF()" title="CPF Recebedor" class="ao_cpf" name="cpfRecebedor" style="font-size: 2.5rem; width: 90%; height: 50px; text-align: center; border-radius: 50px 10px; border-color:#1b223c; border-width:1px;" > 
				
	</center>
	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== SEÇÃO ENTREGA - RADIO BUTTON ====================== --!-->		
	<!--!===================================== ************************************** ================== --!-->

		<div name="entrega" id="entrega" class="wrapper" style="font-size: 2.5rem; text-align:center; border-color:#FFD40D; border-radius: 50px; height:6rem; width:90%;"> 
			<input style="display:none;" value="0" type="radio" name="entrega" id="option-1" checked>
			<input style="display:none;" value="1" type="radio" name="entrega" id="option-2" >					
			<label for="option-1" class="option option-1">
				<div class="dot" ></div>
					<span onclick="escondeOcorren()" style="font-size: 1.8rem;">ENTREGA NORMAL</span>
			</label>
			<label for="option-2" class="option option-2" >
				<div class="dot"></div>
					<span onclick="mostra()" style="font-size: 1.8rem;">OCORRÊNCIA</span>
			</label>					
		</div><br><br>

	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== LISTA DE OCORRÊNCIAS PARA LISTA ====================== --!-->		
	<!--!===	================================== ************************************** ================== --!-->
				
		<div id="ocorrencia" style="text-align:left; display:none;">
		<center>
			<select onclick="escondebotao()" name="selecionaOcorrencia" style="
				width: 780px; height: 100px;
				background-color:#FFF; 
				border: 1px solid #CCCCCE;
				border-radius: 50px; border-color:#CCCCCE; 
				box-shadow: 0 3px 0 rgba(0, 0, 0, .3),
							0 2px 7px rgba(0, 0, 0, 0.2);
				color: #CCCCCCE; 
				display: block; width:90%; height: 60px;				
				font-size: 2rem;
				font-weight: bold;
				line-height: 25px;
				text-align: center;
				text-decoration: none;
				text-transform: uppercase;
				text-color:#FFF;
				padding: 5px 15px;
				position: relative;">
				<option name="pega_ocorrencia" value="1" selected>Selecione a ocorrência</option>
				<option name="pega_ocorrencia" value="547|CLIENTE AUSENTE" >CLIENTE AUSENTE</option>
				<option name="pega_ocorrencia" value="548|CLIENTE MUDOU DE ENDERECO" >CLIENTE MUDOU DE ENDEREÇO</option>
				<option name="pega_ocorrencia" value="539|ENDEREÇO NAO LOCALIZADO" >ENDEREÇO NÃO LOCALIZADO</option>
				<option name="pega_ocorrencia" value="4|RECUSA POR PEDIDO DE COMPRA CANCELADO" >RECUSA POR PEDIDO DE COMPRA CANCELADO</option>
				<option name="pega_ocorrencia" value="13|TRANSPORTADORA NAO ATENDE A CIDADE DO CLIENTE DESTINO">TRANSPORTADORA NÃO ATENDE A CIDADE DO CLIENTE DESTINO</option>
				<option name="pega_ocorrencia" value="21|ESTABELECIMENTO FECHADO" >ESTABELECIMENTO FECHADO</option>
				<option name="pega_ocorrencia" value="26|NOTA FISCAL RETIDA PELA FISCALIZACAO" >NOTA FISCAL RETIDA PELA FISCALIZAÇÃO</option>
				<option name="pega_ocorrencia" value="34|CLIENTE FECHADO PARA BALANCO" >CLIENTE FECHADO PARA BALANÇO</option>
				<option name="pega_ocorrencia" value="43|FERIADO LOCAL/NACIONAL" >FERIADO LOCAL/NACIONAL</option>
				<option name="pega_ocorrencia" value="44|EXCESSO DE VEICULOS" >EXCESSO DE VEICULOS</option>
				<option name="pega_ocorrencia" value="45|CLIENTE DESTINO ENCERROU ATIVIDADES" >CLIENTE DESTINO ENCERROU ATIVIDADES</option>
				<option name="pega_ocorrencia" value="46|RESPONSAVEL DE RECEBIMENTO AUSENTE" >RESPONSAVEL DE RECEBIMENTO AUSENTE</option>
				<option name="pega_ocorrencia" value="47|CLIENTE DESTINO EM GREVE" >CLIENTE DESTINO EM GREVE</option>
				<option name="pega_ocorrencia" value="58|QUEBRA DO VEICULO DE ENTREGA" >QUEBRA DO VEICULO DE ENTREGA</option>
				<option name="pega_ocorrencia" value="60|ENDERECO DE ENTREGA INCORRETO" >ENDERECO DE ENTREGA INCORRETO</option>
				<option name="pega_ocorrencia" value="87|FERIAS COLETIVAS" >FERIAS COLETIVAS</option>
				<option name="pega_ocorrencia" value="100|NAO DEU TEMPO DE ENTREGAR" >NAO DEU TEMPO DE ENTREGAR</option>
				<option name="pega_ocorrencia" value="545|PRODUTO INDISPONIVEL PARA COLETA" >PRODUTO INDISPONÍVEL PARA COLETA</option>						
			</select>
			<br>
		</center>
		</div>
				
	<!--DESCREVER OCORREN--><label onclick="mostraOcorren()" id="botao-ocorren" class="botao01" style="display: none; font-size: 2.5rem; width:90%; height: 80px; text-align: center; border-radius: 30px; border-color:#FFD40D; border-width:10px; background-color: #042f66;"><br>Descreva a ocorrência</label><br>
					<div id="ocorren" style="display: none;">
	<!--OCORREN-->	<textarea onKeyDown="limitText(this,80);" onKeyUp="limitText(this,80);" placeholder="DESCREVA A OCORRÊNCIA" title="Motivo da Ocorrência" name="motivoOcorrencia" style="font-size: 2rem; width: 90%; height: 100px; text-align: center; border-radius: 50px 10px; border-color:#FFD40D; border-width:10px;"></textarea><br><br>				
					</div><br>
	<!--ANEXO-->	<label for="arquivo" class="botao01" style="font-size: 2.5rem; width: 90%; height: 80px; text-align: center; border-radius: 30px; border-color:#FFD40D; border-width:10px; background-color: #042f66;"><br>Anexar Comprovante</label>
	<!--ANEXO-->	<input type="file" value="Enviar anexo" placeholder="ANEXO" id="arquivo" name="arquivo" required name="arquivo" style="display: none; font-size: 50px; width: 880px; height: 100px; text-align: center; border-radius: 50px; border-color:#FFD40D; border-width:10px;"/>

	<!-- BOTÃO ENVIAR--><br><br><input type="submit" value="Finalizar entrega" class="botao01" style="font-size: 3rem;  background-color: #042f66;	 width:90%; height: 120px; border-radius: 30px;">  
				</form>	
	<!-- BOTÃO =========== suporte técnico -->			
				<br><br><br><br><a href="tel:+5511976527871"  style="text-decoration: none;">
					<label  style=" display: flex;  align-items: center; text-align: center; width: 90%;  cursor:pointer; color: white; font-size: 2.2rem;
					height: 6.4rem;  border-radius: 50px; background-color: #f24a4a; border-radius:30px; margin-left:2.5rem;">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="telefone.png" style="width: 50px; height: 50px; " />
					&nbsp;&nbsp;&nbsp;&nbsp;LIGAR PARA SUPORTE TÉCNICO</label>
				</a><br>
				<div id="rodape">
					<label style="background-color: #042f66;    text-align: center;    font-weight:bold;    width:100%;
					color:white;     position:fixed;     bottom:0px;    left:0px;     font-size: 2rem;    height: 7rem;">
					<br>Atuex Express &copy; Todos os direitos reservados - <?php echo date('Y'); ?></label>
				</div>
			</font>
		</center>
	</body>
</html>
