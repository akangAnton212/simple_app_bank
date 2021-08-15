const Koa = require('koa')
var router = require('koa-router')();
const app = new Koa()
const axios = require('axios')
const CircularJSON = require('circular-json');
require('dotenv').config()

router.get('getUser', async(req, res) => {
  return axios.get(`${process.env.API_URL}getUser`, {
    headers: {
      Authorization: 'Bearer ' + req.session.auth.access_token
    }
  })
  .then((response) => {
    let json = CircularJSON.stringify(response);
    req.body = json;
  })
  .catch((error) => {
    req.status = error.response.status
    req.body = {
      status: error.response.status,
      message: error.response.data
    }
  })
});

module.exports = router;

