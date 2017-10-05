
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const CustomFeeTypes = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.customFeeTypes.index',
    path: '/admin/customFeeTypes',
    component: CustomFeeTypes,
    meta,
    children: [
      {
        name: 'admin.customFeeTypes.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.customFeeTypes.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
