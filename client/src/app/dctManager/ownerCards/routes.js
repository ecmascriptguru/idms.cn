
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Cards = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.ownerCards.index',
    path: '/dct/ownerCards',
    component: Cards,
    meta,
    children: [
      {
        name: 'dctManager.ownerCards.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.ownerCards.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
