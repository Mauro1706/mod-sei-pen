<?php

/**
 * Testes de mapeamento de tipos de processo e relacionamento entre org�os
 * Cadastro mapeamento de org�os
 */
class MapeamentoTipoProcessoRelacionamentoOrgaosCadastroTest extends CenarioBaseTestCase
{
    public static $remetente;
    public static $remetenteB;
    
    /**
     * Teste de cadastro de novo mapeamento entre ogr�os
     *
     * @return void
     */
    public function test_cadastrar_novo_mapeamento_orgao_externo()
    {
        // Configura��o do dados para teste do cen�rio
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);
        $this->acessarSistema(
            self::$remetente['URL'],
            self::$remetente['SIGLA_UNIDADE'],
            self::$remetente['LOGIN'],
            self::$remetente['SENHA']
        );
        $this->paginaCadastroOrgaoExterno->navegarCadastroOrgaoExterno();
        $this->paginaCadastroOrgaoExterno->novoMapOrgao();
        $this->paginaCadastroOrgaoExterno->setarParametros(
            self::$remetente['REP_ESTRUTURAS'],
            self::$remetente['NOME_UNIDADE_ESTRUTURA'],
            self::$remetente['NOME_UNIDADE_ORGAO_DESTINO']
        );
        $this->paginaCadastroOrgaoExterno->salvar();

        $orgaoOrigem = $this->paginaCadastroOrgaoExterno->buscarOrgaoOrigem(self::$remetente['NOME_UNIDADE_ESTRUTURA']);
        $orgaoDestino = $this->paginaCadastroOrgaoExterno->buscarOrgaoDestino(self::$remetente['NOME_UNIDADE_ORGAO_DESTINO']);

        $this->assertNotNull($orgaoOrigem);
        $this->assertNotNull($orgaoDestino);
        sleep(1);
        $mensagemRetornoAlert = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Relacionamento cadastrado com sucesso.'),
            $mensagemRetornoAlert
        );
    }

    /**
     * Teste para cadastro de mapeamento de org�o exteno j� existente
     *
     * @group MapeamentoOrgaoExterno
     *
     * @return void
     */
    public function test_cadastrar_mapeamento_orgao_externo_ja_cadastrado()
    {
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);
        $this->acessarSistema(
            self::$remetente['URL'],
            self::$remetente['SIGLA_UNIDADE'],
            self::$remetente['LOGIN'],
            self::$remetente['SENHA']
        );
        $this->paginaCadastroOrgaoExterno->navegarCadastroOrgaoExterno();
        $this->paginaCadastroOrgaoExterno->novoMapOrgao();
        $this->paginaCadastroOrgaoExterno->setarParametros(
            self::$remetente['REP_ESTRUTURAS'],
            self::$remetente['NOME_UNIDADE_ESTRUTURA'],
            self::$remetente['NOME_UNIDADE_ORGAO_DESTINO']
        );
        $this->paginaCadastroOrgaoExterno->salvar();
        sleep(1);
        $mensagemRetornoAlert = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Cadastro de relacionamento entre �rg�os j� existente.'),
            $mensagemRetornoAlert
        );
    }

    /**
     * Teste para editar mapeamento de org�o exteno
     *
     * @group MapeamentoOrgaoExterno
     *
     * @return void
     */
    public function test_editar_mapeamento_orgao_externo()
    {
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);
        self::$remetenteB = $this->definirContextoTeste(CONTEXTO_ORGAO_B);
        $this->acessarSistema(
            self::$remetente['URL'],
            self::$remetente['SIGLA_UNIDADE'],
            self::$remetente['LOGIN'],
            self::$remetente['SENHA']
        );
        $this->paginaCadastroOrgaoExterno->navegarCadastroOrgaoExterno();

        $this->paginaCadastroOrgaoExterno->editarMapOrgao();
        $this->paginaCadastroOrgaoExterno->setarParametros(
            self::$remetenteB['REP_ESTRUTURAS'],
            self::$remetenteB['NOME_UNIDADE_ESTRUTURA'],
            self::$remetente['NOME_UNIDADE_ORGAO_DESTINO']
        );
        $this->paginaCadastroOrgaoExterno->salvar();

        $orgaoOrigem = $this->paginaCadastroOrgaoExterno->buscarOrgaoOrigem(self::$remetenteB['NOME_UNIDADE_ESTRUTURA']);
        $orgaoDestino = $this->paginaCadastroOrgaoExterno->buscarOrgaoDestino(self::$remetente['NOME_UNIDADE_ORGAO_DESTINO']);

        $this->assertNotNull($orgaoOrigem);
        $this->assertNotNull($orgaoDestino);
        sleep(1);
        $mensagemRetornoAlert = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Relacionamento atualizado com sucesso.'),
            $mensagemRetornoAlert
        );
    }

    function tearDown(): void
    {
        parent::tearDown();
    }
}