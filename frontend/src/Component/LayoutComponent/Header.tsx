import { useEffect, useState } from 'react';
import Logo from '../../assets/imgs/template/logo.svg'
import { Link } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';
import { AppDispatch, RootState } from '../../Redux/store';
import { login } from '../../Redux/Reducer/AuthReducer';
import {notification } from 'antd';
import { CartItem } from '../../Redux/Reducer/CartReducer';

interface User {
    id: number;
    fullname: string;
    avatar: string | null;
    role: string[];
    branch_id: number;
}

const Header: React.FC = () => {
    const [isSearchActive, setIsSearchActive] = useState(false);
    const [isCartActice, setIsCartActice] = useState(false);
    const [isAccountActive, setIsAccountActive] = useState(false);
    const [activeTab, setActiveTab] = useState('login');
    const dispatch = useDispatch<AppDispatch>();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const loading = useSelector((state: RootState) => state.auth.loading);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState<User | null>(null)
    const [cartItems, setCartItems] = useState<any[]>([]);
    const { cart } = useSelector((state: RootState) => state.cart);
    const cartItemsLength = JSON.parse(localStorage.getItem('cartItems') || '[]');  
    const cartLength = cartItemsLength.length;  
    useEffect(() => {
        const storedCart = localStorage.getItem('cartItems');
        console.log(storedCart);
        
        if (storedCart) {
            setCartItems(JSON.parse(storedCart));
        }
    }, []); 
    console.log(cartLength);  
    
    useEffect(() => {
        const userData = localStorage.getItem('user');
        if (userData) {
            setIsLoggedIn(true);
            setUser(JSON.parse(userData) as User);
        }
    }, []);

    const handleLogin = async (e: React.FormEvent) => {
        e.preventDefault();
        console.log('Email:', email);
        console.log('Password:', password);
        if (!password.trim() || !email.trim()) {
            notification.error(
                {
                    message: 'Vui lòng nhập đầy đủ thông tin'
                }
            )
            return false;
        }
        try {
            const resultAction = await dispatch(login({ email, password }));

            if (login.fulfilled.match(resultAction)) {
                const userData = resultAction.payload;
                localStorage.setItem('user', JSON.stringify(userData));
                localStorage.setItem('token', userData.token);
                window.location.reload();

                notification.success({
                    message: 'Đăng nhập thành công',
                    description: `Chào mừng, ${resultAction.payload.email}! đẹp traiii`,
                });
                setTimeout(() => {
                }, 1000);
            } else {
                notification.error({
                    message: 'Tài khoản không chính khác'
                })
                setPassword('')
            }
        } catch (err) {
            console.error('Đăng nhập thất bại:', err);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        localStorage.removeItem('cart');
        setIsLoggedIn(false);
        notification.success({
            message: 'Đăng xuất thành công'
        })
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    };



    const showLoginForm = () => {
        setActiveTab('login');
    };

    const showSignUpForm = () => {
        setActiveTab('signup');
    };

    const openSearchPopup = (e: any) => {
        e.preventDefault();
        setIsSearchActive(true);
    };

    const openAccountPopup = (e: any) => {
        e.preventDefault();
        setIsAccountActive(true);
    }

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

    useEffect(() => {
        // Lấy thông tin người dùng từ localStorage
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
          setUser(JSON.parse(storedUser));
        }
      }, []);

    return (
        <>
            <header className="header sticky-bar header-style-1">
                <div className="container">
                    <div className="main-header">
                        <div className="header-logo"><Link to='/' className="d-flex"><img alt="kidify" src={Logo} /></Link></div>
                        <div className="header-menu">
                            <div className="header-nav">
                                <nav className="nav-main-menu d-none d-xl-block">
                                    <ul className="main-menu">
                                        <li className="has-mega-menu"><Link to='/' className="active">Trang chủ</Link>
                                        </li>
                                        <li><Link to='/about'>About</Link></li>
                                        <li className="has-mega-menu"><Link to="/product">Cửa hàng</Link></li>
                                        <li className="has-children"><Link to='/blog'>Blog</Link>
                                        </li>
                                        <li><Link to='/contact'>Contact</Link></li>
                                    </ul>
                                </nav>
                                <div className="burger-icon burger-icon-white"><span className="burger-icon-top" /><span className="burger-icon-mid" /><span className="burger-icon-bottom" /></div>
                            </div>
                        </div>
                        <div className="header-account">
      <a className="account-icon search" onClick={openSearchPopup} href="#">
        {/* Icon Search */}
      </a>

      {user ? (
        <div className="account-info">
          <img
            // src={user.avatar}
            alt="User Avatar"
            className="avatar"
            style={{ width: '28px', height: '28px', borderRadius: '50%' }}
          />
          <span className="fullname">{user.fullname}</span>
        </div>
      ) : (
        <a className="account-icon account" href="#" onClick={openAccountPopup}>
          {/* Icon Account */}
        </a>
      )}

      <a className="account-icon wishlist" href="compare.html">
        <span className="number-tag">3</span>
        {/* Icon Wishlist */}
      </a>

      <Link to="/cart" className="account-icon cart" onClick={openCartPopup}>
        <span className="number-tag">{cartLength}</span>
        {/* Icon Cart */}
      </Link>
    </div>
                    </div>
                </div>
            </header>
            <div className="box-popup-search perfect-scrollbar" style={{ visibility: isSearchActive ? 'visible' : 'hidden' }} >
                <div className="box-search-overlay" />
                <div className={`box-search-wrapper ${isSearchActive ? 'active' : ''}`}><a onClick={closeSearchPopup} className="btn-close-popup" href="#">
                    <svg className="icon-16 d-inline-flex align-items-center justify-content-center" fill="#111111" stroke="#111111" width={24} height={24} viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg></a>
                    <h5 className="mb-15">Search products</h5>
                    <form action="#">
                        <div className="form-group">
                            <input className="form-control search-icon" type="text" placeholder="Enter keyword" />
                        </div>
                    </form>
                    <div className="box-quick-search"><span className="text-17 neutral-medium-dark mr-5">Quick search:</span><a className="text-17" href="#">T-Shirt</a><a className="text-17" href="#">Jeans</a><a className="text-17" href="#">Mens</a></div>
                    <h5 className="mb-15 mt-30">Category</h5>
                    <form action="#">
                        <div className="form-group">
                            <select className="form-control arrow-select">
                                <option>All Categories</option>
                                <option>Animals &amp; Pet Supplies</option>
                                <option>Baby &amp; Toddler</option>
                                <option>Boys' Clothing</option>
                                <option>Baby Care</option>
                                <option>Safety Equipment</option>
                                <option>Activity &amp; Gear</option>
                                <option>Baby Shoes</option>
                                <option>Children's Shoes</option>
                                <option>Family Outfits</option>
                                <option>Womens</option>
                            </select>
                        </div>
                    </form>
                    <div className="block-filter mt-30">
                        <h5 className="item-collapse">Price</h5>
                        <div className="box-collapse">
                            <div className="box-slider-range mt-20 mb-25">
                                <div className="row mb-20">
                                    <div className="col-sm-12">
                                        <div id="slider-range-popup" />
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-lg-12">
                                        <label className="lb-slider font-sm neutral-500 mr-5">Price Range:</label><span className="min-value-money font-sm neutral-900" />
                                        <label className="lb-slider font-sm neutral-900" />-<span className="max-value-money font-sm neutral-900" />
                                    </div>
                                    <div className="col-lg-12">
                                        <input className="form-control min-value" type="hidden" name="min-value" />
                                        <input className="form-control max-value" type="hidden" name="max-value" />
                                    </div>
                                </div>
                            </div>
                            <ul className="list-filter-checkbox">
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">$100 - $200</span><span className="checkmark" />
                                    </label><span className="number-item">12</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">$200 - $400</span><span className="checkmark" />
                                    </label><span className="number-item">24</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">$400 - $600</span><span className="checkmark" />
                                    </label><span className="number-item">54</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">$600 - $800</span><span className="checkmark" />
                                    </label><span className="number-item">78</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">Over $1000</span><span className="checkmark" />
                                    </label><span className="number-item">125</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="block-filter mt-30">
                        <h5 className="item-collapse">Size</h5>
                        <div className="box-collapse">
                            <div className="block-size">
                                <div className="list-sizes"><span className="item-size">XS</span><span className="item-size active">S</span><span className="item-size">M</span><span className="item-size">XL</span><span className="item-size">XXL</span></div>
                            </div>
                        </div>
                    </div>
                    <div className="block-filter mt-30">
                        <h5 className="item-collapse mb-5">Colors</h5>
                        <div className="box-collapse">
                            <ul className="list-color">
                                <li className="active"><span className="box-circle-color"><a className="color-red active" href="#" /></span><span className="font-xs">Red</span></li>
                                <li><span className="box-circle-color"><a className="color-green" href="#" /></span><span className="font-xs">Green</span></li>
                                <li><span className="box-circle-color"><a className="color-orange" href="#" /></span><span className="font-xs">Orange</span></li>
                                <li><span className="box-circle-color"><a className="color-yellow" href="#" /></span><span className="font-xs">Yellow</span></li>
                                <li><span className="box-circle-color"><a className="color-blue" href="#" /></span><span className="font-xs">Blue</span></li>
                                <li><span className="box-circle-color"><a className="color-gray" href="#" /></span><span className="font-xs">Gray</span></li>
                                <li><span className="box-circle-color"><a className="color-brown" href="#" /></span><span className="font-xs">Brown</span></li>
                                <li><span className="box-circle-color"><a className="color-cyan" href="#" /></span><span className="font-xs">Cyan</span></li>
                                <li><span className="box-circle-color"><a className="color-cyan-2" href="#" /></span><span className="font-xs">Cyan 2</span></li>
                                <li><span className="box-circle-color"><a className="color-purple" href="#" /></span><span className="font-xs">Purple</span></li>
                            </ul>
                        </div>
                    </div>
                    <div className="block-filter mt-30">
                        <h5 className="item-collapse mb-15">Brand</h5>
                        <div className="box-collapse">
                            <ul className="list-filter-checkbox">
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">Seraphine</span><span className="checkmark" />
                                    </label><span className="number-item">136</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">Monica + Andy</span><span className="checkmark" />
                                    </label><span className="number-item">136</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">Maisonette</span><span className="checkmark" />
                                    </label><span className="number-item">136</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">Pink Chicken</span><span className="checkmark" />
                                    </label><span className="number-item">136</span>
                                </li>
                                <li>
                                    <label className="cb-container">
                                        <input type="checkbox" /><span className="text-small">Hanna Andersson</span><span className="checkmark" />
                                    </label><span className="number-item">136</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div className="box-popup-cart perfect-scrollbar" style={{ visibility: isCartActice ? 'visible' : 'hidden' }}>
                <div className="box-cart-overlay">
                    <div className={`box-cart-wrapper ${isCartActice ? 'active' : ''}`}>
                        <a onClick={closeCartPopup} href="#">
                            <svg className="icon-16 d-inline-flex align-items-center justify-content-center" fill="#111111" stroke="#111111" width={24} height={24} viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                        <h5 className="mb-15 mt-50">Your Cart</h5>
                        <div className="list-product-4 mb-50">
                            {cartItems.length > 0 ? (
                                cartItems.map((item, index) => (
                                    <div key={index} className="cardProduct cardProduct4">
                                        <div className="cardImage">
                                            {/* <Link to={`/product/${item.id}`}>
                                                <img src={`http://127.0.0.1:8000/storage/${item.product.avatar}` || 'default-image.jpg'} alt={item.product.name} />
                                            </Link> */}
                                        </div>
                                        <div className="cardInfo">
                                            <Link to={`/product/${item.id}`}>
                                                <h6 className="font-md-bold cardTitle">{item.product_name}</h6>
                                            </Link>
                                            <div className="product-price-bottom">
                                                <p className="font-lg cardDesc">
                                                    {item.price.toLocaleString('vi-VN')}VNĐ x {item.quantity}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <p>Your cart is empty.</p>
                            )}
                        </div>
                        <Link to='/cart' onClick={closeCartPopup} className="btn btn-brand-1-xl-bold w-100 font-md-bold">
                            View your cart
                            <svg className="icon-16 ml-5" fill="none" stroke="#ffffff" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
            <div className="box-popup-account" style={{ visibility: isAccountActive ? 'visible' : 'hidden', display: isAccountActive ? 'block' : 'none' }}>
                <div className="box-account-overlay" />
                <div className={`box-account-wrapper ${isAccountActive ? 'active' : ''}`}><a className='btn-close-popup btn-close-popup-account' onClick={closeAccountPopup} href="#">
                    <svg className="icon-16 d-inline-flex align-items-center justify-content-center" fill="#111111" stroke="#111111" width={24} height={24} viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg></a>
                    <div className="box-banner-popup" />
                    <div className="form-account-info">
                        <a
                            className={`button-tab btn-for-login ${activeTab === 'login' ? 'active' : ''}`}
                            href="#"
                            onClick={showLoginForm}
                        >
                            Login
                        </a>
                        <a
                            className={`button-tab btn-for-signup ${activeTab === 'signup' ? 'active' : ''}`}
                            href="#"
                            onClick={showSignUpForm}
                        >
                            Sign Up
                        </a>
                        {activeTab === 'login' && (
                            <form action="" onSubmit={handleLogin}>
                                <div className="form-login">
                                    <div className="form-group">
                                        <input
                                            className="form-control"
                                            type="email"
                                            placeholder="Email"
                                            value={email}
                                            onChange={(e) => setEmail(e.target.value)}
                                        />

                                    </div>
                                    <div className="form-group">
                                        <input
                                            className="form-control"
                                            type="password"
                                            placeholder="Password"
                                            value={password}
                                            onChange={(e) => setPassword(e.target.value)}
                                        />
                                    </div>
                                    <div className="form-group">
                                        <a className="brand-1 font-sm buttun-forgotpass" href="#">Forgot your password?</a>
                                    </div>
                                    <div className="form-group">
                                        <button className="btn btn-login d-block" disabled={loading}>{loading ? 'Login...' : 'Login'}</button>
                                    </div>
                                    <div className="form-group mt-100 text-center">
                                        <a className="font-sm" href="#">Privacy &amp; Terms</a>
                                    </div>
                                </div>
                            </form>

                        )}

                        {activeTab === 'signup' && (
                            <div className="form-register" style={{ display: activeTab === "signup" ? 'block' : 'none' }}>
                                <div className="form-group">
                                    <input className="form-control" type="text" placeholder="First Name" />
                                </div>
                                <div className="form-group">
                                    <input className="form-control" type="text" placeholder="Last Name" />
                                </div>
                                <div className="form-group">
                                    <input className="form-control" type="text" placeholder="Email" />
                                </div>
                                <div className="form-group">
                                    <input className="form-control" type="password" placeholder="Password" />
                                </div>
                                <div className="form-group">
                                    <label className="d-flex align-items-start">
                                        <input className="cb-agree" type="checkbox" />
                                        <span className="text-agree body-p2">
                                            Join for Free and start earning points today. Benefits include 15% off your first purchase.
                                        </span>
                                    </label>
                                </div>
                                <div className="form-group">
                                    <button className="btn btn-login d-block">Create my account</button>
                                </div>
                                <div className="text-center">
                                    <p className="body-p2 neutral-medium-dark">
                                        Already have an account? <a className="neutral-dark login-now" href="#" onClick={showLoginForm}>Login Now</a>
                                    </p>
                                </div>
                                <div className="form-group mt-100 text-center">
                                    <a className="font-sm" href="#">Privacy &amp; Terms</a>
                                </div>
                            </div>
                        )}
                    </div>
                    <div className="form-password-info">
                        <h5>Recover password</h5>
                        <div className="form-login">
                            <div className="form-group">
                                <input className="form-control" type="text" placeholder="Enter your email" />
                            </div>
                            <div className="form-group">
                                <button className="btn btn-login d-block">Recover</button>
                            </div>
                            <div className="text-center">
                                <p className="body-p2 neutral-medium-dark">Already have an account?<a className="neutral-dark login-now" href="#">Login Now</a></p>
                            </div>
                            <div className="form-group mt-100 text-center"><a className="font-sm" href="#">Privacy &amp; Terms</a></div>
                        </div>
                    </div>
                </div>
            </div>

        </>
    )
}

export default Header
