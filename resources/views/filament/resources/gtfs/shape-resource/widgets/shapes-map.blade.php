<x-filament-widgets::widget>
    <x-filament::section>
        <script id="shapes-data" type="application/json">@json($this->getPageTableRecords())</script>
        <div
            id="map"
            style="height: 30vh;"
            x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('shapes-map'))]"
        ></div>
    </x-filament::section>
</x-filament-widgets::widget>
