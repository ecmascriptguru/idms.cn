
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const FingerPrints = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.fingerPrints.index',
    path: '/dct/fingerPrints',
    component: FingerPrints,
    meta,
    children: [
      {
        name: 'dctManager.fingerPrints.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.fingerPrints.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
