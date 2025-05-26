import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Force HTTP for local development connections
if (window.location.hostname === '127.0.0.1' || window.location.hostname === 'localhost') {
    window.axios.defaults.baseURL = `http://${window.location.hostname}:8000`;
}

// Configure timeout
window.axios.defaults.timeout = 10000;

// Add response interceptor for better error handling
window.axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.message === 'Network Error') {
            console.error('Connection issue - please ensure the server is running');
        }
        return Promise.reject(error);
    },
);

// Enable credentials for CORS
window.axios.defaults.withCredentials = true;
