
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Buildings = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.buildings.index',
    path: '/dct/buildings',
    component: Buildings,
    meta,
    children: [
      {
        name: 'dctManager.buildings.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.buildings.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
