import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

import request from './request.module'

export const store = new Vuex.Store({
  modules: {
    request
  }
});