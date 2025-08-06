import React from "react";

type EditButtonProps = {
  onClick: () => void;
  className?: string;
};

const EditButton: React.FC<EditButtonProps> = ({
  onClick,
  className = "",
}) => (
  <button
    type="button"
    onClick={onClick}
    className={`inline-flex items-center px-2 py-1 border border-gray-300 rounded hover:bg-blue-100 text-red-600 hover:text-red-800 transition-colors ${className}`}
  >
    Edit
  </button>
);

export default EditButton;
