import { routes as Dashboard} from './dashboard'
import { routes as Buildings } from './buildings'
import { routes as Flats } from './flats'
import { routes as Import } from './import'
import { routes as FeeStandards } from './feeStandards'
import { routes as BillTerms } from './billTerms'

const routes = [ ...Dashboard, ...Buildings, ...Flats, ...Import, ...FeeStandards, ...BillTerms ]

export { routes }