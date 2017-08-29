

export const AdminDashboard = r => require.ensure([], () => r(require('../components/admindashboard')), 'admin-bundle')