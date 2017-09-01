
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const PropertyCompanies = r => require.ensure([], () => r(require('./main')), 'opManager-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'opManager-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'opManager.propertyCompanies.index',
    path: '/op/ppCompanies',
    component: PropertyCompanies,
    meta,
    children: [
      {
        name: 'opManager.propertyCompanies.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'opManager.propertyCompanies.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
