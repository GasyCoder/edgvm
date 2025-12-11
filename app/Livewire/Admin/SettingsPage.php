<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsPage extends Component
{
    use WithFileUploads;

    /* =======================
     *  PARAMÈTRES (liés à settings.* dans la vue)
     * ======================= */

    public array $settings = [
        'site_name'           => '',
        'site_baseline'       => null,

        'contact_email'       => null,
        'contact_phone'       => null,
        'contact_address'     => null,

        'social_facebook'     => null,
        'social_twitter'      => null,
        'social_linkedin'     => null,
        'social_youtube'      => null,
        'social_instagram'    => null,

        'meta_title'          => null,
        'meta_description'    => null,
        'meta_keywords'       => null,
        'sitemap_url'         => null,

        'maintenance_enabled' => false,
        'maintenance_message' => null,
    ];

    /* =======================
     *  MÉDIAS (logo + favicon)
     *  (liés aux inputs wire:model="logo" et wire:model="favicon")
     * ======================= */

    public $logo;
    public $favicon;

    public ?string $logoPath = null;
    public ?string $faviconPath = null;

    /* =======================
     *  SÉCURITÉ
     *  (liés à security.* dans la vue)
     * ======================= */

    public array $security = [
        'current_password'          => '',
        'new_password'              => '',
        'new_password_confirmation' => '',
    ];

    /* =======================
     *  COMPTE SECRÉTAIRE
     *  (liés à secretaire.* dans la vue)
     * ======================= */

    public array $secretaire = [
        'name'     => '',
        'email'    => '',
        'password' => '',
    ];

    /* =======================
     *  MOUNT : on charge les données depuis la table settings
     * ======================= */

    public function mount(): void
    {
        $settingsModel = Setting::main(); // récupère (ou crée) l’unique ligne

        // Généraux
        $this->settings['site_name']       = $settingsModel->site_name ?? 'EDGVM';
        $this->settings['site_baseline']   = null; // si tu ajoutes une colonne plus tard, on pourra la mapper ici

        $this->settings['contact_email']   = $settingsModel->site_email;
        $this->settings['contact_phone']   = $settingsModel->site_phone;
        $this->settings['contact_address'] = $settingsModel->site_address;

        // Réseaux sociaux
        $this->settings['social_facebook']  = $settingsModel->facebook_url;
        $this->settings['social_twitter']   = $settingsModel->twitter_url;
        $this->settings['social_linkedin']  = $settingsModel->linkedin_url;
        $this->settings['social_youtube']   = $settingsModel->youtube_url;
        $this->settings['social_instagram'] = $settingsModel->instagram_url;

        // SEO / sitemap
        $this->settings['meta_title']       = $settingsModel->seo_title;
        $this->settings['meta_description'] = $settingsModel->meta_description;
        $this->settings['meta_keywords']    = $settingsModel->meta_keywords;
        $this->settings['sitemap_url']      = $settingsModel->sitemap_url;

        // Maintenance
        $this->settings['maintenance_enabled'] = (bool) $settingsModel->maintenance_mode;
        $this->settings['maintenance_message'] = $settingsModel->maintenance_message
            ?? "Le site est actuellement en maintenance. Merci de revenir plus tard.";

        // Médias existants
        $this->logoPath    = $settingsModel->logo_path;
        $this->faviconPath = $settingsModel->favicon_path;

        // Secrétaire existant
        $secretary = User::where('role', 'secrétaire')->first();
        if ($secretary) {
            $this->secretaire['name']  = $secretary->name;
            $this->secretaire['email'] = $secretary->email;
        }
    }

    /* =======================
     *  FORMULAIRE GÉNÉRAL (Identité + contacts + réseaux)
     *  wire:submit.prevent="save"
     * ======================= */

    public function save(): void
    {
        $this->validate([
            'settings.site_name'       => 'required|string|max:255',
            'settings.site_baseline'   => 'nullable|string|max:255',

            'settings.contact_email'   => 'nullable|email|max:255',
            'settings.contact_phone'   => 'nullable|string|max:255',
            'settings.contact_address' => 'nullable|string|max:500',

            'settings.social_facebook'  => 'nullable|url|max:255',
            'settings.social_twitter'   => 'nullable|url|max:255',
            'settings.social_linkedin'  => 'nullable|url|max:255',
            'settings.social_youtube'   => 'nullable|url|max:255',
            'settings.social_instagram' => 'nullable|url|max:255',
        ]);

        $settingsModel = Setting::main();

        $settingsModel->fill([
            'site_name'    => $this->settings['site_name'],
            'site_email'   => $this->settings['contact_email'],
            'site_phone'   => $this->settings['contact_phone'],
            'site_address' => $this->settings['contact_address'],

            'facebook_url' => $this->settings['social_facebook'],
            'twitter_url'  => $this->settings['social_twitter'],
            'linkedin_url' => $this->settings['social_linkedin'],
            'youtube_url'  => $this->settings['social_youtube'],
            'instagram_url'=> $this->settings['social_instagram'],
        ]);

        $settingsModel->save();

        session()->flash('settings_saved', 'Paramètres généraux enregistrés avec succès.');
    }

    /* =======================
     *  FORMULAIRE SEO
     *  wire:submit.prevent="saveSeo"
     * ======================= */

    public function saveSeo(): void
    {
        $this->validate([
            'settings.meta_title'       => 'nullable|string|max:255',
            'settings.meta_description' => 'nullable|string|max:500',
            'settings.meta_keywords'    => 'nullable|string|max:500',
            'settings.sitemap_url'      => 'nullable|url|max:255',
        ]);

        $settingsModel = Setting::main();

        $settingsModel->fill([
            'seo_title'       => $this->settings['meta_title'],
            'meta_description'=> $this->settings['meta_description'],
            'meta_keywords'   => $this->settings['meta_keywords'],
            'sitemap_url'     => $this->settings['sitemap_url'],
        ]);

        $settingsModel->save();

        session()->flash('settings_saved', 'Paramètres SEO enregistrés avec succès.');
    }

    /* =======================
     *  FORMULAIRE MAINTENANCE
     *  wire:submit.prevent="saveMaintenance"
     * ======================= */

    public function saveMaintenance(): void
    {
        $this->validate([
            'settings.maintenance_enabled' => 'boolean',
            'settings.maintenance_message' => 'nullable|string|max:500',
        ]);

        $settingsModel = Setting::main();

        $settingsModel->maintenance_mode    = $this->settings['maintenance_enabled'] ? 1 : 0;
        $settingsModel->maintenance_message = $this->settings['maintenance_message'];

        $settingsModel->save();

        session()->flash('settings_saved', 'Mode maintenance mis à jour avec succès.');
    }

    /* =======================
     *  MÉDIAS (logo + favicon)
     *  bouton : wire:click="saveMedia"
     * ======================= */

    public function saveMedia(): void
    {
        $this->validate([
            'logo'    => 'nullable|image|max:2048', // 2 Mo
            'favicon' => 'nullable|image|max:1024', // 1 Mo
        ]);

        $settingsModel = Setting::main();

        // Logo
        if ($this->logo) {
            if ($this->logoPath) {
                Storage::disk('public')->delete($this->logoPath);
            }
            $path = $this->logo->store('settings/logo', 'public');
            $settingsModel->logo_path = $path;
            $this->logoPath = $path;
        }

        // Favicon
        if ($this->favicon) {
            if ($this->faviconPath) {
                Storage::disk('public')->delete($this->faviconPath);
            }
            $path = $this->favicon->store('settings/favicon', 'public');
            $settingsModel->favicon_path = $path;
            $this->faviconPath = $path;
        }

        $settingsModel->save();

        // on vide juste les champs fichier
        $this->reset('logo', 'favicon');

        session()->flash('settings_saved', 'Logo et favicon mis à jour avec succès.');
    }

    /* =======================
     *  MOT DE PASSE ADMIN
     *  wire:submit.prevent="updateAdminPassword"
     * ======================= */

    public function updateAdminPassword(): void
    {
        $this->validate([
            'security.current_password'          => 'required|current_password',
            'security.new_password'              => 'required|min:8|same:security.new_password_confirmation',
            'security.new_password_confirmation' => 'required',
        ], [
            'security.current_password.current_password' => 'Le mot de passe actuel est incorrect.',
        ]);

        $user = Auth::user();
        if ($user) {
            $user->password = Hash::make($this->security['new_password']);
            $user->save();
        }

        $this->security = [
            'current_password'          => '',
            'new_password'              => '',
            'new_password_confirmation' => '',
        ];

        session()->flash('security_updated', 'Mot de passe administrateur mis à jour.');
    }

    /* =======================
     *  COMPTE SECRÉTAIRE
     *  wire:submit.prevent="createOrUpdateSecretaire"
     * ======================= */

    public function createOrUpdateSecretaire(): void
    {
        $this->validate([
            'secretaire.name'     => 'required|string|max:255',
            'secretaire.email'    => 'required|email|max:255',
            'secretaire.password' => 'nullable|min:8',
        ]);

        $secretary = User::firstOrNew(['email' => $this->secretaire['email']]);

        $secretary->name   = $this->secretaire['name'];
        $secretary->role   = 'secrétaire';
        $secretary->active = true;

        if (!empty($this->secretaire['password'])) {
            $secretary->password = Hash::make($this->secretaire['password']);
        } elseif (! $secretary->exists) {
            // mot de passe par défaut si nouvelle création sans password saisi
            $secretary->password = Hash::make('password1234');
        }

        $secretary->save();

        // on ne garde pas le mdp en mémoire
        $this->secretaire['password'] = '';

        session()->flash('security_updated', 'Compte secrétaire créé / mis à jour.');
    }

    /* =======================
     *  RENDER
     * ======================= */

    public function render()
    {
        $currentLogoUrl = $this->logoPath
            ? Storage::disk('public')->url($this->logoPath)
            : null;

        $currentFaviconUrl = $this->faviconPath
            ? Storage::disk('public')->url($this->faviconPath)
            : null;

        return view('livewire.admin.settings-page', [
            'title'              => 'Paramètres',
            'subtitle'           => 'Configuration globale du site EDGVM',
            'current_logo_url'   => $currentLogoUrl,
            'current_favicon_url'=> $currentFaviconUrl,
        ]);
    }
}
