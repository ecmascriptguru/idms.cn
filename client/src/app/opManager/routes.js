import { routes as Dashboard} from './dashboard'
import { routes as PropertyCompanies } from './propertyCompanies'
import { routes as Users } from './users'

const routes = [ ...Dashboard, ...PropertyCompanies, ...Users ]

export { routes }