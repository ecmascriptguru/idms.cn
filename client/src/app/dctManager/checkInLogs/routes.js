
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Logs = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.checkInLogs.index',
    path: '/dct/checkInLogs',
    component: Logs,
    meta,
    children: [
      {
        name: 'dctManager.checkInLogs.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.checkInLogs.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
