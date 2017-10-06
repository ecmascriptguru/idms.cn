
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Bills = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.bills.index',
    path: '/dct/bills',
    component: Bills,
    meta,
    children: [
      {
        name: 'dctManager.bills.new',
        path: 'create',
        component: Form,
        meta,
      },
      {
        name: 'dctManager.bills.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
