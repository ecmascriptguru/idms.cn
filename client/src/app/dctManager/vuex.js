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
  [TYPES.DISTRICTS_SET_DATA](st, obj) {
    if (obj.list) {
      st.Districts.list = obj.list
    }
    if (obj.full_list) {
      st.Districts.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Districts.pagination = obj.pagination
    }
  },
}

const actions = {
  districtsSetData({ commit }, obj) {
    commit(TYPES.DISTRICTS_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
