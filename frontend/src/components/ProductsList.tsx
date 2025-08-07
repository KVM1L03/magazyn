import React, { useState, useEffect } from 'react';
import { fetchProducts } from '../api/api';
import EditButton from './EditButton';
import AddButton from './AddButton';
import AddProductModal from './AddProductModal';
import EditProductModal from './EditProductModal';

interface Product {
  id: number;
  name: string;
  quantity: number;
}

const ProductList: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [selectedProductId, setSelectedProductId] = useState<number | null>(null);

  const loadProducts = async () => {
    try {
      setLoading(true);
      const data = await fetchProducts('token123');
      setProducts(data);
    } catch (error) {
      console.error('Error fetching products:', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProducts();
  }, []);

  const handleEdit = (productId: number) => {
    setSelectedProductId(productId);
    setIsEditModalOpen(true);
  };

  const handleAddClick = () => {
    console.log('Add button clicked!');
    setIsAddModalOpen(true);
  };

  console.log('Modal state:', isAddModalOpen);

  if (loading) {
    return <div className="p-4">Ładowanie listy produktów...</div>;
  }

  return (
    <div className="p-6">
      <h2 className="text-2xl font-bold mb-6 text-white">Lista produktów</h2>

      <div className="bg-white rounded-xl shadow-2xl overflow-hidden">
        <table className="w-full">
          <thead className="bg-gradient-to-r from-blue-600 to-blue-700">
            <tr>
              <th className="p-4 text-left text-white font-semibold">ID</th>
              <th className="p-4 text-left text-white font-semibold">Nazwa produktu</th>
              <th className="p-4 text-left text-white font-semibold">Ilość</th>
              <th className="p-4 text-center text-white font-semibold">Akcje</th>
            </tr>
          </thead>
          <tbody className="bg-gray-50">
            {products.map((prod, index) => (
              <tr 
                key={prod.id} 
                className={`
                  ${index % 2 === 0 ? 'bg-white' : 'bg-gray-50'} 
                  hover:bg-blue-50 transition-colors duration-200 border-b border-gray-100 last:border-b-0
                `}
              >
                <td className="p-4 text-gray-800 font-medium">{prod.id}</td>
                <td className="p-4 text-gray-800">{prod.name}</td>
                <td className="p-4 text-gray-800">
                  <span className={`
                    inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    ${prod.quantity > 10 
                      ? 'bg-green-100 text-green-800' 
                      : prod.quantity > 0 
                        ? 'bg-yellow-100 text-yellow-800'
                        : 'bg-red-100 text-red-800'
                    }
                  `}>
                    {prod.quantity}
                  </span>
                </td>
                <td className="p-4 text-center">
                  <EditButton onClick={() => handleEdit(prod.id)} />
                </td>
              </tr>
            ))}
            {products.length === 0 && (
              <tr>
                <td colSpan={4} className="p-8 text-center text-gray-500 italic">
                  Brak produktów w magazynie
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      <div className="mt-6">
        <AddButton onClick={handleAddClick} />
      </div>

      <AddProductModal
        isOpen={isAddModalOpen}
        onClose={() => setIsAddModalOpen(false)}
        onSuccess={loadProducts}
      />

      {selectedProductId && (
        <EditProductModal
          isOpen={isEditModalOpen}
          onClose={() => {
            setIsEditModalOpen(false);
            setSelectedProductId(null);
          }}
          onSuccess={loadProducts}
          productId={selectedProductId}
        />
      )}
    </div>
  );
};

export default ProductList;