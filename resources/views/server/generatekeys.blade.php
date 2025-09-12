<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body style="background: linear-gradient(135deg, #dfe1ec 0%, #282729 100%); min-height: 100vh; margin:0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <div style="background: #252322; padding: 2rem 3rem; border-radius: 16px; box-shadow: 0 8px 32px rgba(44,62,80,0.15); text-align: center;">
        <h1 style="color: #dedee0; margin-bottom: 1.5rem;">Gerar Chaves Pública e Privadas</h1>
        <form action="{{ route('keys.generate') }}" method="POST">
            @csrf
            <button type="submit" style="background: #4f46e5; color: #fff; border: none; padding: 0.75rem 2rem; border-radius: 8px; font-size: 1.1rem; cursor: pointer; transition: background 0.2s;">
                Gerar Chaves
            </button>
        </form>
    </div>
</body>
</body>
</html>
