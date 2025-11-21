<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Actions\Action;
use App\Filament\Pages\Settings;
use App\Filament\Widgets\DashboardWidgets;
use Filament\Tables\Grouping\Group;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->spa()
            ->unsavedChangesAlerts()
            ->colors([
                'primary' => '#6366f1',
            ])
            ->font('Poppins')
            ->registerErrorNotification(
                title: 'An error occurred',
                body: 'Please try again later.',
            )
            ->userMenuItems([
                Action::make('settings')
                    ->url(fn(): string => Settings::getUrl())
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->login()


            ->brandLogo(fn() => view('filament.adminLogo'))
            ->sidebarWidth('300px')
            ->sidebarCollapsibleOnDesktop()
            //->viteTheme("resources/css/sidebar.css")
            ->colors([
                'primary' => Color::Blue,
            ])


            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
