
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const ParkingLots = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.parkingLots.index',
    path: '/admin/parkingLots',
    component: ParkingLots,
    meta,
    children: [
      {
        name: 'admin.parkingLots.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.parkingLots.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
