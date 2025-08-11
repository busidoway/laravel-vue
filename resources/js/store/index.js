import Vue from "vue";
import Vuex from 'vuex';
import reestr from "./modules/reestr";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        reestr
    }
});
