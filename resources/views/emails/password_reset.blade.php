{{-- <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
</head>
<body>
    <p>Hola,</p>
    <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
    <p><a href="{{ $resetUrl }}">Restablecer Contraseña</a></p>
    <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
    <p>Saludos,<br>Tu equipo de soporte</p>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecimiento de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 400px;
            text-align: center;
        }
        .header-image {
            width: 100%;
            height: auto;
            border-radius: 8px 8px 0 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #7ee963;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #5c5454;
            color: white
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwzNjUyOXwwfDF8c2VhcmNofDEwfHxjb21wdXRlciUyMHNjcmVlbnxlbnwwfHx8fDE2OTYzMDQ1OTg&ixlib=rb-4.0.3&q=80&w=400" alt="Pantalla de Computadora" class="header-image"> --}}

        <p>Hola,</p>
        <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta. Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
        {{-- <a href="{{ $resetUrl }}">Restablecer Contraseña</a> --}}
        <a href="{{ $url }}" class="btn">Restablecer Contraseña</a>

        <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
        <p>Saludos,<br>Soporte <b>Stream Tech</b></p>
    </div>
</body>
</html>
