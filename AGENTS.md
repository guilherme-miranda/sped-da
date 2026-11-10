# AGENTS.md

Este repositório é um **fork** de [`nfephp-org/sped-da`](https://github.com/nfephp-org/sped-da)
que diverge de propósito. Antes de mexer no código ou sincronizar, leia o
[FORK.md](FORK.md): ele é a fonte única das customizações (§2–§4), das armadilhas
(§6) e das decisões já tomadas sobre o upstream (§7).

## Sincronizar com o upstream

A sincronização é **seletiva**: cada commit do upstream é avaliado e só entra o que
vale para o fork. Não buscamos paridade com o upstream, por isso não se faz
`git merge upstream/master`.

1. **Levantar o intervalo.** `git fetch upstream` e liste os commits depois do
   "último commit avaliado" registrado no FORK.md §7:
   `git log --reverse --stat --format='== %h %ad %an %s' --date=short <último>..upstream/master`.
   Pronto quando cada commit do intervalo tem a lista de arquivos que toca.
2. **Classificar cada commit** como *trazer*, *trazer adaptado*, *rejeitar* ou
   *indiferente* (toca só classes que não usamos — FORK.md §1). Para cada um:
   confronte o diff com as customizações e as flags de default invertido do FORK.md,
   e confirme **no nosso código** se o bug que ele corrige existe aqui. Commits
   revertidos depois, no próprio upstream, contam como rejeitados. Pronto quando
   todo commit tem classe e motivo.
3. **Apresentar a análise ao usuário e esperar aprovação.** Só aplique o que ele
   aprovar.
4. **Aplicar** cada commit aprovado com `git cherry-pick -n <sha>` e adaptar o que for
   preciso. Um commit por tema, com a mensagem indicando que é sincronização do
   upstream e citando o commit/PR de origem. Testes do upstream instanciam
   sem `printParameters()`, e no fork isso gera PDF em branco: ajuste-os para instanciar
   como o emissor-back (FORK.md §1).
5. **Validar** com lint e phpunit (FORK.md §5). Se tocar layout ou paginação, compare
   os PDFs de referência antes/depois.
6. **Registrar** no FORK.md §7: o novo "último commit avaliado" e uma linha por
   commit com decisão e motivo, inclusive os rejeitados. Pronto quando a próxima
   sincronização começa do passo 1 sem reavaliar nada.

## Mudanças que um patch reverte em silêncio

Depois de aplicar qualquer coisa do upstream, confira:

- as 5 flags de default invertido (FORK.md §2);
- `modulo11()` presente em `src/Legacy/Common.php` (FORK.md §4);
- uma só ocorrência de CIOT em `src/MDFe/Damdfe.php` (FORK.md §6);
- `DanfeSimples.php`: patches do upstream não se aplicam, reimplemente em DOM (FORK.md §6).
