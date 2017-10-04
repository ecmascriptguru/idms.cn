
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const HouseTypes = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.houseTypes.index',
    path: '/admin/houseTypes',
    component: HouseTypes,
    meta,
    children: [
      {
        name: 'admin.houseTypes.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.houseTypes.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
