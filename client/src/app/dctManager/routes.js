import { routes as Dashboard} from './dashboard'
import { routes as Buildings } from './buildings'
import { routes as Flats } from './flats'

const routes = [ ...Dashboard, ...Buildings, ...Flats, ]

export { routes }