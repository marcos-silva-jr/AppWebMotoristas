
<!-- =======================================================-->
<!---------------- VERIFICAR DADOS - COMEÇANDO PHP ----------->
<!-- =======================================================-->

<?php
	require_once "conexaoSQL/Conexao.php";

	date_default_timezone_set('America/Sao_Paulo');
	$hoje = date('Y.m.d'); //PEGA DATA ATUAL PARA PREENCHER AUTOMATICO NA BAIXA
	$hora = date('H:i'); //PEGA HORA ATUAL PARA PREENCHER AUTOMATICO NA BAIXA
	$datetime = date('Y.m.d H:i:s'); //DATA E HORA ATUAL PARA PREENCHER AUTOMATICO NA BAIXA	
	
	$dia=date('d');
	$mes=date('m');
	$ano=date('Y');
	
	$dataArquivo = $dia.$mes.$ano;	
	
	// ==================== ************************************ ==================
	// ==================== ************************************ ==================
	// ================== MANIPULANDO DADOS VINDOS DA OUTRA PAGE ==================
	// ==================== ************************************ ==================
	// ==================== ************************************ ==================
	
	$filial = $_POST['filial']; //PEGA CAMPO DA FILIAL E SALVA NA VARIAVEL
	$numerodoc = $_POST['numerodoc']; //PEGA CAMPO DO CTE E SALVA AQUI
	session_start(); 
	$operador =  $_SESSION['login']; //CRIA VARIAVEL COM NOME DE USUARIO 
	$chavecte = $_POST['chavecte']; //PEGA CAMPO CTE OCULTO E SALVA AQUI
	$nome_recebedor = $_POST['nomeRecebedor']; //PEGA CAMPO NOME E SALVA
	$nome_recebedor=strtoupper($nome_recebedor); //CONVERTE TUDO PRA MAIUSCULA
	$cpf_recebedor= $_POST['cpfRecebedor']; //PEGA CAMPO CPF E SALVA
	
	// ==================== ************************************ ==================
	// ================== TIPO DE DOCUMENTO OPÇÃO RADIO BUTTON ===================
	// ==================== ************************************ ==================
	
	if($_POST['tipo_doc']=="3") { 
		$tipoDoc = "CT"; //SALVA VARIAVEL CTE
	}
	else if($_POST['tipo_doc']=="4") { 
		$tipoDoc = "MN"; //SALVA VARIAVEL MINUTA
		$chavecte = " ";
	}
	else if($_POST['tipo_doc']=="5") { 
		$tipoDoc = "CO"; ////SALVA VARIAVEL COLETA
		$chavecte = " ";
	} else { }	
	
	
	// ==================== ******************************************* ==================
	// ================== GUARDANDO NAS VARIAVEIS PARA INSERIR NO BANCO ==================
	// ==================== ******************************************* ==================

	// ====================================== ************************************ =====================================
	// =============================== VERIFICA O RADIO BUTTON ESCOLHIDO - ENTREGA OU OCORRENCIA =======================
	// ======================================= ************************************ ====================================

	$nomearquivo = $chavecte."_".$dataArquivo.".JPG"; //PEGA O NUMERO DA CTE E ACRESCENTA .JPG
	$nomearquivo = str_replace('/', '', $nomearquivo); //RETIRA AS BARRAS DA DATA
	
	
	if($_POST['entrega']==0) //=================VERIFICA AS OPÇÕES DO RADIO BUTTON DE ENTREGA ESCOLHIDAS
	{ 
		$tipoentrega = "0"; // SE ENTREGA FOR 0 (ENTREGA NORMAL) ESCREVE NO BANCO 0

		if($tipoDoc=="MN" || $tipoDoc=="CO"){ 
			$chavecte=" ";
			$nomearquivo = $tipoDoc."_".$filial."_".$numerodoc."_".$hoje.".JPG"; //PEGA O NUMERO DA CTE E ACRESCENTA .JPG
			$nomearquivo = str_replace('/', '', $nomearquivo); //RETIRA AS BARRAS DA DATA
		}	
		
		$uploaddir = 'arquivosBaixaNoSistema/'; //SALVA NA PASTA DO SERVIDOR
		$uploadfile = $uploaddir . $nomearquivo; //PEGA O NOVO ARQUIVO E ACRESCENTA A PASTA		
		$ocorrencia = "";
	}
	else{	//SE NÃO, É ------ OCORRÊNCIA ---------	
		// ==================== ******************************************* ==================
		// ================== MANIPULANDO O SELECT NAME DE OCORRENCIAS, A LISTA ==================
		// ==================== ******************************************* ==================
		$nomearquivo = $chavecte."_".$hoje.".JPG"; //ESCREVE O NOME DO ARQUIVO COM "o" de OCORRENCIA
		
		$motivoOcorren=$_POST['motivoOcorrencia']; //CAMPO DE DESCRICAO OCORRENCIA
		
		//SE NAO ESTIVER VAZIA PASSA ELA PARA A VARIAVEL OCORRENCIA
		if($motivoOcorren != ""){  $ocorrencia =  $motivoOcorren; $tipoentrega = "99";}	
		else{ 
			$pegaOcorren = explode('|',$_POST['selecionaOcorrencia']);
			$codigoOcorren=$pegaOcorren[0];
			$descricaoOcorren=$pegaOcorren[1];
			$ocorrencia = $descricaoOcorren ;
			$ocorrencia=strtoupper($ocorrencia); //CONVERTE TUDO PRA MAIUSCULA
			$tipoentrega = $codigoOcorren;
		}	
		//=========================== CRIA ARQUIVO COM MOTIVO DA OCORRENCIA ================
		if($tipoDoc=="MN" || $tipoDoc=="CO"){ 
			$chavecte=" ";
			$nomearquivo = $tipoDoc."_".$filial."_".$numerodoc."_".$hoje.".JPG"; //PEGA O NUMERO DA CTE E ACRESCENTA .JPG
			$nomearquivo = str_replace('/', '', $nomearquivo); //RETIRA AS BARRAS DA DATA
		}			
		$uploaddir = 'arquivosBaixaNoSistema/Ocorrencias/'; //SALVA NA PASTA DO SERVIDOR
		$uploadfile = $uploaddir . $nomearquivo; //PEGA O NOVO ARQUIVO E ACRESCENTA A PASTA

		$hojeSemAspas = str_replace('/', '', $hoje); //RETIRA AS BARRAS DA DATA
		$gerarArquivo = fopen('arquivosBaixaNoSistema/Ocorrencias/'.$tipoDoc.'_'.$filial.'_'.$numerodoc.'_'.$hojeSemAspas.'_O.txt', 'w'); //ABRE O ARQUIVO PARA ESCRITA (W)
		fwrite($gerarArquivo, "TIPO DE DOCUMENTO: ".$tipoDoc."\nFILIAL: ".$filial."\nNUMERO DOCUMENTO: ".$numerodoc."\nOCORRENCIA-DOC: ".$chavecte."\nMOTORISTA: ".$operador."\nRECEBEDOR(A): ".$nome_recebedor."\nDESCRICAO_OCORRENCIA: ".$ocorrencia."\nDATA: ".$hoje."\nHORARIO: ".$hora); //ESCREVE A VARIVAEL MOTIVO DENTRO DO ARQUIVO
		fclose($gerarArquivo);	
		//exec("C:\teste\enviaEmail.py"); //EXECUTA O COMANDO PYTHON PARA ENVIAR AS INFORMAÇÕES JUNTO COM O ANEXO POR EMAIL
	}
	
	//============================= ************************************************ =================
	//============================= VERIFICA SE NUMERO DO SCANNER DOCUMENTO É VÁLIDO ==================
	//============================= ************************************************ ===================
	
	if($chavecte=="" || !is_numeric($chavecte)){ //-===========SE TEM LETRAS OU ESTIVER VAZIO NÃO PASSA
		
		//============================= ************************************************ =================
		//============================= VERIFICA SE NUMERO DO SCANNER DOCUMENTO É VÁLIDO ==================
		//============================= ************************************************ ===================
		if ($tipoDoc=="CT"){
			echo "<script type='text/javascript'>alert('ERRO :: NUMERO DO DOCUMENTO INVALIDO, SCANEAR CODIGO DE BARRAS NOVAMENTE'); window.location='enviarDados-ModuloMotorista.php';</script>";
		}
		else{
			$validaMinutaColeta=0;
		}
	} 
	
	else {

		// ==================== ------------------------------------------------------------ ===================
		// ==================== ------------------------------------------------------------ ===================
		// ================== ENTRA NO BANCO A_TB_SYSTEM ATRAVES DO SELECT PARA VERIFICAR SE CTE JÁ EXISTE ======
		// ==================== ------------------------------------------------------------- ===================
		// ==================== ------------------------------------------------------------ ====================
		$validacao=2;
		try {
			$Conectar = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
			
			$procura   = $queryProcuraDocumento->fetchAll();	//EXECUTA O SELECT

			//============================= ********************** ======================
			//============================= ENTRA NO FOR PARA PROCURAR ==================
			//============================= ********************** ======================

			foreach($procura as $buscaDoc) { 
				
				if($chavecte==$buscaDoc['CHAVE_CTE']){ //--------------SE ACHOU DOCUMENTO JOGA A MENSAGEM
					$validacao=0;
					break; //JA CAI FORA PARA NAO CONTINUAR NO LAÇO
				}
				else {
					$validacao=1;
				}
			}			
		}
		catch (Exception $e){ }			

		if($validacao==0){
			echo "<script type='text/javascript'>alert('ERRO :: DOCUMENTO $numerodoc JÁ CONSTA COM DATA DE ENTREGA'); window.location='enviarDados-ModuloMotorista.php';</script>";
			$podeInserir=1;
		}
		else{
			try {	
				//============================= ********************** ===============================
				//======= ENTRA NO BANCO DA ATUEX E PROCURA PARA VER SE TEM ENTREGA ==================
				//============================= ********************** ===============================			
											
				$fazSelect   = $selectProcura->fetchAll();	//EXECUTA O SELECT DE VERIFICAÇÃO	
				
				foreach($fazSelect as $verificar) { 
					if($verificar['VERIFICA']==1){
						$podeInserir=1; //SETA QUE NÃO PODE INSERIR, JOGA A MENSAGEM E CAI FORA
						echo "<script type='text/javascript'>alert('ERRO :: DOCUMENTO $numerodoc JÁ CONSTA COM DATA DE ENTREGA'); window.location='enviarDados-ModuloMotorista.php';</script>";						
					}
					else {
						$podeInserir=0; //SETA QUE PODE INSERIR
					}
				}
			}
			catch (Exception $e){  }	
		}
		
		//VALIDA PARA VER SE TEM OCORRENCIA
		
		if($tipoentrega!=0 && $podeInserir==0){
			
			try {				
				//============================= ********************** ======================
				//============================= INSERT DE OCORRENCIA ==================
				//============================= ********************** ======================
				
				echo "<script type='text/javascript'>alert('OK :: BAIXA NO DOCUMENTO $numerodoc REALIZADA COM SUCESSO'); window.location='enviarDados-ModuloMotorista.php';</script>";
				$Conectar = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
												
				$fazInsert   = $queryInserir->fetchAll();	//EXECUTA O SELECT DE VERIFICAÇÃO				
			}
			catch (Exception $e){ echo $e;}		
			
		}
		
		else {
		
			if($operador==""){
				echo "<script type='text/javascript'>alert('ERRO :: REALIZAR LOGIN NOVAMENTE'); window.location='enviarDados-ModuloMotorista.php';</script>";	
			}
			else{
				//SE NÃO TEM DATA DE ENTREGA PODE INSERIR ATRAVÉS DO IF ABAIXO EXECUTANDO O INSERT NAS 3 TABELAS
				
				if($podeInserir==0 && $tipoentrega==0){
					
					try {				
						//============================= ********************** ======================
						//============================= INSERT DOS DADOS NO BANCO ==================
						//============================= ********************** ======================
						
						echo "<script type='text/javascript'>alert('OK :: BAIXA NO DOCUMENTO $numerodoc REALIZADA COM SUCESSO'); window.location='enviarDados-ModuloMotorista.php';</script>";
						$Conectar = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
						
						$fazInsert   = $queryInserir->fetchAll();	//EXECUTA O SELECT DE VERIFICAÇÃO				
					}
					catch (Exception $e){ echo $e;}		
				}		
			}
		}
					
		//============================= ********************************************* ======================
		//============================= FAZ MANIPULAÇÃO DA IMAGEM PARA PODER COMPRIMI-LA =====================
		//======================================== ********************** ==================================
				
				
		if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)) { //MOVE O ARQUIVO QUE PEGOU PARA A PASTA DO SERVIDOR COM NOVO NOME DE ARUQIVO QUE CRIEI LA ENCIMA
		} 
		//============================= ********************************************* ================
		//================ AQUI REALIZA A REDUÇÃO DE TAMANHO DO ARQUIVO ENVIADO PELO USUÁRIO =========
		//================================== ********************** ==================================
		
		$width = 1100; //CRIA LARGURA
		$height = 670; //CRIA ALTURA

		list($width_orig, $height_orig) = getimagesize($uploadfile); // Obtendo o tamanho original
		$ratio_orig = $width_orig/$height_orig; // Calculando a proporção

		if ($width/$height > $ratio_orig) { //==========ALTERANDO DIMENSAO DO ARQUIVO=====
		$width = $height*$ratio_orig;
		} else { $height = $width/$ratio_orig; 	}	

		//================GERANDO NOVA IMAGEM COM NOVAS DIMENSOES :P
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($uploadfile);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagejpeg($image_p,$uploadfile, 75); //SALVA O ARQUIVO ENCIMA DO MESMO ARQUIVO		
	}		

?>