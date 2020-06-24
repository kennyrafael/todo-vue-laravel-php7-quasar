import axios from 'axios'

const baseDomain = 'http://localhost:8000/api/'
const baseURL = `${baseDomain}`
// let refreshToken = options.refreshToken || localStorage.getItem('refreshToken')

const requestHandler = request => {
  return request
}

const errorHandler = (error) => {
  console.log(error)
  return Promise.reject({ ...error })
}

const successHandler = (response) => {
  return response
}

const client = axios.create({
  baseURL
})

client.interceptors.request.use(
  request => requestHandler(request)
)

client.interceptors.response.use(
  response => successHandler(response),
  error => errorHandler(error)
)

export default client
