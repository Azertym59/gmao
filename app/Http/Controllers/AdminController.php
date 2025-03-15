<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Chantier;
use App\Models\ProduitCatalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct()
    {
        // Nous n'avons pas besoin d'appliquer le middleware ici car
        // nous l'appliquons déjà dans les routes
    }

    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'clients_count' => Client::count(),
            'chantiers_count' => Chantier::count(),
            'produits_catalogue_count' => ProduitCatalogue::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    public function users()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,technicien',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
    }
    
    public function createUser()
    {
        return view('admin.users.create');
    }
    
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,technicien',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }
    
    public function deleteUser(User $user)
    {
        // Empêcher la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }
    
    /**
     * Affiche l'interface de gestion de la base de données
     */
    public function databaseManager()
    {
        // Récupérer la liste des tables
        $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table" ORDER BY name');
        
        // Récupérer la taille de la base de données
        $dbPath = config('database.connections.sqlite.database');
        $dbSize = File::exists($dbPath) ? round(File::size($dbPath) / 1024 / 1024, 2) : 0;
        
        return view('admin.database.manager', compact('tables', 'dbSize'));
    }
    
    /**
     * Effectue une sauvegarde de la base de données
     */
    public function backupDatabase()
    {
        try {
            // Chemin de la base de données
            $dbPath = config('database.connections.sqlite.database');
            
            // Chemin de la sauvegarde
            $backupPath = storage_path('backups');
            
            // Créer le répertoire des sauvegardes s'il n'existe pas
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }
            
            // Créer le nom du fichier de sauvegarde avec la date
            $filename = 'gmao_backup_' . date('Y-m-d_H-i-s') . '.sqlite';
            $backupFilePath = $backupPath . '/' . $filename;
            
            // Copier le fichier de base de données
            File::copy($dbPath, $backupFilePath);
            
            return redirect()->route('admin.database.manager')
                ->with('success', 'Sauvegarde de la base de données effectuée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.database.manager')
                ->with('error', 'Erreur lors de la sauvegarde de la base de données: ' . $e->getMessage());
        }
    }
    
    /**
     * Liste les sauvegardes disponibles
     */
    public function listBackups()
    {
        $backupPath = storage_path('backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $files = File::files($backupPath);
        $backups = [];
        
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => round(File::size($file) / 1024 / 1024, 2),
                'date' => File::lastModified($file),
            ];
        }
        
        return view('admin.database.backups', compact('backups'));
    }
    
    /**
     * Télécharge une sauvegarde
     */
    public function downloadBackup($filename)
    {
        $backupPath = storage_path('backups/' . $filename);
        
        if (File::exists($backupPath)) {
            return response()->download($backupPath);
        }
        
        return redirect()->route('admin.database.backups')
            ->with('error', 'Le fichier de sauvegarde demandé n\'existe pas.');
    }
    
    /**
     * Restaure la base de données à partir d'une sauvegarde
     */
    public function restoreBackup($filename)
    {
        try {
            // Chemins des fichiers
            $backupPath = storage_path('backups/' . $filename);
            $dbPath = config('database.connections.sqlite.database');
            
            // Vérifier que le fichier de sauvegarde existe
            if (!File::exists($backupPath)) {
                return redirect()->route('admin.database.backups')
                    ->with('error', 'Le fichier de sauvegarde demandé n\'existe pas.');
            }
            
            // Fermer les connexions à la base de données
            DB::disconnect();
            
            // Remplacer le fichier de base de données
            File::copy($backupPath, $dbPath);
            
            // Nettoyer le cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            
            return redirect()->route('admin.database.manager')
                ->with('success', 'Base de données restaurée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.database.backups')
                ->with('error', 'Erreur lors de la restauration de la base de données: ' . $e->getMessage());
        }
    }
    
    /**
     * Affiche la confirmation avant réinitialisation de la base de données
     */
    public function confirmReset()
    {
        return view('admin.database.confirm-reset');
    }
    
    /**
     * Affiche le formulaire de création d'un nouvel administrateur après réinitialisation
     */
    public function setupAdmin()
    {
        // Vérifier si la base de données est neuve (aucun utilisateur)
        if (User::count() > 0) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Il existe déjà des utilisateurs dans la base de données.');
        }
        
        return view('admin.database.setup-admin');
    }
    
    /**
     * Réinitialise la base de données
     */
    public function resetDatabase(Request $request)
    {
        try {
            // Vérification de confirmation
            $request->validate([
                'confirm' => 'required|in:RESET',
            ]);
            
            // Sauvegarde de sécurité avant réinitialisation
            $dbPath = config('database.connections.sqlite.database');
            $backupPath = storage_path('backups');
            
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0777, true);
            }
            
            $filename = 'pre_reset_backup_' . date('Y-m-d_H-i-s') . '.sqlite';
            $backupFilePath = $backupPath . '/' . $filename;
            
            // Utiliser les commandes système pour éviter les problèmes de permission
            if (File::exists($dbPath)) {
                shell_exec("cp {$dbPath} {$backupFilePath}");
                shell_exec("chmod 666 {$backupFilePath}");
            }
            
            // Fermer les connexions à la base de données
            DB::disconnect();
            
            // Supprimer le fichier de base de données
            if (File::exists($dbPath)) {
                File::delete($dbPath);
            }
            
            // Créer un nouveau fichier SQLite vide
            File::put($dbPath, '');
            shell_exec("chmod 666 {$dbPath}");
            
            // Exécuter les migrations
            Artisan::call('migrate:fresh', ['--force' => true]);
            
            // Nettoyer le cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            // Rediriger vers le formulaire de création d'un nouvel administrateur
            return redirect()->route('admin.database.setup-admin');
        } catch (\Exception $e) {
            return redirect()->route('admin.database.confirm-reset')
                ->with('error', 'Erreur lors de la réinitialisation de la base de données: ' . $e->getMessage());
        }
    }
    
    /**
     * Créer un compte administrateur après réinitialisation
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Créer l'utilisateur admin
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'admin',
        ]);
        
        // Connecter l'utilisateur
        auth()->login($user);
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Base de données réinitialisée avec succès et compte administrateur créé.');
    }
}