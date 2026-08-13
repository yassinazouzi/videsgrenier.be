<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AdminMotDePasse extends Command
{
    protected $signature = 'admin:mot-de-passe {email} {--nom=} {--role=super_admin}';

    protected $description = 'Crée un compte admin ou remplace son mot de passe (saisie masquée)';

    public function handle(): int
    {
        $email = $this->argument('email');

        $motDePasse = $this->secret('Nouveau mot de passe');
        if (strlen((string) $motDePasse) < 12) {
            $this->error('Le mot de passe doit faire au moins 12 caractères.');

            return self::FAILURE;
        }

        if ($motDePasse !== $this->secret('Confirmez le mot de passe')) {
            $this->error('Les deux saisies ne correspondent pas.');

            return self::FAILURE;
        }

        $admin = Admin::firstOrNew(['email' => $email]);
        $admin->nom = $this->option('nom') ?: ($admin->nom ?: 'Administrateur');
        $admin->role = $this->option('role');
        $admin->mot_de_passe = Hash::make($motDePasse);
        $admin->save();

        $this->info("Compte {$email} enregistré (rôle : {$admin->role}).");

        return self::SUCCESS;
    }
}
