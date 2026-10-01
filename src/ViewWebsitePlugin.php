<?php

namespace Digit7s\FilamentViewWebsite;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class ViewWebsitePlugin implements Plugin
{
    use EvaluatesClosures;

    protected string|Closure $url;

    protected string|Closure $label = 'Visit Website';

    protected string|Closure $icon = 'heroicon-o-arrow-top-right-on-square';

    protected string|Closure|null $tooltip = 'Open public website';

    protected bool|Closure $openInNewTab = true;

    protected bool|Closure $visible = true;

    public function __construct()
    {
        $this->evaluationIdentifier = 'plugin';
        $this->url = fn (): string => url('/');
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'digit7s-view-website';
    }

    public function register(Panel $panel): void
    {
        $panel->renderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): string => Blade::render(
                '@include("filament-view-website::view-website", ["plugin" => $plugin])',
                ['plugin' => $this],
            ),
        );
    }

    public function boot(Panel $panel): void
    {
        // Nothing to boot. The action is registered with the panel in register().
    }

    public function url(string|Closure $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        $url = $this->evaluate($this->url);

        return filled($url) ? (string) $url : null;
    }

    public function label(string|Closure $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return (string) $this->evaluate($this->label);
    }

    public function icon(string|Closure $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): string
    {
        return (string) $this->evaluate($this->icon);
    }

    public function tooltip(string|Closure|null $tooltip): static
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    public function getTooltip(): ?string
    {
        $tooltip = $this->evaluate($this->tooltip);

        return filled($tooltip) ? (string) $tooltip : null;
    }

    public function openInNewTab(bool|Closure $condition = true): static
    {
        $this->openInNewTab = $condition;

        return $this;
    }

    public function shouldOpenInNewTab(): bool
    {
        return (bool) $this->evaluate($this->openInNewTab);
    }

    public function visible(bool|Closure $condition = true): static
    {
        $this->visible = $condition;

        return $this;
    }

    public function isVisible(): bool
    {
        return (bool) $this->evaluate($this->visible);
    }
}
