
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Flats = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.flats.index',
    path: '/dct/flats',
    component: Flats,
    meta,
    children: [
      {
        name: 'dctManager.flats.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.flats.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
