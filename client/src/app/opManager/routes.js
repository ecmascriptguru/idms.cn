import { routes as Dashboard} from './dashboard'
import { routes as PropertyCompanies } from './propertyCompanies'
import { routes as Users } from './users'
import { routes as Shop } from './shop'

const routes = [ ...Dashboard, ...PropertyCompanies, ...Users, ...Shop ]

export { routes }