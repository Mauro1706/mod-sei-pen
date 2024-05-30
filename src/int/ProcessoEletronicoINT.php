<?php

require_once DIR_SEI_WEB.'/SEI.php';

class ProcessoEletronicoINT extends InfraINT {

    //Situa��o de cada uma das etapas da envio externo de processos
    const NEE_EXPEDICAO_ETAPA_PROCEDIMENTO = 1;
    const TEE_EXPEDICAO_ETAPA_VALIDACAO = 'Validando informa��es do processo...';
    const TEE_EXPEDICAO_ETAPA_PROCEDIMENTO = 'Enviando dados do processo %s';
    const TEE_EXPEDICAO_ETAPA_DOCUMENTO = 'Enviando documento %s';
    const TEE_EXPEDICAO_ETAPA_CONCLUSAO = 'Tr�mite externo do processo finalizado com sucesso!';

    /**
     * Concate as siglas das hierarquias no nome da unidade
     *
     * @param array(EstruturaDTO) $estruturas
     * @return array
     */
  public static function gerarHierarquiaEstruturas($estruturas = array()){

    if(empty($estruturas)) {
        return $estruturas;
    }

    foreach($estruturas as &$estrutura) {
      if($estrutura->isSetArrHierarquia()) {
          $nome  = $estrutura->getStrNome();
          $nome .= ' - ';

          $array = array($estrutura->getStrSigla());
        foreach($estrutura->getArrHierarquia() as $sigla) {
          if(trim($sigla) !== '' && !in_array($sigla, array('PR', 'PE', 'UNIAO'))) {
                $array[] = $sigla;
          }
        }

          $nome .= implode(' / ', $array);
          $estrutura->setStrNome($nome);
      }
    }

      return $estruturas;
  }

  /**
   * Concate as siglas das hierarquias no nome da unidade
   *
   * @param array(EstruturaDTO) $estruturas
   * @return array
   */
  public static function gerarHierarquiaEstruturasAutoCompletar($estruturas = array())
  {

    if (empty($estruturas['itens'])) {
      return $estruturas;
    }

    foreach ($estruturas['itens'] as &$estrutura) {
      if ($estrutura->isSetArrHierarquia()) {
        $nome  = $estrutura->getStrNome();
        $nome .= ' - ';

        $array = array($estrutura->getStrSigla());
        foreach ($estrutura->getArrHierarquia() as $sigla) {
          if (trim($sigla) !== '' && !in_array($sigla, array(
            'PR', 'PE', 'UNIAO'
          ))) {
            $array[] = $sigla;
          }
        }

        $nome .= implode(' / ', $array);
        $estrutura->setStrNome($nome);
      }
    }

    return $estruturas;
  }

  public static function autoCompletarEstruturas($idRepositorioEstrutura, $strPalavrasPesquisa, $bolPermiteEnvio = false) {
       
       
      $objConecaoWebServerRN = new ProcessoEletronicoRN();
      $arrObjEstruturas = $objConecaoWebServerRN->listarEstruturas(
          $idRepositorioEstrutura,
          $strPalavrasPesquisa,
          null, null, null, null, null, true, $bolPermiteEnvio
      );

      return static::gerarHierarquiaEstruturas($arrObjEstruturas);
  }

  public static function autoCompletarEstruturasAutoCompletar($idRepositorioEstrutura, $strPalavrasPesquisa, $bolPermiteEnvio = false)
  {

    $objConecaoWebServerRN = new ProcessoEletronicoRN();
    $arrObjEstruturas = $objConecaoWebServerRN->listarEstruturasAutoCompletar(
      $idRepositorioEstrutura,
      $strPalavrasPesquisa,
      null,
      null,
      null,
      null,
      null,
      true,
      $bolPermiteEnvio
    );

    return static::gerarHierarquiaEstruturasAutoCompletar($arrObjEstruturas);
  }

  /**
   * Auto completar repositorio de estruturas
   *
   * @param string $strPalavrasPesquisa
   * @return array
   */
  public static function autoCompletarRepositorioEstruturas($strPalavrasPesquisa)
  {
    $objProcessoEletronicoRN = new ProcessoEletronicoRN();
    $arrObjRepositorioDTO = (array) $objProcessoEletronicoRN->listarRepositoriosDeEstruturas();
    $arrayRepositorioEstruturas = array();
    foreach ($arrObjRepositorioDTO as $value) {
      if (strpos(strtoupper($value->getStrNome()), strtoupper($strPalavrasPesquisa)) !== false) {
        $arrayRepositorioEstruturas[] = $value;
      }
    }
    return $arrayRepositorioEstruturas;
  }

  public static function autoCompletarProcessosApensados($dblIdProcedimentoAtual, $numIdUnidadeAtual, $strPalavrasPesquisa) {
      $objExpedirProcedimentoRN = new ExpedirProcedimentoRN();
      return $objExpedirProcedimentoRN->listarProcessosApensados($dblIdProcedimentoAtual, $numIdUnidadeAtual, $strPalavrasPesquisa);
  }


  public static function formatarHierarquia($ObjEstrutura)
    {
      $nome = "";

    if(isset($ObjEstrutura->hierarquia)) {

        $arrObjNivel = $ObjEstrutura->hierarquia->nivel;

        $siglasUnidades = array();
        $siglasUnidades[] = $ObjEstrutura->sigla;

      foreach($arrObjNivel as $key => $objNivel){
        $siglasUnidades[] = $objNivel->sigla  ;
      }

      for($i = 1; $i <= 3; $i++){
        if(isset($siglasUnidades[count($siglasUnidades) - 1])){
          unset($siglasUnidades[count($siglasUnidades) - 1]);
        }
      }

      foreach($siglasUnidades as $key => $nomeUnidade){
        if($key == (count($siglasUnidades) - 1)){
            $nome .= $nomeUnidade." ";
        }else{
            $nome .= $nomeUnidade." / ";
        }
      }

        $objNivel=current($arrObjNivel);

    }
      $dados=["nome"=>$nome,"objNivel"=>$objNivel];

      return $dados;

  }


  public static function getCaminhoIcone($imagem, $relPath = null) {
      $arrConfig = ConfiguracaoSEI::getInstance()->getValor('SEI', 'Modulos');
      $strModulo = $arrConfig['PENIntegracao'];

    if (InfraUtil::compararVersoes(SEI_VERSAO, ">=", "4.0.0")){

      switch ($imagem) {
        case 'imagens/consultar.gif':
            return '/infra_css/svg/consultar.svg';
            break;
        case 'imagens/alterar.gif':
            return '/infra_css/svg/alterar.svg';
            break;
        case 'imagens/excluir.gif':
            return '/infra_css/svg/excluir.svg';
            break;
        case '/pen_expedir_procedimento.gif':
            // return '/infra_css/svg/upload.svg';
            // return 'svg/arquivo_mapeamento_assunto.svg';
            return 'modulos/' . $strModulo . '/imagens/pen_expedir_procedimento.png';
            break;
        case '/pen_consultar_recibos.png':
            // return '/infra_css/svg/pesquisar.svg';
            return 'modulos/' . $strModulo . '/imagens/consultar_recibo.png';
            break;
        case '/pen_cancelar_tramite.gif':
            // return '/infra_css/svg/remover.svg';
            return 'modulos/' . $strModulo . '/imagens/pen_cancelar_envio.svg';
            break;
        case '/infra_js/arvore/plus.gif':
            return '/infra_css/svg/mais.svg';
            break;
        case '/infra_js/arvore/minus.gif':
            return '/infra_css/svg/menos.svg';
            break;
        case 'imagens/anexos.gif':
            return '/infra_css/imagens/anexos.gif';
            break;
        case 'imagens/sei_erro.png':
            return 'modulos/' . $strModulo . '/imagens/sei_erro.png';
          break;
        default:
          if($relPath==null){
                return $imagem;
          }
            return $relPath . $imagem;
            break;
      }
    }

    if($relPath==null){
        return $imagem;
    }

      return $relPath . $imagem;
  }

  public static function getCssCompatibilidadeSEI4($arquivo)
    {
    if (InfraUtil::compararVersoes(SEI_VERSAO, ">=", "4.0.0") && InfraUtil::compararVersoes(SEI_VERSAO, "<=", "4.0.1")) {

      switch ($arquivo) {
        case 'pen_procedimento_expedir.css':
            return 'pen_procedimento_expedir_sei4.css';
            break;

        default:
            return $arquivo;
            break;
      }
    }elseif (InfraUtil::compararVersoes(SEI_VERSAO, ">", "4.0.1")) {

      switch ($arquivo) {
        case 'pen_procedimento_expedir.css':
            return 'pen_procedimento_expedir_sei402.css';
              break;

        default:
            return $arquivo;
              break;
      }
    }

      return $arquivo;
  }

  /**
   * Monta a regra de negocio para o tramite.gov.br
   *
   * @param string $idUnidade
   * @param string $strCss
   * @param string $strHtml
   * @param string $strJsGlobal
   * @param string $strJsicializar
   * @return string
   */
  public static function montarRestricaoTramitaGovBr($idUnidade, &$strCss, &$strHtml, &$strJsGlobal, &$strJsInicializar) {
    try {
      $objPenUnidadeRestricaoDTO = new PenUnidadeRestricaoDTO();
      $objPenUnidadeRestricaoDTO->setNumIdUnidade($idUnidade);
      $objPenUnidadeRestricaoDTO->retTodos();

      $objPenUnidadeRestricaoRN = new PenUnidadeRestricaoRN();
      $arrObjPenUnidadeRestricaoDTO = $objPenUnidadeRestricaoRN->listar($objPenUnidadeRestricaoDTO);
      $items = array();
      $arrayKeys = array();
      $arrObjPenUnidadeDTO = array();
      $itemsUnidades = array();
      $hdnRepoEstruturas = array();
      $strHtmlRepoEstruturasUnidades = "";
      foreach ($arrObjPenUnidadeRestricaoDTO as $item) {
        if (!in_array($item->getNumIdUnidadeRestricao(), $arrayKeys)) {
          //IdUnidadeRestricao NomeUnidadeRestricao
          $arrayKeys[] = $item->getNumIdUnidadeRestricao();
          $items[] = array($item->getNumIdUnidadeRestricao(), $item->getStrNomeUnidadeRestricao());
          //$strHtmlRepoEstruturasUnidades .= '<input type="hidden" id="hdnRepoEstruturas' . $item->getNumIdUnidadeRestricao() . '" name="hdnRepoEstruturas' . $item->getNumIdUnidadeRestricao() . '" value="" />' . "\n";
          $hdnRepoEstruturas[$item->getNumIdUnidadeRestricao()] = array();
        }
        if ($item->getNumIdUnidadeRHRestricao() != null) {
          $arrObjPenUnidadeDTO[] = $item;
          $itemsUnidades[] = array($item->getNumIdUnidadeRHRestricao(), $item->getStrNomeUnidadeRHRestricao());
          $hdnRepoEstruturas[$item->getNumIdUnidadeRestricao()][] = $item->getNumIdUnidadeRHRestricao() . '�' . $item->getStrNomeUnidadeRHRestricao();
        }
      }
      foreach ($hdnRepoEstruturas as $key => $unidades) {
        $value = implode('�', $unidades);
        $strHtmlRepoEstruturasUnidades .= '<input type="hidden" id="hdnRepoEstruturas' . $key 
          . '" name="hdnRepoEstruturas' . $key . '" value="' . $value . '" />' . "\n";
      }
      $arrRepoEstruturasSelecionados = PaginaSEI::getInstance()->gerarItensLupa($items);
      $arrUnidadesSelecionadas = PaginaSEI::getInstance()->gerarItensLupa($itemsUnidades);
      $strItensSelRepoEstruturasRestricao = parent::montarSelectArrInfraDTO(null, null, null, $arrObjPenUnidadeRestricaoDTO, 'IdUnidadeRestricao', 'NomeUnidadeRestricao');
      $strItensSelUnidadesRestricao = parent::montarSelectArrInfraDTO(null, null, null, $arrObjPenUnidadeDTO, 'IdUnidadeRHRestricao', 'NomeUnidadeRHRestricao');

      $strCss = ''
        . ' #lblRepoEstruturas {position:absolute;left:0%;top:0%;width:20%;}'
        . ' #txtRepoEstruturas {position:absolute;left:0%;top:13%;width:19.5%;}'
        . ' #selRepoEstruturas {position:absolute;left:0%;top:29%;width:20%;}'
        . ' #divOpcoesRepoEstruturas {position:absolute;left:21%;top:29%;}'
        . ' '
        . ' #lblUnidades {position:absolute;left:25%;top:0%;}'
        . ' #txtUnidade {position:absolute;left:25%;top:13%;width:54.5%;}'
        . ' #selUnidades {position:absolute;left:25%;top:29%;width:55%;}'
        . ' #divOpcoesUnidades {position:absolute;left:81%;top:29%;}';

      $strJsGlobal = ''
        . ' var objLupaRepositoriosEstruturas = null;'
        . ' var objAutoCompletarOrgao = null;'
        . ' var objLupaUnidades = null;'
        . ' var objAutoCompletarUnidade = null;'
        . ' '
        . ' function trocarOrgaoRestricao(){'
        . ' document.getElementById(\'hdnUnidades\').value = document.getElementById(\'hdnRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value;'
        . ' objLupaUnidades.montar();'
        . ' };';

      $strJsInicializar = ''
        . ' objLupaRepositoriosEstruturas	= new infraLupaSelect(\'selRepoEstruturas\',\'hdnRepoEstruturas\',\'' . /*SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_selecionar&tipo_selecao=2&id_object=objLupaRepositoriosEstruturas') .*/ '\');'
        . ' objLupaRepositoriosEstruturas.processarRemocao = function(itens){'
        . '   let hdnRepoEstruturaAux = "";'
        . '   let hdnRepoEstruturaArr = "";'
        . '   let hdnRepoEstruturaOptValue = [];'
        . ' 	for(var i=0;i < itens.length;i++){'
        . '     hdnRepoEstruturaAux = document.getElementById(\'hdnRepoEstruturas\' + itens[i].value).value.split("�");'
        . '     for (var x = 0; x < hdnRepoEstruturaAux.length; x++) {'
        . '       hdnRepoEstruturaArr = hdnRepoEstruturaAux[x].split("�");'
        . '       hdnRepoEstruturaOptValue.push(hdnRepoEstruturaArr[0]);'
        . '     }'
        . ' 	  document.getElementById(\'hdnRepoEstruturas\' + itens[i].value).value = \'\';'
        . ' 	}'
        . '   var selectUnidades = document.getElementById("selUnidades");'
        . '   for (var x = 0; x < hdnRepoEstruturaOptValue.length; x++){'
        . '     for (var i = 0; i < selectUnidades.options.length; i++) {'
        . '       if (selectUnidades.options[i].value === hdnRepoEstruturaOptValue[x]) {'
        . '         selectUnidades.remove(i);'
        .'        }'
        . '     }'
        . '   }'
        . ' 	return true;'
        . ' };'
        . ' '
        . ' objLupaRepositoriosEstruturas.finalizarSelecao = function(){'
        . ' 	objLupaUnidades.limpar();'
        . ' };'
        . ' '
        . ' objAutoCompletarRepoEstruturas = new infraAjaxAutoCompletar(\'hdnIdRepoEstruturas\',\'txtRepoEstruturas\',\'' . SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=pen_listar_repositorios_estruturas_auto_completar') . '\');'
        . ' objAutoCompletarRepoEstruturas.limparCampo = true;'
        . ' objAutoCompletarRepoEstruturas.mostrarAviso = true;'
        . ' objAutoCompletarRepoEstruturas.tempoAviso = 10000000;'
        . ' objAutoCompletarRepoEstruturas.prepararExecucao = function(){'
        . ' 	return \'palavras_pesquisa=\'+document.getElementById(\'txtRepoEstruturas\').value;'
        . ' };'
        . ' '
        . ' objAutoCompletarRepoEstruturas.processarResultado = function(id,descricao,complemento){'
        . '   window.infraAvisoCancelar();'
        . ' 	if (id!=\'\'){ '
        . ' 	  objLupaRepositoriosEstruturas.adicionar(id,descricao,document.getElementById(\'txtRepoEstruturas\'));'
        . '     hdnRepoEst = document.getElementById("hdnRepoEstruturas" + id); '
        . '     if (hdnRepoEst == null) { '
        . '       html = document.createElement(\'input\'); '
        . '       html.type = \'hidden\'; '
        . '       html.id=\'hdnRepoEstruturas\' + id;'
        . '       html.name= \'hdnRepoEstruturas\'+ id;'
        . '       divRestricao = document.getElementById(\'divRestricao\');'
        . '       divRestricao.appendChild(html);'
        . '     };'
        . ' 	};'
        . ' };'
        . ' '
        . ' objLupaUnidades = new infraLupaSelect(\'selUnidades\',\'hdnUnidades\',\'\');'
        . ' objLupaUnidades.validarSelecionar = function(){'
        . ' 	if (document.getElementById(\'selOrgaos\').selectedIndex==-1){'
        . ' 	alert(\'Nenhum Reposit�rio de Estruturas selecionado.\');'
        . ' 	return false;'
        . ' 	}'
        . ' 	objLupaUnidades.url = document.getElementById(\'lnkRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value;'
        . ' 	return true;'
        . ' };'
        . ' '
        . ' objLupaUnidades.finalizarRemocao = function(options){'
        . '   let auxText = "";'
        . '   if (options.length > 1) {'
        . '     for (var i = 0; i < options.length; i++) {'
        . '       auxText +=  options[i].value + "�" + options[i].text + "�";'
        . '     }'
        . '     auxText = auxText.slice(0, -1);'
        . '   } else if (options.length == 1){'
        . '     auxText =  options[0].value + "�" + options[0].text;'
        . '   }'
        . '   let hdnRepoEstruturasAux = document.getElementById(\'hdnRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value;'
        . '   if (!hdnRepoEstruturasAux.includes(auxText)) {'
        . '     alert("A(s) unidade(s) selecionada(s) n�o pertence(m) �(s) estrutura(s) selecionada(s)");'
        . '     for (var i = 0; i < options.length; i++) {'
        . '       var option = document.createElement("option");'
        . '       option.text = options[i].text;'
        . '       option.value = options[i].value;'
        . '       document.getElementById("selUnidades").appendChild(option);'
        . '     }'
        . '   }'
        . '   hdnRepoEstruturasAux = hdnRepoEstruturasAux.replace(auxText, "");'
        . ' 	document.getElementById(\'hdnRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value = hdnRepoEstruturasAux;'
        . ' 	return true;'
        . ' };'
        . ' '
        . ' objLupaUnidades.finalizarSelecao = function(){'
        . ' 	document.getElementById(\'hdnRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value = document.getElementById(\'hdnUnidades\').value;'
        . ' };'
        . ' '
        . ' objAutoCompletarUnidade = new infraAjaxAutoCompletar(\'hdnIdUnidade\',\'txtUnidade\',\'' . SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=pen_unidade_auto_completar_expedir_procedimento') . '\');'
        . ' objAutoCompletarUnidade.limparCampo = true;'
        . ' objAutoCompletarUnidade.mostrarAviso = true;'
        . ' objAutoCompletarUnidade.tempoAviso = 10000000;'
        . ' objAutoCompletarUnidade.prepararExecucao = function(){'
        . ' 	if (document.getElementById(\'selRepoEstruturas\').selectedIndex==-1){'
        . ' 	alert(\'Nenhum Reposit�rio de Estruturas selecionado.\');'
        . ' 	return false;'
        . ' 	}'
        . ' 	return \'palavras_pesquisa=\'+document.getElementById(\'txtUnidade\').value+\'&id_repositorio=\'+document.getElementById(\'selRepoEstruturas\').value;'
        . ' };'
        . ' '
        . ' objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){'
        . '   window.infraAvisoCancelar();'
        . ' 	if (id!=\'\'){ '
        . ' 	objLupaUnidades.adicionar(id,descricao,document.getElementById(\'txtUnidade\'));'
        . '   repo = document.getElementById(\'hdnRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value;'
        . '   repo += (repo != \'\' ? "�" : "") + id + "�" + descricao;'
        . ' 	document.getElementById(\'hdnRepoEstruturas\' + document.getElementById(\'selRepoEstruturas\').value).value = repo;'
        . ' 	}'
        . ' };'
        . ' '
        . ' if (document.getElementById(\'selRepoEstruturas\').options.length){'
        . ' 	document.getElementById(\'selRepoEstruturas\').disabled = false;'
        . ' 	document.getElementById(\'selRepoEstruturas\').options[0].selected = true;'
        . ' 	trocarRepoEstruturasRestricao();'
        . ' };';

      $strHtml = ' <div id=\'divRestricao\' class=\'infraAreaDados\' style=\'height:16em;\'>
			  <label id="lblRepoEstruturas" for="selRepoEstruturas" class="infraLabelOpcional">Restringir as Estruturas Organizacionais:</label>
			  <input type="text" id="txtRepoEstruturas" name="txtRepoEstruturas" class="infraText" />
			  <input type="hidden" id="hdnIdRepoEstruturas" name="hdnIdRepoEstruturas" class="infraText" value="" />
			  <select id="selRepoEstruturas" name="selRepoEstruturas" size="6" multiple="multiple" class="infraSelect" onchange="trocarRepoEstruturasRestricao()" >
			  ' . $strItensSelRepoEstruturasRestricao . '
			  </select>
			  <div id="divOpcoesRepoEstruturas">
			  <img id="imgExcluirRepoEstruturas" onclick="objLupaRepositoriosEstruturas.remover();" src="' . PaginaSEI::getInstance()->getIconeRemover() . '" alt="Remover Estruturas Selecionados" title="Remover Estruturas Selecionadas" class="infraImgNormal"  />
			  </div>
			  <input type="hidden" id="hdnRepoEstruturas" name="hdnRepoEstruturas" value="'.$arrRepoEstruturasSelecionados.'" />
			  <label id="lblUnidades" for="selUnidades" class="infraLabelOpcional">Restringir �s Unidades:</label>
			  <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" />
			  <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
			  <select id="selUnidades" name="selUnidades" size="6" multiple="multiple" class="infraSelect" >
        ' . $strItensSelUnidadesRestricao . '
			  </select>
			  <div id="divOpcoesUnidades">
			  <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="' . PaginaSEI::getInstance()->getIconeRemover() . '" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg"  />
			  </div>
			  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="'.$arrUnidadesSelecionadas.'" />
			  ' . $strHtmlRepoEstruturasUnidades . '
			  </div>';

    } catch (Exception $e) {
      // n�o grava nada e n�o retorna objeto restri��o mapeamento de unidades
    }
  }
}
