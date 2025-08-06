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
    <div className="p-4">
      <h2 className="text-xl font-bold mb-4">Lista produktów</h2>

      <table className="w-full border border-gray-300">
        <thead>
          <tr>
            <th className="p-2 border">ID</th>
            <th className="p-2 border">Nazwa</th>
            <th className="p-2 border">Ilość</th>
            <th className="p-2 border text-center">Akcje</th>
          </tr>
        </thead>
        <tbody>
          {products.map((prod) => (
            <tr key={prod.id}>
              <td className="p-2 border">{prod.id}</td>
              <td className="p-2 border">{prod.name}</td>
              <td className="p-2 border">{prod.quantity}</td>
              <td className="p-2 border text-right">
                <EditButton onClick={() => handleEdit(prod.id)} />
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <AddButton onClick={handleAddClick} />

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