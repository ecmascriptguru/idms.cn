import { routes as Dashboard} from './dashboard'
import { routes as Buildings } from './buildings'
import { routes as Flats } from './flats'
import { routes as Import } from './import'
import { routes as FeeStandards } from './feeStandards'
import { routes as BillTerms } from './billTerms'
import { routes as Bills } from './bills'
import { routes as Devices } from './devices'
import { routes as Monitor } from './deviceMonitor'
import { routes as Certifications } from './certifications'
import { routes as OwnerCards } from './ownerCards'
import { routes as FingerPrints } from './fingerPrints'
import { routes as CheckInLogs } from './checkInLogs'

const routes = [ ...Dashboard, ...Buildings, ...Flats, ...FingerPrints,
    ...Import, ...FeeStandards, ...BillTerms, ...Bills, ...CheckInLogs,
    ...Devices, ...Monitor, ...Certifications, ...OwnerCards ]

export { routes }