/**
 * FormPositioner - Auth Form Positioning
 * Handles left/right positioning for auth cover & illustration layouts
 */
class FormPositioner {
    constructor(themeManager) {
        this.themeManager = themeManager;
        this.formColumn = document.querySelector('[data-form-column]');
        this.mediaColumn = document.querySelector('[data-media-column]');
    }

    /**
     * Initialize form positioning from localStorage
     */
    init() {
        if (!this.formColumn || !this.mediaColumn) {
            // Not on a layout that supports positioning
            return;
        }

        const position = this.themeManager.getSetting('auth-form-position') || 'left';
        this.applyPosition(position);

        // Listen for live updates
        this.themeManager.subscribe((name, value) => {
            if (name === 'auth-form-position') {
                this.applyPosition(value);
            }
        });
    }

    /**
     * Apply form position (left or right)
     * @param {string} position - 'left' or 'right'
     */
    applyPosition(position) {
        if (position === 'right') {
            this.formColumn.style.order = '2';
            this.mediaColumn.style.order = '1';
        } else {
            this.formColumn.style.order = '';
            this.mediaColumn.style.order = '';
        }
    }
}

export default FormPositioner;
