
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const OperatingCompanies = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.ops.index',
    path: '/admin/ops',
    component: OperatingCompanies,
    meta,
    children: [
      {
        name: 'admin.ops.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.ops.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
