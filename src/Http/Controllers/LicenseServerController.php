<?php

namespace LicenseServer\Http\Controllers;
use LicenseServer\Helpers\GlobalVariables;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\ServerLicence;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
//use App\Helpers\LicenseHelper;
use LicenseServer\Helpers\LicenseHelper;

use Illuminate\Support\Facades\File;
use LicenseClient\Services\GeneratorLicenseService;

use Illuminate\Contracts\Encryption\DecryptException;
// Carrega os helpers globais do pacote
require_once base_path('vendor/eluki/license-client/src/Helpers/globalVariables.php');
require_once base_path('vendor/eluki/license-client/src/Helpers/LicenseHelper.php');

class LicenseServerController extends Controller
{
    protected GeneratorLicenseService $licenseService;
    public function __construct(GeneratorLicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
    }
    public function generateKeyForm()
    {
        return view('license-server::generatekeys');
    }

    public function generateKeys()
    {
        $result = $this->licenseService->generateKeys();

        return view('license-server::generateKeysSucess', [
            'mensagem' => $result['mensagem'],
            'privateKeyPath' => $result['privateKeyPath'],
            'publicKeyPath' => $result['publicKeyPath'],
        ]);
    }


    public function downloadKey($tipo)
    {
        return $this->licenseService->downloadKey($tipo);
    }
    public function createForm()
    {
        return view('license-server::create');
    }

    public function store(Request $request)
    {

        $result = $this->licenseService->validateRequestCode($request->request_code);

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        $dadosRequest = $result['dadosRequest'];
        $keysPath = $result['keysPath'];

        if (!is_array($dadosRequest)) {
            return redirect()->back()->with('error', 'Request code inválido.');
        }

        // Gerar dados da licença via service
        $dadosLicense = $this->licenseService->generateLicenseData($dadosRequest, $request->dias);
        $expiraEm = $dadosLicense['expire_in'];

        //Assinatura RSA (somente dados originais)
        $dadosLicense = $this->licenseService->signLicense($dadosLicense);

        //Assinatura HMAC (incluindo RSA)
        $dadosLicense = $this->licenseService->signLicenseHmac($dadosLicense);

        //Criptografa tudo
        $licenseCode = $this->licenseService->encryptLicense($dadosLicense);

        //Salva no banco (opcional)
        $licenca = $this->licenseService->saveDatabaseLicense(
            $request,
            $dadosRequest,
            $expiraEm,
            $licenseCode
        );


        return view('license-server::show', compact('licenca'));
    }

}

