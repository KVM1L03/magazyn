import AddButton from "./components/AddButton";
import ProductList from "./components/ProductsList";

function App() {
  return (
    <div className="bg-gray-950 text-white min-h-screen">
      <ProductList />
      <AddButton onClick={() => console.log("Add new product")} />
    </div>
  );
}

export default App;
