import ProductOne from '../../assets/imgs/page/homepage1/product1.png';
import ProductFour from '../../assets/imgs/page/homepage1/product4.png';
import ProductThree from '../../assets/imgs/page/homepage1/product3.png';
import ProductTwo from '../../assets/imgs/page/homepage1/product2.png';
import ProductSix from '../../assets/imgs/page/homepage1/product6.png';
import ProductSeven from '../../assets/imgs/page/homepage1/product7.png'
import Sale from '../../assets/imgs/page/homepage1/upto60.png'
import Banner from '../../assets/imgs/page/homepage1/bg-section2.png'
import ProductDetail from '../../assets/imgs/page/product/img.png'
import ProductDetailTwo from '../../assets/imgs/page/product/img-2.png'
import ProductDetailThree from '../../assets/imgs/page/product/img-3.png'
import ProductDetailFour from '../../assets/imgs/page/product/img-4.png'
import ProductDetailFive from '../../assets/imgs/page/product/img-5.png'
import ProductDetailSix from '../../assets/imgs/page/product/img-6.png'
import { Swiper, SwiperSlide } from 'swiper/react';
import Star from '../../assets/imgs/template/icons/star.svg'
import api from '../../configAxios/axios';
import { message } from 'antd';
import { useState , useEffect} from 'react';
import { IProduct } from '../../types/cart';
import Cookies from 'js-cookie';
const ProductWithCategories: React.FC = () => {
    const [products, setProducts] = useState<IProduct[]>([]);
   
    const GetProductCategory = async () => {
        try {
          const { data } = await api.get(`/products`);
          setProducts(data.products);
        } catch (error) {
          message.error("Lỗi api !");
        }
      };

     

      const boyProducts = products.filter(product => product.categories.name === 'Nam');
      const girlProducts = products.filter(product => product.categories.name === 'Nữ');
      const kidProducts = products.filter(product => product.categories.name === 'Trẻ em');
      
      console.log("user", document.cookie);

     

    useEffect(()=>{
        GetProductCategory();
    },[])


    return (
        <>
            <section className="section block-section-1">
                <div className="container">
                    <div className="text-center">
                        <p className="font-xl brand-2 wow animate__animated animate__fadeIn">
                            <span className="rounded-text">NEW IN STORE</span>
                        </p>
                        <div className="box-tabs wow animate__animated animate__fadeIn">
                            <ul className="nav nav-tabs" role="tablist">
                                <li className="nav-item" role="presentation">
                                    <button
                                        className="nav-link active"
                                        id="girls-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#girls"
                                        type="button"
                                        role="tab"
                                        aria-controls="girls"
                                        aria-selected="true"
                                    >
                                        Thời trang Nam
                                    </button>
                                </li>
                                <li className="nav-item" role="presentation">
                                    <button
                                        className="nav-link"
                                        id="boys-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#boys"
                                        type="button"
                                        role="tab"
                                        aria-controls="boys"
                                        aria-selected="false"
                                    >
                                       Thời trang Nữ
                                    </button>
                                </li>
                                <li className="nav-item" role="presentation">
                                    <button
                                        className="nav-link"
                                        id="accessories-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#accessories"
                                        type="button"
                                        role="tab"
                                        aria-controls="accessories"
                                        aria-selected="false"
                                    >
                                       Dành cho bé yêu
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {/* sản phẩm theo danh mục */}
                    <div className="tab-content">
                        <div
                            className="tab-pane fade show active"
                            id="girls"
                            role="tabpanel"
                            aria-labelledby="girls-tab"
                        >
                            <div className="row">
                            {boyProducts.map((product) => (
                                <div
                                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                                    data-wow-delay=".5s"
                                >
                                    <div className="cardProduct wow fadeInUp">
                                        <div className="cardImage">
                                            <label className="lbl-hot">hot</label>
                                            <a href="product-single.html">
                                                <img
                                                    className="imageMain"
                                                    src={product.avatar_url}
                                                    alt="kidify"
                                                />
                                                <img
                                                    className="imageHover"
                                                    src={product.avatar_url}
                                                    alt="kidify"
                                                />
                                            </a>
                                            <div className="button-select">
                                                <a href="product-single.html">Add to Cart</a>
                                            </div>
                                            <div className="box-quick-button">
                                                <a
                                                    className="btn"
                                                    aria-label="Quick view"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#quickViewModal"
                                                >
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
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
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                                        />
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a className="btn" href="#">
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
                                                        width={18}
                                                        height={18}
                                                        viewBox="0 0 18 18"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                        <path
                                                            d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                        <path
                                                            d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                        />
                                                        <path
                                                            d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                        />
                                                    </svg>
                                                </a>
                                                <a className="btn" href="#">
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
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
                                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                                        />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div className="cardInfo">
                                            <a href="product-single.html">
                                                <h6 className="font-md-bold cardTitle">{product.name}</h6>
                                            </a>
                                            <p className="font-lg cardDesc"> {Math.round(product.price ?? 0).toLocaleString(  "vi-VN", {style: "currency", currency: "VND",} )}</p>
                                        </div>
                                    </div>
                                </div>
                                   ))}
                            </div>
                        </div>
                        <div 
                            className="tab-pane fade"
                            id="boys"
                            role="tabpanel"
                            aria-labelledby="boys-tab"
                        >
                            <div className="row">
                            {girlProducts.map((product) => (
                                <div
                                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                                    data-wow-delay=".1s"
                                >
                                    <div className="cardProduct wow fadeInUp">
                                        <div className="cardImage">
                                            <label className="lbl-hot">hot</label>
                                            <a href="product-single.html">
                                                <img
                                                    className="imageMain"
                                                    src={product.avatar_url}
                                                    alt="kidify"
                                                />
                                                <img
                                                    className="imageHover"
                                                    src={product.avatar_url}
                                                    alt="kidify"
                                                />
                                            </a>
                                            <div className="button-select">
                                                <a href="product-single.html">Add to Cart</a>
                                            </div>
                                            <div className="box-quick-button">
                                                <a
                                                    className="btn"
                                                    aria-label="Quick view"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#quickViewModal"
                                                >
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
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
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                                        />
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a className="btn" href="#">
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
                                                        width={18}
                                                        height={18}
                                                        viewBox="0 0 18 18"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                        <path
                                                            d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                        <path
                                                            d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                        />
                                                        <path
                                                            d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                        />
                                                    </svg>
                                                </a>
                                                <a className="btn" href="#">
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
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
                                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                                        />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div className="cardInfo">
                                            <a href="product-single.html">
                                                <h6 className="font-md-bold cardTitle">{product.name}</h6>
                                            </a>
                                            <p className="font-lg cardDesc"> {Math.round(product.price ?? 0).toLocaleString(  "vi-VN", {style: "currency", currency: "VND",} )}</p>
                                        </div>
                                    </div>
                                </div>
                                ))}
                            </div>
                        </div>
                        <div
                            className="tab-pane fade"
                            id="accessories"
                            role="tabpanel"
                            aria-labelledby="children"
                        >
                            <div className="row">
                            {kidProducts.map((product) => (
                                <div
                                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                                    data-wow-delay=".1s"
                                >
                                    <div className="cardProduct wow fadeInUp">
                                        <div className="cardImage">
                                            <label className="lbl-hot">hot</label>
                                            <a href="product-single.html">
                                                <img
                                                    className="imageMain"
                                                    src={product.avatar_url}
                                                    alt="kidify"
                                                />
                                                <img
                                                    className="imageHover"
                                                    src={product.avatar_url}
                                                    alt="kidify"
                                                />
                                            </a>
                                            <div className="button-select">
                                                <a href="product-single.html">Add to Cart</a>
                                            </div>
                                            <div className="box-quick-button">
                                                <a
                                                    className="btn"
                                                    aria-label="Quick view"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#quickViewModal"
                                                >
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
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
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                                        />
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                    </svg>
                                                </a>
                                                <a className="btn" href="#">
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
                                                        width={18}
                                                        height={18}
                                                        viewBox="0 0 18 18"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                            d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                        <path
                                                            d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                        />
                                                        <path
                                                            d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                        />
                                                        <path
                                                            d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75"
                                                            stroke="#294646"
                                                            strokeWidth="1.5"
                                                            strokeLinecap="round"
                                                        />
                                                    </svg>
                                                </a>
                                                <a className="btn" href="#">
                                                    <svg
                                                        className="d-inline-flex align-items-center justify-content-center"
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
                                                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                                        />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div className="cardInfo">
                                            <a href="product-single.html">
                                                <h6 className="font-md-bold cardTitle">{product.name}</h6>
                                            </a>
                                            <p className="font-lg cardDesc"> {Math.round(product.price ?? 0).toLocaleString(  "vi-VN", {style: "currency", currency: "VND",} )}</p>
                                        </div>
                                    </div>
                                </div>
                              ))}
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <section className="section block-section-2">
                <div className="container">
                    <div className="box-info-section2">
                        <h2 className="heading-banner mb-25 wow animate__animated animate__bounceIn"><span className="text-up">Special sale</span><span className="text-under">Special sale</span></h2>
                        <p className="font-3xl-bold neutral-900 mb-35 wow animate__animated animate__fadeIn">Special promotions for our regular<br className="d-none d-lg-block" />customers. Time is limited.</p><a className="btn btn-brand-3" href="#">SHOP NOW</a>
                    </div>
                    <div className="block-sale-60 wow animate__animated animate__bounceIn"><img src={Sale} alt="Kidify" /></div>
                    <div className="block-section-img wow animate__animated animate__fadeIn"><img src={Banner} alt="Kidify" /></div>
                </div>
            </section>
            <div className="modal fade custom-modal" id="quickViewModal" tabIndex={-1} aria-labelledby="quickViewModalLabel" aria-hidden="true">
                <div className="box-newsletter-wrapper">
                    <div className="box-newsletter-inner">
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <a className="btn-close-popup" type="button" data-bs-dismiss="modal" aria-label="Close">
                                    <svg className="icon-16 d-inline-flex align-items-center justify-content-center" fill="#111111" stroke="#111111" width={24} height={24} viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                                <div className="modal-body">
                                    <div className="row">
                                        <div className="col-lg-6 box-images-product-left">
                                            <div className="detail-gallery">
                                                <Swiper spaceBetween={10} slidesPerView={1} navigation={true} pagination={{ clickable: true }} className="product-image-slider">
                                                    <SwiperSlide>
                                                        <figure className="border-radius-10">
                                                            <a className="glightbox" href={ProductDetail}><img src={ProductDetail} alt="kidify" /></a>
                                                        </figure>
                                                    </SwiperSlide>
                                                    <SwiperSlide>
                                                        <figure className="border-radius-10">
                                                            <a className="glightbox" href={ProductDetailTwo}><img src={ProductDetailTwo} alt="kidify" /></a>
                                                        </figure>
                                                    </SwiperSlide>
                                                    <SwiperSlide>
                                                        <figure className="border-radius-10">
                                                            <a className="glightbox" href={ProductDetailThree}><img src={ProductDetailThree} alt="kidify" /></a>
                                                        </figure>
                                                    </SwiperSlide>
                                                    <SwiperSlide>
                                                        <figure className="border-radius-10">
                                                            <a className="glightbox" href={ProductDetailFour}><img src={ProductDetailFour} alt="kidify" /></a>
                                                        </figure>
                                                    </SwiperSlide>
                                                    <SwiperSlide>
                                                        <figure className="border-radius-10">
                                                            <a className="glightbox" href={ProductDetailFive}><img src={ProductDetailFive} alt="kidify" /></a>
                                                        </figure>
                                                    </SwiperSlide>
                                                    <SwiperSlide>
                                                        <figure className="border-radius-10">
                                                            <a className="glightbox" href={ProductDetailSix}><img src={ProductDetailSix} alt="kidify" /></a>
                                                        </figure>
                                                    </SwiperSlide>
                                                </Swiper>
                                            </div>
                                        </div>
                                        <div className="col-lg-6 box-images-product-middle">
                                            <div className="box-product-info">
                                                <label className="flash-sale-red">Extra 2% off</label>
                                                <h2 className="font-2xl-bold">Autumn Winter Girls Kids Pants Plus Velvet Children's Leggings Cotton</h2>
                                                <div className="block-rating">
                                                    <img src={Star} alt="kidify" />
                                                    <img src={Star} alt="kidify" />
                                                    <img src={Star} alt="kidify" />
                                                    <img src={Star} alt="kidify" />
                                                    <img src={Star} alt="kidify" />
                                                    <span className="font-md neutral-500">(14 Reviews - 25 Orders)</span>
                                                </div>
                                                <div className="block-price"><span className="price-main">$15.00</span><span className="price-line">$25.00</span></div>
                                                <div className="block-color"><span>Color:</span>
                                                    <ul className="list-color">
                                                        <li className="active"><span className="box-circle-color"><a className="color-red active" href="#" /></span></li>
                                                        <li><span className="box-circle-color"><a className="color-green" href="#" /></span></li>
                                                        <li><span className="box-circle-color"><a className="color-orange" href="#" /></span></li>
                                                        <li><span className="box-circle-color"><a className="color-yellow" href="#" /></span></li>
                                                        <li><span className="box-circle-color"><a className="color-blue" href="#" /></span></li>
                                                        <li><span className="box-circle-color"><a className="color-gray" href="#" /></span></li>
                                                    </ul>
                                                </div>
                                                <div className="block-size"><span>Size:</span>
                                                    <div className="box-list-sizes">
                                                        <div className="list-sizes"><span className="item-size out-stock">XS</span><span className="item-size active">S</span><span className="item-size">M</span><span className="item-size">XL</span></div>
                                                    </div>
                                                </div>
                                                <div className="block-quantity">
                                                    <div className="font-sm neutral-500 mb-15">Quantity</div>
                                                </div>
                                                <div className="box-form-cart">
                                                    <div className="form-cart">
                                                        <span className="minus" />
                                                        <input className="form-control" type="text" defaultValue={1} />
                                                        <span className="plus" />
                                                    </div>
                                                    <a className="btn btn-brand-1-xl" href="#">Add to Cart</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           


        </>
    )
}

export default ProductWithCategories