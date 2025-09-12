<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chave Pública & Privada</title>
</head>

<body style="background: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0;">
    @include('license-server::spinner')
    <div
        style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 32px;">
        <h1 style="color: #2563eb; margin-bottom: 8px; font-size: 2rem;">Chaves: Pública & Privada.</h1>
        <h2 style="color: #334155; font-size: 1.2rem; margin-bottom: 24px;">{{ $mensagem }}</h2>
        <div style="margin-bottom: 16px;">
            <strong>Chave Privada:</strong>
            <span style="color: #64748b;">{{ $privateKeyPath }}</span>
        </div>
        <div style="margin-bottom: 16px;">
            <strong>Chave Pública:</strong>
            <span style="color: #64748b;">{{ $publicKeyPath }}</span>
        </div>
        <label for="publicKey" style="font-weight: 600; color: #2563eb;">Conteúdo da Chave Pública:</label>
        <textarea id="publicKey" cols="80" rows="10" readonly
            style="width: 100%; border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px; margin-top: 8px; margin-bottom: 16px; background: #f1f5f9; color: #334155; font-size: 1rem; resize: vertical; display: block;">
{{ file_get_contents(storage_path('keys/public.pem')) }}
        </textarea>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('keys.download', 'public') }}" class="btn btn-primary"
                style="background: #2563eb; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                Baixar chave pública
            </a>
            <button onclick="copyKey()"
                style="background: #22c55e; color: #fff; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                Copiar chave pública
            </button>
            <a href="{{ route('server.create', 'public') }}" class="btn btn-primary"
                style="background: #316104; color: #fff; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                Ativar a licença
            </a>
        </div>
    </div>
</body>
</body>
<script>
    function copyKey() {
        const textarea = document.getElementById('publicKey');
        textarea.style.display = 'block'; // precisa estar visível para copiar
        textarea.select();
        document.execCommand('copy');
        textarea.style.display = 'none';
        alert('Chave pública copiada para o clipboard!');
    }
</script>

</html>
