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
	
	$datahoje = date('Y.m.d 00:00:00');


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
		
		<script type="text/javascript">
		
		 /* ============================================================================================
			================================== ------------------------- =================================
			================= ESCONDER DIV PRINCIPAL E MOSTRAR A DIV DE CARREGAMENTO PARA ILUDIR O MOTORISTA =============
			================================== ------------------------- ================================= */
			
			/* ========= DIV ACESSO RAPIDO  =========== */
			
			function carrega(){		
				
				//ESCONDE AS OUTRAS DIVS				
				$('#carregar').show(); 
				$('#baixa-container').hide(); 			
				
			}
		</script>
	<!-- ====================== ********************* ================== -->
	<!-- =================== COMEÇANDO CORPO DA PÁGINA ================= -->
	<!-- ====================== ********************* ================== -->

	<body style="background-color: #EFEFED;">
	
		<div id="cabecalho" style="z-index:1000;background-color: white;   display: flex;  align-items: center; box-shadow: 0 3px 0 rgba(0, 0, 0, .3), 
		,0 0 2px 7px rgba(0, 0, 0, 0.2);    color: white;  height:5.4rem; top:0px; left:0px; 
		margin: 0 auto;     position:fixed;      width: 100%; "> 
		 
			&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><img src="inicial.png" title="Página Inicial" style="width: 190px; height: 72px; text-align: center;" /></a>
			
			<label class="botaoSair" style=" display: flex;  align-items: center; text-align: center; position:fixed;  width: 26rem;  color: white; font-size: 1.6rem;
				height: 3.4rem;   background-color: gray; border-radius:5px; text-align:center; margin-left:28%;">&nbsp;&nbsp;MÓDULO MOTORISTAS - V1.4.22</label>
				
			<a href="mobile-Painel-ModuloMotorista.php"  style="text-decoration: none;">
				<label  style=" display: flex;  align-items: center; text-align: center; position:fixed; right:2.8rem; width: 9rem;  cursor:pointer; color: white; font-size: 1.6rem;
				height: 3.4rem;  margin-top:-1.7rem; background-color: #f24a4a; border-radius:5px; text-align:center; z-index:1000;">&nbsp;&nbsp;&nbsp;&nbsp;VOLTAR</label>
			</a>
				
		</div>
		
		<div id="baixa-container" style="width:90%; margin-top:7rem;">	
				
			<div style="margin-top:-1.3rem;">	
			
			<?php
				
				
				/* =========================================== ********************************** ==============================	
				====================================================== PASSO 1 - PEGAR OO CPF DO CARA ============================
				=========================================== ********************************======================================*/
				
				try {
					$Conexao    = Conexao::getConnection(); 
					
					$usuarios   = $query->fetchAll();
					
						foreach($usuarios as $busca_usuario) {	
						
							$cpf =  $busca_usuario['USUARIO_CPF']; //SALVA O CPF DO CARA			
							
						}
					} catch (Exception $e){	}
					
					
					/* =========================================== ********************************** ==============================	
					====================================================== PASSO 2 - PEGAR O ROMANEIO DESSE MOTORISTA ================
					=========================================== ********************************======================================*/
					
					try {
						$Conexao    = Conexao::getConnection(); 
						
						$usuarios   = $query->fetchAll();
						
							foreach($usuarios as $busca_usuario) {	
											
								$romaneio = "ATU.".$busca_usuario['ROMANEIO']." (ROMS)"; //SALVA O NUMERO DO ROMANEIO OU MANIFESTO
														
							}
					} catch (Exception $e){	}

					/* =========================================== ********************************** ==============================	
					====================================================== PASSO 3 - CONTAR QUANTOS DOCUMENTOS TEM DO MOTORISTA ================
					=========================================== ********************************======================================*/
					
					try {
						$Conexao    = Conexao::getConnection(); 
						
						$usuarios   = $query->fetchAll();
						
							foreach($usuarios as $busca_usuario) {	
											
								$contadorBaix = $busca_usuario['CONTAR']; ;// SALVA A VARIAVEL NESTE CONTADOR
														
							}
					} catch (Exception $e){	}
				
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
			border-radius:5px; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2);  width: 98%; display: flex;  align-items: center; z-index:1000;">
			<?php echo "<label style='text-align:center; color:white; font-size:1.8rem;'>DOCUMENTOS PARA ENTREGA HOJE: ".$contadorBaix."</label>"; ?></label>
			<br>
				
			<form action="verificaDadosRecebidos-ModuloMotorista3.php" method="post" enctype="multipart/form-data"> 
			<?php
				
				/* =========================================== ********************************** ==============================	
				=================================================== MOSTRAR A RELAÇÃO DE DOCS DA DATA DE HOJE ================
				=========================================== ********************************======================================*/
				
				try {
					$Conexao    = Conexao::getConnection(); 
										
					$usuarios   = $query->fetchAll();
										
						echo "<table style='width:98%;'>";
						
						foreach($usuarios as $busca_usuario) {	
						
							/* ====================== VERIFICA SE DOCUMENTO JÁ FOI ENTREGUE ============= */
							
							/* ====================== ------------------------------------ ============= */
							/* ====================== ---- DOCUMENTO NÃO ENTREGUE -------- ============= */
							/* ====================== ------------------------------------- ============= */
							
							if($busca_usuario['ENTREGA'] == NULL){
								echo "<tr>";
									
								/* ====================== NOVA COLUNA - RADIO BUTTON E INFORMAÇÕES DO DOCUMENTO ============= */
								echo "<td>";
									echo "<input type='radio' name='documento' value='".$busca_usuario['CTO_DOCUMENTO']."_".$busca_usuario['CTO_FILIAL']."_".$busca_usuario['CTO_NUMERO']."' 
									id='".$busca_usuario['CTO_DOCUMENTO']."_".$busca_usuario['CTO_FILIAL']."_".$busca_usuario['CTO_NUMERO']."'
									style='height: 3.5rem; width:2.8rem; margin-bottom:1px; border: 1px solid #ccc;'>";
									echo "</td>";
									echo "<td>";
									echo "<label style='text-align: center;  color: white; font-size: 2rem; margin-top:0.5rem; height: 4rem;   background-color: #46789c;
										border-radius:10px; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2);  display: flex; margin-bottom:5px;
										align-items: center; z-index:1000;'>&nbsp;".$busca_usuario['CTO_DOCUMENTO']." ".$busca_usuario['CTO_NUMERO'];
									
								echo "</td>";
								
								/* ====================== NOVA COLUNA - ENVIAR COMPROVANTE ============= */
								echo "<td>";
								echo "<label for='arquivo' style='display: flex; align-items: center; margin-bottom:1px; font-size: 2rem; color: white; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),
								0 2px 7px rgba(0, 0, 0, 0.2); width: 100%; height: 4rem; text-align: center; border-radius: 10px;  background-color: #46789c;'>&nbsp;COMPROVANTE
								<img src='documento.png' style='width: 50px; height: 50px;'/></label>
									<input type='file' value='Enviar anexo' id='arquivo' name='arquivo[]' multiple style='display: none;'/>";
								echo "</td>";
								
								/* ====================== ULTIMA COLUNA - BAIXAR ============= */
								
								echo "<td>";
									echo "<input type='submit' onclick='carrega()' value='&nbsp;&nbsp;&nbsp;&nbsp;Baixar&nbsp;' class='botao01' style='text-align:center;
									box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2); font-size: 2rem;  background-color: #f06262;	
									display: flex; align-items: center; margin-bottom:5px; height: 4rem; margin-top:0.5rem; width:90%;  border-radius: 10px;'>
									";
								echo "</td>";
									
								echo '</tr>'; //FECHA A LINHA DE CIMA DO DOCUMENTO NÃO ENTREGUE
								
							}
							
							/* ====================== ---------------------------------- ============= */
							/* ====================== ---- DOCUMENTO ENTREGUE -------- ============= */
							/* ====================== ---------------------------------- ============= */
							
							else{
								echo "<tr>";
									echo "<td colspan='3'>";
										echo "<label style='margin-top:0.5rem; text-align: center;  color: white; font-size: 2rem; height: 4rem;   background-color: #067368;
											border-radius:10px; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2);  display: flex;
											align-items: center; z-index:1000;'>&nbsp;&nbsp;".$busca_usuario['CTO_DOCUMENTO']." ".$busca_usuario['CTO_NUMERO']
											." - ".$busca_usuario['ENT_CIDADE'].", ".$busca_usuario['ENT_UF']."&nbsp;&nbsp;</label></center>";
									echo "</td>";
									/* ====================== NOVA COLUNA - ENTREGUE ============= */
									echo "<td colspan='4'>";									
										echo "<label style='width: 95%; margin-top:0.5rem; text-align: center;  color: white; font-size: 2rem;	 height: 4rem;   background-color: #0b8c7f;
											border-radius:10px; box-shadow: 0 3px 0 rgba(0, 0, 0, .3),  0 2px 7px rgba(0, 0, 0, 0.2);   display: flex;
											align-items: center;  z-index:1000;'>&nbsp;&nbsp;ENTREGUE&nbsp;&nbsp;<img src='coletas-ok.png' style='width: 40px; height: 40px;'/></label></center>";
									echo "</td>";
								echo "</tr>";
								
							}						
																				
						}
										
					} catch (Exception $e){	}
					echo "</form></table>";
					?>
							
				</center>
				<!-- BOTÃO =========== suporte técnico -->			
				<br><br><br><br><a href="tel:+5511976527871"  style="text-decoration: none;">
					<label  style=" display: flex;  align-items: center; text-align: center; width: 90%;  cursor:pointer; color: white; font-size: 2.2rem;
					height: 6.4rem;  border-radius: 50px; background-color: #f24a4a; border-radius:30px; margin-left:2.5rem;">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="telefone.png" style="width: 50px; height: 50px; " />
					&nbsp;&nbsp;&nbsp;&nbsp;LIGAR PARA SUPORTE TÉCNICO</label></a>
		</div>
			<div id="carregar" style="display:none; margin-top:10rem;">	
				<br><br><br><br><br><br><br>
				<center><img src="gifCarregar1.gif" style="width: 400px; height: 400px;"/></center>
				<br><br><label style="text-align:center;">Aguarde um instante...</label>				
			</div>
			
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
