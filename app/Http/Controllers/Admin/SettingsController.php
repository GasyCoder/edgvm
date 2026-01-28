<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Http\Requests\UpdateSettingsGeneralRequest;
use App\Http\Requests\UpdateSettingsMaintenanceRequest;
use App\Http\Requests\UpdateSettingsMediaRequest;
use App\Http\Requests\UpdateSettingsSecretaireRequest;
use App\Http\Requests\UpdateSettingsSecurityRequest;
use App\Http\Requests\UpdateSettingsSeoRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(): Response
    {
        $settings = Setting::main();

        $secretary = User::where('role', 'secrétaire')->first();

        return Inertia::render('Admin/Settings/Index', [
            'settings' => [
                'site_name' => $settings->site_name,
                'site_baseline' => null,
                'contact_email' => $settings->site_email,
                'contact_phone' => $settings->site_phone,
                'contact_address' => $settings->site_address,
                'social_facebook' => $settings->facebook_url,
                'social_twitter' => $settings->twitter_url,
                'social_linkedin' => $settings->linkedin_url,
                'social_youtube' => $settings->youtube_url,
                'social_instagram' => $settings->instagram_url,
                'meta_title' => $settings->seo_title,
                'meta_description' => $settings->meta_description,
                'meta_keywords' => $settings->meta_keywords,
                'sitemap_url' => $settings->sitemap_url,
                'maintenance_enabled' => (bool) $settings->maintenance_mode,
                'maintenance_message' => $settings->maintenance_message ?? 'Le site est actuellement en maintenance.',
                'logo_url' => $settings->logo_path ? Storage::disk('public')->url($settings->logo_path) : null,
                'favicon_url' => $settings->favicon_path ? Storage::disk('public')->url($settings->favicon_path) : null,
                'message_direction_doctorants' => $settings->message_direction_doctorants,
                'message_direction_equipes' => $settings->message_direction_equipes,
                'message_direction_theses' => $settings->message_direction_theses,
            ],
            'security' => [
                'secretaire' => $secretary ? [
                    'name' => $secretary->name,
                    'email' => $secretary->email,
                ] : null,
            ],
        ]);
    }

    public function updateGeneral(UpdateSettingsGeneralRequest $request): RedirectResponse
    {
        $settings = Setting::main();

        $settings->fill([
            'site_name' => $request->validated('site_name'),
            'site_email' => $request->validated('contact_email'),
            'site_phone' => $request->validated('contact_phone'),
            'site_address' => $request->validated('contact_address'),
            'facebook_url' => $request->validated('social_facebook'),
            'twitter_url' => $request->validated('social_twitter'),
            'linkedin_url' => $request->validated('social_linkedin'),
            'youtube_url' => $request->validated('social_youtube'),
            'instagram_url' => $request->validated('social_instagram'),
        ])->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Parametres generaux enregistres.');
    }

    public function updateSeo(UpdateSettingsSeoRequest $request): RedirectResponse
    {
        $settings = Setting::main();

        $settings->fill([
            'seo_title' => $request->validated('meta_title'),
            'meta_description' => $request->validated('meta_description'),
            'meta_keywords' => $request->validated('meta_keywords'),
            'sitemap_url' => $request->validated('sitemap_url'),
        ])->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Parametres SEO enregistres.');
    }

    public function updateMaintenance(UpdateSettingsMaintenanceRequest $request): RedirectResponse
    {
        $settings = Setting::main();

        $settings->maintenance_mode = $request->boolean('maintenance_enabled');
        $settings->maintenance_message = $request->validated('maintenance_message');
        $settings->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Mode maintenance mis a jour.');
    }

    public function updateStatistics(UpdateSettingsRequest $request): RedirectResponse
    {
        $settings = Setting::main();

        $settings->fill([
            'message_direction_doctorants' => $request->validated('message_direction_doctorants'),
            'message_direction_equipes' => $request->validated('message_direction_equipes'),
            'message_direction_theses' => $request->validated('message_direction_theses'),
        ])->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Statistiques mises a jour.');
    }

    public function updateMedia(UpdateSettingsMediaRequest $request): RedirectResponse
    {
        $settings = Setting::main();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $settings->logo_path = $request->file('logo')->store('settings/logo', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $settings->favicon_path = $request->file('favicon')->store('settings/favicon', 'public');
        }

        $settings->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Logo et favicon mis a jour.');
    }

    public function updateSecurity(UpdateSettingsSecurityRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            $user->password = Hash::make($request->validated('new_password'));
            $user->save();
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Mot de passe administrateur mis a jour.');
    }

    public function updateSecretaire(UpdateSettingsSecretaireRequest $request): RedirectResponse
    {
        $secretary = User::firstOrNew(['email' => $request->validated('email')]);

        $secretary->name = $request->validated('name');
        $secretary->role = 'secrétaire';
        $secretary->active = true;

        if ($request->validated('password')) {
            $secretary->password = Hash::make($request->validated('password'));
        } elseif (! $secretary->exists) {
            $secretary->password = Hash::make('password1234');
        }

        $secretary->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Compte secretaire mis a jour.');
    }
}
