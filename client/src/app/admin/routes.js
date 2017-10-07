import { routes as Dashboard} from './dashboard'
import { routes as Roles } from './roles'
import { routes as HouseTypes } from './houseTypes'
import { routes as CustomFeeTypes } from './customFeeTypes'
import { routes as Ops } from './opCompanies'
import { routes as Users } from './users'
import { routes as ParkingLots } from './parkingLots'
import { routes as Provinces } from './provinces'
import { routes as Cities } from './cities'
import { routes as CheckInTypes } from './checkInTypes'
import { routes as CheckInPositions } from './checkInPositions'

const routes = [ ...Dashboard, ...Roles, ...Ops, ...Users,
     ...HouseTypes, ...CustomFeeTypes, ...ParkingLots, ...Provinces, ...Cities,
     ...CheckInTypes, ...CheckInPositions ]

export { routes }