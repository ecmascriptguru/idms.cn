import * as TYPES from '../../../store/types'

const state = {
  Roles: {
    list: [],
    full_list: [],
    pagination: {},
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
}

const actions = {
  rolesSetData({ commit }, obj) {
    commit(TYPES.ROLES_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
