require('dotenv').config()
const express = require('express')
const bodyParser = require('body-parser')
const cookieSession = require('cookie-session')
const consola = require('consola')
const axios = require('axios')
const { Nuxt, Builder } = require('nuxt')
const api = process.env.SERVER_API_URL || process.env.API_URL
const app = express()

app.set('port', process.env.PORT)
// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }))

// parse application/json
app.use(bodyParser.json())
app.use(cookieSession({
  name: 'session',
  secret: process.env.APP_KEY,
  maxAge: new Date(Date.now() + 86400)
}))

// create application/x-www-form-urlencoded parser
const urlencodedParser = bodyParser.urlencoded({ extended: false })

// Import and Set Nuxt.js options
const config = require('../nuxt.config.js')
config.dev = process.env.NODE_ENV !== 'production'

async function start () {
  app.post('/login', urlencodedParser, function (req, res) {
    if (req.body.username && req.body.password) {
      return axios.post(api + 'oauth/token', {
        client_id: process.env.CLIENT_ID,
        client_secret: process.env.CLIENT_SECRET,
        grant_type: 'password',
        username: req.body.username,
        password: req.body.password
      }).then((response) => {
        return axios.get(api + 'api/v1/user/profile', {
          headers: {
              Authorization: 'Bearer ' + response.data.access_token
          }
        }).then(resp => {
          req.session.auth = response.data
          req.session.type = 'admin'
          return res.json({
              ...response.data,
              user: resp.data
          })
        }).catch(e => {
          return res.status(500).json({
              status: false,
              error: 'Kesalahan server',
              description: e.message
          })
        })
      }).catch((error) => {
        return res.status(500).json({
          status: false,
          error: 'Kesalahan server',
          description: error.message
        })
      })
    } else {
      return res.status(400).json({
        status: false,
        error: 'username atau password tidak cocok',
      })
    }
  })

  app.post('/customer/login', urlencodedParser, function (req, res) {
    if (req.body.username && req.body.password) {
      return axios.post(api + '/customer/login', {
        account_number: req.body.username,
        password: req.body.password
      }).then((response) => {
        return axios.get(api + 'api/v1/customer/info', {
          headers: {
              Authorization: 'Bearer ' + response.data.access_token
          }
        }).then(resp => {
          req.session.auth = response.data
          req.session.type = 'customer'
          return res.json({
              ...response.data,
              user: resp.data
          })
        }).catch(e => {
          return res.status(500).json({
              status: false,
              error: 'Kesalahan server',
              description: e.message
          })
        })
      }).catch((error) => {
        return res.status(500).json({
          status: false,
          error: 'Kesalahan server',
          description: error.message
        })
      })
    } else {
      return res.status(400).json({
        status: false,
        error: 'username atau password tidak cocok',
      })
    }
  })

  // POST /logout JSON bodies
  app.post('/logout', urlencodedParser, function (req, res) {
    // create user in req.body
    if (req.session.auth) {
      // return res.send(req.session.auth.access_token)
      return axios.get(api + 'user/logout', {
        headers: {
          Authorization: 'Bearer ' + req.session.auth.access_token
        }
      }).then(resp => {
        delete req.session.auth
        return res.json({
          status: resp.status,
          message: resp.message
        })
      }).catch(e => {
       
        return res.status(500).json({
          status: false,
          error: 'Kesalahan server',
          description: e.message
        })
      })
    }
  })

  // Init Nuxt.js
  const nuxt = new Nuxt(config)

  const { host, port } = nuxt.options.server

  // Build only in dev mode
  if (config.dev) {
    const builder = new Builder(nuxt)
    await builder.build()
  } else {
    await nuxt.ready()
  }

  // Give nuxt middleware to express
  app.use(nuxt.render)

  // Listen the server
  app.listen(port, host)
  consola.ready({
    message: `Server listening on http://${host}:${port}`,
    badge: true
  })
}
start()
