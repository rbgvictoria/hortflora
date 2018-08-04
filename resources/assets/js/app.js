
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
require('./keybase')

window.Vue = require('vue')

import router from './router'
import store from './store'
import '../../../node_modules/nprogress/nprogress.css'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
)

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
)

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
)

Vue.component(
    'taxon-page',
    require('./components/TaxonPage.vue')
)

import App from './components/App.vue'

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store
})
