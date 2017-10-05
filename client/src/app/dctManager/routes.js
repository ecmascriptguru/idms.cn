import { routes as Dashboard} from './dashboard'
import { routes as Buildings } from './buildings'
import { routes as Flats } from './flats'
import { routes as Import } from './import'

const routes = [ ...Dashboard, ...Buildings, ...Flats, ...Import, ]

export { routes }