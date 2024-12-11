import { useEffect, useState, useRef } from "react";
import Logo from "../../assets/imgs/template/logo.svg";
import { Link } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { AppDispatch, RootState } from "../../Redux/store";
import { login } from "../../Redux/Reducer/AuthReducer";
import { Button, Dropdown, Flex, MenuProps, notification, Space } from "antd";
import { useNavigate } from "react-router-dom";
import { CartItem } from "../../Redux/Reducer/CartReducer";
import { useCart } from "../../context/cartContext";
import "./Header.css";
import api from "../../Axios/Axios";
import { fetchCart } from "../../Redux/Reducer/CartReducer";
import {
  InfoCircleFilled,
  InfoCircleOutlined,
  LogoutOutlined,
  CloseOutlined,
} from "@ant-design/icons";
import { message } from "antd";
import axios from "axios";
import { registerUser } from "../../Redux/Reducer/Register";
interface User {
  id: number;
  email: string;
  username: string | null;
  fullname: string | null;
  birth_day: string;
  phone: string;
  address: string;
  role: number;
  is_active: number;
  avatar: string;
  token: string;
}

const Header: React.FC = () => {
  // const { totalQuantity } = useCart();
  const nav = useNavigate()
  const { totalQuantity, status } = useSelector((state: RootState) => state.cart);
  const [searchText, setSearchText] = useState("");
  const [isSearchActive, setIsSearchActive] = useState(false);
  const [isCartActice, setIsCartActice] = useState(false);
  const [isAccountActive, setIsAccountActive] = useState(false);
  const [activeTab, setActiveTab] = useState("login");
  const [products, setProducts] = useState<any[]>([]);
  const dispatch = useDispatch<AppDispatch>();
  const [email, setEmail] = useState('');
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const loading = useSelector((state: RootState) => state.auth.loading);
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [user, setUser] = useState<User | null>(null);
  const [isShow, setIsShow] = useState(false);
  const [cartItems, setCartItems] = useState<any[]>([]);
  const [check, setCheck] = useState<any>();
  const { cart } = useSelector((state: RootState) => state.cart);
  const cartItemsLength = JSON.parse(localStorage.getItem("cartItems") || "[]");
  const cartLength = cartItemsLength.length;
  const ref = useRef<HTMLDivElement>(null);
  const GetAllProducts = async () => {
    try {
      const { data } = await axios.get("http://127.0.0.1:8000/api/products");
      setProducts(data.products);
    } catch (error) {
      message.error("Lỗi api!");
    }
  };

 
  

  console.log("checkkkkk", check);
  

  const handleClick = () => {
    setIsShow(true);
  };

  const filteredProducts = products.filter((product) =>
    product.name.toLowerCase().includes(searchText.toLowerCase())
  );

  const handleClickOutside = (event: any) => {
    if (ref.current && !ref.current.contains(event.target)) {
      setIsShow(false);
    }
  };

  console.log(totalQuantity, 'sollllll');
  

  useEffect(() => {
    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  useEffect(() => {
    const storedCart = localStorage.getItem("cartItems");
    GetAllProducts()
   
    if (storedCart) {
      setCartItems(JSON.parse(storedCart));
    }
  }, []);

  useEffect(() => {
    const fetchAuthStatus = async () => {
      const userData = localStorage.getItem("user");
      const token = localStorage.getItem("token");

      // Kiểm tra nếu `userData` và `token` tồn tại
      if (userData && token) {
        try {
          // Parse dữ liệu user từ localStorage
          const parsedUser = JSON.parse(userData);
          setIsLoggedIn(true);
          setUser(parsedUser.user);

          // Gọi API để kiểm tra auth
          const res = await axios.get("http://127.0.0.1:8000/api/auth/check", {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });
          // if(check.role === 0){
          //   nav('http://127.0.0.1:8000/admin/dashboard')
          // }else{
          //   nav('http://127.0.0.1:8000/user/dashboard')
          // }
          // console.log("Auth Check Response:", res.data);
          setCheck(res.data); // Lưu dữ liệu kiểm tra auth vào state
        } catch (error) {
          console.error("Error fetching auth status:", error);
          setIsLoggedIn(false);
          setUser(null);
          setCheck(null);
        }
      } else {
        console.log("No user data or token found in localStorage.");
      }
    };

    fetchAuthStatus(); // Gọi hàm kiểm tra auth
  }, []); // Chỉ chạy khi component được mount

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    console.log("Email:", email);
    console.log("Password:", password);
    if (!password.trim() || !email.trim()) {
      notification.error({
        message: "Vui lòng nhập đầy đủ thông tin",
      });
      return false;
    }
    try {
      const resultAction = await dispatch(login({ email, password }));

      if (login.fulfilled.match(resultAction)) {
        const userData = resultAction.payload;
        localStorage.setItem("user", JSON.stringify(userData));
        localStorage.setItem("token", userData.token);
        window.location.reload();

        
      } else {
        notification.error({
          message: "Tài khoản không chính khác",
        });
        setPassword("");
      }
    } catch (err) {
      console.error("Đăng nhập thất bại:", err);
    }
  };
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (password !== confirmPassword) {
      notification.error({
        message: 'Lỗi đăng ký',
        description: 'Mật khẩu và xác nhận mật khẩu không khớp!',
        placement: 'topRight',  
      });
      return;
    }

    const userData = { email, username, password, confirmPassword }; 
    dispatch(registerUser(userData));
    notification.success({
      message: 'Đăng ký thành công',
      description: `Vui lòng đăng nhập để đặt hàng tại website`,
      placement: 'topRight', 
    });
    setActiveTab("login");
  };

  const navDashBoard = () => {
    if (check.role === 2) {
      window.location.href = 'http://127.0.0.1:8000/admin/dashboard';
    } if (check.role === 0) {
      window.location.href = 'http://127.0.0.1:8000/user/dashboard';  
    }
  };
  

  const handleLogout = () => {
    localStorage.removeItem("user");
    localStorage.removeItem("token");
    // localStorage.removeItem("cart");
    setIsLoggedIn(false);
    notification.success({
      message: "Đăng xuất thành công !",
      placement:'bottomRight'
    });
    nav('/')
    
  };

  const items: MenuProps["items"] = [
    {
      key: "1",
      label: <span>{user?.fullname}</span>,
    },
    {
      key: "2",
      label: (
        <a
          target="_blank"
          rel="noopener noreferrer"
          href="https://www.aliyun.com"
        >
          Thông tin tài khoản
        </a>
      ),
    },
    {
      key: "3",
      label: (
        <a target="_blank" rel="noopener noreferrer" onClick={handleLogout}>
          Đăng Xuất
        </a>
      ),
    },
  ];

  const showLoginForm = () => {
    setActiveTab("login");
  };

  const showSignUpForm = () => {
    setActiveTab("signup");
  };

  const openSearchPopup = (e: any) => {
    e.preventDefault();
    setIsSearchActive(true);
  };

  const openAccountPopup = (e: any) => {
    e.preventDefault();
    setIsAccountActive(true);
  };

  const closeAccountPopup = () => {
    setIsAccountActive(false);
  };

  const openCartPopup = (e: any) => {
    e.preventDefault();
    setIsCartActice(true);
  };

  const closeSearchPopup = () => {
    setIsSearchActive(false);
  };

  const closeCartPopup = () => {
    setIsCartActice(false);
  };

  return (
    <>
      <header className="header sticky-bar header-style-1">
        <div className="container">
          <div className="main-header">
            <div className="header-logo">
              <Link to="/" className="d-flex">
                <img alt="kidify" src={Logo} />
              </Link>
            </div>
            <div className="header-menu">
              <div className="header-nav">
                <nav className="nav-main-menu d-none d-xl-block">
                  <ul className="main-menu">
                    <li className="has-mega-menu">
                      <Link to="/" className="active">
                        Trang chủ
                      </Link>
                    </li>
                    <li>
                      <Link to="/about">Giới thiệu</Link>
                    </li>
                    <li className="has-mega-menu">
                      <Link to="/product">Cửa hàng</Link>
                    </li>
                    <li className="has-children">
                      <Link to="/blog">Tin tức</Link>
                    </li>
                    <li>
                      <Link to="/contact">Liên hệ</Link>
                    </li>
                  </ul>
                </nav>
                <div className="burger-icon burger-icon-white">
                  <span className="burger-icon-top" />
                  <span className="burger-icon-mid" />
                  <span className="burger-icon-bottom" />
                </div>
              </div>
            </div>
            {/* Thông tin tài khoản */}
            {isShow && (
              <div className="custom-user" ref={ref}>
                <div
                  className="css-contact"
                  style={{
                    marginTop: "15px",
                    marginLeft: "10px",
                    display: "flex",
                    justifyContent: "start",
                    alignItems: "center",
                  }}
                >
                  <InfoCircleOutlined className="icon-css" />
               
                    <span onClick={()=>navDashBoard()} className="hover-text" style={{ marginLeft: "5px" }}>
                      Thông tin tài khoản
                    </span>
                 
                </div>
                <div className="css-logout" onClick={() => handleLogout()}>
                  <LogoutOutlined className="icon-css" />
                  <span style={{ marginLeft: "5px" }}>Đăng xuất</span>
                </div>
              </div>
            )}
            {/* end */}
            <div
              className="header-account"
              style={{
                display: "flex",
                alignItems: "center",
                justifyContent: "end",
              }}
            >
              <div style={{ width: "70px" }} className="account-icon account">
                {isLoggedIn && user ? (
                  <div className="dropdown">
                    <span className="hi-user">{user.email}</span>
                    {/* <img
                      style={{ borderRadius: "50%" }}
                      className="dropbtn"
                      src={user.avatar}
                      alt=""
                    /> */}
                    <a
                      className="account-icon account hover-user"
                      onClick={() => handleClick()}
                    >
                      <svg
                        className="d-inline-flex align-items-center justify-content-center"
                        width={28}
                        height={28}
                        viewBox="0 0 28 28"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <g clipPath="url(#clip0_116_451)">
                          <path d="M6 24C6 21.8783 6.84285 19.8434 8.34315 18.3431C9.84344 16.8429 11.8783 16 14 16C16.1217 16 18.1566 16.8429 19.6569 18.3431C21.1571 19.8434 22 21.8783 22 24H20C20 22.4087 19.3679 20.8826 18.2426 19.7574C17.1174 18.6321 15.5913 18 14 18C12.4087 18 10.8826 18.6321 9.75736 19.7574C8.63214 20.8826 8 22.4087 8 24H6ZM14 15C10.685 15 8 12.315 8 9C8 5.685 10.685 3 14 3C17.315 3 20 5.685 20 9C20 12.315 17.315 15 14 15ZM14 13C16.21 13 18 11.21 18 9C18 6.79 16.21 5 14 5C11.79 5 10 6.79 10 9C10 11.21 11.79 13 14 13Z" />
                        </g>
                        <defs>
                          <clipPath id="clip0_116_451">
                            <rect
                              width={24}
                              height={24}
                              fill="white"
                              transform="translate(2 2)"
                            />
                          </clipPath>
                        </defs>
                      </svg>
                    </a>
                  </div>
                ) : (
                  <a
                    className="account-icon account"
                    onClick={openAccountPopup}
                  >
                    <svg
                      className="d-inline-flex align-items-center justify-content-center"
                      width={28}
                      height={28}
                      viewBox="0 0 28 28"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <g clipPath="url(#clip0_116_451)">
                        <path d="M6 24C6 21.8783 6.84285 19.8434 8.34315 18.3431C9.84344 16.8429 11.8783 16 14 16C16.1217 16 18.1566 16.8429 19.6569 18.3431C21.1571 19.8434 22 21.8783 22 24H20C20 22.4087 19.3679 20.8826 18.2426 19.7574C17.1174 18.6321 15.5913 18 14 18C12.4087 18 10.8826 18.6321 9.75736 19.7574C8.63214 20.8826 8 22.4087 8 24H6ZM14 15C10.685 15 8 12.315 8 9C8 5.685 10.685 3 14 3C17.315 3 20 5.685 20 9C20 12.315 17.315 15 14 15ZM14 13C16.21 13 18 11.21 18 9C18 6.79 16.21 5 14 5C11.79 5 10 6.79 10 9C10 11.21 11.79 13 14 13Z" />
                      </g>
                      <defs>
                        <clipPath id="clip0_116_451">
                          <rect
                            width={24}
                            height={24}
                            fill="white"
                            transform="translate(2 2)"
                          />
                        </clipPath>
                      </defs>
                    </svg>
                  </a>
                )}
              </div>
              <a className="account-icon search" onClick={openSearchPopup}>
                <svg
                  className="d-inline-flex align-items-center justify-content-center"
                  width={28}
                  height={28}
                  viewBox="0 0 28 28"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g clipPath="url(#clip0_91_73)">
                    <path d="M20.031 18.617L24.314 22.899L22.899 24.314L18.617 20.031C17.0237 21.3082 15.042 22.0029 13 22C8.032 22 4 17.968 4 13C4 8.032 8.032 4 13 4C17.968 4 22 8.032 22 13C22.0029 15.042 21.3082 17.0237 20.031 18.617ZM18.025 17.875C19.2941 16.5699 20.0029 14.8204 20 13C20 9.132 16.867 6 13 6C9.132 6 6 9.132 6 13C6 16.867 9.132 20 13 20C14.8204 20.0029 16.5699 19.2941 17.875 18.025L18.025 17.875Z" />
                  </g>
                  <defs>
                    <clipPath id="clip0_91_73">
                      <rect
                        width={24}
                        height={24}
                        fill="white"
                        transform="translate(2 2)"
                      />
                    </clipPath>
                  </defs>
                </svg>
              </a>
              {/* <a className="account-icon wishlist" href="compare.html">
                <span className="number-tag">3</span>
                <svg
                  className="d-inline-flex align-items-center justify-content-center"
                  width={28}
                  height={28}
                  viewBox="0 0 28 28"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g clipPath="url(#clip0_116_452)">
                    <path d="M14.001 6.52898C16.35 4.41998 19.98 4.48998 22.243 6.75698C24.505 9.02498 24.583 12.637 22.479 14.993L13.999 23.485L5.52101 14.993C3.41701 12.637 3.49601 9.01898 5.75701 6.75698C8.02201 4.49298 11.645 4.41698 14.001 6.52898ZM20.827 8.16998C19.327 6.66798 16.907 6.60698 15.337 8.01698L14.002 9.21498L12.666 8.01798C11.091 6.60598 8.67601 6.66798 7.17201 8.17198C5.68201 9.66198 5.60701 12.047 6.98001 13.623L14 20.654L21.02 13.624C22.394 12.047 22.319 9.66498 20.827 8.16998Z" />
                  </g>
                  <defs>
                    <clipPath id="clip0_116_452">
                      <rect
                        width={24}
                        height={24}
                        fill="white"
                        transform="translate(2 2)"
                      />
                    </clipPath>
                  </defs>
                </svg>
              </a> */}
              {/* Giỏ hàng nè */}
              <Link
                to="/cart"
                className="account-icon cart"
              >
                <span className="number-tag">{totalQuantity}</span>
                <svg
                  width={28}
                  height={28}
                  viewBox="0 0 28 28"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <g clipPath="url(#clip0_116_450)">
                    <path d="M9 10V8C9 6.67392 9.52678 5.40215 10.4645 4.46447C11.4021 3.52678 12.6739 3 14 3C15.3261 3 16.5979 3.52678 17.5355 4.46447C18.4732 5.40215 19 6.67392 19 8V10H22C22.2652 10 22.5196 10.1054 22.7071 10.2929C22.8946 10.4804 23 10.7348 23 11V23C23 23.2652 22.8946 23.5196 22.7071 23.7071C22.5196 23.8946 22.2652 24 22 24H6C5.73478 24 5.48043 23.8946 5.29289 23.7071C5.10536 23.5196 5 23.2652 5 23V11C5 10.7348 5.10536 10.4804 5.29289 10.2929C5.48043 10.1054 5.73478 10 6 10H9ZM9 12H7V22H21V12H19V14H17V12H11V14H9V12ZM11 10H17V8C17 7.20435 16.6839 6.44129 16.1213 5.87868C15.5587 5.31607 14.7956 5 14 5C13.2044 5 12.4413 5.31607 11.8787 5.87868C11.3161 6.44129 11 7.20435 11 8V10Z" />
                  </g>
                  <defs>
                    <clipPath id="clip0_116_450">
                      <rect
                        width={24}
                        height={24}
                        fill="white"
                        transform="translate(2 2)"
                      />
                    </clipPath>
                  </defs>
                </svg>
              </Link>
              {/* end giỏ hàng */}
            </div>
          </div>
        </div>
      </header>
      {/* Tìm kiếm sản phẩm */}
      <div
        className="box-popup-search perfect-scrollbar"
        style={{
          visibility: isSearchActive ? "visible" : "hidden",
          height: "100px",
        }}
      >
        <div className="box-search-overlay" />
        <div className={`box-search-wrapper ${isSearchActive ? "active" : ""}`}>
          <a onClick={closeSearchPopup} className="btn-close-popup" >
            <svg
              className="icon-16 d-inline-flex align-items-center justify-content-center"
              fill="#111111"
              stroke="#111111"
              width={24}
              height={24}
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </a>
          <div className="search-popup">
            <h5 className="mb-15">Tìm kiếm sản phẩm</h5>
            <div className="form-group">
              <input
                value={searchText}
                onChange={(e) => setSearchText(e.target.value)}
                className="input-search"
                type="text"
                placeholder="Nhập tên sản phẩm cần tìm kiếm..."
              />
            </div>
            {searchText && (
              <div className="search-results">
                {filteredProducts.length > 0 ? (
                  filteredProducts.map((product) => (
                    <Link to={`/product-detail/${product.id}`}>
                      <div key={product.id} className="product-item">
                        <img
                          src={product.avatar_url}
                          alt={product.name}
                          width="80px"
                          className="product-result"
                        />
                        <div className="text-result">
                          <p style={{fontFamily:'Raleway'}} className="name-result">{product.name}</p>
                          <p  style={{fontFamily:'Raleway'}}className="price-result">
                            {" "}
                            {Math.round(product.price).toLocaleString("vi", {
                              style: "currency",
                              currency: "VND",
                            })}
                          </p>
                        </div>
                      </div>
                    </Link>
                  ))
                ) : (
                  <div style={{fontFamily:'Raleway'}} className="no-results">*Không tìm thấy sản phẩm nào</div>
                )}
              </div>
            )}
          </div>
        </div>
      </div>
      {/* end tìm kiếm */}
      <div
        className="box-popup-account"
        style={{
          visibility: isAccountActive ? "visible" : "hidden",
          display: isAccountActive ? "block" : "none",
        }}
      >
        <div className="box-account-overlay" />
        <div
          className={`box-account-wrapper ${isAccountActive ? "active" : ""}`}
        >
          <a
            className="btn-close-popup btn-close-popup-account"
            onClick={closeAccountPopup}
            href="#"
          >
            <svg
              className="icon-16 d-inline-flex align-items-center justify-content-center"
              fill="#111111"
              stroke="#111111"
              width={24}
              height={24}
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </a>
          <div className="box-banner-popup" />
          <div className="form-account-info">
            <a
              className={`button-tab btn-for-login ${
                activeTab === "login" ? "active" : ""
              }`}
              href="#"
              onClick={showLoginForm}
            >
              Đăng nhập
            </a>
            <a
              className={`button-tab btn-for-signup ${
                activeTab === "signup" ? "active" : ""
              }`}
              href="#"
              onClick={showSignUpForm}
            >
              Đăng ký 
            </a>
            {activeTab === "login" && (
              <form action="" onSubmit={handleLogin}>
                <div className="form-login">
                  <div className="form-group">
                    <input
                      className="form-control"
                      type="email"
                      placeholder="Nhập địa chỉ email"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                    />
                  </div>
                  <div className="form-group">
                    <input
                      className="form-control"
                      type="password"
                      placeholder="Nhập mật khẩu"
                      value={password}
                      onChange={(e) => setPassword(e.target.value)}
                    />
                  </div>
                  <div className="form-group">
                    <a className="brand-1 font-sm buttun-forgotpass" href="#">
                      Quên mật khẩu?
                    </a>
                  </div>
                  <div className="form-group">
                    <button
                      className="btn btn-login d-block"
                      disabled={loading}
                    >
                      {loading ? "Đăng nhập..." : "Đăng nhập"}
                    </button>
                  </div>
                  <div className="form-group mt-100 text-center">
                    <a className="font-sm" href="#">
                      {/* Privacy &amp; Terms */}
                    </a>
                  </div>
                </div>
              </form>
            )}

            {activeTab === "signup" && (
              <form onSubmit={handleSubmit}>
                <div
                  className="form-register"
                  style={{ display: activeTab === "signup" ? "block" : "none" }}
                >
                   <div className="form-group">
                    <input
                      className="form-control"
                      type="text"
                      placeholder="Tên đăng nhập"
                      onChange={(e) => setUsername(e.target.value)}
                    />
                  </div>
                  <div className="form-group">
                    <input
                      className="form-control"
                      type="text"
                      placeholder="Tài khoản email"
                      onChange={(e) => setEmail(e.target.value)}
                    />
                  </div>
                  <div className="form-group">
                    <input
                      className="form-control"
                      type="password"
                      placeholder="Mật khẩu"
                      onChange={(e) => setPassword(e.target.value)}
                    />
                  </div>
                  <div className="form-group">
                    <input
                      className="form-control"
                      type="password"
                      placeholder="Xác nhận lại mật khẩu"
                      onChange={(e) => setConfirmPassword(e.target.value)}

                    />
                  </div>
                  <div className="form-group">
                    <button className="btn btn-login d-block">
                      Đăng ký tài khoản
                    </button>
                  </div>
                  <div className="text-center">
                    <p className="body-p2 neutral-medium-dark">
                      Bạn đã có tài khoản rồi?{" "}
                      <a 
                        style={{color:'red'}}
                        className="neutral-dark login-now"
                        href="#"
                        onClick={showLoginForm}
                      >
                        Đăng nhập ngay
                      </a>
                    </p>
                  </div>
                  <div className="form-group mt-100 text-center">
                    <a className="font-sm" href="#">
                      {/* Privacy &amp; Terms */}
                    </a>
                  </div>
                </div>
              </form>
            )}
          </div>
          <div className="form-password-info">
            <h5>Recover password</h5>
            <div className="form-login">
              <div className="form-group">
                <input
                  className="form-control"
                  type="text"
                  placeholder="Enter your email"
                />
              </div>
              <div className="form-group">
                <button className="btn btn-login d-block">Recover</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Header;
