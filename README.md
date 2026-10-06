# Lynx App Center

Aplicação PHP para gestão de campanhas, contactos, listas, segmentos e acessos.

## Estrutura

| Pasta | Responsabilidade |
|---|---|
| `app/Controllers` | Fluxos HTTP de Auth, AppCenter, Password Manager, Público e dos dois módulos de campanhas |
| `app/Models` | Consultas e gravações na base de dados |
| `app/Services` | Email, encriptação e importação/exportação de contactos |
| `app/Core` | Arranque, sessão, autenticação, router, ligação partilhada à BD e funções comuns |
| `config/routes.php` | Mapa explícito de URLs para métodos dos controladores |
| `views` | Ecrãs por módulo, layout comum e componentes partilhados |
| `assets/js`, `assets/css`, `assets/img` | Scripts, estilos de origem e imagens da aplicação |
| `dist` | CSS compilado pelo Tailwind; gerado por `npm run build` |
| `public/uploads` | Ficheiros carregados pelos utilizadores; dados locais, fora do Git |
| `database/migrations`, `database/seeds` | Alterações de esquema e dados de demonstração |
| `storage/logs` | Logs de execução e ficheiros antigos de debug; fora do Git |
| `scripts` | Verificações e ferramentas de manutenção |
| `tests` | Verificações estruturais e servidor isolado para testes HTTP |

`index.php` é o ponto de entrada HTTP. O Apache encaminha os pedidos através de `.htaccess`. A raiz pública continua a ser esta pasta, sem necessidade de mudar a configuração do XAMPP. As pastas de código, configuração, logs e testes têm acesso HTTP bloqueado.

## Módulos e compatibilidade

- **Auth:** login, registo, recuperação e redefinição de password.
- **AppCenter:** painel das aplicações. `/home`, `/home-center` e `/app-center` usam o mesmo controlador e ecrã.
- **Password Manager:** `/pm-home` mantém-se disponível. As operações estão em `/password-manager/guardar`, `/carregar`, `/senha` e `/cards`; os endereços antigos em `/controllers/...` continuam a funcionar como aliases.
- **Público:** `/publico`, com views separadas para visão geral, contactos, segmentos, listas e modais. As APIs existentes em `/api/*.php` mantêm os endereços e formatos de resposta.
- **Campanhas de email:** `/campanhas`; edição em `/campanhas/editar?id=...`. O endereço antigo de edição continua disponível.
- **Campanhas de conteúdo:** `/campanhas.php?acao=...` e `/campanhas-conteudo`. É um módulo distinto das campanhas de email.
- **Tracking:** `/track/open.php`, `/track/click.php` e `/track/qrcode.php` preservam os endereços dos links enviados.

Os dados encriptados do Password Manager mantêm a chave e o formato existentes. A reorganização não requer migração de passwords nem alterações às tabelas.

`index1.html` permanece na raiz como modelo para a criação de uma campanha. As imagens que utiliza continuam em `assets/img`.

## Instalação e estilos

1. Instalar as dependências PHP com `composer install`.
2. Configurar o ficheiro local `.env` com as chaves de `.env.example`. A configuração local existente é preservada.
3. Instalar as dependências JavaScript com `npm ci`.
4. Compilar os estilos com `npm run build`, também necessário após instalar uma nova cópia do projeto.

`npm run dev` recompila os estilos durante o desenvolvimento. `vendor`, `node_modules`, configuração local, uploads e logs não pertencem ao repositório.

As pastas `dashboard`, `xampp`, `webalizer` e `img` pertencem à instalação local do XAMPP; não fazem parte da aplicação.

## Verificação

`npm test` verifica sintaxe PHP/JavaScript, rotas, ficheiros das views, assets e compatibilidade da encriptação. No Windows, usa o PHP do PATH ou `C:\xampp\php\php.exe`.

`npm run check:db` também verifica as tabelas da base indicada no `.env` e executa a consulta usada no login, sem alterar dados. A aplicação lê a configuração local; `.env.example` é apenas um modelo. A marca `D` do `.ENV` no Git indica que deixou de estar versionado, mesmo continuando no disco.

Para os testes HTTP, iniciar um servidor de teste dedicado:

```powershell
C:\xampp\php\php.exe -S 127.0.0.1:8087 -t . tests/fixture-router.php
node tests/http-structure.mjs
```

O Password Manager usa exclusivamente dados fictícios guardados em `.cache/fixture-accesses.json`, reiniciados automaticamente pelo teste. Os restantes testes fazem consultas de leitura à BD configurada. Não enviam campanhas, não inserem contactos e não alteram listas. Parar o servidor após o teste.

O teste SMTP é uma ferramenta CLI com destinatário explícito: `php scripts/test-smtp.php destinatario@example.com`. Só executar quando se pretende enviar um email real.

## Deployment no Railway

O `Dockerfile` instala PHP 8.3, Apache, Composer e as extensões necessárias. O Tailwind é compilado com Node durante o build; `vendor` e `dist/output.css` são gerados na imagem. Não carregar `.env`, dependências locais ou dumps SQL para o Git.

1. No projeto Railway que já contém o MySQL, adicionar um serviço a partir do repositório GitHub `Synth-Axis/campaignmanager`, branch `main`, com a raiz `/`.
2. Nas **Variables do serviço da aplicação**, adicionar as referências abaixo. `MySQL` deve corresponder ao nome do serviço de base de dados existente.

   ```dotenv
   APP_ENV=production
   PORT=8080
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_NAME=${{MySQL.MYSQLDATABASE}}
   DB_USER=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   ```

3. Criar um volume **no serviço da aplicação**, montado em `/var/www/html/public/uploads`, para guardar as imagens carregadas nas campanhas entre deployments. O volume existente no MySQL continua dedicado à base de dados.
4. Fazer Deploy. O `railway.json` define o comando de arranque e o healthcheck `/health`, que verifica a ligação à tabela `users` importada. Não configurar um comando de migração: a base já foi importada.
5. Em **Settings → Networking**, gerar o domínio da aplicação com a porta `8080`. `ADDRESS` é obtido automaticamente do `RAILWAY_PUBLIC_DOMAIN`; para um domínio personalizado, definir `ADDRESS=https://o-teu-dominio`.
6. Abrir `/login` nesse domínio e usar as credenciais da aplicação importadas da instalação local. As credenciais MySQL servem apenas para a ligação à base de dados.

As variáveis do processo têm prioridade sobre o `.env` local. Também são aceites os nomes nativos `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER` e `MYSQLPASSWORD`. O XAMPP continua a usar o ficheiro local existente.

O script de arranque escuta na porta `PORT`, ativa apenas o MPM `prefork` do Apache e ajusta as permissões do volume de uploads. Em produção, erros são enviados para os logs do Railway e os cookies de sessão usam HTTPS. Manter uma réplica: as sessões são guardadas no disco do container e terminam quando este é substituído.

Para recuperação de password e envio de campanhas, configurar também `PHPMAILER_HOST`, `PHPMAILER_PORT`, `PHPMAILER_USERNAME` e `PHPMAILER_PASSWORD`. `PHPMAILER_FROM_EMAIL` deve ser um remetente autorizado pelo serviço SMTP; `PHPMAILER_FROM_NAME` define o nome apresentado. Sem estas credenciais, o envio de emails não está configurado. Não enviar mensagens de teste sem destinatário explícito.

Os uploads locais não são copiados com o dump SQL: se existirem imagens em `public/uploads`, transferi-las para o volume da aplicação. `index1.html` continua intacto como modelo de campanha; URLs absolutos escritos nesse modelo devem ser revistos quando for usado para uma campanha em produção.

Para verificar o build com Docker instalado:

```sh
docker build -t lynx-app-center .
```

Referências: [Dockerfiles no Railway](https://docs.railway.com/builds/dockerfiles), [variáveis e referências](https://docs.railway.com/variables), [healthchecks](https://docs.railway.com/deployments/healthchecks).
