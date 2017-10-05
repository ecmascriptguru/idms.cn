import { routes as Dashboard} from './dashboard'
import { routes as Roles } from './roles'
import { routes as HouseTypes } from './houseTypes'
import { routes as CustomFeeTypes } from './customFeeTypes'
import { routes as Ops } from './opCompanies'
import { routes as Users } from './users'
import { routes as ParkingLots } from './parkingLots'

const routes = [ ...Dashboard, ...Roles, ...Ops, ...Users, ...HouseTypes, ...CustomFeeTypes, ...ParkingLots ]

export { routes }