import React, { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { notification } from 'antd';
import { useSelector } from 'react-redux';
import { RootState, useAppDispatch } from '../../Redux/store';
import { fetchCart } from '../../Redux/Reducer/CartReducer';
import { postShipAddress } from '../../Redux/Reducer/ShipAddressReducer';
import { postOrder } from '../../Redux/Reducer/OrderReducer';
import { fetchVouchers } from '../../Redux/Reducer/VoucherReducer';

const CheckoutComponent: React.FC = () => {
  const dispatch = useAppDispatch();
  const { userId } = useParams<{ userId: string }>();
  const { cart } = useSelector((state: RootState) => state.cart);
  const paymentMethod = useSelector((state: RootState) => state.paymentMethod.methods);
  const vouchers = useSelector((state: RootState) => state.voucherReducer.vouchers); 
  const [totalPrice, setTotalPrice] = useState(0);
  const [discount, setDiscount] = useState<number>(0);
  const [checkoutCart, setCheckoutCart] = useState(cart);
  const [recipientName, setRecipientName] = useState('');
  const [email, setEmail] = useState('');
  const [phoneNumber, setPhoneNumber] = useState('');
  const [shipAddress, setShipAddress] = useState('');
  const [paymentMethodId, setPaymentMethodId] = useState<number>(1);
  const [voucherId, setVoucherId] = useState<number | null>(null);  
console.log(cart);

  useEffect(() => {
    if (userId) {
      dispatch(fetchCart(Number(userId)));
    }
    dispatch(fetchVouchers());  
  }, [dispatch, userId]);

  useEffect(() => {
    setCheckoutCart(cart);
  }, [cart]);

  const subtotal = checkoutCart?.items?.reduce((total: number, item: any) => total + item.price * item.quantity, 0) || 0;
  console.log(subtotal);
  
  useEffect(() => {
    const appliedDiscount = voucherId ? 
      Number(vouchers.find(voucher => voucher.id === voucherId)?.discount_value) || 0 : 0;
    console.log("Applied discount:", appliedDiscount);
    
    setDiscount(appliedDiscount);
    setTotalPrice(subtotal - appliedDiscount); 
  }, [checkoutCart, voucherId, vouchers]);
  
  useEffect(() => {
    console.log("Updated totalPrice:", totalPrice);
  }, [totalPrice]); 
  

  const formatCurrency = (amount: string | number): string => {
    const numberAmount = typeof amount === 'string' ? parseFloat(amount) : amount;
    return numberAmount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
  };

  const handlePlaceOrder = async (e: React.FormEvent) => {
    e.preventDefault();
  
    if (!recipientName || !email || !phoneNumber || !shipAddress || !paymentMethodId) {
      notification.error({
        message: 'Thông tin không đầy đủ',
        description: 'Vui lòng điền đầy đủ thông tin vào các trường bắt buộc.',
      });
      return;
    }
  
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const userId = user.id ? user.id.toString() : '';
    const addressData = {
      user_id: userId,
      recipient_name: recipientName,
      is_default: true,
      ship_address: shipAddress,
      phone_number: phoneNumber,
    };
    
    try {
      const shipAddressResponse = await dispatch(postShipAddress(addressData)).unwrap();
      
      const orderData = {
        user_id: userId,
        total_amount: totalPrice, 
        ship_address_id: shipAddressResponse.id,
        phone_number: phoneNumber,
        subtotal: subtotal, 
        totalPrice, 
        status: 1,
        ship_method: 1,
        payment_method_id: paymentMethodId
      };
      console.log(orderData);
      
      await dispatch(postOrder(orderData)).unwrap();
      localStorage.removeItem('cartItems');
      notification.success({
        message: 'Đặt hàng thành công',
        description: 'Cảm ơn bạn đã tin tưởng sản phẩm bên chúng tôi',
      });
  
    } catch (err) {
      notification.error({
        message: 'Lỗi khi đặt hàng',
        description: 'Có sự cố khi đặt hàng. Vui lòng thử lại.',
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
                        />
                      </div>
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
                      <div className="form-group">
                        <div className="mb-3">
                          <span>Chọn Voucher</span>
                          <select
                            style={{marginTop: "5px"}}
                            name="voucher"
                            className="form-control"
                            value={voucherId || ''}
                            onChange={(e) => setVoucherId(Number(e.target.value))}
                          >
                            <option value="">Select Voucher</option>
                            {vouchers.map((voucher) => (
                              <option key={voucher.id} value={voucher.id}>
                                {voucher.code} - {formatCurrency(voucher.discount_value)}
                              </option>
                            ))}
                          </select>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-xl-bold">Subtotal</span>
                          <span className="font-md-bold">{formatCurrency(subtotal)}</span>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-sm">Voucher Discount</span>
                          <span className="font-md-bold">{formatCurrency(discount)}</span>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-sm">Total</span>
                          <span className="font-xl-bold">{formatCurrency(totalPrice)}</span>
                        </div>
                      </div>
                      <button type="submit" className="btn btn-brand-1-xl-bold w-100 font-md-bold">
                        Place an Order
                      </button>
                    </div>
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
