import { routes as Dashboard} from './dashboard'
import { routes as Roles } from './roles'
import { routes as HouseTypes } from './houseTypes'
import { routes as Ops } from './opCompanies'
import { routes as Users } from './users'

const routes = [ ...Dashboard, ...Roles, ...Ops, ...Users, ...HouseTypes ]

export { routes }