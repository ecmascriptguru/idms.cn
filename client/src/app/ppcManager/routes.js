import { routes as Dashboard} from './dashboard'
import { routes as Districts } from './districts'
import { routes as Users } from './users'
import { routes as FeeStandards } from './feeStandards'

const routes = [ ...Dashboard, ...Districts, ...Users, ...FeeStandards ]

export { routes }