import React from 'react';

const ProductList: React.FC = () => {
  const products = [
    { id: 1, name: 'Młotek', quantity: 10 },
    { id: 2, name: 'Wkrętarka', quantity: 5 },
    { id: 3, name: 'Piła', quantity: 7 },
  ];

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
