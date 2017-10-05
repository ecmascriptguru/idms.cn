
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Cities = r => require.ensure([], () => r(require('./main')), 'admin-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'admin-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'admin.cities.index',
    path: '/admin/cities',
    component: Cities,
    meta,
    children: [
      {
        name: 'admin.cities.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'admin.cities.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
