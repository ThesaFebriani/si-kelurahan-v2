<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Core Entitites
        \App\Models\User::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\Keluarga::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\AnggotaKeluarga::observe(\App\Observers\AuditLogObserver::class);
        
        // Surat & Process
        \App\Models\PermohonanSurat::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\PermohonanSurat::observe(\App\Observers\PermohonanSuratNotificationObserver::class);
        \App\Models\Surat::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\ApprovalFlow::observe(\App\Observers\AuditLogObserver::class);
        // \App\Models\TimelinePermohonan::observe(\App\Observers\AuditLogObserver::class); // Timeline sudah log, mungkin redundant tapi oke
        
        // Master Data
        \App\Models\JenisSurat::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\SuratTemplate::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\TemplateField::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\RequiredDocument::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\Rt::observe(\App\Observers\AuditLogObserver::class);
        \App\Models\Rw::observe(\App\Observers\AuditLogObserver::class);
        
        // Configuration
        // \App\Models\Role::observe(\App\Observers\AuditLogObserver::class); // Role jarang berubah
        
        // AUTH EVENTS
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, [\App\Listeners\AuthLogger::class, 'handleLogin']);
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, [\App\Listeners\AuthLogger::class, 'handleLogout']);
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Failed::class, [\App\Listeners\AuthLogger::class, 'handleFailed']);
    }
}
