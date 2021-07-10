// import Vue from 'vue'
import client from './clientEndPoint'

const apiResource = 'supportpeople'

export default {
  async execute(method, resource, data) {
    //const accessToken = await Vue.prototype.$auth.getAccessToken()
    return client({
      method,
      url: resource,
      data
      /*headers: {
        Authorization: `Bearer ${accessToken}`
      }*/
    }).then(req => {
      return req.data
    })
  },
  getAll() {
    return this.execute('get', apiResource)
  },
  getAllPaged(params) {
    let paramsStr = JSON.stringify(params)
    return this.execute('get', apiResource + `/paged?params=${paramsStr}`)
  },
  getSupportPeopleConstraints(from, to) {
    return this.execute('get', apiResource + `/constraints?from=${from}&to=${to}`)
  },
  get(id) {
    return this.execute('get', apiResource + `/${id}`)
  },
  create(data) {
    return this.execute('post', apiResource + '/', data)
  },
  update(id, data) {
    return this.execute('put', apiResource + `/${id}`, data)
  },
  delete(id) {
    return this.execute('delete', apiResource+ `/${id}`)
  }
}
