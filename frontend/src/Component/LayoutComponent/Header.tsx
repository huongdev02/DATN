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


   
    const hanldeNavigate = async () =>{
        try {
            const { data } = await api.get(`http://127.0.0.1:8000/api/users/${userId}`);
             if(data.user.role === 1 || data.user.role === 2){
                window.location.href = 'http://127.0.0.1:8000/admin/dashboard';
             }else{
                window.location.href = 'http://127.0.0.1:8000/user/dashboard';
             }
          } catch (error) {
            window.location.href = 'http://127.0.0.1:8000/login';
          }
        
    }

    const GetUser = async () => {
        try {
          const { data } = await api.get(`http://127.0.0.1:8000/api/users/${userId}`);
         
        } catch (error) {
           console.log(error);
        }
      };
    
    useEffect(()=>{
        GetUser();
    },[])  
    

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
                                <svg className="d-inline-flex align-items-center justify-content-center" width={28} height={28} viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                                    <g clipPath="url(#clip0_91_73)">
                                        <path d="M20.031 18.617L24.314 22.899L22.899 24.314L18.617 20.031C17.0237 21.3082 15.042 22.0029 13 22C8.032 22 4 17.968 4 13C4 8.032 8.032 4 13 4C17.968 4 22 8.032 22 13C22.0029 15.042 21.3082 17.0237 20.031 18.617ZM18.025 17.875C19.2941 16.5699 20.0029 14.8204 20 13C20 9.132 16.867 6 13 6C9.132 6 6 9.132 6 13C6 16.867 9.132 20 13 20C14.8204 20.0029 16.5699 19.2941 17.875 18.025L18.025 17.875Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_91_73">
                                            <rect width={24} height={24} fill="white" transform="translate(2 2)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                            <a
                                className="account-icon account"
                                href="#"
                                onClick={hanldeNavigate}  
                            >
                                <svg className="d-inline-flex align-items-center justify-content-center" width={28} height={28} viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                                    <g clipPath="url(#clip0_116_451)">
                                        <path d="M6 24C6 21.8783 6.84285 19.8434 8.34315 18.3431C9.84344 16.8429 11.8783 16 14 16C16.1217 16 18.1566 16.8429 19.6569 18.3431C21.1571 19.8434 22 21.8783 22 24H20C20 22.4087 19.3679 20.8826 18.2426 19.7574C17.1174 18.6321 15.5913 18 14 18C12.4087 18 10.8826 18.6321 9.75736 19.7574C8.63214 20.8826 8 22.4087 8 24H6ZM14 15C10.685 15 8 12.315 8 9C8 5.685 10.685 3 14 3C17.315 3 20 5.685 20 9C20 12.315 17.315 15 14 15ZM14 13C16.21 13 18 11.21 18 9C18 6.79 16.21 5 14 5C11.79 5 10 6.79 10 9C10 11.21 11.79 13 14 13Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_116_451">
                                            <rect width={24} height={24} fill="white" transform="translate(2 2)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                            {/* <SettingOutlined style={{fontSize: '18px'}}/> */}
                            <a className="account-icon wishlist" href="compare.html"><span className="number-tag">3</span>
                                <svg className="d-inline-flex align-items-center justify-content-center" width={28} height={28} viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                                    <g clipPath="url(#clip0_116_452)">
                                        <path d="M14.001 6.52898C16.35 4.41998 19.98 4.48998 22.243 6.75698C24.505 9.02498 24.583 12.637 22.479 14.993L13.999 23.485L5.52101 14.993C3.41701 12.637 3.49601 9.01898 5.75701 6.75698C8.02201 4.49298 11.645 4.41698 14.001 6.52898ZM20.827 8.16998C19.327 6.66798 16.907 6.60698 15.337 8.01698L14.002 9.21498L12.666 8.01798C11.091 6.60598 8.67601 6.66798 7.17201 8.17198C5.68201 9.66198 5.60701 12.047 6.98001 13.623L14 20.654L21.02 13.624C22.394 12.047 22.319 9.66498 20.827 8.16998Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_116_452">
                                            <rect width={24} height={24} fill="white" transform="translate(2 2)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a>
                            <Link to='/cart' className="account-icon cart" onClick={openCartPopup}><span className="number-tag">{cartLength}</span>
                                <svg width={28} height={28} viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg">
                                    <g clipPath="url(#clip0_116_450)">
                                        <path d="M9 10V8C9 6.67392 9.52678 5.40215 10.4645 4.46447C11.4021 3.52678 12.6739 3 14 3C15.3261 3 16.5979 3.52678 17.5355 4.46447C18.4732 5.40215 19 6.67392 19 8V10H22C22.2652 10 22.5196 10.1054 22.7071 10.2929C22.8946 10.4804 23 10.7348 23 11V23C23 23.2652 22.8946 23.5196 22.7071 23.7071C22.5196 23.8946 22.2652 24 22 24H6C5.73478 24 5.48043 23.8946 5.29289 23.7071C5.10536 23.5196 5 23.2652 5 23V11C5 10.7348 5.10536 10.4804 5.29289 10.2929C5.48043 10.1054 5.73478 10 6 10H9ZM9 12H7V22H21V12H19V14H17V12H11V14H9V12ZM11 10H17V8C17 7.20435 16.6839 6.44129 16.1213 5.87868C15.5587 5.31607 14.7956 5 14 5C13.2044 5 12.4413 5.31607 11.8787 5.87868C11.3161 6.44129 11 7.20435 11 8V10Z" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_116_450">
                                            <rect width={24} height={24} fill="white" transform="translate(2 2)" />
                                        </clipPath>
                                    </defs>
                                </svg>
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
