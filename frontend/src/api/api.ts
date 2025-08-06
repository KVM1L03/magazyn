import axios from 'axios';

const API_URL = 'http://localhost:8000/api';
const DEFAULT_TOKEN = 'token123';

export const fetchProducts = async (token: string = DEFAULT_TOKEN) => {
  try {
    const response = await axios.get(`${API_URL}/products`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });

    return response.data;
  } catch (error) {
    console.error('API Error:', error);
    throw error;
  }
};
