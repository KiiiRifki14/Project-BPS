import './bootstrap';
import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

if (!window.alpineStarted) {
    Alpine.start();
    window.alpineStarted = true;
}

// Re-initialize Alpine on Turbo page loads if needed
document.addEventListener('turbo:load', () => {
    // Scroll page body smoothly to top on navigation
    window.scrollTo({ top: 0, behavior: 'instant' });
});
