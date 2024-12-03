import { Routes, Route } from 'react-router-dom';
import Product from '../Layout/Product/Product';
import Home from '../Layout/Home/Home';
import ProductDetail from '../Layout/ProductDetail/ProductDetail';
import Cart from '../Layout/Cart/Cart';
import Checkout from '../Layout/Checkout/Checkout';
import Contact from '../Layout/Contact/Contact';
import Blog from '../Layout/Blog/Blog';
import Found from '../Layout/Found/Found';
import About from '../Layout/About/About';
import OrderHistory from '../Layout/OrderHistory/OrderHistory';

const AppRoutes = () => {
  return (
    <Routes>
      <Route path='/' element={<Home />} />
      <Route path="/product" element={<Product />} />
      <Route path="/product-detail/:id" element={<ProductDetail />} />
      <Route path="/cart" element={<Cart />} />
      <Route path="/checkout/:userId" element={<Checkout />} />
      <Route path="/order-history" element={<OrderHistory />} />
      <Route path="/contact" element={<Contact />} />
      <Route path='/blog' element={<Blog />} />
      <Route path='/about' element={<About />} />
      <Route path='*' element={<Found />} />
    </Routes>
  );
};

export default AppRoutes;
