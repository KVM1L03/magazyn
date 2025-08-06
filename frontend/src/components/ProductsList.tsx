import React, { useState, useEffect } from 'react';
import { fetchProducts } from '../api/api';

interface Product {
  id: number;
  name: string;
  quantity: number;
}

const ProductList: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const loadProducts = async () => {
      try {
        const data = await fetchProducts('token123');
        setProducts(data);
      } catch (error) {
        console.error('Error fetching products:', error);
      } finally {
        setLoading(false);
      }
    };

    loadProducts();
  }, []);

  if (loading) {
    return <div className="p-4">Ładowanie listy produktów...</div>;
  }

  return (
    <div className="p-4">
      <h2 className="text-xl font-bold mb-4">Lista produktów</h2>
      <table className="w-full border border-gray-300">
        <thead>
          <tr>
            <th className="p-2 border">ID</th>
            <th className="p-2 border">Nazwa</th>
            <th className="p-2 border">Ilość</th>
          </tr>
        </thead>
        <tbody>
          {products.map((prod) => (
            <tr key={prod.id}>
              <td className="p-2 border">{prod.id}</td>
              <td className="p-2 border">{prod.name}</td>
              <td className="p-2 border">{prod.quantity}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ProductList;
