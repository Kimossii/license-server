<?php
namespace LicenseServer\Services;
//use App\Helpers\LicenseHelper;
use LicenseServer\Helpers\LicenseHelper;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
//use App\Models\ServerLicence;
use LicenseServer\Models\ServerLicence;
use function LicenseServer\Helpers\write_ServerLicense_log;
use LicenseServer\Helpers\GlobalVariables;

// Carrega os helpers globais do pacote
//require_once base_path('vendor/eluki/license-server/src/Helpers/globalVariables.php');
//require_once base_path('vendor/eluki/license-server/src/Helpers/LicenseHelper.php');


class GeneratorLicenseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    /*private function getStorageKeys(): string
    {
         return storage_path('keys');
    }*/
    private function extraSecret()
    {
        return LicenseHelper::getSegredoExtra();
    }
    private function getServerFileKeyPrivate()
    {
        //return getFilePrivateKey();
        return getServerStorageKeys() . '/private.pem';
    }

    public function downloadKey(string $tipo)
    {
        $keysPath = getServerStorageKeys();

        $file = $tipo === 'public'
            ? $keysPath . '/public.pem'
            : $keysPath . '/private.pem';

        // Verifica se o arquivo existe
        if (!file_exists($file)) {
            write_ServerLicense_log("Arquivo {$tipo} não encontrado para download.", 'warning');
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Arquivo {$tipo} não encontrado.");
        }

        // Log do início do download
        write_ServerLicense_log("Download iniciado para o arquivo {$tipo}.", 'info');

        // Retorna o download
        return Response::download($file);
    }


    public function checkKeysExist(): array
    {
        $keysPath = getServerStorageKeys();

        if (!File::exists($keysPath)) {
            write_ServerLicense_log(
                "Chave pública ou privada não existe. Gera as chaves primeiro e partilha com o cliente.",
                'warning'
            );

            return [
                'success' => false,
                'message' => 'Chave pública ou privada não existe. Gera as chaves primeiro e partilha com o cliente.'
            ];
        }

        write_ServerLicense_log("Chaves verificadas com sucesso no caminho {$keysPath}", 'info');

        return [
            'success' => true,
            'path' => $keysPath
        ];
    }

    private function checkRequestCode(?string $requestCode): array
    {
        if (empty($requestCode)) {
            write_ServerLicense_log("Request code ausente.", 'warning');

            return [
                'success' => false,
                'message' => 'Request code ausente.'
            ];
        }

        $decoded = base64_decode($requestCode, true);
        if ($decoded === false) {
            write_ServerLicense_log("Request code inválido (não é base64).", 'warning');

            return [
                'success' => false,
                'message' => 'Request code inválido (não é base64).'
            ];
        }

        $dadosRequest = json_decode($decoded, true);
        if (!is_array($dadosRequest)) {
            write_ServerLicense_log("Request code inválido (JSON malformado).", 'warning');

            return [
                'success' => false,
                'message' => 'Request code inválido (JSON malformado).'
            ];
        }

        write_ServerLicense_log("Request code validado com sucesso.", 'info');

        // Se deu tudo certo, retorna os dados
        return [
            'success' => true,
            'dadosRequest' => $dadosRequest
        ];
    }


    public function validateRequestCode(?string $requestCode): array
    {
        // 1. Validar chaves
        $keysCheck = $this->checkKeysExist();
        if (!$keysCheck['success']) {
            return $keysCheck;
        }

        // 2. Validar request_code
        $requestCheck = $this->checkRequestCode($requestCode);
        if (!$requestCheck['success']) {
            return $requestCheck;
        }

        // 3. Tudo certo → unir os resultados
        write_ServerLicense_log("Validação do request code bem-sucedida.", 'info');

        return [
            'success' => true,
            'dadosRequest' => $requestCheck['dadosRequest'],
            'keysPath' => $keysCheck['path']
        ];
    }

    public function signLicense(array $dadosLicense): array
    {
        $privateKeyPath = $this->getServerFileKeyPrivate(); //getFilePrivateKey();//storage_path('keys/private.pem');
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        openssl_sign(json_encode($dadosLicense), $assinaturaRSA, $privateKey, OPENSSL_ALGO_SHA256);

        $dadosLicense['rsa'] = base64_encode($assinaturaRSA);

        write_ServerLicense_log("Validação Assinatura RSA com sucesso.", 'info');

        return $dadosLicense;
    }

    public function generateLicenseData(array $dadosRequest, ?int $dias = null): array
    {
        // 1. Dias de validade (mínimo 1, default 30)
        $diasValidade = filter_var($dias, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) ?? 30;

        // 2. Data de expiração
        $expiraEm = Carbon::now()->addDays($diasValidade)->toDateString();

        // 3. Monta os dados originais da licença
        write_ServerLicense_log(
            "Gerou a licença para o cliente " . ($dadosRequest['customer_registered_name'] ?? $dadosRequest['client_name'] ?? 'Sem nome') .
            " com validade de {$diasValidade} dias.",
            'info'
        );

        try {
            return [
                'client_name' => $dadosRequest['client_name'],
                'hardware_id' => $dadosRequest['hardware_id'],
                'expire_in' => $expiraEm,
            ];
        } catch (\Throwable $e) {
            // Loga o erro e retorna valores padrão
            write_ServerLicense_log("Erro ao montar dados da licença: " . $e->getMessage(), 'error');

            abort(400, "Falha ao montar dados da licença. Verifique os dados enviados.");
        }
    }


    public function signLicenseHmac(array $dadosLicense): array
    {
        $segredoExtra = LicenseHelper::getSegredoExtra();

        $dadosLicense['hmac'] = hash_hmac(
            'sha256',
            json_encode($dadosLicense),
            $segredoExtra
        );

        write_ServerLicense_log(
            "Validação Assinatura HMAC (incluindo RSA) com sucesso.",
            'info'
        );

        return $dadosLicense;
    }

    public function encryptLicense(array $dadosLicense): string
    {
        write_ServerLicense_log("Dados criptografado com sucesso.", 'info');

        return Crypt::encryptString(json_encode($dadosLicense));
    }


    public function saveDatabaseLicense($request, array $dadosRequest, string $expiraEm, string $licenseCode)
    {
        try {
            $nomeCliente = $dadosRequest['customer_registered_name']
                ?? $dadosRequest['client_name']
                ?? 'Sem nome';

            $clientName = $dadosRequest['client_name'] ?? 'N/A';
            $hardwareId = $dadosRequest['hardware_id'] ?? 'N/A';

            write_ServerLicense_log("Licença de Máquina {$nomeCliente} salva na base de dados com sucesso.", 'info');

            return ServerLicence::create([
                'customer_registered_name' => $request->customer_registered_name ?? null,
                'client_name' => $clientName,
                'hardware_id' => $hardwareId,
                'expire_in' => $expiraEm,
                'license_code' => $licenseCode,
            ]);
        } catch (\Throwable $e) {
            write_ServerLicense_log("Erro ao salvar licença no banco: " . $e->getMessage(), 'error');

            abort(400, "Falha ao montar dados da licença. Verifique os dados enviados.");
        }
    }

    //Geradoeres de chaves publicas e privadas
    public function generateKeys(): array
    {
        $keysPath = getServerStorageKeys();

        // 1️⃣ Cria a pasta se não existir
        if (!File::exists($keysPath)) {
            File::makeDirectory($keysPath, 0755, true); // recursive = true
            write_ServerLicense_log("Pasta de chaves criada com sucesso em {$keysPath}", 'info');
        } else {
            write_ServerLicense_log("As chaves já foram geradas anteriormente!", 'warning');

            return [
                'success' => false,
                'mensagem' => "As chaves já foram geradas anteriormente!",
                'privateKeyPath' => $keysPath . '/private.pem',
                'publicKeyPath' => $keysPath . '/public.pem',
            ];
        }

        // 2️⃣ Caminhos dos arquivos de chave
        $privateKeyPath = $keysPath . '/private.pem';
        $publicKeyPath = $keysPath . '/public.pem';

        // 3️⃣ Gera chave privada se não existir
        if (!File::exists($privateKeyPath)) {
            $privateKey = openssl_pkey_new([
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ]);

            // Salva chave privada
            openssl_pkey_export($privateKey, $privateKeyString);
            File::put($privateKeyPath, $privateKeyString);
            write_ServerLicense_log("Private key salva em {$privateKeyPath}", 'info');

            // Extrai e salva chave pública
            $publicKeyDetails = openssl_pkey_get_details($privateKey);
            $publicKeyString = $publicKeyDetails['key'];
            File::put($publicKeyPath, $publicKeyString);
            write_ServerLicense_log("Public key salva em {$publicKeyPath}", 'info');
        }

        write_ServerLicense_log("As chaves Públicas e privadas geradas com sucesso!", 'info');

        return [
            'success' => true,
            'mensagem' => "Chaves geradas com sucesso!",
            'privateKeyPath' => $privateKeyPath,
            'publicKeyPath' => $publicKeyPath,
        ];
    }


}
