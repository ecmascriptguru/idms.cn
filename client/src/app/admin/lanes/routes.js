
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Lanes = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.lanes.index',
    path: '/admin/lanes',
    component: Lanes,
    meta,
    children: [
      {
        name: 'admin.lanes.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.lanes.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
