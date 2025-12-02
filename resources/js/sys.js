import './global.js';

// --- Choices.js
import Choices from 'choices.js';
window.Choices = Choices;

// --- Global Search
import { GlobalSearch } from './components/GlobalSearch.js';
window.GlobalSearch = GlobalSearch;

// --- Notification Manager
import { NotificationManager } from './components/Notification.js';
window.NotificationManager = NotificationManager;

// --- TOAST UI Editor (dynamic import)
window.initToastEditor = function(selector, config = {}) {
    import('@toast-ui/editor').then(({ Editor }) => {
        new Editor({
            el: document.querySelector(selector),
            ...config
        });
    });
};
