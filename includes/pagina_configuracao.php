<?php


function carimbador_options_page()
{
    add_menu_page(
        'Carimbador Maluco',
        'Carimbador Maluco',
        'manage_options',
        'carimbador',
        'carimbador_options_page_html',
        plugins_url( 'carimbador/img/icon.png'),
        20
    );
}
add_action('admin_menu', 'carimbador_options_page');


//
// adicionando color-piker
//
add_action( 'admin_enqueue_scripts', 'cm_enqueue_color_picker' );
function cm_enqueue_color_picker() {
    
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'cm-script-handle', plugins_url('cm-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}



 // ------------------------------------------------------------------
 // Registrando as seçoes e campos de configuração
 // ------------------------------------------------------------------
 //
 
 function cm_settings_api_init() {
	// criando uma section para colocar as opções de configuração
 	add_settings_section(
		'cm_setting_section',
		'Configuração do Carimbador Maluco',
		'cm_setting_section_callback_function',
		'carimbador'
	);
 	
 	// Adicionando os campos que vão estar nessa seção
 	add_settings_field(
		'cm_setting_margem',
		'Margem no topo do documento',
		'cm_setting_cb_margem',
		'carimbador',
		'cm_setting_section'
	);
	$texto_settings = "Texto a ser carimbado<br>
	<small>voce pode usar os termos {nome}, {email} e {pedido} para substituir no carimbo</small>";
 	add_settings_field(
		'cm_setting_texto',
		$texto_settings,
		'cm_setting_cb_texto',
		'carimbador',
		'cm_setting_section'
	);	
 	add_settings_field(
		'cm_setting_cor',
		'Cor do texto',
		'cm_setting_cb_cor',
		'carimbador',
		'cm_setting_section'
	);
	 	add_settings_field(
		'cm_setting_fontfamily',
		'Fonte',
		'cm_setting_cb_fontfamily',
		'carimbador',
		'cm_setting_section'
	);
	 	add_settings_field(
		'cm_setting_fontsize',
		'Tamanho Fonte',
		'cm_setting_cb_fontsize',
		'carimbador',
		'cm_setting_section'
	); 

 	//registrando as opções pro wordpress gravar pra nós
 	register_setting( 'carimbador', 'cm_setting_margem' );
 	register_setting( 'carimbador', 'cm_setting_texto' );
 	register_setting( 'carimbador', 'cm_setting_cor' );
 	register_setting( 'carimbador', 'cm_setting_font' );
  	register_setting( 'carimbador', 'cm_setting_fontsize' );
  	
 	
 } 
 
 // inicializando o trabalho feito até aqui
 // colocamos no gancho admin_init
 add_action( 'admin_init', 'cm_settings_api_init' );



// ------------------------------------------------------------------
 // Escrevendo as funções de callback usadas pelos campos e pela seção 
 // ------------------------------------------------------------------
 //
 // 
 //
 
 function cm_setting_section_callback_function() {
 	if(!$texto = get_option("cm_setting_texto")){
		?>
		<p><b>IMPORTANTE:</b></p>
		<p>Aparentemente, é sua primeira vez aqui, ou suas configurações não foram salvas corretamente na ultima vez que voce usou esta página.</br>
		Caso os valores apresentados na tela sejam satisfatórios, apenas clique em Salvar.</p>
		<p>Se voce não salvar nenhuma modificação nesta página, o Carimbador Maluco vai usar os valores definidos por padrão</p>
		<?php
	} else {
		?>
		<p>Aparentemente suas opções foram salvas corretamente. Caso algum valor tenha sido inserido de forma indevida ou deixado em branco, o Carimbador Maluco irá usar os valores definidos por padrão.</p>
		<p>Aproveite a experiência com o Carimbador Maluco. Duvidas e Sugestões? Envie um e-mail para diegoperotto@gmail.com</p>
		<?php
	}

 }
 
 // ------------------------------------------------------------------
 // Escrevendo as funções de callback dos campos
 // ------------------------------------------------------------------
 //
 // aqui eu criei o output dos campos texto e cor, usando um color-piker e um textarea
 //
 
 function cm_setting_cb_margem(){
 	?>
 	<input type="text" class="code" id="cm_setting_margem" size="4" maxlength="3" name="cm_setting_margem" value="<?php  
 	if($valor = get_option("cm_setting_margem")){
		echo $valor;
	} else {
		
		echo CARIMABDOR_MARGEM_PADRAO;
	}
 	?>"/>cm
 	<?php
 }
 
 function cm_setting_cb_texto() {
 	?>
 	<textarea name="cm_setting_texto" id="cm_setting_texto" class="code" rows="4"><?php
 	$valor = "";
 	$valor =  get_option( 'cm_setting_texto' );
 	if($valor==""){
		$valor = CARIMABDOR_TEXTO_PADRAO;
	}
 	echo $valor;
 	?></textarea>
 	
		<?php
 }
function cm_setting_cb_cor() {
 	 	echo '<input name="cm_setting_cor" id="cm_setting_cor" type="text" value="';
 	 	if($valor = get_option( 'cm_setting_cor' )){
 	 		if(preg_match( '/^#[a-f0-9]{6}$/i', $valor )){
				echo $valor;
			} else{
				$valor = CARIMABDOR_COR_PADRAO;
				echo $valor;
			}
 	 		
		} else {
			$valor = CARIMABDOR_COR_PADRAO;
			echo $valor;
		
		}
 	 	echo '" class="cm-color-field" data-default-color="'.$valor.'"/>';
}

function cm_setting_cb_fontsize(){
	if(!$valor = get_option("cm_setting_fontsize")){
		$valor = CARIMABDOR_TAMANHO_PADRAO;
	}
	echo '
	<select name="cm_setting_fontsize" id="cm_setting_fontsize">
	<option value="8" ';
	if($valor==8){ echo 'SELECTED';}
	echo '>8</option>';
	echo '<option value="9" ';
	if($valor==9){ echo 'SELECTED';}
	echo '>9</option>';
	echo '<option value="10" ';
	if($valor==10){ echo 'SELECTED';}
	echo '>10</option>';
	echo '<option value="11" ';
	if($valor==11){ echo 'SELECTED';}
	echo '>11</option>';
	echo '<option value="12" ';
	if($valor==12){ echo 'SELECTED';}
	echo '>12</option>';
	echo '<option value="13" ';
	if($valor==13){ echo 'SELECTED';}
	echo '>13</option>';
	echo '<option value="14" ';
	if($valor==14){ echo 'SELECTED';}
	echo '>14</option>';	
	echo '</select>';
	
}
function cm_setting_cb_fontfamily(){
	if(!$valor = get_option("cm_setting_fontfamily")){
		$valor = CARIMABDOR_FONTE_PADRAO;
	}
	echo '
	<select name="cm_setting_fontfamily" id="cm_setting_fontfamily">
	<option value="Helvetica" ';
	if($valor=='Helvetica'){ echo 'SELECTED';}
	echo '>Helvetica</option>';
	echo '<option value="Times" ';
	if($valor=='Times'){ echo 'SELECTED';}
	echo '>Times New Roman</option>';
	echo '<option value="Courier" ';
	if($valor=='Courier'){ echo 'SELECTED';}
	echo '>Courier</option>';
	
	echo '</select>';
	
}
 // ------------------------------------------------------------------
 // escrevendo o HTML mostrado na pagina do carimbador maluco
 // ------------------------------------------------------------------
 //
 // 
 //

function carimbador_options_page_html(){
	?><div class="wrap">
<h1><img src="<?php echo plugins_url( 'carimbador/img/icon.png')?>">&nbsp;Carimbador Maluco</h1>
	<form method="POST" action="options.php">
	<?php 	settings_fields( 'carimbador' );	//pass slug name of page, also referred
                                        //to in Settings API as option group name
			do_settings_sections( 'carimbador' ); 	//pass slug name of page
			submit_button();
		?>
		</form>
		</div>
		<?php
}
?>