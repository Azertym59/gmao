<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Temporaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 4px;
            color: white;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #3B82F6;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #2563EB;
        }
        .alert {
            background-color: #EF4444;
            color: white;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion</h1>
        
        @if(session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif
        
        <form method="POST" action="/temp-login">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>