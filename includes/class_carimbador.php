<?php
namespace setasign\Fpdi;

use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use setasign\Fpdi\PdfParser\Type\PdfNull;

require_once(dirname( CARIMBADOR_PLUGIN_FILE ) . '/FPDI/fpdf.php');
require_once(dirname( CARIMBADOR_PLUGIN_FILE ) . '/FPDI/src/FpdfTpl.php');
require_once(dirname( CARIMBADOR_PLUGIN_FILE ) . '/FPDI/src/FpdiTrait.php');
require_once(dirname( CARIMBADOR_PLUGIN_FILE ) . '/FPDI/src/Fpdi.php');
require_once(dirname( CARIMBADOR_PLUGIN_FILE ) . '/FPDI/src/autoload.php');

class CarimbadorMaluco extends Fpdi{

	public  function inicializa() {
		

	add_action( 'carimbador_salva_arquivo', array( new CarimbadorMaluco(), 'salvaArquivoModificado' ),10,3);
		
	}
	
	private function hexaPraRGB($hexa){
		$r = hexdec(substr($hexa,1,2));
		$g = hexdec(substr($hexa,3,2));
		$b = hexdec(substr($hexa,5,2));
		return array($r,$g,$b);
	}
	
	private function pegaNome($userId){
		$nome = get_user_meta($userId,"billing_first_name",TRUE)." ".get_user_meta($userId,"billing_last_name",TRUE);
		return $nome;
	}
	
	private function montaTexto($dados){
		
		$user = get_user_by_email($dados[0]);
		$userId = $user->ID; // pagamos o ID do usuario pra montar o nome dele
		$nome = $this->pegaNome($userId);
		$texto = get_option('cm_setting_texto');
		if(!$texto){
			$texto = CARIMABDOR_TEXTO_PADRAO;
		}
		$texto = str_replace("{nome}",$nome,$texto);
		$texto = str_replace("{email}",$dados[0],$texto);
		$texto = str_replace("{pedido}","#".$dados[1],$texto);
		return $texto;
		
	}
	
	/**
	* Funçao que cria o arquivo carimbado e força o download dele usando os dados passados
	* 
	* 
	* @param string $arquivo
	* @param string $nomeArquivo
	* @param string $texto
	* 
	* @return
	*/
	
	public  function salvaArquivoModificado($arquivo,$nomeArquivo,$dados){
		$corhex = get_option( 'cm_setting_cor' );
		if(!$corhex){
			$corhex = CARIMABDOR_COR_PADRAO;
		}
		
		
 	if((!$margem = get_option("cm_setting_margem")) || 
 		(!is_numeric($margem))){
		$margem = CARIMABDOR_MARGEM_PADRAO;
	}

		$cor = $this->hexaPraRGB($corhex);
		
		$texto = $this->montaTexto($dados);
		
		
		$paginas = $this->setSourceFile($arquivo);
		$pagina =1;

		$tplId = self::ImportPage($pagina);
		$dadosArquivo = self::getTemplateSize($tplId);
		$orientacao = $dadosArquivo["orientation"];
		$altura = $dadosArquivo["height"];
		$largura = $dadosArquivo["width"];
		self::SetMargins(0,$margem);
		self::AddPage($orientacao,[$largura,$altura]);

		$tpl = self::UseTemplate($tplId);
		$largura = $tpl[0];
		$altura = $tpl[1];

		$fontsize = get_option("cm_setting_fontsize");
		if(!$fontsize){
			$fontsize = CARIMABDOR_TAMANHO_PADRAO;
		}
		$font = get_option("cm_setting_fontfamily");
		if(!$font){
			$font = CARIMABDOR_FONTE_PADRAO;
		}
		self::SetFont($font, "B", $fontsize);
			
		self::SetTextColor($cor[0], $cor[1], $cor[2]);
		
		self::Cell($largura,0,$texto,0,1,"C");
		//self::MultiCell($largura,3,$texto,0,"C");
		
		while($pagina<$paginas){
			$pagina++;
			$tplId = self::ImportPage($pagina);
			self::AddPage($orientacao,[$largura,$altura]);
			self::UseTemplate($tplId);
			self::Cell($largura,0,$texto,0,1,"C");
			//self::MultiCell($largura,3,$texto,0,"C");
		}
				
		self::Output($nomeArquivo,"D");
		
	}
	
}
$t = new CarimbadorMaluco();
$t->inicializa();