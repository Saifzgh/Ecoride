import axios from "axios";

const API_URL = "http://localhost:8000"; // Adresse de l'API backend

const api = axios.create({
  baseURL: API_URL.trim(), // Supprime les espaces et les sauts de ligne
  headers: { "Content-Type": "application/json" }
});

// Ajouter le token JWT si l'utilisateur est connecté
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default api;
