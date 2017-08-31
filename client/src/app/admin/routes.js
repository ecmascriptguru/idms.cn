import { routes as Dashboard} from './dashboard'
import { routes as Roles } from './roles'
import { routes as ops } from './opCompanies'

const routes = [ ...Dashboard, ...Roles, ...ops ]

export { routes }