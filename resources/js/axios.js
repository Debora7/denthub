import axios from 'axios';

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_APP_URL || 'https://dentportal.ro', // Use the environment variable or a default
  headers: {
    'Content-Type': 'application/json',
  },
});

export default axiosInstance;
