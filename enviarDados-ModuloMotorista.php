<!-- =======================================================-->
<!--------------------- COMEÇANDO CÓDIGO PHP ----------------->
<!-- =======================================================-->

<?php

	/* CONFERENCIA DE DISPOSITIVO SE É MOBILE OU DESKTOP */
	
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$symbian = strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
	$windowsphone = strpos($_SERVER['HTTP_USER_AGENT'],"Windows Phone");

	if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian || $windowsphone == true) {
	   $dispositivo = "mobile"; //SE FOR MOBILE REDIRECIONA PARA A OUTRA PAGIN
	   header('Location: mobile-enviarDados-ModuloMotorista.php');
	}

	else { $dispositivo = "computador";} //SE NÃO MANTEM A VERSÃO DESKTOP :)	
	
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

	session_start(); // --------------------- INICIA A SESSÃO DO USUARIO
	$operador =  $_SESSION['login']; //------ CRIA VARIAVEL COM NOME DE USUARIO 
	
	try {
		$Conexao    = Conexao::getConnection(); //SELECT PARA CONTAR QUANTOS USUÁRIOS TEM NO TOTAL
		
		$usuarios2   = $query2->fetchAll();
	
			foreach($usuarios2 as $busca_usuario2) {							
				if($busca_usuario2['TIPO_USUARIO'] != 2){
					
					echo "<script type='text/javascript'>alert('ATENCAO :: ACESSO NÃO PERMITIDO'); window.location='index.php';</script>";
					
				} else { }
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
	<div id="baixa-container" style="width:90%;">	
			
	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== QUANTIDADE DE BAIXAS HOJE ====================== --!-->		
	<!--!===================================== ************************************** ================== --!-->
	<div style="margin-top:-1.3rem;">	
	<?php
		echo "<table style='font-size: 0.8rem; width: 8rem; height: 3rem; border-radius:0px 0px 10px 10px; background-color: #08558b; box-shadow: 5px 5px 30px rgba(0,0,0,0.2);'>";
		echo "<tr>";
		echo "<th style='font-weight:normal;color: white; text-align:left;'>BAIXAS HOJE: </th>";
		
		try {
			$Conexao = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
		
			$consulta   = $queryConsultaBaixas->fetchAll();		//EXECUTA O SELECT
			
			//============================= ********************** ======================
			//============================= ENTRA NO FOR PARA PROCURAR ==================
			//============================= ********************** ======================

			foreach($consulta as $busca_usuario) { 
				echo "<td style='color: white;font-size: 1.5rem; '>".$busca_usuario['CONSULTAR']."</td>"; //MOSTRA :)
				echo "</tr>";
			}							
		}
		catch (Exception $e){	 }			
		echo "</table>"; //FECHA TABELA
	?>
	</div>
	
	<img src="inicial.png" title="Logo" style="width: 140px; height: 60px; margin-top:-2.5rem;" />  <!--EXIBE IMAGEM LOGO-->
																																	  	
	<!--!===================================== ************************************** ================== --!-->	
	<!--!========================================= SEÇÃO ESCOLHER OPÇÃO INICIAL ======================== --!-->		
	<!--!===================================== ************************************** ================== --!-->

	<form action="verificaDadosRecebidos-ModuloMotorista.php" method="post" enctype="multipart/form-data"> 
			<label for="tipodoc" style="font-size: 1.2rem;color: black;padding: 15px; text-align:center;">Tipo de Documento</label>
			<div name="opcaoInicial" id="opcaoInicial" class="wrapper" style="font-size: 1.4rem;color: white; text-align:center; border-radius:50px; height:5rem; width:90%;"> 
			<input style="display:none;" value="3" type="radio" name="tipo_doc" id="option-3" checked>
			<input style="display:none;" value="4" type="radio" name="tipo_doc" id="option-4" >	
			<input style="display:none;" value="5" type="radio" name="tipo_doc" id="option-5" >	
	
	<!--!==================================== ************************************** ================== --!-->	
	<!--!========================================== ----------- CTE ---------- ======================== --!-->		
	<!--!==================================== ************************************** ================== --!-->
		
		<label for="option-3" class="option option-3" onclick="clicarCTE()"> 
				<div class="dot" ></div>
					<span style='font-size:1rem;'>CT-E</span>
			</label>
			<label for="option-4" class="option option-4" onclick="clicarMinuta()">
				<div class="dot"></div>
					<span style='font-size:1rem;'>MINUTA</span>
			</label>
			<label for="option-5" class="option option-5" onclick="clicarColeta()" id="coletaL">
				<div class="dot"></div>
					<span id="coleta" style='font-size:1rem;'>COLETA</span>
			</label>								
		</div>

	<!--!==================================== ************************************** =================== --!-->	
	<!--!========================================== CAMPO DIGITAR FILIAL =============================== --!-->		
	<!--!===================================== ************************************** ================== --!-->
	<center>
		<div id="filial" style="display: none; font-size: 2rem;">
			<label for="siglafil" style="font-size: 1rem; padding: 15px; text-align:center">Sigla da Filial</label>
			<input  type="text"  required onKeyDown="limitText(this,3);" onKeyUp="limitText(this,3);" value=<?php echo $filial ?> name="filial" style="font-size: 1rem; width: 500px; height: 50px; text-align: center; border-radius: 50px;text-transform: uppercase"> 
		</div>

	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== CAMPO DIGITAR NUMERO DOC ================================== --!-->		
	<!--!===================================== ************************************** ================== --!-->

		<div id="numeroDoc" style="display: none; font-size: 20px;">
			<label for="siglanr" style="font-size: 1rem;padding: 15px; text-align:center">Numero Documento</label>
			<input type="number" onKeyDown="limitText(this,7);" onKeyUp="limitText(this,7);" value=<?php echo $numeroCTE ?> class="ao_cpf" name="numerodoc" style="font-size: 1rem; width: 500px; height: 50px; text-align: center; border-radius: 50px; border-width:1px;"> 
		</div>	
	</center>	
	
	<!--!===================================== ************************************** =================== --!-->	
	<!--!============================================== CAMPO CHAVE CTE  ================================ --!-->		
	<!--!===================================== ************************************** =================== --!-->
	
	<!--!===================================== ************************************** =================== --!-->	
	<!--!===================================== ************************************** =================== --!-->	
	<!--!===================================== ------- ATENÇÃO ---------------------- =================== --!-->	
	<!--!========================= TROCAR ESTE ENDEREÇO PARA REALIZAR TESTES INTERNOS =================== --!-->
	<!--!===== ENDEREÇO PADRÃO PARA FUNCIONAR: app.aosafe.com.br:8082/App/enviarDados-ModuloMotorista.php --!-->	
	<!--!===================================== ************************************** =================== --!-->	
	<!--!===================================== ************************************** =================== --!-->
		
		<input type="hidden" id="sonumero" value=<?php if($pegaCTE=="/enviarDados-ModuloMotorista.php") //VALOR VAI VIR ENDEREÇO DA PAGINA, ENTÃO..
					echo "CHAVE-ELETRONICA"; //ALTERO PARA CHAVE-ELETRONICA
				else //PARA DEPOIS QUANDO BUSCAR O NUMERO DA CTE SCANEADA JOGA AQUI
					echo $pegaCTE ?> name="chavecte" class="somente-numero" title="Chave CT-e" onKeyDown="limitText(this,44);" onKeyUp="limitText(this,44);" placeholder="CHAVE CT-e" style="font-size: 50px; width: 900px; height: 100px; text-align: center; border-radius: 50px 10px; border-color:#FFD40D; border-width:10px;" value="">

	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== CAMPO NOME RECEBEDOR ================================= --!-->		
	<!--!===================================== ************************************** ================== --!-->
	<center>
		<table>
			<tr>
				<td>
					<input type="text" placeholder="NOME RECEBEDOR" required onkeypress="return ApenasLetras(event,this);" title="Nome Recebedor" name="nomeRecebedor" style="font-size: 1.5rem; width: 300px; height: 50px; text-align: center; border-radius: 50px 10px; border-color:#1b223c; border-width:1px;">
				</td>
	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== CAMPO CPF RECEBEDOR ================================== --!-->		
	<!--!===================================== ************************************** ================== --!-->
				<td>
					<input type="number"  onKeyDown="limitText(this,11);" onKeyUp="limitText(this,11);" placeholder="CPF/RG RECEBEDOR" required id="validaCPF()" title="CPF Recebedor" class="ao_cpf" name="cpfRecebedor" style="font-size: 1.5rem; width: 300px; height: 50px; text-align: center; border-radius: 50px 10px; border-color:#1b223c; border-width:1px;" > 
				</td>
			</tr>
		</table>
	</center>
	<!--!===================================== ************************************** =================== --!-->	
	<!--!======================================== SEÇÃO ENTREGA - RADIO BUTTON ====================== --!-->		
	<!--!===================================== ************************************** ================== --!-->

		<div name="entrega" id="entrega" class="wrapper" style="font-size: 1.4rem; text-align:center; border-color:#FFD40D; border-radius: 50px; height:5rem; width:90%;"> 
			<input style="display:none;" value="0" type="radio" name="entrega" id="option-1" checked>
			<input style="display:none;" value="1" type="radio" name="entrega" id="option-2" >					
			<label for="option-1" class="option option-1">
				<div class="dot" ></div>
					<span onclick="escondeOcorren()" style="font-size: 1rem;">ENTREGA NORMAL</span>
			</label>
			<label for="option-2" class="option option-2" >
				<div class="dot"></div>
					<span onclick="mostra()" style="font-size: 1rem;">OCORRÊNCIA</span>
			</label>					
		</div><br><br>

		<!--!===================================== ************************************** =================== --!-->	
		<!--!======================================== LISTA DE OCORRÊNCIAS PARA LISTA ====================== --!-->		
		<!--!===	================================== ************************************** ================== --!-->
				
		<div id="ocorrencia" style="text-align:left; display:none;">
		<center>
			<select onclick="escondebotao()" name="selecionaOcorrencia" style="
				width: 780px; height: 80px;
				background-color:#FFF; 
				border: 1px solid #CCCCCE;
				border-radius: 50px; border-color:#CCCCCE; 
				box-shadow: 0 3px 0 rgba(0, 0, 0, .3),
							0 2px 7px rgba(0, 0, 0, 0.2);
				color: #CCCCCCE; 
				display: block; width:90%; height: 40px;				
				font-size: 1rem;
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
				
	<!--DESCREVER OCORREN--><label onclick="mostraOcorren()" id="botao-ocorren" class="botao01" style="display: none; font-size: 1.2rem; width:90%; height: 40px; text-align: center; border-radius: 50px; border-color:#FFD40D; border-width:10px; background-color: #042f66;">Descreva a ocorrência</label><br>
					<div id="ocorren" style="display: none;">
	<!--OCORREN-->	<textarea onKeyDown="limitText(this,80);" onKeyUp="limitText(this,80);" placeholder="DESCREVA A OCORRÊNCIA" title="Motivo da Ocorrência" name="motivoOcorrencia" style="font-size: 1.3rem; width: 90%; height: 100px; text-align: center; border-radius: 50px 10px; border-color:#FFD40D; border-width:10px;"></textarea><br><br>				
					</div>
	<!--ANEXO-->	<label for="arquivo" class="botao01" style="font-size: 1.2rem; width: 90%; height: 40px; text-align: center; border-radius: 50px; border-color:#FFD40D; border-width:10px; background-color: #042f66;">Anexar Comprovante</label>
	<!--ANEXO-->	<input type="file" value="Enviar anexo" placeholder="ANEXO" id="arquivo" name="arquivo" required name="arquivo" style="display: none; font-size: 50px; width: 880px; height: 100px; text-align: center; border-radius: 50px; border-color:#FFD40D; border-width:10px;"/>

	<!-- BOTÃO ENVIAR--><br><input type="submit" value="Finalizar entrega" class="botao01" style="font-size: 1.6rem;  background-color: #042f66;	 width:90%; height: 60px; border-radius: 50px;">  
				</form>	
				<div id="rodape">
					<label style="background-color: #042f66;    text-align: center;    font-weight:normal;    width:100%;
				color:white;     position:fixed;     bottom:0px;    left:0px;     font-size: 1.1rem;    height: 2rem;"><?php  echo "Módulo [Motorista] - ", ucfirst($operador); ?> - Versão do Sistema: 2.1.5 &nbsp;
					<a href="index.php" style="text-decoration: none; border-radius: 40px  widht:30px; border-color:white; border-width:10px; color: white; font-size: 1.4rem; font-weight: bold;"  >&nbsp;SAIR</a> </label>
				</div>
			</font>
		</center>
	</body>
</html>
