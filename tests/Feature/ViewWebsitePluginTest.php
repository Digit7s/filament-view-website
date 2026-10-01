<?php

use Digit7s\FilamentViewWebsite\ViewWebsitePlugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

it('creates a stable panel plugin', function (): void {
    $plugin = ViewWebsitePlugin::make();

    expect($plugin)
        ->toBeInstanceOf(ViewWebsitePlugin::class)
        ->and($plugin->getId())->toBe('digit7s-view-website');
});

it('uses the documented defaults', function (): void {
    $plugin = ViewWebsitePlugin::make();

    expect($plugin->getUrl())
        ->toBe('http://localhost')
        ->and($plugin->getLabel())->toBe('Visit Website')
        ->and($plugin->getIcon())->toBe('heroicon-o-arrow-top-right-on-square')
        ->and($plugin->getTooltip())->toBe('Open public website')
        ->and($plugin->shouldOpenInNewTab())->toBeTrue()
        ->and($plugin->isVisible())->toBeTrue();
});

it('supports static and closure configuration', function (): void {
    $plugin = ViewWebsitePlugin::make()
        ->url('https://example.com')
        ->label(fn (): string => 'Public site')
        ->icon('heroicon-o-globe-alt')
        ->tooltip('Open example.com')
        ->openInNewTab(false)
        ->visible(fn (): bool => true);

    expect($plugin->getUrl())->toBe('https://example.com')
        ->and($plugin->getLabel())->toBe('Public site')
        ->and($plugin->getIcon())->toBe('heroicon-o-globe-alt')
        ->and($plugin->getTooltip())->toBe('Open example.com')
        ->and($plugin->shouldOpenInNewTab())->toBeFalse()
        ->and($plugin->isVisible())->toBeTrue();
});

it('does not render for a blank resolved URL', function (): void {
    $plugin = ViewWebsitePlugin::make()->url(fn (): string => '');

    $html = view('filament-view-website::view-website', compact('plugin'))->render();

    expect($html)->toBe('');
});

it('does not render when hidden', function (): void {
    $plugin = ViewWebsitePlugin::make()->visible(false);

    $html = view('filament-view-website::view-website', compact('plugin'))->render();

    expect($html)->toBe('');
});

it('renders a native Filament link with safe new-tab attributes', function (): void {
    $plugin = ViewWebsitePlugin::make()->url('https://example.com');

    $html = view('filament-view-website::view-website', compact('plugin'))->render();

    expect($html)
        ->toContain('href="https://example.com"')
        ->toContain('target="_blank"')
        ->toContain('rel="noopener noreferrer"')
        ->toContain('Visit Website')
        ->toContain('Open public website')
        ->toContain('<svg');
});

it('omits new-tab attributes in same-tab mode', function (): void {
    $plugin = ViewWebsitePlugin::make()
        ->url('https://example.com')
        ->openInNewTab(false)
        ->tooltip(null);

    $html = view('filament-view-website::view-website', compact('plugin'))->render();

    expect($html)
        ->toContain('href="https://example.com"')
        ->not->toContain('target="_blank"')
        ->not->toContain('rel="noopener noreferrer"')
        ->not->toContain('x-tooltip');
});

it('registers its render hook only on the panel using the plugin', function (): void {
    $panel = Panel::make('admin')->plugin(ViewWebsitePlugin::make());
    $otherPanel = Panel::make('other');

    expect($panel->hasPlugin('digit7s-view-website'))->toBeTrue()
        ->and($otherPanel->hasPlugin('digit7s-view-website'))->toBeFalse();

    $panel->boot();

    expect(FilamentView::hasRenderHook(PanelsRenderHook::USER_MENU_BEFORE))->toBeTrue()
        ->and(FilamentView::renderHook(PanelsRenderHook::USER_MENU_BEFORE)->toHtml())
        ->toContain('Visit Website');
});
