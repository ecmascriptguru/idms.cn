
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const CheckInTypes = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.checkInTypes.index',
    path: '/admin/checkInTypes',
    component: CheckInTypes,
    meta,
    children: [
      {
        name: 'admin.checkInTypes.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.checkInTypes.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
