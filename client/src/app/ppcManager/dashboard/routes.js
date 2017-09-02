const Dashboard = r => require.ensure([], () => r(require('./main')), 'ppc-bundle')

const children = [{
    name: 'ppcManager.dashboard',
    path: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true, requiresAdmin: true, },
  }, ]
  
export default [{
    children,
    name: 'ppcManager',
    path: '/ppc',
    component: Dashboard,
    redirect: { name: 'ppcManager.dashboard' },
    meta: { requiresAuth: true, requiresAdmin: true, },
  }]
  