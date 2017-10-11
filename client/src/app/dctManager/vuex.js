import * as TYPES from '../../store/types'

const state = {
  Buildings: {
    list: [],
    full_list: [],
    pagination: {},
  },
  BillTerms: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Bills: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Flats: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Devices: {
    list: [],
    full_list: [],
    pagination: {},
  },
  Certifications : {
    list: [],
    full_list: [],
    pagination: {},
  },
  OwnerCards : {
    list: [],
    full_list: [],
    pagination: {},
  },
  FingerPrints : {
    list: [],
    full_list: [],
    pagination: {},
  },
  CheckInLogs : {
    list: [],
    full_list: [],
    pagination: {},
  },
  Dashboard: {

  }
}

/* eslint-disable no-param-reassign */
const mutations = {
  [TYPES.DCT_BUILDINGS_SET_DATA](st, obj) {
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
  [TYPES.DCT_CERTIFICATIONS_SET_DATA](st, obj) {
    if (obj.list) {
      st.Certifications.list = obj.list
    }
    if (obj.full_list) {
      st.Certifications.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Certifications.pagination = obj.pagination
    }
  },
  [TYPES.DCT_FLATS_SET_DATA](st, obj) {
    if (obj.list) {
      st.Flats.list = obj.list
    }
    if (obj.full_list) {
      st.Flats.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Flats.pagination = obj.pagination
    }
  },
  [TYPES.DCT_BILL_TERMS_SET_DATA](st, obj) {
    if (obj.list) {
      st.BillTerms.list = obj.list
    }
    if (obj.full_list) {
      st.BillTerms.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.BillTerms.pagination = obj.pagination
    }
  },
  [TYPES.DCT_BILLS_SET_DATA](st, obj) {
    if (obj.list) {
      st.Bills.list = obj.list
    }
    if (obj.full_list) {
      st.Bills.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Bills.pagination = obj.pagination
    }
  },
  [TYPES.DCT_DEVICES_SET_DATA](st, obj) {
    if (obj.list) {
      st.Devices.list = obj.list
    }
    if (obj.full_list) {
      st.Devices.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.Devices.pagination = obj.pagination
    }
  },
  [TYPES.DCT_OWNER_CARDS_SET_DATA](st, obj) {
    if (obj.list) {
      st.OwnerCards.list = obj.list
    }
    if (obj.full_list) {
      st.OwnerCards.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.OwnerCards.pagination = obj.pagination
    }
  },
  [TYPES.DCT_FINGER_PRINTS_SET_DATA](st, obj) {
    if (obj.list) {
      st.FingerPrints.list = obj.list
    }
    if (obj.full_list) {
      st.FingerPrints.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.FingerPrints.pagination = obj.pagination
    }
  },
  [TYPES.DCT_CHECK_IN_LOGS_SET_DATA](st, obj) {
    if (obj.list) {
      st.CheckInLogs.list = obj.list
    }
    if (obj.full_list) {
      st.CheckInLogs.full_list = obj.full_list
    }
    if (obj.pagination) {
      st.CheckInLogs.pagination = obj.pagination
    }
  },
}

const actions = {
  buildingsSetData({ commit }, obj) {
    commit(TYPES.DCT_BUILDINGS_SET_DATA, obj)
  },
  certificationsSetData({ commit }, obj) {
    commit(TYPES.DCT_CERTIFICATIONS_SET_DATA, obj)
  },
  flatsSetData({ commit }, obj) {
    commit(TYPES.DCT_FLATS_SET_DATA, obj)
  },
  billTermsSetData({ commit }, obj) {
    commit(TYPES.DCT_BILL_TERMS_SET_DATA, obj)
  },
  billsSetData({ commit }, obj) {
    commit(TYPES.DCT_BILLS_SET_DATA, obj)
  },
  devicesSetData({ commit }, obj) {
    commit(TYPES.DCT_DEVICES_SET_DATA, obj)
  },
  ownerCardsSetData({ commit }, obj) {
    commit(TYPES.DCT_OWNER_CARDS_SET_DATA, obj)
  },
  fingerPrintsSetData({ commit }, obj) {
    commit(TYPES.DCT_FINGER_PRINTS_SET_DATA, obj)
  },
  checkInLogsSetData({ commit }, obj) {
    commit(TYPES.DCT_CHECK_IN_LOGS_SET_DATA, obj)
  },
}

const module = {
  state,
  mutations,
  actions,
}

export default { module }
