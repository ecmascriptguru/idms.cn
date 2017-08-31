
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Users = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.users.index',
    path: '/admin/users',
    component: Users,
    meta,
    children: [
      {
        name: 'admin.users.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.users.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
