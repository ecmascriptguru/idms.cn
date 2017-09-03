import { routes as Dashboard} from './dashboard'
import { routes as PropertyCompanies } from './propertyCompanies'
import { routes as Users } from './users'
import { routes as Shop } from './shop'
import { routes as appAdvs} from './appAdvs'

const routes = [ ...Dashboard, ...PropertyCompanies, ...Users, ...appAdvs, ...Shop ]

export { routes }