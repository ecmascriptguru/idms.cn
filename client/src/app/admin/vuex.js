// import { vuex as Dashboard } from './dashboard'
// import { vuex as Roles } from './roles'
// import { vuex as OperatingCompanies } from './opCompanies'

// const vuex = { ...Dashboard, ...Roles, ...OperatingCompanies }

// export { vuex }

import * as TYPES from '../../store/types'

const state = {
  Roles: {
    list: [],
    full_list: [],
    pagination: {},
  },
  OperatingCompanies: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Dashboard: {
      
  }
}

/* eslint-disable no-param-reassign */
const mutations = {
  [TYPES.ROLES_SET_DATA](st, obj) {
    if (obj.list) {
      st.Roles.list = obj.list
    }
    if (obj.full_list) {
      st.Roles.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Roles.pagination = obj.pagination
    }
  },
  [TYPES.OPERATING_COMPANIES_SET_DATA](st, obj) {
    if (obj.list) {
      st.OperatingCompanies.list = obj.list
    }
    if (obj.full_list) {
      st.OperatingCompanies.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.OperatingCompanies.pagination = obj.pagination
    }
  },
}

const actions = {
  rolesSetData({ commit }, obj) {
    commit(TYPES.ROLES_SET_DATA, obj)
  },
  operatingCompaniesSetData({ commit }, obj) {
    commit(TYPES.OPERATING_COMPANIES_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
