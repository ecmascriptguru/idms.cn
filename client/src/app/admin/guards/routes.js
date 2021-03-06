
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Guards = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.guards.index',
    path: '/admin/guards',
    component: Guards,
    meta,
    children: [
      {
        name: 'admin.guards.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.guards.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
