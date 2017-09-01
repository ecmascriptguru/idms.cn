const Dashboard = r => require.ensure([], () => r(require('./main')), 'op-bundle')

const children = [{
    name: 'opManager.dashboard',
    path: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true, requiresAdmin: true, },
  }, ]
  
export default [{
    children,
    name: 'op',
    path: '/op',
    component: Dashboard,
    redirect: { name: 'opManager.dashboard' },
    meta: { requiresAuth: true, requiresAdmin: true, },
  }]
  