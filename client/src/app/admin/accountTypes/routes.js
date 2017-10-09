
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const AccTypes = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.accountTypes.index',
    path: '/admin/accountTypes',
    component: AccTypes,
    meta,
    children: [
      {
        name: 'admin.accountTypes.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.accountTypes.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
