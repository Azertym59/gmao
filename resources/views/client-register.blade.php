<!DOCTYPE html>
<html>
<head>
    <title>Inscription Client</title>
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
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
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
        .login-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .login-link a {
            color: #3b82f6;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Inscription Espace Client</h1>
        <p style="text-align: center; margin-bottom: 20px;">Créez votre compte pour accéder à vos projets</p>
        
        <div style="background-color: #e3f2fd; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;">
            <strong>Déjà client ?</strong> Si vous avez déjà des projets avec nous, utilisez la même adresse email que celle utilisée lors de la création de vos chantiers pour retrouver tout votre historique.
        </div>

        <form method="POST" action="{{ route('client.register.submit') }}">
            @csrf

            <div class="form-group">
                <label for="civilite">Civilité</label>
                <select id="civilite" name="civilite">
                    <option value="M.">M.</option>
                    <option value="Mme">Mme</option>
                </select>
                @error('civilite')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="nom">Nom</label>
                <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required>
                @error('nom')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required>
                @error('prenom')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}" required>
                @error('telephone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
            </div>

            <button type="submit">S'inscrire</button>
        </form>

        <div class="login-link">
            Vous avez déjà un compte? <a href="{{ route('client.login') }}">Connectez-vous</a>
        </div>
    </div>
</body>
</html>