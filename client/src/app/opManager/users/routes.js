
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Users = r => require.ensure([], () => r(require('./main')), 'opManager-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'opManager-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'opManager.users.index',
    path: '/op/users',
    component: Users,
    meta,
    children: [
      {
        name: 'opManager.users.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'opManager.users.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
