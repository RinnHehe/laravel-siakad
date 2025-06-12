import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// Set base URL for API requests
window.axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Configure timeout
window.axios.defaults.timeout = 30000;

// Get CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

// Add request interceptor for CSRF token
window.axios.interceptors.request.use(
    async (config) => {
        if (!config.headers['X-CSRF-TOKEN']) {
            const token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                config.headers['X-CSRF-TOKEN'] = token.content;
            }
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor for better error handling
window.axios.interceptors.response.use(
    (response) => response,
    async (error) => {
        if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            if (error.response.status === 419) {
                // CSRF token mismatch, try to refresh the page
                window.location.reload();
            }
            console.error('Response Error:', error.response.data);
        } else if (error.request) {
            // The request was made but no response was received
            console.error('Network Error - No response received');
        } else {
            // Something happened in setting up the request that triggered an Error
            console.error('Request Error:', error.message);
        }
        return Promise.reject(error);
    }
);
