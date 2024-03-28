<x-filament-panels::page>
    <p>
        This will generate the following files:
        <ul>
            @foreach($this->files as $file)
            <li>
                <x-filament::link target="_blank" :href="asset('storage/regio/'.$file['file'])">{{ $file['file'] }}</x-filament::link>
                @if($this->currentlyGenerating === $file['file']) <x-filament::loading-indicator class="w-4 h-4" /> @endif
            </li>
            @endforeach
        </ul>
    </p>
    <div>
        {{ $this->generateAction }}
    </div>
</x-filament-panels::page>
