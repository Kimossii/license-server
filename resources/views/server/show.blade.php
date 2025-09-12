<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div style="max-width: 600px; margin: 40px auto; padding: 32px; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); font-family: 'Segoe UI', Arial, sans-serif;">
        <h2 style="color: #2d3748; margin-bottom: 24px; text-align: center;">License Code Gerado</h2>
        <div style="margin-bottom: 18px;">
            <p style="margin: 8px 0;"><strong>Cliente Nome:</strong> {{ $licenca->customer_registered_name }}</p>
            <p style="margin: 8px 0;"><strong>Cliente Nome máquina:</strong> {{ $licenca->client_name }}</p>
            <p style="margin: 8px 0;"><strong>Hardware:</strong> {{ $licenca->hardware_id }}</p>
            <p style="margin: 8px 0;"><strong>Validade:</strong> {{ $licenca->expire_in }}</p>
        </div>
        <textarea id="licenseCode" cols="80" rows="5" readonly style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f9fafb; font-size: 1rem; margin-bottom: 12px; resize: none;">{{ $licenca->license_code }}</textarea>
        <p style="color: #4b5563; margin-bottom: 20px;">Envie este código ao cliente para ativar o software.</p>
        <div style="display: flex; gap: 12px;">
            <button onclick="copiarCodigo()" style="flex: 1; background: #2563eb; color: #fff; border: none; padding: 10px 0; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background 0.2s;">Copiar</button>
            <a href="{{ route('server.create') }}" style="flex: 1; display: inline-block; text-align: center; background: #f3f4f6; color: #2563eb; border: 1px solid #2563eb; padding: 10px 0; border-radius: 6px; font-size: 1rem; text-decoration: none; transition: background 0.2s;">Gerar nova licença</a>
        </div>
    </div>
</body>
<script>
    function copiarCodigo() {
        var textarea = document.getElementById("licenseCode");

        // Cria um elemento temporário para seleção
        textarea.removeAttribute('readonly'); // necessário para alguns navegadores
        textarea.select();
        textarea.setSelectionRange(0, 99999);

        try {
            var sucesso = document.execCommand('copy');
            if (sucesso) {
                alert('Código copiado para a área de transferência!');
            } else {
                alert('Não foi possível copiar.');
            }
        } catch (err) {
            alert('Erro ao copiar: ' + err);
        }

        textarea.setAttribute('readonly', true);
    }
</script>

</html>
