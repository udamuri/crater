import Vue from 'vue'
import Vuex from 'vuex'

import * as getters from './getters'
import mutations from './mutations'
import actions from './actions'

import auth from './modules/auth'
import user from './modules/user'
import company from './modules/company'
import dashboard from './modules/dashboard'
import modal from './modules/modal'
import users from './modules/users'
import backup from './modules/backup'
import disks from './modules/disk'
import search from './modules/search'
import notification from './modules/notification'

Vue.use(Vuex)

const initialState = {
  languages: [],

  timeZones: [],

  dateFormats: [],

  fiscalYears: [],

  currencies: [],

  countries: [],

  isAppLoaded: false,

  isSidebarOpen: false,
}

export default new Vuex.Store({
  strict: true,
  state: initialState,
  getters,
  mutations,
  actions,

  modules: {
    auth,
    user,
    company,
    dashboard,
    modal,
    users,
    backup,
    disks,
    search,
    notification,
  },
})
