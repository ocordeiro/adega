<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Login;
use App\Filament\Widgets\RecentWines;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\WinesByCountryChart;
use App\Filament\Widgets\WinesByTypeChart;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->colors([
                // Paleta manual ancorada nas cores da home:
                // 500 = --rose (#c4506a), 700 = nosso vinho (#7b1f3a),
                // 800 = --wine (#6b1528), 900 = --burgundy (#4a0e1f)
                'primary' => [
                    50  => '254, 242, 245',
                    100 => '253, 229, 236',
                    200 => '249, 208, 220',
                    300 => '242, 177, 199',
                    400 => '228, 131, 163',
                    500 => '196, 80, 106',
                    600 => '158, 45, 76',
                    700 => '123, 31, 58',
                    800 => '107, 21, 40',
                    900 => '74, 14, 31',
                    950 => '47, 8, 19',
                ],
                'gray' => Color::Slate,
            ])
            ->brandName('Adega')
            ->font('Inter')
            ->sidebarCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString($this->loginPageCss()),
                scopes: Login::class,
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString($this->globalBrandCss()),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverview::class,
                WinesByTypeChart::class,
                WinesByCountryChart::class,
                RecentWines::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Catálogo'),
                NavigationGroup::make('Harmonização'),
                NavigationGroup::make('Configurações'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->spa();
    }

    private function globalBrandCss(): string
    {
        return <<<'CSS'
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&display=swap" rel="stylesheet">
        <style>
            .fi-logo {
                font-family: 'Cormorant Garamond', serif !important;
                font-size: 1.6rem !important;
                font-weight: 600 !important;
                letter-spacing: 0.08em !important;
                text-transform: uppercase !important;
                line-height: 1 !important;
            }
        </style>
        CSS;
    }

    private function loginPageCss(): string
    {
        return <<<'CSS'
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&display=swap" rel="stylesheet">
        <style>
            /* ── Background idêntico ao hero da home ───────────────────────── */
            .fi-simple-layout {
                position: relative;
                min-height: 100dvh;
            }

            .fi-simple-layout::before {
                content: '';
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(ellipse 80% 60% at 60% 50%, rgba(107, 21, 40, 0.35) 0%, transparent 70%),
                    linear-gradient(160deg, #0d0508 0%, #1c0610 35%, #2d0a18 60%, #1a0610 100%);
                pointer-events: none;
            }

            /* Grid de linhas finas — idêntico ao hero-bg::after */
            .fi-simple-layout::after {
                content: '';
                position: absolute;
                inset: 0;
                background-image:
                    repeating-linear-gradient(
                        0deg,
                        transparent,
                        transparent 80px,
                        rgba(255, 255, 255, .015) 80px,
                        rgba(255, 255, 255, .015) 81px
                    ),
                    repeating-linear-gradient(
                        90deg,
                        transparent,
                        transparent 80px,
                        rgba(255, 255, 255, .015) 80px,
                        rgba(255, 255, 255, .015) 81px
                    );
                pointer-events: none;
            }

            .fi-simple-main-ctn {
                position: relative;
                z-index: 1;
            }

            /* ── Card ───────────────────────────────────────────────────────── */
            .fi-simple-main {
                background: rgba(255, 255, 255, 0.97) !important;
                box-shadow:
                    0 0 0 1px rgba(13, 5, 8, 0.12),
                    0 24px 64px rgba(0, 0, 0, 0.50),
                    0 4px 16px rgba(0, 0, 0, 0.25) !important;
                border-radius: 1rem !important;
                overflow: hidden;
            }

            .dark .fi-simple-main {
                background: rgba(17, 24, 39, 0.97) !important;
            }

            /* ── Nome "Adega" — idêntico ao .navbar-name da home ───────────── */
            .fi-logo {
                font-family: 'Cormorant Garamond', serif !important;
                font-size: 1.85rem !important;
                font-weight: 600 !important;
                letter-spacing: 0.08em !important;
                text-transform: uppercase !important;
                color: #1c0910 !important;
                line-height: 1 !important;
            }

            .dark .fi-logo {
                color: #f9fafb !important;
            }
        </style>
        CSS;
    }
}
