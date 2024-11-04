import React, { useEffect, useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { notification } from 'antd';
import { useSelector } from 'react-redux';
import { RootState, useAppDispatch } from '../../Redux/store';
import { fetchPaymentMethods } from '../../Redux/Reducer/PaymentMethodReducer';
import { postShipAddress, ShipAddress } from '../../Redux/Reducer/ShipAddressReducer';
import { Order, postOrder } from '../../Redux/Reducer/OrderReducer';
import { clearCart } from '../../Redux/Reducer/CartReducer'; // Import clearCart action

const CheckoutComponent: React.FC = () => {
  const dispatch = useAppDispatch();
  const location = useLocation();
  const { items } = location.state || { items: [] };
  const subtotal = items.reduce((acc: number, item: any) => acc + (Number(item.price) * Number(item.pivot.quantity)), 0).toLocaleString();
  const total = subtotal;
  const navigate = useNavigate();
  const paymentMethod = useSelector((state: RootState) => state.paymentMethod.methods);
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  const [formData, setFormData] = useState<ShipAddress>({
    recipient_name: '',
    email: '',
    phone_number: '',
    ship_address: '',
    user_id: user.id || null,
  });

  const [orderData, setOrderData] = useState<Order>({
    user_id: user.id,
    total_amount: parseFloat(total.replace(/,/g, '')),
    ship_method: 1,
    payment_method_id: 1,
    ship_address_id: 1,
    status: 1,
    order_details: []
  });

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));

    if (name === 'payment_method') {
      const paymentMethodId = Number(value);
      setOrderData((prev) => ({ ...prev, payment_method_id: paymentMethodId }));
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    try {
      const shipAddressResponse = await dispatch(postShipAddress(formData)).unwrap();
      const shipAddressId = shipAddressResponse.id;
      const updatedOrderData = {
        ...orderData,
        ship_address_id: shipAddressId,
        order_details: items.map((item: any) => ({
          product_detail_id: item.id,
          quantity: item.pivot.quantity,
          price: item.price,
          name_product: item.name,
          size: item.size || '',
          color: item.color || '',
          total: item.pivot.quantity * Number(item.price)
        }))
      };

      console.log('Dữ liệu gửi đi:', updatedOrderData);
      await dispatch(postOrder(updatedOrderData)).unwrap();
      notification.success({

        message: 'Bạn đã đặt hàng thành công'
      })
      dispatch(clearCart());
      navigate('/product');

    } catch (error) {
      console.error('Đã xảy ra lỗi:', error);
      notification.error({
        message: 'Có lỗi xảy ra khi thêm đơn hàng.',
        description: 'Vui lòng kiểm tra lại thông tin và thử lại.'
      });
    }
  };

  useEffect(() => {
    dispatch(fetchPaymentMethods());
  }, [dispatch]);

  return (
    <main className="main">
      <form onSubmit={handleSubmit}>
        <section className="section block-blog-single block-cart">
          <div className="container">
            <div className="top-head-blog">
              <div className="text-center">
                <h2 className="font-4xl-bold">Checkout</h2>
                <div className="breadcrumbs d-inline-block">
                  <ul>
                    <li><Link to="/">Home</Link></li>
                    <li><Link to="/shop">Shop</Link></li>
                    <li><Link to="/checkout">Checkout</Link></li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="box-table-cart box-form-checkout">
              <div className="box-enter-code">
                <span className="font-md coupon-code">Have a coupon? <a className="brand-1 text-sm" href="#">Enter your code here</a></span>
              </div>
              <div className="row">
                <div className="col-lg-7">
                  <h4 className="font-2xl-bold mb-25">Billing Details</h4>
                  <div className="row">
                    <div className="col-lg-6">
                      <div className="form-group">
                        <input
                          className="form-control"
                          type="text"
                          name="recipient_name"
                          placeholder="Fullname *"
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="form-group">
                        <input
                          className="form-control"
                          type="email"
                          name="email"
                          placeholder="Email *"
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="form-group">
                        <input
                          className="form-control"
                          type="tel"
                          name="phone_number"
                          placeholder="Phone Number *"
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="form-group">
                        <input
                          className="form-control"
                          type="text"
                          name="ship_address"
                          placeholder="Address *"
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-12">
                      <div className="form-group">
                        <select name="payment_method" className='form-control' onChange={handleChange} required>
                          <option value="" disabled selected>Select Payment Method</option>
                          {paymentMethod.map((method) => (
                            <option key={method.id} value={method.id}>
                              {method.name}
                            </option>
                          ))}
                        </select>
                      </div>
                    </div>
                  </div>
                  <div className="row">
                    <div className="col-lg-12 mt-40">
                      <Link to='/cart' className="btn btn-brand-1-border-2">
                        <svg className="icon-16 mr-10" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                        </svg>Return to Cart
                      </Link>
                    </div>
                  </div>
                </div>
                <div className="col-lg-5">
                  <div className="box-total-checkout">
                    <div className="head-total-checkout">
                      <span className="font-xl-bold">Name</span>
                      <span className="font-xl-bold">Quantity</span>
                      <span className="font-xl-bold">Price</span>
                    </div>
                    {items.map((item: any) => (
                      <div key={item.id} className="box-list-item-checkout">
                        <div className="item-checkout">
                          <span className="title-item">{item.name}</span>
                          <span className="num-item">x{item.pivot.quantity}</span>
                          <span className="price-item font-md-bold">${item.price}</span>
                        </div>
                      </div>
                    ))}
                    <div className="box-footer-checkout">
                      <div className="item-checkout justify-content-between">
                        <span className="font-xl-bold">Subtotal</span>
                        <span className="font-md-bold">{subtotal} VND</span>
                      </div>
                      <div className="item-checkout justify-content-between">
                        <span className="font-sm">Shipping</span>
                        <span className="font-md-bold">0</span>
                      </div>
                      <div className="item-checkout justify-content-between">
                        <span className="font-sm">Total</span>
                        <span className="font-xl-bold">{total} VND</span>
                      </div>
                    </div>
                    <button type="submit" className="btn btn-brand-1-xl-bold w-100 font-md-bold">
                      Place an Order
                      <svg className="icon-16 ml-10" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 12h18M3 12l6 6m0-6l-6 6" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </form>
    </main>
  );
};

export default CheckoutComponent;
