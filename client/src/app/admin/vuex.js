import * as TYPES from '../../store/types'

const state = {
  Roles: {
    list: [],
    full_list: [],
    pagination: {},
  },
  HouseTypes: {
    list: [],
    full_list: [],
    pagination: {},
  },
  CustomFeeTypes: {
    list: [],
    full_list: [],
    pagination: {},
  },
  OperatingCompanies: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Users: {
    organization_id: 2,
    role_id: 2,
    list: [],
    full_list: [],
    pagination: {},
  },
  ParkingLots: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Provinces: {
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
  [TYPES.ADMIN_USERS_SET_DATA](st, obj) {
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
  [TYPES.ADMIN_HOUSE_TYPES_SET_DATA](st, obj) {
    if (obj.list) {
      st.HouseTypes.list = obj.list
    }
    if (obj.full_list) {
      st.HouseTypes.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.HouseTypes.pagination = obj.pagination
    }
  },
  [TYPES.ADMIN_CUSTOM_FEE_TYPES_SET_DATA](st, obj) {
    if (obj.list) {
      st.CustomFeeTypes.list = obj.list
    }
    if (obj.full_list) {
      st.CustomFeeTypes.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.CustomFeeTypes.pagination = obj.pagination
    }
  },
  [TYPES.ADMIN_PARKING_LOTS_SET_DATA](st, obj) {
    if (obj.list) {
      st.ParkingLots.list = obj.list
    }
    if (obj.full_list) {
      st.ParkingLots.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.ParkingLots.pagination = obj.pagination
    }
  },
  [TYPES.ADMIN_PROVINCES_SET_DATA](st, obj) {
    if (obj.list) {
      st.Provinces.list = obj.list
    }
    if (obj.full_list) {
      st.Provinces.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Provinces.pagination = obj.pagination
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
  usersSetData({ commit }, obj) {
    commit(TYPES.ADMIN_USERS_SET_DATA, obj)
  },
  houseTypesSetData({ commit }, obj) {
    commit(TYPES.ADMIN_HOUSE_TYPES_SET_DATA, obj)
  },
  customFeeTypesSetData({ commit }, obj) {
    commit(TYPES.ADMIN_CUSTOM_FEE_TYPES_SET_DATA, obj)
  },
  parkingLotsSetData({ commit }, obj) {
    commit(TYPES.ADMIN_PARKING_LOTS_SET_DATA, obj)
  },
  provincesSetData({ commit }, obj) {
    commit(TYPES.ADMIN_PROVINCES_SET_DATA, obj)
  }
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
