import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import persist from '@alpinejs/persist'

window.Alpine = Alpine;

Alpine.plugin(collapse)
Alpine.plugin(persist)


Alpine.store('darkMode', {
    on: Alpine.$persist(true).as('darkMode_on'),

    toggle() {
        this.on = !this.on
    }
})


Alpine.start();
