<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Paramètres du site')]
#[Layout('layouts.app')]
class SettingsPage extends Component
{
    use WithFileUploads;

    // Paramètres généraux / SEO
    public string $site_name = '';
    public ?string $seo_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;

    // Contact
    public ?string $site_email = null;
    public ?string $site_phone = null;
    public ?string $site_address = null;

    // Réseaux sociaux
    public ?string $facebook_url = null;
    public ?string $twitter_url = null;
    public ?string $linkedin_url = null;
    public ?string $youtube_url = null;
    public ?string $instagram_url = null;

    // SEO / sitemap
    public ?string $sitemap_url = null;

    // Maintenance
    public bool $maintenance_mode = false;
    public ?string $maintenance_message = null;

    // Uploads Livewire
    public $logoUpload;
    public $faviconUpload;
    public ?string $logo_current_path = null;
    public ?string $favicon_current_path = null;

    // Sécurité : mot de passe admin
    public ?string $current_password = null;
    public ?string $password = null;
    public ?string $password_confirmation = null;

    // Sécurité : compte secrétaire
    public ?string $secretary_name = null;
    public ?string $secretary_email = null;
    public ?string $secretary_password = null;

    public function mount(): void
    {
        $setting = Setting::main();

        // Remplir les propriétés depuis la DB
        $this->site_name        = $setting->site_name ?? 'EDGVM';
        $this->seo_title        = $setting->seo_title;
        $this->meta_description = $setting->meta_description;
        $this->meta_keywords    = $setting->meta_keywords;

        $this->site_email   = $setting->site_email;
        $this->site_phone   = $setting->site_phone;
        $this->site_address = $setting->site_address;

        $this->facebook_url  = $setting->facebook_url;
        $this->twitter_url   = $setting->twitter_url;
        $this->linkedin_url  = $setting->linkedin_url;
        $this->youtube_url   = $setting->youtube_url;
        $this->instagram_url = $setting->instagram_url;

        $this->sitemap_url = $setting->sitemap_url;

        $this->maintenance_mode    = (bool) $setting->maintenance_mode;
        $this->maintenance_message = $setting->maintenance_message;

        $this->logo_current_path    = $setting->logo_path;
        $this->favicon_current_path = $setting->favicon_path;
    }

    /* ---------- Validation ---------- */

    protected function rulesSettings(): array
    {
        return [
            'site_name'        => ['required', 'string', 'max:255'],
            'seo_title'        => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords'    => ['nullable', 'string'],

            'site_email'   => ['nullable', 'email', 'max:255'],
            'site_phone'   => ['nullable', 'string', 'max:255'],
            'site_address' => ['nullable', 'string'],

            'facebook_url'  => ['nullable', 'url'],
            'twitter_url'   => ['nullable', 'url'],
            'linkedin_url'  => ['nullable', 'url'],
            'youtube_url'   => ['nullable', 'url'],
            'instagram_url' => ['nullable', 'url'],

            'sitemap_url' => ['nullable', 'url'],

            'maintenance_mode'    => ['boolean'],
            'maintenance_message' => ['nullable', 'string'],

            'logoUpload'    => ['nullable', 'image', 'max:2048'],
            'faviconUpload' => ['nullable', 'image', 'max:1024'],
        ];
    }

    protected function rulesPassword(): array
    {
        return [
            'current_password'      => ['required'],
            'password'              => ['required', 'min:8', 'same:password_confirmation'],
            'password_confirmation' => ['required'],
        ];
    }

    protected function rulesSecretary(): array
    {
        return [
            'secretary_name'     => ['required', 'string', 'max:255'],
            'secretary_email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'secretary_password' => ['nullable', 'string', 'min:8'],
        ];
    }

    /* ---------- Actions ---------- */

    public function save(): void
    {
        $this->validate($this->rulesSettings());

        $setting = Setting::main();

        $setting->site_name        = $this->site_name;
        $setting->seo_title        = $this->seo_title;
        $setting->meta_description = $this->meta_description;
        $setting->meta_keywords    = $this->meta_keywords;

        $setting->site_email   = $this->site_email;
        $setting->site_phone   = $this->site_phone;
        $setting->site_address = $this->site_address;

        $setting->facebook_url  = $this->facebook_url;
        $setting->twitter_url   = $this->twitter_url;
        $setting->linkedin_url  = $this->linkedin_url;
        $setting->youtube_url   = $this->youtube_url;
        $setting->instagram_url = $this->instagram_url;

        $setting->sitemap_url = $this->sitemap_url;

        $setting->maintenance_mode    = $this->maintenance_mode;
        $setting->maintenance_message = $this->maintenance_message;

        // Upload logo via Livewire
        if ($this->logoUpload) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $path = $this->logoUpload->store('branding', 'public');
            $setting->logo_path = $path;
            $this->logo_current_path = $path;
        }

        // Upload favicon via Livewire
        if ($this->faviconUpload) {
            if ($setting->favicon_path) {
                Storage::disk('public')->delete($setting->favicon_path);
            }
            $path = $this->faviconUpload->store('branding', 'public');
            $setting->favicon_path = $path;
            $this->favicon_current_path = $path;
        }

        $setting->save();

        session()->flash('status', 'Paramètres du site mis à jour avec succès.');
    }

    public function updatePassword(): void
    {
        $this->validate($this->rulesPassword(), [], [
            'current_password' => 'mot de passe actuel',
            'password'         => 'nouveau mot de passe',
        ]);

        $user = auth()->user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Le mot de passe actuel est incorrect.');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('password_status', 'Mot de passe administrateur mis à jour.');
    }

    public function createSecretary(): void
    {
        $this->validate($this->rulesSecretary());

        $plainPassword = $this->secretary_password ?: Str::random(10);

        $user = User::create([
            'name'              => $this->secretary_name,
            'email'             => $this->secretary_email,
            'password'          => Hash::make($plainPassword),
            'role'              => 'secrétaire',
            'active'            => true,
            'email_verified_at' => now(),
        ]);

        $this->reset(['secretary_name', 'secretary_email', 'secretary_password']);

        session()->flash(
            'secretary_status',
            "Compte secrétaire créé : {$user->email}. Mot de passe provisoire : {$plainPassword}"
        );
    }

    public function render()
    {
        return view('livewire.admin.settings-page');
    }
}
