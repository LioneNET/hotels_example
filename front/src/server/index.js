import axios from "axios";

const API_URL = import.meta.env.VITE_BASE_URL || 'http://localhost:8080/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    Accept: "application/json",
  }
})

export {api}