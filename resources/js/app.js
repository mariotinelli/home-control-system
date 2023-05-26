import './bootstrap';

import Alpine from 'alpinejs';

import Focus from '@alpinejs/focus';
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
import AlpineFloatingUI from '@awcodes/alpine-floating-ui'
import NotificationsAlpinePlugin from '../../vendor/filament/notifications/dist/module.esm'
import collapse from '@alpinejs/collapse'
import persist from '@alpinejs/persist'
import Tooltip from "@ryangjchandler/alpine-tooltip";


window.Alpine = Alpine;

Alpine.plugin(Tooltip);
Alpine.plugin(collapse)
Alpine.plugin(persist)
Alpine.plugin(Focus)
Alpine.plugin(FormsAlpinePlugin)
Alpine.plugin(AlpineFloatingUI)
Alpine.plugin(NotificationsAlpinePlugin)

Alpine.store('darkMode', {
    on: Alpine.$persist(true).as('darkMode_on'),

    toggle() {
        this.on = !this.on
    }
})


Alpine.start();
