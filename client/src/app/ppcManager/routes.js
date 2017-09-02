import { routes as Dashboard} from './dashboard'
import { routes as Districts } from './districts'
import { routes as Users } from './users'

const routes = [ ...Dashboard, ...Districts, ...Users ]

export { routes }