<?php

/**
 * Testes de mapeamento de org�os exportar tipos de processos
 */
class MapeamentoTipoProcessoRelacionamentoOrgaosExportar extends CenarioBaseTestCase
{
    public static $remetente;

    /**
     * Teste de exporta��o de tipos de processos
     *
     * @return void
     */
    public function test_exportar_tipos_de_processo()
    {
        // Configura��o do dados para teste do cen�rio
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $this->acessarSistema(
            self::$remetente['URL'],
            self::$remetente['SIGLA_UNIDADE'],
            self::$remetente['LOGIN'],
            self::$remetente['SENHA']
        );
        $this->navegarPara('pen_map_orgaos_exportar_tipos_processos');

        $this->paginaExportarTiposProcesso->selecionarParaExportar();
        $this->assertEquals(
            $this->paginaExportarTiposProcesso->verificarExisteBotao('btnExportarModal'),
            'Exportar'
        );
        $this->assertEquals(
            $this->paginaExportarTiposProcesso->verificarExisteBotao('btnFecharModal'),
            'Fechar'
        );
        $this->paginaExportarTiposProcesso->verificarQuantidadeDeLinhasSelecionadas();
        $this->paginaExportarTiposProcesso->btnExportar();
    }

    /**
     * Teste para pesquisar tipos de processos
     *
     * @return void
     */
    public function test_pesquisar_tipos_de_processos()
    {
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $this->acessarSistema(
            self::$remetente['URL'],
            self::$remetente['SIGLA_UNIDADE'],
            self::$remetente['LOGIN'],
            self::$remetente['SENHA']
        );
        $this->navegarPara('pen_map_orgaos_exportar_tipos_processos');

        $this->assertTrue($this->paginaExportarTiposProcesso->selecionarPesquisaSinalizacao());
    }
}