
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Districts = r => require.ensure([], () => r(require('./main')), 'ppc-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'ppc-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'ppcManager.districts.index',
    path: '/op/ppCompanies',
    component: Districts,
    meta,
    children: [
      {
        name: 'ppcManager.districts.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'ppcManager.districts.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
