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

export const createProduct = async (product: { name: string; quantity: number }, token: string = DEFAULT_TOKEN) => {
  try {
    const response = await axios.post(`${API_URL}/products`, product, {
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

export const updateProductAmount = async (productId: number, amount: number, token: string = DEFAULT_TOKEN) => {
  try {
    const response = await axios.post(`${API_URL}/products/${productId}/update`, { amount }, {
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

