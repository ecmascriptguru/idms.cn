const Dashboard = r => require.ensure([], () => r(require('./main')), 'dct-bundle')

const children = [{
    name: 'dctManager.dashboard',
    path: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true, requiresAdmin: true, },
  }, ]
  
export default [{
    children,
    name: 'dctManager',
    path: '/dct',
    component: Dashboard,
    redirect: { name: 'dctManager.dashboard' },
    meta: { requiresAuth: true, requiresAdmin: true, },
  }]
  