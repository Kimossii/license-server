<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gera a sua licença</title>
  
</head>

<body>
    @include('license-server::spinner')
    <div
        style="max-width: 600px; margin: 40px auto; padding: 32px; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); font-family: 'Segoe UI', Arial, sans-serif;">
        <h2 style="color: #2d3748; text-align: center; margin-bottom: 24px;">Gerar Licença</h2>
        @if (session('error'))
            <div
                style="margin-bottom:16px; color: #e53e3e; background: #fff5f5; border: 1px solid #fed7d7; padding: 10px 16px; border-radius: 6px;">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('server.store') }}">
            @csrf
            <div style="margin-bottom: 18px;">
                <label for="customer_registered_name" style="display:block; font-weight:500; margin-bottom:6px;">Nome do
                    Cliente</label>
                <input type="text" name="customer_registered_name" id="customer_registered_name" list="clientes"
                    autocomplete="off"
                    style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:5px;">
                <datalist id="clientes">
                    <option value="Empresa ABC">
                    <option value="Cliente XYZ">
                    <option value="Outro...">
                </datalist>
            </div>
            <div style="margin-bottom: 18px;">
                <label for="request_code" style="display:block; font-weight:500; margin-bottom:6px;">Request Code
                    recebido do cliente:</label>
                <textarea name="request_code" id="request_code" cols="80" rows="5" required
                    style="width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:5px; resize:vertical;"></textarea>
            </div>
            <div style="margin-bottom: 24px;">
                <label for="dias" style="display:block; font-weight:500; margin-bottom:6px;">Validade
                    (dias):</label>
                <input type="number" name="dias" id="dias" value="30" required
                    style="width:100px; padding:8px 10px; border:1px solid #cbd5e1; border-radius:5px;">
            </div>
            <button type="submit"
                style="background:#2563eb; color:#fff; padding:10px 28px; border:none; border-radius:6px; font-size:1rem; font-weight:600; cursor:pointer; transition:background 0.2s;">Gerar
                Licença</button>
        </form>
        <div style="margin-top: 32px; text-align: center;">
            <a href="{{ route('generate.keys') }}"
                style="color:#2563eb; text-decoration:underline; font-weight:500;">Gerar as chaves Pública e
                Privadas</a>
        </div>
    </div>
   
</body>

</html>
