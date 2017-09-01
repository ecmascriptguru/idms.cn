// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import

import { routes as categories } from './categories'
import { routes as products } from './products'
import { routes as auth } from './auth'
import { routes as admin } from './admin'
import { routes as ops } from './opManager'
import { routes as dashboard } from './dashboard'

// https://developer.mozilla.org/pt-BR/docs/Web/JavaScript/Reference/Operators/Spread_operator
// Thus a new array is created, containing all objects that match the routes.
// ...dashboard must be the last one because of the catchall instruction
export default [...auth, ...categories, ...products,  ...admin, ...ops, ...dashboard]
