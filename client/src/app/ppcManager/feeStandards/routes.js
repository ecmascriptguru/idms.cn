
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const FeeStandards = r => require.ensure([], () => r(require('./main')), 'ppc-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'ppc-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'ppcManager.feeStandards.index',
    path: '/ppc/feeStandards',
    component: FeeStandards,
    meta,
    children: [
      {
        name: 'ppcManager.feeStandards.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'ppcManager.feeStandards.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
