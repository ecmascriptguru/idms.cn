
/**
* Components are lazy-loaded - See "Grouping Components in the Same Chunk"
* http://router.vuejs.org/en/advanced/lazy-loading.html
*/
/* eslint-disable global-require */
const OriginDashboard = r => require.ensure([], () => r(require('./main.backup')), 'app-bundle')
const Dashboard = r => require.ensure([], () => r(require('./main')), 'app-bundle')

export default [
  {
    name: 'dashboard.index',
    path: '/',
    component: Dashboard,
    meta: { requiresAuth: true },
  }, {
    name: "dashboard.backup",
    path: '/origin',
    component: OriginDashboard,
    meta: { requiresAuth: true },
  }, {
    name: 'catchall',
    path: '*',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
]
