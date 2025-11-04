# NOTAS DE VERSÃO MOD-SEI-PEN (versão 3.8.3)

Este documento descreve as principais mudanças aplicadas nesta versão do módulo de integração do SEI com o TRAMITA.GOV.BR.

As melhorias entregues em cada uma das versões são cumulativas, ou seja, contêm todas as implementações realizadas em versões anteriores.

## Compatibilidade de versões
* O módulo é compatível com as seguintes versões do **SEI**:
  * 4.0.0 até 4.0.12,
  * 4.1.1, 4.1.2, 4.1.3, 4.1.4 e 4.1.5
    
Para maiores informações sobre os procedimentos de instalação ou atualização, acesse os seguintes documentos localizados no pacote de distribuição mod-sei-pen-VERSAO.zip:
> Atenção: É impreterível seguir rigorosamente o disposto no README.md do Módulo para instalação ou atualização com sucesso.
* **INSTALACAO.md** - Procedimento de instalação e configuração do módulo
* **ATUALIZACAO.md** - Procedimento específicos para atualização de uma versão anterior

### Lista de melhorias e correções de problemas

Todas as atualizações podem incluir itens referentes à segurança, requisito em permanente monitoramento e evolução, motivo pelo qual a atualização com a maior brevidade possível é sempre recomendada.

#### **CORREÇÕES DE PROBLEMAS**

#### Nesta versão, foram corrigidos os seguintes erros:

* **Correção da paginação da tela Processos em Tramitação Externa - Versão 3.8.3** A paginação da tela Processos em Tramitação Externa (Menu -> Tramita GOV.BR -> Processos em Tramitação Externa), foi corrigida. [#786](https://github.com/pengovbr/mod-sei-pen/issues/786);

* **AGU - Evolução do endpoint para o último trâmite:** Permite que o órgão destinatário solicite ao rementente a reprodução do último trâmite com sucesso para reenvio dos componentes digitais. [#872](https://github.com/pengovbr/mod-sei-pen/issues/872);

* **Erro ao cancelar documento que contenha multiplos componentes digitais:** Corrige erro de cancelar múltiplos componentes digitais. O erro em tela era: "Falha no envio externo do processo. Erro: 0047 - Inconsistência identificada no documento de ordem '1' do processo tramitado por este NRE, '0000025656262025', com protocolo '13990.811212/2020-00': hash de ao menos um componente digital não confere ". Agora permite o cancelamento do mesmo. [#882](https://github.com/pengovbr/mod-sei-pen/issues/882);

* **Recuperação do padrão de mensagem de erro exibida na versão 3.8.1** Exibe detalhamento dos erros ao tentar enviar um trâmite. [#914](https://github.com/pengovbr/mod-sei-pen/issues/914);

* **Erro que permite que o destinarário receba documentos sem anexo** Durante uma tramitação, caso ocorra um erro php, não é realizado um rollback da transação, de modo que parte dos componentes digitais cheguem no destinatário e parte não chega. [#956](https://github.com/pengovbr/mod-sei-pen/issues/956);

Para obter informações detalhadas sobre cada um dos passos de atualização, vide arquivo **ATUALIZACAO.md**.

#### Instruções

1. Baixar a última versão do módulo de instalação do sistema (arquivo `mod-sei-pen-[VERSÃO].zip`) localizado na página de [Releases do projeto MOD-SEI-PEN](https://github.com/spbgovbr/mod-sei-pen/releases), seção **Assets**. _Somente usuários autorizados previamente pela Coordenação-Geral do Processo Eletrônico Nacional podem ter acesso às versões._

2. Fazer backup dos diretórios "sei", "sip" e "infra" do servidor web;

3. Descompactar o pacote de instalação `mod-sei-pen-[VERSÃO].zip`;

4. Copiar os diretórios descompactados "sei", "sip" para os servidores, sobrescrevendo os arquivos existentes;

5. Executar o script de instalação/atualização `sei_atualizar_versao_modulo_pen.php` do módulo para o SEI localizado no diretório `sei/scripts/mod-pen/`

```bash
php -c /etc/php.ini <DIRETÓRIO RAIZ DE INSTALAÇÃO DO SEI E SIP>/sei/scripts/mod-pen/sei_atualizar_versao_modulo_pen.php
```

6. Executar o script de instalação/atualização `sip_atualizar_versao_modulo_pen.php` do módulo para o SIP localizado no diretório `sip/scripts/mod-pen/`

```bash
php -c /etc/php.ini <DIRETÓRIO RAIZ DE INSTALAÇÃO DO SEI E SIP>/sip/scripts/mod-pen/sip_atualizar_versao_modulo_pen.php
```

7. Verificar a correta instalação e configuração do módulo

Para executar a verificação, execute o script ```verifica_instalacao_modulo_pen.php``` localizado no diretório de scripts do SEI ```<DIRETÓRIO RAIZ DE INSTALAÇÃO DO SEI E SIP>/sei/scripts/mod-pen/```.

```bash
$ php -c /etc/php.ini <DIRETÓRIO RAIZ DE INSTALAÇÃO DO SEI E SIP>/sei/scripts/mod-pen/verifica_instalacao_modulo_pen.php
``` 
