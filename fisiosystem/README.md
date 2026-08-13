# FisioSystem — Instruções e Resumo de Segurança

Resumo rápido
- Projeto PHP simples para gerenciar pacientes, avaliações e evoluções.
- Proteções adicionadas: autenticação básica, CSRF para formulários, tratamento genérico de erro de conexão, sessões.

Como rodar localmente
1. Importe o banco (MySQL):

```bash
mysql -u root -p fisiosystem < banco/fisiosystem.sql
```

2. Inicie servidor PHP no diretório do projeto:

```bash
php -S localhost:8000 -t .
# Acesse: http://localhost:8000/login.php
```

Credenciais de exemplo (trocar imediatamente)
- Usuário: `admin`
- Senha: `admin123`

Arquivos novos/alterados importantes
- `config/auth.php` — autenticação de sessão (usuário de exemplo).
- `login.php` / `logout.php` — páginas de autenticação.
- `config/csrf.php` — helpers CSRF.
- `config/conexao.php` — agora registra erros em log e exibe mensagem genérica.
- Formulários atualizados: `cadastrar_paciente.php`, `avaliacao.php`, `evolucao.php`, `kanban.php` (incluem token CSRF e verificações).

Resumo de achados e recomendações

1) Autenticação e gerenciamento de usuários
- O sistema agora exige login para páginas sensíveis. Atualmente usa um usuário definido em `config/auth.php`.
- Recomendo migrar usuários para uma tabela `usuarios` no banco com campos (username, password_hash, role) e adicionar gerenciamento (criar/alterar senha, revogar).

2) Credenciais e ambiente
- Não use `root` sem senha em produção. Mova credenciais sensíveis para variáveis de ambiente e um arquivo `.env` fora do repositório.
- Use HTTPS em produção e marque cookies de sessão como `secure`.

3) CSRF e sessões
- CSRF adicionado nos formulários principais. Garanta que qualquer requisição AJAX inclua o token.

4) Validação e saneamento
- Já há uso de `htmlspecialchars()` em saídas e `prepare`/`bind_param` para evitar SQL injection.
- Adicionar validações de servidor para CPF, telefone e datas; normalizar entradas; e tratar inserções de NULL explicitamente quando aplicável.

5) Logs e tratamento de erros
- Erros de conexão agora são logados. Sugiro configurar logs rotativos e um nível de log apropriado (info/warn/error).

6) Outras recomendações
- Implementar força de senha e política de bloqueio/limitação de tentativas de login.
- Habilitar CSP/headers de segurança (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Content-Security-Policy`).
- Revisar permissões de arquivos no servidor e desabilitar listagem de diretórios.

Próximos passos que posso aplicar
- Migrar usuários para DB + telas de administração.
- Implementar alteração de senha e forçar alteração do usuário `admin` inicial.
- Adicionar validação de CPF/telefone nos formulários.
- Gerar um script de criação de usuário inicial seguro.

Se quiser, aplico agora a migração mínima de usuários (tabela `usuarios` + criar usuário admin seguro) ou gero o script `README` para deploy em produção. Qual prefere? 
