import { vuex as Dashboard } from './dashboard'
import { vuex as Roles } from './roles'

const vuex = { ...Dashboard, ...Roles }

export { vuex }