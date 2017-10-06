
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const BillTerms = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.billTerms.index',
    path: '/dct/billTerms',
    component: BillTerms,
    meta,
    children: [
      {
        name: 'dctManager.billTerms.new',
        path: 'create',
        component: Form,
        meta,
      },
    ],
  },
]
