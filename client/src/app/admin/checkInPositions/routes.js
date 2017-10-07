
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const CheckInPositions = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.checkInPositions.index',
    path: '/admin/checkInPositions',
    component: CheckInPositions,
    meta,
    children: [
      {
        name: 'admin.checkInPositions.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.checkInPositions.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
