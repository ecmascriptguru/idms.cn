
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Provinces = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.provinces.index',
    path: '/admin/provinces',
    component: Provinces,
    meta,
    children: [
      {
        name: 'admin.provinces.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.provinces.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
