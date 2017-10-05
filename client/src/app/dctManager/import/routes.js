const Form = r => require.ensure([], () => r(require('./form')), 'dctManager-bundle')

const meta = {
    requiresAuth: true,
}

export default [
    {
        name: "dctManager.import",
        path: "/dct/import",
        component: Form,
        meta,
    }
]