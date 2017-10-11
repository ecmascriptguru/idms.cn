
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const Certifications = r => require.ensure([], () => r(require('./main')), 'dct-bundle')
const Form = r => require.ensure([], () => r(require('./form')), 'dct-bundle')

const meta = {
  requiresAuth: true,
  requiresAdmin: true,
}

export default [
  {
    name: 'dctManager.certifications.index',
    path: '/dct/certifications',
    component: Certifications,
    meta,
    children: [
      {
        name: 'dctManager.certifications.new',
        path: 'create',
        component: Form,
        meta,
      }, {
        name: 'dctManager.certifications.edit',
        path: ':id/edit',
        component: Form,
        meta,
      },
    ],
  },
]
