@if ($plugin->isVisible())
    @php
        $url = $plugin->getUrl();
        $shouldOpenInNewTab = $plugin->shouldOpenInNewTab();
    @endphp

    @if (filled($url))
        <x-filament::button
            :href="$url"
            :icon="$plugin->getIcon()"
            :rel="$shouldOpenInNewTab ? 'noopener noreferrer' : null"
            :target="$shouldOpenInNewTab ? '_blank' : null"
            :tooltip="$plugin->getTooltip()"
            color="gray"
            size="sm"
            tag="a"
        >
            {{ $plugin->getLabel() }}
        </x-filament::button>
    @endif
@endif
