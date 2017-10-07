
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Devices = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.devices.index',
    path: '/dct/devices',
    component: Devices,
    meta,
    children: [
      {
        name: 'dctManager.devices.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.devices.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
