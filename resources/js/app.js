import './bootstrap';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

/**
 * Global Password Visibility Toggle
 */
window.togglePasswordVisibility = function(fieldId, btnOrIconId) {
    const input = document.getElementById(fieldId);
    if (!input) return;
    let icon = null;
    if (typeof btnOrIconId === 'string') {
        icon = document.getElementById(btnOrIconId);
    } else if (btnOrIconId) {
        icon = btnOrIconId.querySelector ? btnOrIconId.querySelector('i') : btnOrIconId;
    }
    if (!icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
};
