import * as TYPES from '../../store/types'

const state = {
  Buildings: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Dashboard: {

  }
}

/* eslint-disable no-param-reassign */
const mutations = {
  [TYPES.BUILDINGS_SET_DATA](st, obj) {
    if (obj.list) {
      st.Buildings.list = obj.list
    }
    if (obj.full_list) {
      st.Buildings.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Buildings.pagination = obj.pagination
    }
  },
}

const actions = {
  buildingsSetData({ commit }, obj) {
    commit(TYPES.BUILDINGS_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
