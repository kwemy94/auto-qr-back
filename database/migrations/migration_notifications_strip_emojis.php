<?php

use App\Services\FcmService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Retire les émojis des textes de signalement déjà enregistrés (ex. « 🚗 Votre
 * véhicule… »), affichés tels quels dans l'historique de l'app.
 * (Nom sans date : doit passer après migration_notifications.php, qui crée la table.)
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('qr_notifications')->orderBy('id')->chunkById(500, function ($rows) {
            foreach ($rows as $row) {
                // Texte de référence actuel si le motif est connu, sinon émojis retirés
                $clean = FcmService::MESSAGES[$row->message_key]
                    ?? trim(preg_replace('/[\x{1F000}-\x{1FFFF}\x{2600}-\x{27BF}\x{FE0F}\x{200D}]/u', '', $row->message_text));

                if ($clean !== $row->message_text) {
                    DB::table('qr_notifications')->where('id', $row->id)->update(['message_text' => $clean]);
                }
            }
        });
    }

    public function down(): void
    {
        // Données : pas de retour arrière (on ne remet pas les émojis)
    }
};
