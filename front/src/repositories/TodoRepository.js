import Repository from './Repository'

const resource = 'todos'
export default {
  get (filters = {}) {
    return Repository.get(`${resource}`)
  },

  getOne (id) {
    return Repository.get(`${resource}/${id}`)
  },

  create (data) {
    return Repository.post(`${resource}`, data)
  },

  update (id, data) {
    return Repository.put(`${resource}/${id}`, data)
  },

  delete (id) {
    return Repository.delete(`${resource}/${id}`)
  }
}
