
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Users = r => require.ensure([], () => r(require('./main')), 'ppc-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'ppc-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'ppcManager.users.index',
    path: '/ppc/users',
    component: Users,
    meta,
    children: [
      {
        name: 'ppcManager.users.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'ppcManager.users.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
