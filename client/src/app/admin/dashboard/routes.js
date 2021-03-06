const Dashboard = r => require.ensure([], () => r(require('./main')), 'admin-bundle')

const children = [{
    name: 'admin.dashboard',
    path: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true, requiresAdmin: true, },
  }, ]
  
export default [{
    children,
    name: 'admin',
    path: '/admin',
    component: Dashboard,
    redirect: { name: 'admin.dashboard' },
    meta: { requiresAuth: true, requiresAdmin: true, },
  }]
  