<!DOCTYPE html>
<html>
<head>
    <title>Login Client Test</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2d3748;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #4a5568;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #2563eb;
        }
        .error {
            color: #e53e3e;
            margin-top: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Espace Client</h1>
        <p style="text-align: center; margin-bottom: 20px;">Connectez-vous pour accéder à vos projets</p>
        
        <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
            <strong>Premier accès ?</strong> Si vous n'avez jamais créé de compte, veuillez vous <a href="{{ route('client.register') }}" style="color: #3b82f6; text-decoration: underline;">inscrire ici</a> en utilisant l'adresse email à laquelle vous recevez nos communications.
        </div>

        <form method="POST" action="{{ route('client.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center;">
                <input id="remember_me" type="checkbox" name="remember" style="width: auto; margin-right: 10px;">
                <label for="remember_me" style="display: inline; font-weight: normal;">Se souvenir de moi</label>
            </div>

            <button type="submit">Se connecter</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px; font-size: 14px;">
            Vous n'avez pas de compte? <a href="{{ route('client.register') }}" style="color: #3b82f6; text-decoration: none;">Inscrivez-vous</a>
        </div>
    </div>
</body>
</html>