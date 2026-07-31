# FORK.md — guia deste fork do sped-da

> **Leia este arquivo antes de mexer no código ou atualizar a partir do upstream.**
> Ele existe porque o fork diverge bastante do projeto original, e várias das
> diferenças são intencionais. Um merge feito sem conhecê-las quebra layout,
> paginação ou cálculo de chave — já aconteceu.

Última revisão: **31/07/2026**

---

## 1. O que é este repositório

Fork de [`nfephp-org/sped-da`](https://github.com/nfephp-org/sped-da), a biblioteca
que gera os PDFs dos documentos auxiliares do SPED (DANFE, DACTE, DAMDFE, etc.).

| | |
|---|---|
| Origem | `https://github.com/guilherme-miranda/sped-da` (público) |
| Upstream | `https://github.com/nfephp-org/sped-da` |
| Branch principal | `master` |
| PHP | >= 7.4 (roda em 8.3) |
| Dependência crítica | `nfephp-org/sped-common: ^5.1.0` — precisa ser **>= v5.1.15** para CNPJ alfanumérico |

O fork existe porque o código da comunidade tinha bugs e limitações de layout que
precisavam de correção imediata. Com o tempo acumulou melhorias de paginação e
formatação que **não existem no upstream**.

### Quem consome

| Projeto | Constraint | Observação |
|---|---|---|
| `emissor-back` | `dev-master` | consumidor principal |
| `tef-pos` | `^1.0` | preso na tag `v1.0.1`, bem antiga |

⚠️ Existem **32 tags** publicadas (até v1.1.x). Criar uma tag nova faz o `tef-pos`
saltar de v1.0.1 para ela no próximo `composer update`. Se for criar tag, valide o
`tef-pos` antes.

### Classes efetivamente usadas em produção

`Danfe` (NF-e 55) · `Danfce` (NFC-e 65) · `Dacte` (CT-e 57) · `DacteOS` (CT-e OS 67) ·
`Damdfe` (MDF-e 58) · `Daevento` (NFe/CTe/MDFe)

**Não** são usadas: `DanfeVarejo`, `Danfse` (NFS-e), `Dabpe` (BP-e), `DanfeEtiqueta`.
Mudanças do upstream nesses arquivos podem ser aceitas sem medo.

### Como o emissor-back instancia

Reproduza estes parâmetros ao testar — **sem `printParameters()` o DACTE sai em branco**:

```php
$danfe   = new Danfe($xml);   $danfe->setDefaultFont('arial');   $danfe->printParameters('P','A4',6,6);
                              $danfe->descProdInfoComplemento = true;
$dacte   = new Dacte($xml);   $dacte->setDefaultFont('arial');   $dacte->printParameters('P','A4',5,6);
$dacteos = new DacteOS($xml); $dacteos->setDefaultFont('arial'); $dacteos->printParameters('P','A4',6,6);
$damdfe  = new Damdfe($xml);  $damdfe->setDefaultFont('arial');  $damdfe->printParameters('P','A4',5,5);
```

---

## 2. Flags com default invertido

O fork mudou o **valor padrão** destas propriedades. São diferenças silenciosas: um
merge que traga a linha do upstream reverte o comportamento sem gerar conflito.

| Flag | Fork | Upstream | Efeito |
|---|:---:|:---:|---|
| `powered` (`DaCommon`) | `false` | `true` | não imprime "Powered by NFePHP" |
| `exibirValorTributos` | `false` | `true` | não soma o texto de tributos aprox. |
| `exibirTextoFatura` | `true` | `false` | exibe o texto da fatura |
| `exibirEmailDestinatario` | `false` | `true` | não imprime e-mail do destinatário |
| `usarLinhaTracejadaSeparacaoItens` | `false` | `true` | separador **sólido** entre colunas |

---

## 3. Customizações do fork, por documento

### CT-e (`src/CTe/Dacte.php`) — o mais divergente

- **`calculoAlturaObservacao()`** — método que **só existe aqui**. Mede o texto de
  `xObs`, reduz a fonte de 7.5 até 5 e calcula a altura do bloco de observações,
  encolhendo `docsPrimeiraPag` para abrir espaço.
- **Paginação de documentos originários reescrita** — a tabela hardcoded de 11 faixas
  (`switch ($qtdeNFe) { case > 1044 ... }`) foi trocada por cálculo genérico com
  `docsPrimeiraPag` / `docsPagAdic`. Passou a contar também `infNF` e `idDocAntEle`.
- **Peso KG/TON automático** — se qualquer item tem `cUnid=01`, tudo sai em KG;
  senão, converte para TON.
- **Coluna `QTDE(LTS)`** dedicada (unidade 04). O MMBTU (unidade 05) foi portado do
  upstream para a coluna `QTDE(VOL)`, na linha de baixo.
- Margens configuráveis, QR Code com posição/tamanho parametrizáveis, layout em
  colunas dinâmicas (`$wa`/`$xa`) no lugar de offsets fixos.

### CT-e OS (`src/CTe/DacteOS.php`)

- Correção do **CSLL** — havia um `%` indevido num valor monetário.
- `getTagValue($this->infCteComp, "chCTe")` — o upstream lia a tag `"chave"`, que não
  existe no schema, então a referência vinha sempre vazia.
- Suporte a **CT-e Substituto** (`tpCTe == 3`) e cabeçalho impresso nos dois ramos.
- Watermark "SEM VALOR FISCAL" **desativado** (bloco comentado).
- **Fonte dinâmica nas observações** (7.5 → 4), com o bloco "Motorista:" reposicionado
  por `max(11.5, altura real do texto)`.

### MDF-e (`src/MDFe/Damdfe.php`)

- **CIOT com Nº e Responsável** (CNPJ/CPF), em tabela, dentro do bloco do modal
  rodoviário. O upstream imprime só os números concatenados, no cabeçalho.
  ⚠️ **As duas implementações ocupam regiões diferentes do arquivo** — ver §6.
- Cabeçalho **"Documentos"** acima de "Chaves de acesso"; `quantidadeChavesLayout`
  21 → 18 (retrato).
- Rodapé "Page X/{nb}" → **"Página X/{nb}"**, impresso só quando `flagDocs`.
- Data de emissão via `date(...strtotime())` no lugar de `explode('T', ...)`.
- **Fonte dinâmica nas observações** (8 → 4). A **altura do bloco é fixa** (`$h`);
  só a fonte se adapta.
- Título e conteúdo das observações em `textBox` separados — juntos, a linha
  divisória cortava a primeira linha do texto.
- Fontes maiores nos rótulos de seção (Vale Pedágio, Veículo, Condutor: 8 → 10pt).

### NF-e (`src/NFe/Danfe.php`)

- **FCP/FCPST não somam** a `vICMS`/`vST` nos totais (bloco comentado).
  → O PR #647 do upstream faz isso via flag; se for adotado, esta customização some.
- Rótulo **"V. TOT. TRIB." → "TRIBUTOS APROXIMADOS"**.
- **"FONE / FAX" → "FONE"** em destinatário, entrega e retirada.
- Telefone do emitente com máscara `(##)#####-####` / `(##)####-####`.
  ⚠️ Não trata prefixos não geográficos (0800/0300/0500/0900).
- `rodape()` **desativado** (chamadas comentadas).
- Veículos novos: bloco reposicionado antes do NCM, fonte 5pt, um campo por linha;
  `dadosItenVeiculoDANFE` desativado.
- **RESERVADO AO FISCO com fonte dinâmica** (7 → 4) e altura do bloco considerando
  também a coluna do fisco.
- `valor_original` só é impresso quando > 0.

### Eventos (`Daevento` de NFe, CTe e MDFe)

- `preg_replace('/^ID/', '', $id)` no lugar de `str_replace('ID', '', $id)` — o
  `str_replace` removeria um "ID" que aparecesse **dentro** do trecho alfanumérico
  da chave.

### `src/Legacy/Common.php`

- **`modulo11()` mantido**, com `ord($c) - 48` no lugar de `(int)$c`. Ver §4.
- `toDateTime()` retorna `false` para entrada vazia — `new \DateTime('')` devolve a
  data/hora atual, o que fazia a DANFE imprimir o horário da impressão em
  "HORA DA SAÍDA/ENTRADA".
- `toTimestamp()` com guarda para `null` (deprecation no PHP 8.1, erro no PHP 9).

---

## 4. CNPJ alfanumérico — o ponto mais delicado

Obrigatório desde 2026. A chave de acesso segue `^[0-9]{6}[A-Z0-9]{12}[0-9]{26}$`:
as 12 posições do meio (parte do CNPJ) **podem conter letras**.

### O que o fork faz

| Ponto | Solução |
|---|---|
| Extrair chave do `@Id` | `Keys::extractAccessKey()` |
| DV da **chave de acesso** (43 chars) | `Keys::verifyingDigit()` — do `sped-common` |
| DV da **chave de contingência** (35 chars) | **`$this->modulo11()` local** |
| Máscara CNPJ | `##.###.###/####-##` |
| Id de evento | `preg_replace('/^ID/', ...)` |

### ⚠️ Por que `modulo11()` continua existindo

O upstream **removeu** `modulo11()` e trocou as 4 chamadas por `Keys::verifyingDigit()`.
Isso está **errado** para a chave adicional de contingência:

```
Keys::verifyingDigit() exige exatamente 43 caracteres e retorna '' fora disso.
A chave de acesso tem 43 + DV.  ✅
A chave adicional de contingência tem 35 + DV.  ❌ → devolvia string vazia
```

Resultado no upstream: DANFE em contingência (`tpEmis` 2 ou 5) sai com a chave
adicional **sem dígito verificador**.

O `modulo11()` do fork usa `ord($c) - 48`, então aceita letras **e** funciona com
qualquer comprimento. Validado: com CNPJ alfanumérico, `(int)'A'` dá 0 e
`ord('A')-48` dá 17 — o DV muda de `2` (errado) para `6` (correto). Com CNPJ
numérico o resultado é idêntico ao anterior, então é retrocompatível.

> **Não aceite a remoção do `modulo11()` num merge futuro.** É a diferença mais
> importante deste fork em relação ao upstream.

### O que NÃO precisa de tratamento

- `substr($chave, 22, 3)` (série) e `substr($chave, 25, 9)` (número) — ficam na
  faixa sempre numérica; o CNPJ está em 6-19.
- Máscara: `formatField()` alinha à direita e ignora `#` sobrando, então
  `###.###.###/####-##` e `##.###.###/####-##` produzem **saída idêntica**. A
  correção de máscara do upstream é cosmética.
- `Pdf::code128()` já faz auto-switching A/B/C e renderiza chave com letras.

---

## 5. Como atualizar a partir do upstream

```bash
git remote add upstream https://github.com/nfephp-org/sped-da.git   # se ainda não existir
git fetch upstream
git checkout -b atualizacao-<data> master
git merge upstream/master
```

### Antes de começar

1. **Gere os PDFs de referência** com o código atual, usando XMLs reais de cada tipo
   de documento, e guarde. Sem isso não há como provar que nada regrediu.
2. Cubra estes cenários — são os que pegam regressão:
   - CT-e com muitos documentos originários (força paginação)
   - CT-e e MDF-e com observação longa (`xObs` até 2000, `infCpl` até 5000)
   - NF-e com muitos itens
   - **NF-e com 3 e 4 veículos novos** (`veicProd`) — ver §6
   - NF-e em **contingência** (`tpEmis` 2 ou 5)
   - MDF-e com `chNFe` **e** com `chCTe` vinculados

### Depois do merge

```bash
find src -name "*.php" -exec php -l {} \;    # lint
vendor/bin/phpunit -c phpunit.xml.dist       # testes
```

Regenere os PDFs e compare com os de referência. Vale comparar **pixel a pixel**
(`pdftoppm -r 100 -png` + `compare -metric AE`) e conferir a **contagem de páginas**
de cada documento — mudança de paginação é o sinal mais confiável de regressão.

⚠️ Os XMLs reais contêm dados de clientes e **este repositório é público**. Mantenha-os
fora do git.

---

## 6. Armadilhas conhecidas

### CIOT do MDF-e — mescla errado sem gerar conflito

Fork e upstream implementaram CIOT em **regiões diferentes** do `Damdfe.php`. O git
mescla os dois sem acusar conflito e o campo sai **impresso duas vezes**. Ao mesclar,
confirme que existe **uma só** ocorrência:

```bash
grep -n "CIOT" src/MDFe/Damdfe.php
```

A versão correta é a do fork: dentro do `if ($this->rodo)`, com Nº e Responsável.

### Altura do bloco de veículo novo — dois pontos que precisam concordar

Existem duas contas de altura para `veicProd`, e elas precisam bater **no valor e na
ordem**:

| Onde | O que faz |
|---|---|
| contagem de páginas (`monta()`, ~linha 691) | soma `55` **depois** de avaliar a quebra |
| impressão (`itens()`, ~linha 3474) | soma `55` **depois** de avaliar a quebra |

- Se o valor da contagem for **menor** que o da impressão → o total de páginas é
  subestimado e **os últimos itens somem sem aviso** (NF-e com 3 veículos perdia 1).
- Se a contagem somar **antes** da quebra → quebra cedo demais e o PDF ganha uma
  **página final repetida**.

As duas issues abertas no upstream (#532 e #466) são o mesmo defeito visto pelos dois
lados. Corrigir só um aspecto troca um bug pelo outro.

### `$totpag` é passado por valor

Em `itens($x, $y, &$nInicio, $hmax, $pag, $totpag, ...)` só `$nInicio` é referência.
O incremento de `$totpag` lá dentro **não volta** para `monta()`. O mecanismo de
recuperação (`if ($n == $totPag && qtdeItensProc < qtdeItens)`) vive **dentro** do
laço `for ($n = 2; $n <= $totPag; $n++)` — com `$totPag = 1` o laço não roda e a
recuperação não acontece.

### Flags de default invertido

Ver §2. Um merge pode trazer a linha do upstream e reverter o comportamento sem
gerar conflito. Confira as 5 depois de mesclar.

---

## 7. Estado em 31/07/2026

Merge do upstream até o commit `2a003cc` (23/07/2026), com 43 commits da comunidade
e autoria preservada. Sobre ele, 4 commits do fork:

| Commit | O quê |
|---|---|
| `35bc2b8` | fonte dinâmica nas observações (MDF-e, CT-e OS, DANFE) |
| `f3488c2` | remoção do `DacteV4` |
| `4642a2a` | guarda de `null` em `toTimestamp` (issue #537) |
| `2bd8bbc` | paginação de veículos novos (issues #532 e #466) |

Para separar o que é nosso do que é da comunidade:

```bash
git diff 7546997 master     # só as customizações do fork
git show 7546997            # só o merge da comunidade
```

### Bugs do upstream corrigidos neste fork

1. `Keys::verifyingDigit()` na chave de contingência (§4)
2. `new \DateTime('')` devolvendo a hora atual
3. "Peso Total" some no MDF-e do modal aquaviário
4. Perda de itens / página repetida com múltiplos veículos (§6)
5. `preg_match(null)` em `toTimestamp`

### O `DacteV4` foi removido

Existiu de fev/2024 a jul/2026. Nunca esteve no upstream, nunca foi usado por
nenhum projeto e estava quebrado (`canhoto()` lançava
`Cannot access offset of type string on string`). O `Dacte` comum imprime CT-e 4.00
normalmente. Está no histórico (`git show f3488c2^:src/CTe/DacteV4.php`) se
alguém precisar.

### Pendências conhecidas

| Item | Situação |
|---|---|
| Upstream tem 2 commits novos (PR #667) | tocam só `Danfse.php`, que não usamos |
| Teste de regressão dos veículos novos | não existe — hoje só há validação manual |
| PR #647 (flag de FCP) | tornaria a customização do FCP desnecessária |
| PR #649 (IBS/CBS, Reforma Tributária) | obrigatório em produção desde 03/08/2026; o PR embute paginação hardcoded que conflita com a nossa |
| PR #609 (sobreposição de itens) | pode afetar o fork; não investigado |
| Issue #568 (timeout com muitas tags `RASTRO`) | não investigado |
| `infAdFisco` não é impresso no CT-e OS | `DacteOS.php:1552` concatena num `$texto` já consumido pelo `explode()` acima |
| Telefone não geográfico (0800 etc.) | máscara do `Danfe.php` não trata |
