# License Server for Laravel

## 📦 Packagist

[![Latest Stable Version](https://img.shields.io/packagist/v/eluki/license-server.svg)](https://packagist.org/packages/eluki/license-server)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Encontre este pacote no [Packagist](https://packagist.org/packages/eluki/license-server) para facilitar a instalação e atualização via Composer.


Pacote Laravel para gerenciamento de licenças de software, permitindo criar, gerar e distribuir licenças de forma segura para clientes.

O **License Server** funciona como backend central de licenciamento, gerenciando todas as licenças emitidas para o License Client.

---

## 📦 Instalação

Instale via Composer:

```bash
composer require eluki/license-server
```

Publique os arquivos de configuração e migrations:

```bash
php artisan vendor:publish --provider="LicenseServer\ServerServiceProvider"
```

Execute as migrations do servidor:

```bash
php artisan migrate --path=database/migrations/vendor/license-server
```

---

## 🖥 Rotas do License Server

As rotas são protegidas e usadas para gerenciar licenças:

```php
Route::prefix('license-server')->group(function () {
    Route::get('/', [LicenseServerController::class, 'createForm'])->name('server.create');
    Route::get('/generate', [LicenseServerController::class, 'generateKeyForm'])->name('generate.keys');
    Route::get('/download-key/{tipo}', [LicenseServerController::class, 'downloadKey'])->name('keys.download');

    Route::post('/license/store', [LicenseServerController::class, 'store'])->name('server.store');
    Route::post('/generate-keys', [LicenseServerController::class, 'generateKeys'])->name('keys.generate');
});
```

> **Dica:** Proteja essas rotas com autenticação para evitar acesso não autorizado.

---

## ⚡ Uso Básico

- Acesse o formulário de criação de licença:  
  `http://localhost/your-app/license-server`
- Gere chaves para os clientes via formulário:
  - **Generate Key** → formulário de geração de chaves
  - **Download Key** → baixar chave pública/privada
- Armazene licenças no banco de dados usando o endpoint `/license/store`.

---

## 🔑 Geração e Download de Chaves

- **Chave Pública:** usada pelo License Client para validar licenças localmente.
- **Chave Privada:** usada pelo License Server para assinar licenças.

Exemplo de geração de chave via rota:

```php
Route::post('/generate-keys', [LicenseServerController::class, 'generateKeys'])->name('keys.generate');
```

Exemplo de download de chave:

```php
Route::get('/download-key/{tipo}', [LicenseServerController::class, 'downloadKey'])->name('keys.download');
```

---

## 🗂 Estrutura do Projeto

```
license-server/
├── src/
│   ├── Helpers
│   │   ├── globalVariables.php
│   │   ├── HardwareHelper.php
│   │   └── LicenseHelper.php
│   ├── Http
│   │   ├── Controllers
│   │   │   └── LicenseServerController.php
│   │   ├── Middleware
│   │   │   ├── LicenseCheck.php
│   │   │   └── CheckLicense.php
│   │   └── ServerServiceProvider.php
│   ├── Models
│   │   └── License.php
│   ├── Services
│   │   └── LicenseService.php
│   └── ClientServiceProvider.php
├── config/
│   └── license.php
├── database/
│   └── migrations
├── resources/
│   └── views
├── routes/
│   └── web.php
├── composer.json
└── README.md
```

> **Nota:** No seu arquivo `.env` deve ter a mesma chave `APP_KEY=base64:` para reconhecer a máquina e o projeto que pretende licenciar. Caso contrário, o pacote declara invasão de licença.

---

## 🔐 Boas Práticas de Segurança

- Proteja as rotas do License Server com autenticação (`auth`) ou roles administrativas.
- Nunca exponha a chave privada do servidor.
- Use HMAC e criptografia para validar a integridade das licenças.
- Registre tentativas de uso de licenças inválidas para auditoria.
- Limite acesso externo apenas a IPs confiáveis, se possível.

---

## 📦 Ecossistema
- [License Server](https://github.com/Kimossii/license-server) → Gerencia e emite licenças  
- [License Client](https://github.com/Kimossii/license-client) → Valida licenças no lado do cliente


## 📫 Contato

- **Email:** eluckimossi@gmail.com  
- **LinkedIn:** [eluki-baptista](https://www.linkedin.com/in/eluki-baptista/)  
- **GitHub:** [Kimossii](https://github.com/Kimossii)



# NOTA
> ### Importante:** O `APP_KEY` do projeto precisa ser consistente entre ambientes.  
> Essa chave é usada como parte da validação de licença.  
> Se não coincidir, o pacote considera a licença inválida.


> ⚠️ **Dica de Depuração:**  
> Em caso de qualquer erro ou exceção, verifique os arquivos de log em `logs/ClientLicense.log` ou `logs/license_server.log` para mais detalhes.


## 📖 Documentação Completa
Para detalhes mais avançados, acesse a documentação em PDF:

👉 [Abrir Documentação ](https://drive.google.com/file/d/1g2kGkqlq8zPtCLB_5i_NfsneAtvTwh3o/view?usp=drive_link)
























