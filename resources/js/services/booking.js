// import Vue from 'vue'
import client from './clientEndPoint'

const apiResource = 'bookings'

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
    return this.execute('get', apiResource + `/all`)
  },
//   getPage(page, rowsPerPage) {
//     return this.execute('get', apiResource + `/datatable?page=${page}&rows_per_page=${rowsPerPage}`)
//   },
  getPage(params) {
    let paramsStr = JSON.stringify(params)
    console.log(paramsStr)
    return this.execute('get', apiResource + `/datatable?params=${paramsStr}`)
  },
  getInstructorConflicts(params, from, to, instructor) {
    let paramsStr = JSON.stringify(params)
    return this.execute('get', apiResource + `/instructorconflicts?params=${paramsStr}` + `&from=${from}&to=${to}`  + `&instructor=${instructor}` )
  },
  get(id) {
    return this.execute('get', apiResource + `/${id}`)
  },
  create(data) {
    return this.execute('post', apiResource, data)
  },
  update(id, data) {
    return this.execute('put', apiResource + `/${id}`, data)
  },
  delete(id) {
    return this.execute('delete', apiResource+ `/${id}`)
  }, getByDateSpan(from, to) {
    return this.execute('get', apiResource + `?from=${from}&to=${to}`)
  }
}
