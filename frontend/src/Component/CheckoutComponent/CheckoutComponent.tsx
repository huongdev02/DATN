import React, { useEffect, useState } from "react";
import { Link, useParams, useNavigate } from "react-router-dom";
import { notification, Modal } from "antd";
import { useSelector } from "react-redux";
import { RootState, useAppDispatch } from "../../Redux/store";
import { fetchCart } from "../../Redux/Reducer/CartReducer";
import { postShipAddress } from "../../Redux/Reducer/ShipAddressReducer";
import api from "../../Axios/Axios";
import axios from "axios";
import { postOrder } from "../../Redux/Reducer/OrderReducer";
import { fetchVouchers } from "../../Redux/Reducer/VoucherReducer";
import QR from "../../assets/imgs/qr.jpg";
import './checkout.css'

const CheckoutComponent: React.FC = () => {
  const dispatch = useAppDispatch();
  const nav = useNavigate()
  const { userId } = useParams<{ userId: string }>();
  const cart = useSelector((state: RootState) => state.cart);
  const payment_url = useSelector((state: RootState) => state.order.payment_url);
  const paymentMethod = 2;
  const vouchers = useSelector(
    (state: RootState) => state.voucherReducer.vouchers
  );

  const [totalPrice, setTotalPrice] = useState(0);
  const [discount, setDiscount] = useState<number>(0);
  const [recipientName, setRecipientName] = useState("");
  const [email, setEmail] = useState("");
  const [phoneNumber, setPhoneNumber] = useState("");
  const [shipAddress, setShipAddress] = useState("");
  const [paymentMethodId, setPaymentMethodId] = useState<number>(0); 
  const [voucherId, setVoucherId] = useState<number | null>(null);
  const [showModal, setShowModal] = useState<boolean>(false); 

  useEffect(() => {
    if (userId) {
      dispatch(fetchCart(Number(userId)));
    }
    dispatch(fetchVouchers());
  }, [dispatch, userId]);

  const subtotal =
    cart?.items?.reduce(
      (total: number, item: any) => total + item.price * item.quantity,
      0
    ) || 0;

  useEffect(() => {
    const appliedDiscount = voucherId
      ? Number(
          vouchers.find((voucher) => voucher.id === voucherId)?.discount_value
        ) || 0
      : 0;
    setDiscount(appliedDiscount);
    setTotalPrice(subtotal - appliedDiscount);
  }, [cart?.items, voucherId, vouchers]);

  const formatCurrency = (amount: string | number): string => {
    const numberAmount =
      typeof amount === "string" ? parseFloat(amount) : amount;
    return numberAmount.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    });
  };

  const handleModalClose = () => {
    setShowModal(false); // Đóng modal khi nhấn nút "Cancel"
  };

  const handlePaymentSuccess = async (e: React.FormEvent) => {
    e.preventDefault();

    if (
      !recipientName ||
      !email ||
      !phoneNumber ||
      !shipAddress ||
      !paymentMethodId
    ) {
      notification.error({
        message: "Thông tin không đầy đủ",
        description: "Vui lòng điền đầy đủ thông tin vào các trường bắt buộc.",
      });
      return;
    }

    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const userId = user.id ? user.id.toString() : "";
    const addressData = {
      user_id: userId,
      recipient_name: recipientName,
      is_default: true,
      ship_address: shipAddress,
      phone_number: phoneNumber,
    };

    const shipAddressResponse = await dispatch(
      postShipAddress(addressData)
    ).unwrap();


    // Thanh toán
    const orderData = {
      user_id: userId,
      total_amount: totalPrice,
      ship_address_id: shipAddressResponse.id,
      phone_number: phoneNumber,
      subtotal: subtotal,
      totalPrice,
      status: 1,
      ship_method: 1,
      payment_method: paymentMethodId,
    };

    try {
      // Gửi yêu cầu thanh toán lên API (ví dụ sử dụng Redux action `postOrderPayment`)
      await dispatch(postOrder(orderData));
      if (payment_url && paymentMethod === 2) {

      }
      notification.success({
        message: "Thanh toán thành công",
        description:
          "Cảm ơn bạn đã thanh toán. Đơn hàng của bạn đang được xử lý.",
      });

      // Đóng modal sau khi thanh toán thành công
      setShowModal(false);
    } catch (err) {
      notification.error({
        message: "Lỗi khi thanh toán",
        description: "Có sự cố khi thanh toán. Vui lòng thử lại.",
      });
    }
  };

  const handlePlaceOrder = async (e: React.FormEvent) => {
    e.preventDefault();

    if (
      !recipientName ||
      !email ||
      !phoneNumber ||
      !shipAddress ||
      !paymentMethodId
    ) {
      notification.error({
        message: "Thông tin không đầy đủ",
        description: "Vui lòng điền đầy đủ thông tin vào các trường bắt buộc.",
      });
      return;
    }

    const fetchCartItems = async () => {
      try {
        const token = localStorage.getItem('token');
        const user = localStorage.getItem('user');
        
        let parsedUser;
        try {
          parsedUser = JSON.parse(user!);
        } catch (error) {
          console.error('Lỗi khi phân tích dữ liệu người dùng:', error);
        }
  
        if (parsedUser && parsedUser.user) {
          const userId = parsedUser.user.id;
  
          const response = await axios.get(`http://127.0.0.1:8000/api/carts/${userId}`, {
            headers: {
              Authorization: `Bearer ${token}`
            }
          });
          const total = response.data.cart_items.length
       
        } else {
          console.error('Không tìm thấy thông tin người dùng.');
        }
      } catch (error) {
        console.error('Lỗi khi lấy dữ liệu giỏ hàng:', error);
      }
    };
  
   
    

    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const userId = user.user.id;
    const addressData = {
      user_id: userId,
      recipient_name: recipientName,
      is_default: true,
      ship_address: shipAddress,
      phone_number: phoneNumber,
    };

    try {
      const shipAddressResponse = await dispatch(
        postShipAddress(addressData)
      ).unwrap();

      const orderData = {
        user_id: userId,
        total_amount: totalPrice,
        ship_address_id: shipAddressResponse.id,
        phone_number: phoneNumber,
        subtotal: subtotal,
        totalPrice,
        status: 1,
        ship_method: 1,
        payment_method: paymentMethodId,
      };

       const resultAction = await dispatch(postOrder(orderData)).unwrap();
       if(paymentMethodId === 2 && resultAction.payment_url){
        window.location.href = resultAction.payment_url;
       }else{
        nav('/thank')
       }

       localStorage.removeItem("cartItems");
    } catch (err) {
      notification.error({
        message: "Lỗi khi đặt hàng",
        description: "Có sự cố khi đặt hàng. Vui lòng thử lại.",
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
                    <li>
                      <Link to="/">Home</Link>
                    </li>
                    <li>
                      <Link to="/shop">Shop</Link>
                    </li>
                    <li>
                      <Link to="/checkout">Checkout</Link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="box-table-cart box-form-checkout">
              <div className="row">
                <div className="col-lg-7">
                  <h4
                    className="font-2xl-bold mb-25"
                    style={{
                      fontFamily: "Raleway",
                      fontSize: "28px",
                      fontWeight: "700",
                      marginBottom: "10px",
                    }}
                  >
                    Thông tin thanh toán
                  </h4>
                  <div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group">
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "15px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Họ tên
                        </label>
                        <input
                          className="form-control name-pla"
                          type="text"
                          placeholder="*Nhập họ tên"
                          name="recipient_name"
                          value={recipientName}
                          onChange={(e) => setRecipientName(e.target.value)}
                        />
                      </div>
                    </div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group">
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "15px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Email
                        </label>
                        <input
                          className="form-control name-pla"
                          type="email"
                          placeholder="*Nhập thông tin email"
                          name="email"
                          value={email}
                          onChange={(e) => setEmail(e.target.value)}
                        />
                      </div>
                    </div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group name-pla">
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "15px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Số điện thoại
                        </label>
                        <input
                          className="form-control name-pla"
                          type="text"
                          placeholder="*Nhập số điện thoại"
                          name="phone_number"
                          value={phoneNumber}
                          onChange={(e) => setPhoneNumber(e.target.value)}
                        />
                      </div>
                    </div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group" >
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "15px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Địa chỉ
                        </label>
                        <input
                          className="form-control name-pla"
                          type="text"
                          name="ship_address"
                          placeholder="*Nhập địa chỉ"
                          value={shipAddress}
                          onChange={(e) => setShipAddress(e.target.value)}
                        />
                      </div>
                    </div>
                    <div className="col-lg-12">
                      <div className="form-group">
                        <label
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "15px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Chọn phương thức thanh toán
                        </label>
                        <select
                          style={{ marginTop: "5px" }}
                          name="paymentMethod"
                          className="form-control"
                          value={paymentMethodId || ""}
                          onChange={(e) =>
                            setPaymentMethodId(Number(e.target.value))
                          }
                        >
                          <option value="" className="name-pla">Chọn phương thức thanh toán*</option>
                          <option value={1}>COD (thanh toán khi nhận hàng)</option>
                          <option value={2}>VNPAY</option>
                        </select>
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
                    {cart?.items && cart.items.length > 0 ? (
                      cart.items.map((item) => (
                        <div key={item.id} className="box-list-item-checkout">
                          <div className="item-checkout">
                            <span className="title-item">
                              {item.product_name}
                            </span>
                            <span className="num-item">x{item.quantity}</span>
                            <span className="price-item font-md-bold">
                              {(item.price * item.quantity).toLocaleString('vi', {style : 'currency', currency : 'VND'})}
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
                          <span style={{fontFamily:'Raleway'}}>Chọn voucher</span>
                          <select
                            style={{ marginTop: "5px" }}
                            name="voucher"
                            className="form-control"
                            value={voucherId || ""}
                            onChange={(e) =>
                              setVoucherId(Number(e.target.value))
                            }
                          >
                            {/* {vouchers?.map((voucher) => (
                              <option key={voucher.id} value={voucher.id}>
                                {voucher.name} - {formatCurrency(voucher.discount_value)}
                              </option>
                            ))} */}
                          </select>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-xl-bold">Tạm tính</span>
                          <span className="font-md-bold">
                            {formatCurrency(subtotal)}
                          </span>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-sm">Mã giảm giá</span>
                          <span className="font-md-bold">
                            {formatCurrency(discount)}
                          </span>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-sm">Thành tiền</span>
                          <span className="font-xl-bold">
                            {formatCurrency(totalPrice)}
                          </span>
                        </div>
                      </div>
                      <button
                        type="submit"
                        className="btn btn-brand-1-xl-bold w-100 font-md-bold"
                      >
                        Thanh toán
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
