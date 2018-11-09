import Vue from 'vue';
import axios from 'axios';
import store from './store';
import NProgress from 'nprogress';
import '../css/nprogress.css';
NProgress.configure({
    template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><i class="fa fa-spinner fa-2x fa-spin"></i></div>'
});

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import HomePage from './components/HomePage.vue';
import TaxonPage from './components/TaxonPage.vue';
import KeyPage from './components/KeyPage.vue';
import SearchPage from './components/SearchPage.vue';
import LoginPage from './components/LoginPage.vue';
import Glossary from './components/glossary/Glossary.vue'
import PassportDashboard from './components/PassportDashboard.vue'

let router = new VueRouter({
  mode: 'history',
  routes: [
    { path: '/', component: HomePage, name: 'home' },
    { path: '/taxa/:taxon', component: TaxonPage, name: 'taxa' },
    { path: '/keys/:key', component: KeyPage, name: 'keys' },
    { path: '/search', component: SearchPage, name: 'search' },
    { path: '/login', component: LoginPage, name: 'login' },
    { path: '/glossary', component: Glossary, name: 'glossary' }
  ],
  scrollBehavior (to, from, savedPosition) {
    return { x: 0, y: 0 }
  }
});

router.beforeEach((to, from, next) => {
    NProgress.start();
    let serverData = JSON.parse(window.hortflora_server_data);
    if (serverData) {
        store.commit('storeAuthenticationInfo', { route: to.name, serverData });
    }
    if (to.name === 'taxa') {
        let includes = 'vernacularNames,currentTreatment.currentVersion,heroImage,classification,siblings,children,cultivars.currentTreatment.currentVersion';
        axios.get(`/api/taxa/${to.params.taxon}`, {
            params: {
                include: includes
            }
        }).then(({data}) => {
            store.commit('storeTaxon', { route: to.name, data });
            next();
        });
    }
    else if (to.name === 'search') {
        let query = to.query;
        axios.get(`/api/search`, {
            params: query
        }).then(({data}) => {
            store.commit('storeSearchResult', { route: to.name, data });
            next();
        });
    }
    else {
        next();
    }
});

router.afterEach((to, from) => {
    NProgress.done();
})

export default router;
