<div class="settings">
    <a href="#" class="btn btn-floating btn-icon btn-white" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAuthSettings" aria-label="Auth Settings">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /></svg>
    </a>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAuthSettings">
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title">Auth Settings</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Layout Mode -->
            <div class="mb-3">
                <label class="form-label">Layout Mode</label>
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-layout" value="basic" class="form-selectgroup-input" checked>
                        <span class="form-selectgroup-label">Basic</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-layout" value="illustration" class="form-selectgroup-input">
                        <span class="form-selectgroup-label">Illustration</span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-layout" value="cover" class="form-selectgroup-input">
                        <span class="form-selectgroup-label">Cover</span>
                    </label>
                </div>
            </div>

            <!-- Form Position (for Illustration & Cover layouts) -->
            <div class="mb-3">
                <label class="form-label">Form Position</label>
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-form-position" value="left" class="form-selectgroup-input" checked>
                        <span class="form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 4l0 16" /><path d="M6 8l0 8" /><path d="M10 16l0 -8" /></svg>
                            Left
                        </span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-form-position" value="right" class="form-selectgroup-input">
                        <span class="form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 4l0 16" /><path d="M18 8l0 8" /><path d="M14 16l0 -8" /></svg>
                            Right
                        </span>
                    </label>
                </div>
                <small class="form-hint">Applies to Illustration and Cover layouts only</small>
            </div>

            <!-- Theme Mode -->
            <div class="mb-3">
                <label class="form-label">Theme Mode</label>
                <div class="form-selectgroup">
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-theme" value="light" class="form-selectgroup-input" checked>
                        <span class="form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                            Light
                        </span>
                    </label>
                    <label class="form-selectgroup-item">
                        <input type="radio" name="auth-theme" value="dark" class="form-selectgroup-input">
                        <span class="form-selectgroup-label">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                            Dark
                        </span>
                    </label>
                </div>
            </div>

            <hr class="my-3">

            <div class="text-muted small">
                <strong>Note:</strong> Font, radius, and color settings inherit from your System theme preferences.
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const layoutInputs = document.querySelectorAll('input[name="auth-layout"]');
    const themeInputs = document.querySelectorAll('input[name="auth-theme"]');
    const positionInputs = document.querySelectorAll('input[name="auth-form-position"]');
    
    // Load all settings
    const settings = {
        authLayout: localStorage.getItem('tabler-auth-layout') || 'basic',
        authTheme: localStorage.getItem('tabler-auth-theme') || 'light',
        formPosition: localStorage.getItem('tabler-auth-form-position') || 'left',
        sysFont: localStorage.getItem('tabler-theme-font') || 'inter',
        sysRadius: localStorage.getItem('tabler-theme-radius') || '1',
        sysPrimary: localStorage.getItem('tabler-theme-primary') || '#206bc4',
        sysBase: localStorage.getItem('tabler-theme-base') || 'gray'
    };

    // Apply all settings
    applySettings(settings);

    // Set radio states
    document.querySelector(`input[name="auth-layout"][value="${settings.authLayout}"]`).checked = true;
    document.querySelector(`input[name="auth-theme"][value="${settings.authTheme}"]`).checked = true;
    document.querySelector(`input[name="auth-form-position"][value="${settings.formPosition}"]`).checked = true;

    // Event listeners
    layoutInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const val = e.target.value;
            document.body.setAttribute('data-auth-layout', val);
            localStorage.setItem('tabler-auth-layout', val);
        });
    });

    themeInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const val = e.target.value;
            document.documentElement.setAttribute('data-bs-theme', val);
            localStorage.setItem('tabler-auth-theme', val);
        });
    });

    positionInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const val = e.target.value;
            document.body.setAttribute('data-auth-form-position', val);
            localStorage.setItem('tabler-auth-form-position', val);
        });
    });

    // Unified apply function
    function applySettings(s) {
        const { authLayout, authTheme, formPosition, sysFont, sysBase, sysRadius, sysPrimary } = s;
        
        document.body.setAttribute('data-auth-layout', authLayout);
        document.body.setAttribute('data-auth-form-position', formPosition);
        document.documentElement.setAttribute('data-bs-theme', authTheme);
        document.documentElement.setAttribute('data-bs-theme-font', sysFont);
        document.documentElement.setAttribute('data-bs-theme-base', sysBase);
        document.documentElement.setAttribute('data-bs-theme-radius', sysRadius);
        document.documentElement.style.setProperty('--tblr-border-radius', sysRadius + 'rem');
        document.documentElement.style.setProperty('--tblr-primary', sysPrimary);
    }
});
</script>

<style>
.settings {
    position: fixed;
    right: 0;
    bottom: 0;
    z-index: 1000;
    padding: 1rem;
}
.btn-floating {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
