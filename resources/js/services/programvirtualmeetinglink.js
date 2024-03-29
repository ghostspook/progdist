// import Vue from 'vue'
import client from './clientEndPoint'

const apiResource = 'programvirtualmeetinglinks'

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
  },
  setDefaultLink(id){
    return this.execute('put', apiResource + `/setdefault/${id}`)
  },
  getDefaultLink(id){
    return this.execute('get', apiResource + `/getdefault/${id}`)
  }

}
