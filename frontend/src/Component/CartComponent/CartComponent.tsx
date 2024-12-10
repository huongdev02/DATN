import { useSelector } from "react-redux";
import { RootState, useAppDispatch } from "../../Redux/store";
import { useEffect, useState } from "react";
import { fetchCart } from "../../Redux/Reducer/CartReducer";
import { Link } from "react-router-dom";
import { fetchVouchers } from "../../Redux/Reducer/VoucherReducer";
import { DeleteOutlined } from "@ant-design/icons";
import api from "../../Axios/Axios";
import { ExclamationCircleFilled } from "@ant-design/icons";
import { Button, Modal, Space } from "antd";

const { confirm } = Modal;
interface CartProps {
  userId: number;
}

const CartComponent: React.FC<CartProps> = ({ userId }) => {
  const dispatch = useAppDispatch();
  const cart = useSelector((state: RootState) => state.cart.items);
  const vouchers = useSelector((state: RootState) => state.voucherReducer);
  const [loading, setLoading] = useState(true);
  const [voucherCode, setVoucherCode] = useState<string>("");
  const [voucherValid, setVoucherValid] = useState<boolean | null>(null);
  const [discountValue, setDiscountValue] = useState<number>(0);
  const [isCart, setIsCart] = useState<any[]>([]);
  const [cartEmpty, setCartEmpty] = useState<boolean>(false);
  console.log(vouchers);

  useEffect(() => {
    dispatch(fetchCart(userId));
  }, [dispatch, userId]);

  useEffect(() => {
    dispatch(fetchVouchers());
  }, [dispatch]);

  const handleVoucherInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setVoucherCode(e.target.value);
  };

  const showDeleteConfirm = (id: any) => {
    confirm({
      title: "Bạn muốn xóa sản phẩm này khỏi giỏ hàng?",
      icon: <ExclamationCircleFilled />,
      okText: "Xóa",
      okType: "danger",
      cancelText: "Hủy",
      onOk() {
        handleDelete(id); // Gọi hàm xóa khi nhấn OK
      },
      onCancel() {
        console.log("Cancel");
      },
    });
  };

  const getAllCart = async () => {
    try {
      const token = localStorage.getItem("token");
      const user = localStorage.getItem("user");
      if (user && token) {
        let parsedUser;
        try {
          parsedUser = JSON.parse(user);
        } catch (error) {
          console.log("Dữ liệu người dùng không hợp lệ");
          return;
        }
        const userId = parsedUser.user.id;
        const { data } = await api.get(`/carts/${userId}`);
        setIsCart(data.cart_items);
      }
    } catch (error) {
      console.log(error);
    } finally{
      setLoading(false)
    }
  };
  console.log("Cảtttttt", isCart);
  const updateCartQuantity = async (id: any, quantity: number) => {
    try {
      await api.put(`/carts/${id}`, { quantity });
      getAllCart();
    } catch (error) {
      console.log(error);
    }
  };

  const handleDelete = async (productId: number) => {
    try {
      await api.delete(`/carts/${productId}`);
      window.location.reload();
    } catch (error) {
      console.log(error);
    }
  };

  useEffect(() => {
    getAllCart();
  }, []);

  if (loading)
    return (
      <div>
        <div id="preloader-active">
          <div className="preloader d-flex align-items-center justify-content-center">
            <div className="preloader-inner position-relative">
              <div className="page-loading text-center">
                <div className="page-loading-inner">
                  <div />
                  <div />
                  <div />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );

  return (
    <main className="main">
      <section className="section block-blog-single block-cart">
        <div className="container">
          <div className="top-head-blog">
            <div className="text-center">
              <h2 className="font-4xl-bold">Giỏ hàng</h2>
              <div className="breadcrumbs d-inline-block">
                <ul>
                  <li>
                    <a href="#">Trang chủ</a>
                  </li>
                  <li>
                    <a href="#">Cửa hàng</a>
                  </li>
                  <li>
                    <a href="#">Giỏ hàng</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div className="box-table-cart">
            <div className="table-responsive">
              <table className="table table-striped table-cart">
                <thead>
                  <tr>
                    <th>Tên sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tạm tính</th>
                    <th>Hành động</th>
                  </tr>
                </thead>
                {isCart.length === 0 ? (
                  <p
                    style={{
                      fontFamily: "Raleway",
                      fontStyle: "italic",
                      color: "red",
                      marginTop: "40px",
                      fontSize: "16px",
                      fontWeight: "normal",
                    }}
                  >
                    *Giỏ của bạn đang trống
                  </p>
                ) : (
                  isCart.map((item) => (
                    <tbody key={item.id}>
                      <tr>
                        <td>
                          <p>  {item.product_name}</p>
                          <div style={{display:'flex', gap:'10px', justifyContent:'center'}}>
                           <span style={{fontFamily:'Raleway', fontSize:'18px'}}>(</span> <p style={{fontFamily:'Raleway', fontSize:'14px'}}>{item.color}</p>,
                            <p style={{fontFamily:'Raleway', fontSize:'13px'}}>{item.size}</p><span style={{fontFamily:'Raleway', fontSize:'18px'}}>)</span>
                          </div>
                        </td>
                        <td>
                          <img
                            src={`http://127.0.0.1:8000/storage/${item.avatar}`}
                            width={"50px"}
                            alt={item.product_name}
                          />
                        </td>
                        <td>
                          <span className="brand-1" style={{fontFamily:'Raleway'}}>
                            {Number(item.price)
                              ? Number(item.price).toLocaleString("vi-VN", {
                                  style: "currency",
                                  currency: "VND",
                                })
                              : "Không có giá"}
                          </span>
                        </td>
                        <td>
                          <div className="product-quantity">
                            <div className="quantity">
                              {/* Giảm số lượng */}
                              <span
                                className="icon icon-minus d-flex align-items-center"
                                onClick={() =>
                                  item.quantity > 1 &&
                                  updateCartQuantity(item.id, item.quantity - 1)
                                }
                              >
                                <svg
                                  width={24}
                                  height={24}
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H17.75Z"
                                    fill="currentColor"
                                  />
                                </svg>
                              </span>
                              <input
                                className="input-quantity border-0 text-center"
                                type="number"
                                value={item.quantity}
                                readOnly
                              />
                              {/* Tăng số lượng */}
                              <span
                                className="icon icon-plus d-flex align-items-center"
                                onClick={() =>
                                  updateCartQuantity(item.id, item.quantity + 1)
                                }
                              >
                                <svg
                                  width={24}
                                  height={24}
                                  viewBox="0 0 24 24"
                                  fill="none"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path
                                    d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H12.75V17.75C12.75 17.9167 12.6667 18 12.5 18H11.5C11.3333 18 11.25 17.9167 11.25 17.75V12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H11.25V6.25C11.25 6.08333 11.3333 6 11.5 6H12.5C12.6667 6 12.75 6.08333 12.75 6.25V11.25H17.75Z"
                                    fill="currentColor"
                                  />
                                </svg>
                              </span>
                            </div>
                          </div>
                        </td>
                        <td>
                          <span className="brand-1" style={{fontFamily:'Raleway'}}>
                            {(
                              Number(item?.price || 0) *
                              Number(item?.quantity || 1)
                            ).toLocaleString("vi-VN", {
                              style: "currency",
                              currency: "VND",
                            })}
                          </span>
                        </td>
                        <td>
                          <DeleteOutlined
                            onClick={() => showDeleteConfirm(item.id)}
                          />
                        </td>
                      </tr>
                    </tbody>
                  ))
                )}
              </table>
            </div>

            <div
              className="row"
              style={{ display: "flex", justifyContent: "end" }}
            >
              {/* <div className="col-lg-5 mb-30">
                <div className="box-form-discount">
                  <div className="box-form-discount-inner">
                    <input
                      className="form-control"
                      type="text"
                      placeholder="Mã giảm giá"
                      value={voucherCode}
                      onChange={handleVoucherInputChange}
                    />
                   
                  </div>
                </div>
              </div> */}
              {/* Phần tính tổng tiền giỏ hàng */}
              {isCart.length > 0 && (
              <div className="col-lg-4 mb-30">
                <div className="box-cart-total">
                  {/* Hiển thị subtotal (tổng tiền giỏ hàng trước khi áp dụng voucher) */}
                  <div className="item-total">
                    <span className="font-sm">Tạm tính</span>
                    <span className="font-md-bold">
                      <span>
                        {isCart
                          .reduce(
                            (acc, item) =>
                              acc + Number(item.price) * Number(item.quantity),
                            0
                          )
                          .toLocaleString("vi", {
                            style: "currency",
                            currency: "VND",
                          })}
                      </span>
                    </span>
                  </div>

                  {/* Hiển thị phí vận chuyển */}
                  <div className="item-total">
                    <span className="font-sm">Phí ship</span>
                    <span className="font-md-bold">Free</span>
                  </div>
                  {/* <div className="item-total">
                    <span className="font-sm">Estimate for</span>
                    <span className="font-md-bold">United Kingdom</span>
                  </div> */}

                  <div className="item-total border-0">
                    <span className="font-sm">Tổng tiền</span>
                    <span className="font-xl-bold">
                      <span>
                        {(
                          isCart.reduce(
                            (acc, item) =>
                              acc + Number(item.price) * Number(item.quantity),
                            0
                          ) *
                          (1 - discountValue / 100)
                        ).toLocaleString("vi-VN", {
                          style: "currency",
                          currency: "VND",
                        })}
                      </span>
                    </span>
                  </div>
                  <Link to={`/checkout/${userId}`}>
                    <button className="btn btn-brand-1-xl-bold w-100 font-sm-bold">
                      Tiến hành thanh toán
                    </button>
                  </Link>
                </div>
              </div>
              )}
              <div className="col-lg-3 mb-30">
                <div className="box-button-checkout">
                  <Link
                    to="/product"
                    className="btn btn-brand-1-border-2 mr-10"
                  >
                    Tiếp tục mua sắm
                    <svg
                      className="icon-16 ml-5"
                      fill="none"
                      stroke="currentColor"
                      strokeWidth="1.5"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                      aria-hidden="true"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                      />
                    </svg>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  );
};

export default CartComponent;
