
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const DeviceAdvs = r => require.ensure([], () => r(require('./main')), 'opManager-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'opManager-bundle')

const meta = {
  requiresAuth: true,
}

export default [
  {
    name: 'opManager.deviceAdvs.index',
    path: '/op/deviceAdvs',
    component: DeviceAdvs,
    meta,
    children: [
      {
        name: 'opManager.deviceAdvs.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'opManager.deviceAdvs.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
