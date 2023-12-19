<?php

/**
 * Testes de mapeamento de tipos de processo e relacionamento entre org�os
 * Excluir mapeamento entre org�os
 */
class MapeamentoTipoProcessoExcluirTest extends CenarioBaseTestCase
{
    public static $remetente;
    public static $penOrgaoExternoId;

    /**
     * @inheritdoc
     * @return void
     */
    function setUp(): void
    {
        parent::setUp();
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $penOrgaoExternoFixture = new PenOrgaoExternoFixture(CONTEXTO_ORGAO_A);
        self::$penOrgaoExternoId = $penOrgaoExternoFixture->cadastrar([
            'idRepositorioOrigem' => self::$remetente['ID_REP_ESTRUTURAS'],
            'repositorioEstruturasOrigem' => self::$remetente['REP_ESTRUTURAS'],
            'idOrgaoOrigem' => self::$remetente['ID_ESTRUTURA'],
            'nomeOrgaoOrigem' => self::$remetente['NOME_UNIDADE_ESTRUTURA'],
            'idOrgaoDestino' => self::$remetente['ID_UNIDADE_ORGAO_DESTINO'],
            'nomeOrgaoDestino' => self::$remetente['NOME_UNIDADE_ORGAO_DESTINO'],
        ]);
    }

    /**
     * Teste para excluir de mapeamento de org�o exteno
     *
     * @group MapeamentoOrgaoExterno
     *
     * @return void
     */
    public function test_excluir_mapeamento_orgao_externo()
    {
        // Configura��o do dados para teste do cen�rio
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $this->acessarSistema(self::$remetente['URL'], self::$remetente['SIGLA_UNIDADE'], self::$remetente['LOGIN'], self::$remetente['SENHA']);
        $this->paginaTramiteMapeamentoOrgaoExterno->navegarRelacionamentoEntreOrgaos();

        $this->paginaCadastroOrgaoExterno->selecionarExcluirMapOrgao(self::$penOrgaoExternoId);
        sleep(1);
        $mensagem = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Relacionamento entre �rg�os foi exclu�do com sucesso.'),
            $mensagem
        );
    }
}
