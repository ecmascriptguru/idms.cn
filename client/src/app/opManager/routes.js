import { routes as Dashboard} from './dashboard'
import { routes as PropertyCompanies } from './propertyCompanies'
import { routes as Users } from './users'
import { routes as Shop } from './shop'
import { routes as appAdvs} from './appAdvs'
import { routes as deviceAdvs } from './deviceAdvs'

const routes = [ ...Dashboard, ...PropertyCompanies, ...Users, ...appAdvs, ...deviceAdvs, ...Shop ]

export { routes }