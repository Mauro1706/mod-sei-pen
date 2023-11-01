<?php

/**
 * Testes de mapeamento de org�os externos
 */
class TramiteMapeamentoOrgaoExternoTest extends CenarioBaseTestCase
{
    public static $remetente;

    /**
     * Teste para cadastro de mapeamento de org�o exteno
     *
     * @group MapeamentoOrgaoExterno
     *
     * @return void
     */
    public function test_cadastrar_novo_mapeamento_orgao_externo()
    {
        // Configura��o do dados para teste do cen�rio
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $estrutura = 'RE CGPRO';
        $origem = 'Fabrica-org2';
        $destino = 'TESTE';

        $this->acessarSistema(self::$remetente['URL'], self::$remetente['SIGLA_UNIDADE'], self::$remetente['LOGIN'], self::$remetente['SENHA']);
        $this->navegarPara('pen_map_orgaos_externos_listar');
        $this->paginaCadastroOrgaoExterno->novoMapOrgao();
        $this->paginaCadastroOrgaoExterno->setarParametros($estrutura, $origem, $destino);
        $this->paginaCadastroOrgaoExterno->salvar();

        $orgaoOrigem = $this->paginaCadastroOrgaoExterno->buscarOrgaoOrigem($origem);
        $orgaoDestino = $this->paginaCadastroOrgaoExterno->buscarOrgaoDestino($destino);

        $this->assertNotNull($orgaoOrigem);
        $this->assertNotNull($orgaoDestino);
        sleep(1);
        $mensagem = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Relacionamento cadastrado com sucesso.'),
            $mensagem
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
        // Configura��o do dados para teste do cen�rio
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $estrutura = 'RE CGPRO';
        $origem = 'Fabrica-org2';
        $destino = 'TESTE';

        $this->acessarSistema(self::$remetente['URL'], self::$remetente['SIGLA_UNIDADE'], self::$remetente['LOGIN'], self::$remetente['SENHA']);
        $this->navegarPara('pen_map_orgaos_externos_listar');
        $this->paginaCadastroOrgaoExterno->novoMapOrgao();
        $this->paginaCadastroOrgaoExterno->setarParametros($estrutura, $origem, $destino);
        $this->paginaCadastroOrgaoExterno->salvar();

        sleep(1);
        $mensagem = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Cadastro de relacionamento entre �rg�os j� existente.'),
            $mensagem
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
        // Configura��o do dados para teste do cen�rio
        self::$remetente = $this->definirContextoTeste(CONTEXTO_ORGAO_A);

        $estrutura = 'RE CGPRO';
        $origem = 'Fabrica-org1';
        $destino = 'TESTE';

        $this->acessarSistema(self::$remetente['URL'], self::$remetente['SIGLA_UNIDADE'], self::$remetente['LOGIN'], self::$remetente['SENHA']);
        $this->navegarPara('pen_map_orgaos_externos_listar');

        $this->paginaCadastroOrgaoExterno->editarMapOrgao();
        $this->paginaCadastroOrgaoExterno->setarParametros($estrutura, $origem, $destino);
        $this->paginaCadastroOrgaoExterno->salvar();

        $orgaoOrigem = $this->paginaCadastroOrgaoExterno->buscarOrgaoOrigem($origem);
        $orgaoDestino = $this->paginaCadastroOrgaoExterno->buscarOrgaoDestino($destino);

        $this->assertNotNull($orgaoOrigem);
        $this->assertNotNull($orgaoDestino);
        sleep(1);
        $mensagem = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Relacionamento atualizado com sucesso.'),
            $mensagem
        );
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
        $this->navegarPara('pen_map_orgaos_externos_listar');

        $this->paginaCadastroOrgaoExterno->selecionarExcluirMapOrgao();
        sleep(1);
        $mensagem = $this->paginaCadastroOrgaoExterno->buscarMensagemAlerta();
        $this->assertStringContainsString(
            utf8_encode('Relacionamento entre �rg�os foi exclu�do com sucesso.'),
            $mensagem
        );
    }
}
