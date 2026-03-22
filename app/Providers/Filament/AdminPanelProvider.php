<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Login;
use App\Filament\Widgets\RecentSpirits;
use App\Filament\Widgets\RecentWines;
use App\Filament\Widgets\SpiritsByCountryChart;
use App\Filament\Widgets\SpiritsByTypeChart;
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
                SpiritsByTypeChart::class,
                WinesByCountryChart::class,
                SpiritsByCountryChart::class,
                RecentWines::class,
                RecentSpirits::class,
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

            /* ── Tema claro: flat minimalista ───────────────────────────────── */

            /* Fundo da página: cinza-quente neutro */
            :root:not(.dark) body,
            :root:not(.dark) .fi-body {
                background-color: #ede9ea !important;
            }

            /* Sidebar e topbar: branco limpo */
            :root:not(.dark) .fi-sidebar {
                background-color: #ffffff !important;
                border-right: 1px solid #e2dcdd !important;
                box-shadow: none !important;
            }

            :root:not(.dark) .fi-topbar {
                background-color: #ffffff !important;
                border-bottom: 1px solid #e2dcdd !important;
                box-shadow: none !important;
            }

            /* Cards genéricos: branco puro, sem sombra, borda finíssima */
            :root:not(.dark) .fi-section,
            :root:not(.dark) .fi-ta-ctn,
            :root:not(.dark) .fi-wi,
            :root:not(.dark) .fi-card {
                background-color: #ffffff !important;
                border-color: #e2dcdd !important;
                box-shadow: none !important;
            }

            /* Acento no topo de cada widget card */
            :root:not(.dark) .fi-wi {
                border-top: 3px solid #9e2d4c !important;
            }

            /* StatsOverview: widget e a section interna sem fundo */
            :root:not(.dark) .fi-wi-stats-overview,
            :root:not(.dark) .fi-wi-stats-overview section.fi-section,
            :root:not(.dark) .fi-wi-stats-overview .fi-section-content-ctn {
                background-color: transparent !important;
                border-color: transparent !important;
                box-shadow: none !important;
            }

            /* Tipografia: sai do preto puro para um dark warm */
            :root:not(.dark) body,
            :root:not(.dark) .fi-body {
                color: #1e1014 !important;
            }

            :root:not(.dark) h1,
            :root:not(.dark) h2,
            :root:not(.dark) h3,
            :root:not(.dark) .fi-section-header-heading,
            :root:not(.dark) .fi-wi-stats-overview-stat-label,
            :root:not(.dark) .fi-page-header-heading {
                color: #1e1014 !important;
            }

            /* Texto secundário / descrições: warm gray */
            :root:not(.dark) .fi-page-header-subheading,
            :root:not(.dark) .fi-wi-stats-overview-stat-description {
                color: #5c4048 !important;
            }

            /* Labels de tabela e nav items */
            :root:not(.dark) .fi-ta-header-cell,
            :root:not(.dark) .fi-sidebar-item-label {
                color: #3a2028 !important;
            }

            /* Header de tabela: cinza-quente bem suave */
            :root:not(.dark) .fi-ta-header-cell {
                background-color: #f7f4f5 !important;
            }

            /* Hover nas linhas: mínimo */
            :root:not(.dark) .fi-ta-row:hover td {
                background-color: #faf7f8 !important;
            }

            /* ── Espaçamento do grid de widgets ────────────────────────────── */

            /* Conteúdo principal: mais espaço interno */
            .fi-main {
                padding: 1.75rem !important;
            }

            /* Grid de widgets: gap maior para os cards respirarem */
            .fi-widgets-grid,
            .fi-dashboard-widgets-ctn {
                gap: 1.25rem !important;
            }

            /* Garante que widgets individuais tenham bordas arredondadas visíveis */
            :root:not(.dark) .fi-wi {
                border-radius: 0.5rem !important;
                overflow: hidden;
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
