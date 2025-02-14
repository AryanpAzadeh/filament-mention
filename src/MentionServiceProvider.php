<?php

namespace Asmit\Mention;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MentionServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('asmit-mention')
            ->hasViews()
            ->hasConfigFile(['mention']);
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            AlpineComponent::make(id: 'tributejs', path: __DIR__.'/../dist/tributejs.js'),
            Css::make(id: 'asmit-mention', path: __DIR__.'/../resources/css/asmit-mention.css')->loadedOnRequest(),
        ], package: 'asmit/mention');
    }
}
