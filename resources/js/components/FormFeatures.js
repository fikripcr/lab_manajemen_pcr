// Lazy load Flatpickr
export async function loadFlatpickr() {
    if (window.flatpickr) return window.flatpickr;

    const flatpickr = (await import('flatpickr')).default;
    await import('flatpickr/dist/flatpickr.min.css');
    window.flatpickr = flatpickr;
    return flatpickr;
}

// Lazy load Choices.js
export async function loadChoices() {
    if (window.Choices) return window.Choices;

    const Choices = (await import('choices.js')).default;
    window.Choices = Choices;
    return Choices;
}

// Lazy load FilePond
export async function loadFilePond() {
    if (window.FilePond) return window.FilePond;

    const FilePond = await import('filepond');
    await import('filepond/dist/filepond.min.css');

    const FilePondPluginImagePreview = (await import('filepond-plugin-image-preview')).default;
    await import('filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css');

    FilePond.registerPlugin(FilePondPluginImagePreview);
    window.FilePond = FilePond;
    return FilePond;
}

// Load all form features (for pages that need all)
export async function loadAllFormFeatures() {
    await Promise.all([
        loadFlatpickr(),
        loadChoices(),
        loadFilePond()
    ]);
}

// Global helper for backward compatibility
window.loadFormFeatures = loadAllFormFeatures;
