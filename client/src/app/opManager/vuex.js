import * as TYPES from '../../store/types'

const state = {
  PropertyCompanies: {
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
  Dashboard: {

  }
}

/* eslint-disable no-param-reassign */
const mutations = {
  [TYPES.PROPERTY_COMPANIES_SET_DATA](st, obj) {
    if (obj.list) {
      st.PropertyCompanies.list = obj.list
    }
    if (obj.full_list) {
      st.PropertyCompanies.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.PropertyCompanies.pagination = obj.pagination
    }
  },
  [TYPES.OP_USERS_SET_DATA](st, obj) {
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
}

const actions = {
  propertyCompaniesSetData({ commit }, obj) {
    commit(TYPES.PROPERTY_COMPANIES_SET_DATA, obj)
  },
  usersSetData({ commit }, obj) {
    commit(TYPES.OP_USERS_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
