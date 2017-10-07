import { routes as Dashboard} from './dashboard'
import { routes as Buildings } from './buildings'
import { routes as Flats } from './flats'
import { routes as Import } from './import'
import { routes as FeeStandards } from './feeStandards'
import { routes as BillTerms } from './billTerms'
import { routes as Bills } from './bills'
import { routes as Devices } from './devices'
import { routes as Monitor } from './deviceMonitor'

const routes = [ ...Dashboard, ...Buildings, ...Flats, ...Import, ...FeeStandards, ...BillTerms, ...Bills, ...Devices, ...Monitor ]

export { routes }