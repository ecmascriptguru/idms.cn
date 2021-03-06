import * as TYPES from '../../store/types'

const state = {
  Districts: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Users: {
    organization_id: 2,
    role_id: 3,
    list: [],
    full_list: [],
    pagination: {},
  },
  FeeStandards: {
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
  [TYPES.PPC_USERS_SET_DATA](st, obj) {
    if (obj.list) {
      st.Users.list = obj.list
    }
    if (obj.full_list) {
      st.Users.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Users.pagination = obj.pagination
    }
  },
  [TYPES.PPC_FEE_STANDARDS_SET_DATA](st, obj) {
    if (obj.list) {
      st.FeeStandards.list = obj.list
    }
    if (obj.full_list) {
      st.FeeStandards.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.FeeStandards.pagination = obj.pagination
    }
  },
}

const actions = {
  districtsSetData({ commit }, obj) {
    commit(TYPES.DISTRICTS_SET_DATA, obj)
  },
  usersSetData({ commit }, obj) {
    commit(TYPES.PPC_USERS_SET_DATA, obj)
  },
  feeStandardsSetData({ commit }, obj) {
    commit(TYPES.PPC_FEE_STANDARDS_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
