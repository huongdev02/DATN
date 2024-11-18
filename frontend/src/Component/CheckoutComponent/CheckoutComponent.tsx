import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { notification } from 'antd';
import { useSelector } from 'react-redux';
import { RootState, useAppDispatch } from '../../Redux/store';
import { fetchCart } from '../../Redux/Reducer/CartReducer';
import { postShipAddress } from '../../Redux/Reducer/ShipAddressReducer';
import { postOrder } from '../../Redux/Reducer/OrderReducer';

const CheckoutComponent: React.FC = () => {
  const dispatch = useAppDispatch();
  const { userId } = useParams<{ userId: string }>();
  const { cart } = useSelector((state: RootState) => state.cart);
  const paymentMethod = useSelector((state: RootState) => state.paymentMethod.methods);

  const [checkoutCart, setCheckoutCart] = useState(cart);
  const [recipientName, setRecipientName] = useState('');
  const [email, setEmail] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [shipAddress, setShipAddress] = useState('');
  const [paymentMethodId, setPaymentMethodId] = useState<number>(1);

  useEffect(() => {
    if (userId) {
      dispatch(fetchCart(Number(userId)));
    }
  }, [dispatch, userId]);

  useEffect(() => {
    setCheckoutCart(cart);
  }, [cart]);

  const subtotal = checkoutCart?.items.reduce((total, item) => total + item.price * item.quantity, 0) || 0;
  const total = subtotal;

  const handlePlaceOrder = async (e: React.FormEvent) => {
    e.preventDefault();
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const userId = user.id ? user.id.toString() : '';
    const addressData = {
      user_id: userId,
      recipient_name: recipientName,
      is_default: true,
      ship_address: shipAddress,
      phone_number: phoneNumber,
    };
    console.log('Address Data:', addressData);
    try {
      const shipAddressResponse = await dispatch(postShipAddress(addressData)).unwrap();
      console.log('Ship Address Response:', shipAddressResponse);
      const orderData = {
        user_id: userId,
        total_amount: total,
        ship_address_id: shipAddressResponse.id,
        phone_number: phoneNumber,
        subtotal,
        total,
        status: 1,
        ship_method: 1,
        payment_method_id: paymentMethodId
      };

      console.log('Order Data:', orderData);
      await dispatch(postOrder(orderData)).unwrap();
      localStorage.removeItem('cartItems')
      notification.success({
        message: 'Đặt hàng  thành công',
        description: 'Cảm ơn bạn đã tin tưởng sản phảm bên chúng tôi',
      });

    } catch (err) {
      notification.error({
        message: 'Error',
        description: 'There was an issue placing your order. Please try again.',
      });
    }
  };

  return (
    <main className="main">
      <form onSubmit={handlePlaceOrder}>
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
                          value={recipientName}
                          onChange={(e) => setRecipientName(e.target.value)}
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
                          value={email}
                          onChange={(e) => setEmail(e.target.value)}
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
                          value={phoneNumber}
                          onChange={(e) => setPhoneNumber(e.target.value)}
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
                          value={shipAddress}
                          onChange={(e) => setShipAddress(e.target.value)}
                          required
                        />
                      </div>
                    </div>
                    {/* <div className="col-lg-12">
                      <div className="form-group">
                        <select
                          name="payment_method"
                          className="form-control"
                          value={paymentMethodId}
                          onChange={(e) => setPaymentMethodId(Number(e.target.value))}
                          required
                        >
                          <option value="" disabled>Select Payment Method</option>
                          {paymentMethod.map((method) => (
                            <option key={method.id} value={method.id}>
                              {method.name}
                            </option>
                          ))}
                        </select>
                      </div>
                    </div> */}
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
                    {checkoutCart?.items && checkoutCart.items.length > 0 ? (
                      checkoutCart.items.map((item) => (
                        <div key={item.id} className="box-list-item-checkout">
                          <div className="item-checkout">
                            <span className="title-item">{item.product.name}</span>
                            <span className="num-item">x{item.quantity}</span>
                            <span className="price-item font-md-bold">
                              {(item.price * item.quantity).toLocaleString('vi-VN')} VND
                            </span>
                          </div>
                        </div>
                      ))
                    ) : (
                      <div>No items in your cart</div>
                    )}

                    <div className="box-footer-checkout">
                      <div className="item-checkout justify-content-between">
                        <span className="font-xl-bold">Subtotal</span>
                        <span className="font-md-bold">{subtotal.toLocaleString('vi-VN')} VND</span>
                      </div>
                      <div className="item-checkout justify-content-between">
                        <span className="font-sm">Shipping</span>
                        <span className="font-md-bold">0 VND</span>
                      </div>
                      <div className="item-checkout justify-content-between">
                        <span className="font-sm">Total</span>
                        <span className="font-xl-bold">{total.toLocaleString('vi-VN')} VND</span>
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
