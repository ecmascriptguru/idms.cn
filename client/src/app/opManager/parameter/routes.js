const Form = r => require.ensure([], () => r(require('./form')), 'opManager-bundle')

const meta = {
    requiresAuth: true,
}

export default [
    {
        name: "opManager.param",
        path: "/op/parameters",
        component: Form,
        meta,
    }
]