<x-filament-widgets::widget>
    <x-filament::section>
        <script id="stops-data" type="application/json">@json($this->getPageTableRecords())</script>
        <div
            id="map"
            style="height: 30vh;"
            x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('stops-map'))]"
        ></div>
    </x-filament::section>
</x-filament-widgets::widget>
