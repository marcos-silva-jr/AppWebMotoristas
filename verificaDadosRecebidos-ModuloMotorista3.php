
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
	
	session_start(); 
	$operador = $_SESSION['login']; //CRIA VARIAVEL COM NOME DE USUARIO 

	$documento = $_POST['documento'] = isset($_POST['documento']) ? $_POST['documento'] : ''; // VALIDA DE SELECIONOU ALGUM DOCUMENTO
	
	// ===== VALIDAÇÃO SE TEM AO MENOS 1 COMPROVANTES ANEXADO, SE NÃO TEM JOGA A MENSAGEM E REDIRECIONA PARA A PÁGINA NOVAMENTE
	
	if($documento == ''){
		echo "<script type='text/javascript'>alert('ERRO :: SELECIONE UM DOCUMENTO'); window.location='mobile-enviarDados-ModuloMotorista3.php';</script>"; //SE FOR JOGA PRA PÁGINA DE NOVO
	}
	else{
		
		//============================= ********************************************* ======================
		//============================= FAZ MANIPULAÇÃO DA IMAGEM PARA PODER COMPRIMI-LA =====================
		//======================================== ********************** ==================================					
				
		$contaArquivos = count($_FILES['arquivo']['name']);
		for($i=0;$i<$contaArquivos;$i++){			
			$filename = $_FILES['arquivo']['name'][$i];			
			//echo $filename;
			if($filename != ''){
				break;
			}
			//move_uploaded_file($_FILES['arquivo']['tmp_name'][$i],$uploadfile);
			//break;					
		}
		
		// ======== SE PODE INSERIR ENTÃO VAMOS COMEÇAR A BRINCADEIRA ==========================
		if($filename == ''){
			echo "<script type='text/javascript'>alert('ERRO ::::: É NECESSÁRIO ANEXAR UM COMPROVANTE'); window.location='mobile-enviarDados-ModuloMotorista3.php';</script>";
		}
		
		else{ //SE NÃO LIBERA PARA FAZER TODAS AS AÇÕES
			//============================= ********************** ===============================
			//======= SEPARA O QUE É A FILIAL, O TIPO E O NÚMERO DO DOCUMENTO ====================
			//============================= ********************** ===============================			
			$divideDocumento = explode('_',$documento);
			$tipo=$divideDocumento[0];
			$filial=$divideDocumento[1];
			$numeroCT=$divideDocumento[2];
			
			try {	
				$Conectar = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
				//============================= ********************** ===============================
				//======= ENTRA NO BANCO DA ATUEX E PEGA A CHAVE DO CTE, OU MELHOR, DA NFE ===========
				//============================= ********************** ===============================			
				
				$fazSelect   = $selectProcura->fetchAll();	//EXECUTA O SELECT DE VERIFICAÇÃO	
				
				foreach($fazSelect as $verificar) { 
					$chave = $verificar['NFE_CHAVE']; //PEGA A CHAVE DO CTE, AGORA TENHO TODAS INFORMAÇÕES									
				}
			}
			catch (Exception $e){  }	
			
			// ============================== FEITO TUDO ISSO PODEMOS PASSAR PARA AS OUTRAS PARTES
			
			$nomearquivo = $chave."_".$dataArquivo.".JPG"; //PEGA O NUMERO DA CTE E ACRESCENTA .JPG
			$uploaddir = 'arquivosBaixaNoSistema/'; //SALVA NA PASTA DO SERVIDOR
			$uploadfile = $uploaddir . $nomearquivo; //PEGA O NOVO ARQUIVO E ACRESCENTA A PASTA	

			//============================= ********************** ===============================
			//======= ENTRA NO BANCO DA ATUEX E PROCURA PARA VER SE JÁ FOI ENTREGUE ==============
			//============================= ********************** ===============================
			
			try {	
												
				$fazSelect   = $selectProcura->fetchAll();	//EXECUTA O SELECT DE VERIFICAÇÃO	
				
				foreach($fazSelect as $verificar) { 
					if($verificar['VERIFICA']==1){
						$podeInserir=1; //SETA QUE NÃO PODE INSERIR, JOGA A MENSAGEM E CAI FORA
						echo "<script type='text/javascript'>alert('ERRO ::::: DOCUMENTO $numerodoc JÁ FOI ENTREGUE'); window.location='mobile-enviarDados-ModuloMotorista3.php';</script>";						
					}
					else {
						$podeInserir=0; //SETA QUE PODE INSERIR
					}
				}
			}
			catch (Exception $e){  }
			
			if($podeInserir==0){
				try {				
					//============================= ********************** ======================
					//============================= INSERT DOS DADOS NO BANCO ==================
					//============================= ********************** ======================
					
					echo "<script type='text/javascript'>alert('OK :: BAIXA REALIZADA COM SUCESSO'); window.location='mobile-enviarDados-ModuloMotorista3.php';</script>";
					$Conectar = Conexao::getConnection(); //CONEXÃO NORMAL COM NOSSO BANCO
					
					$fazInsert   = $queryInserir->fetchAll();	//EXECUTA O SELECT DE VERIFICAÇÃO	
					
				}
				catch (Exception $e){ echo $e;}		
						
			}
			
			else{
				echo "<script type='text/javascript'>alert('ERRO ::::: NÃO FOI POSSÍVEL BAIXAR O DOCUMENTO'); window.location='mobile-enviarDados-ModuloMotorista3.php';</script>";						
			}
			
			//============================= ********************************************* ======================
			//============================= FAZ MANIPULAÇÃO DA IMAGEM PARA PODER COMPRIMI-LA =====================
			//======================================== ********************** ==================================					
					
			$contaArquivos = count($_FILES['arquivo']['name']);
			for($i=0;$i<$contaArquivos;$i++){			
				$filename = $_FILES['arquivo']['name'][$i];			
				//echo $filename;
				move_uploaded_file($_FILES['arquivo']['tmp_name'][$i],$uploadfile);
				break;					
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
				
	}

?>