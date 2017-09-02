const Form = r => require.ensure([], () => r(require('./form')), 'opManager-bundle')

const meta = {
    requiresAuth: true,
}

export default [
    {
        name: "opManager.shop",
        path: "/op/shop",
        component: Form,
        meta,
    }
]