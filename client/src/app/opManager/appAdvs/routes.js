
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const AppAdvs = r => require.ensure([], () => r(require('./main')), 'opManager-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'opManager-bundle')

const meta = {
  requiresAuth: true,
}

export default [
  {
    name: 'opManager.appAdvs.index',
    path: '/op/appAdvs',
    component: AppAdvs,
    meta,
    children: [
      {
        name: 'opManager.appAdvs.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'opManager.appAdvs.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
