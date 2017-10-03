var merge = require('webpack-merge')
var prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  API_URL: '"http://localhost:8000/api"',
  RESOURCE_URL: '"http://localhost:8000"',
  VERSION: '"1.0.3"',
})
