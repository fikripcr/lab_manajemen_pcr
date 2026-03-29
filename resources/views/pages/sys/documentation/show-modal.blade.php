<x-tabler.form-modal title="{{ $doc['title'] }}" method="none" size="lg">
    <div class="documentation-content p-2" style="max-height: 500px; overflow-y: auto;">
        {!! $htmlContent !!}
    </div>

    <div class="text-muted small mt-3 border-top pt-2">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4" /><path d="M12 16l0 .01" /><path d="M3 12a9 9 0 1 1 18 0a9 9 0 0 1 -18 0" /></svg>
                Updated: {{ formatTanggalIndo($doc['lastUpdated']) }}
            </div>
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h2m0 4c.01 .01 0 0 0 0s-2 -.01 -2 0m0 4c.01 .01 0 0 0 0s-2 -.01 -2 0" /><path d="M3 5a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /></svg>
                {{ number_format($doc['size'] / 1024, 1) }} KB
            </div>
        </div>
    </div>

    <x-slot:footer>
        <x-tabler.button type="cancel" data-bs-dismiss="modal" text="Close" />
        <div class="d-flex gap-2 ms-auto">
            <x-tabler.button :href="route('sys.documentation.edit', ['path' => $doc['name']])" class="btn-outline-primary" icon="ti ti-pencil" text="Edit" />
            <x-tabler.button :href="route('sys.documentation.show', ['path' => $doc['name']])" icon="ti ti-external-link" text="View Full Page" />
        </div>
    </x-slot:footer>
</x-tabler.form-modal>
