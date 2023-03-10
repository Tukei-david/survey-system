import axios from 'axios'
import store from './store'

// Url for api client
const axiosClient = axios.create({
    baseURL: "http://127.0.0.1:8000/api"
})

//Passing the token and headers
axiosClient.interceptors.request.use(config => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`
    return config
})


export default axiosClient