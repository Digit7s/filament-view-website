<?php

namespace Digit7s\FilamentViewWebsite;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ViewWebsiteServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-view-website';

    public static string $viewNamespace = 'filament-view-website';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews(static::$viewNamespace);
    }
}
