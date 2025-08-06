import React from "react";

type AddButtonProps = {
  onClick?: () => void;
};

const AddButton: React.FC<AddButtonProps> = ({ onClick }) => {
  console.log('AddButton render, onClick:', onClick);
  
  const handleClick = () => {
    console.log('AddButton clicked!');
    onClick?.();
  };

  return (
    <button
      onClick={handleClick}
      className="fixed bottom-6 right-6 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center text-2xl z-50"
    >
      +
    </button>
  );
};

export default AddButton;
