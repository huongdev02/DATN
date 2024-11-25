import React from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation } from 'swiper/modules';
import Arrow from '../../assets/imgs/template/icons/arrow-grey.svg';
import Trending from '../../assets/imgs/page/homepage1/product12.png';
import TrendingTwo from '../../assets/imgs/page/homepage1/product13.png';
import TrendingThree from '../../assets/imgs/page/homepage1/product14.png';
import Star from '../../assets/imgs/page/homepage1/star.png';
import StarTwo from '../../assets/imgs/page/homepage1/star2.png';

const TrendingProduct: React.FC = () => {
    return (
        <>
            <section className="section block-section-3">
                <div className="container">
                    <div className="top-head">
                        <h4 className="text-uppercase brand-1 wow animate__animated animate__fadeIn">
                            Sản phẩm thịnh hành
                            <a className="btn btn-arrow-right neutral-500 text-capitalize ml-10 wow animate__animated animate__fadeIn" href="#">
                                Xem tất cả
                                <img src={Arrow} alt="Kidify" />
                            </a>
                        </h4>
                        <div className="box-button-swiper">
                            <div className="swiper-button-prev swiper-button-prev-collection btn-prev-style-1" />
                            <div className="swiper-button-next swiper-button-next-collection btn-next-style-1" />
                        </div>
                    </div>
                    <div className="box-products wow animate__animated animate__fadeIn">
                        <Swiper
                            navigation
                            modules={[Navigation]}
                            className="swiper-container"
                            slidesPerView={3}
                            spaceBetween={30}
                        >
                            <SwiperSlide>
                                <div className="cardProduct wow fadeInUp">
                                    <div className="cardImage">
                                        <label className="lbl-hot">hot</label>
                                        <a href="product-single.html">
                                            <img className="imageMain" src={TrendingThree} alt="kidify" />
                                            <img className="imageHover" src={TrendingTwo} alt="kidify" />
                                        </a>
                                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                        <div className="box-quick-button">
                                        </div>
                                    </div>
                                    <div className="cardInfo">
                                        <a href="product-single.html">
                                            <h6 className="font-md-bold cardTitle">Junior Jogger Pants</h6>
                                        </a>
                                        <p className="font-lg cardDesc">$16.00</p>
                                    </div>
                                </div>
                            </SwiperSlide>
                            <SwiperSlide>
                                <div className="cardProduct wow fadeInUp">
                                    <div className="cardImage">
                                        <label className="lbl-hot">hot</label>
                                        <a href="product-single.html">
                                            <img className="imageMain" src={Trending} alt="kidify" />
                                            <img className="imageHover" src={TrendingTwo} alt="kidify" />
                                        </a>
                                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                        <div className="box-quick-button">
                                        </div>
                                    </div>
                                    <div className="cardInfo">
                                        <a href="product-single.html">
                                            <h6 className="font-md-bold cardTitle">Junior Jogger Pants</h6>
                                        </a>
                                        <p className="font-lg cardDesc">$16.00</p>
                                    </div>
                                </div>
                            </SwiperSlide>
                            <SwiperSlide>
                                <div className="cardProduct wow fadeInUp">
                                    <div className="cardImage">
                                        <label className="lbl-hot">hot</label>
                                        <a href="product-single.html">
                                            <img className="imageMain" src={TrendingTwo} alt="kidify" />
                                            <img className="imageHover" src={Trending} alt="kidify" />
                                        </a>
                                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                        <div className="box-quick-button">
                                        </div>
                                    </div>
                                    <div className="cardInfo">
                                        <a href="product-single.html">
                                            <h6 className="font-md-bold cardTitle">Junior Jogger Pants</h6>
                                        </a>
                                        <p className="font-lg cardDesc">$16.00</p>
                                    </div>
                                </div>
                            </SwiperSlide>
                        </Swiper>
                    </div>
                </div>
            </section>
            <section className="section block-section-4">
                <div className="container">
                    <div className="box-section-4">
                        <div className="row">
                            <div className="col-lg-6">
                                <div className="box-collection wow animate__animated animate__fadeIn">
                                    <div className="box-collection-info">
                                        <h4 className="heading-4 mb-15">Girls Apparels</h4>
                                        <p className="font-md neutral-900 mb-35">Get an extra 50% discount on premium<br className="d-none d-lg-block" />quality baby clothes. Shop now!</p><a className="btn btn-brand-1 text-uppercase" href="#">Shop Now</a>
                                    </div>
                                    <div className="star-bg-2"><img src={StarTwo} alt="Kidify" /></div>
                                </div>
                            </div>
                            <div className="col-lg-6">
                                <div className="box-collection box-collection-2 wow animate__animated animate__fadeIn">
                                    <div className="box-collection-info">
                                        <h4 className="heading-4 mb-15">Hot Branch</h4>
                                        <p className="font-md neutral-900 mb-35">New Brand Fasion on this Summer.<br className="d-none d-lg-block" />Sale off up to 35%</p><a className="btn btn-brand-1 text-uppercase" href="#">Shop Now</a>
                                    </div>
                                    <div className="star-bg-1"><img src={Star} alt="Kidify" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </>

    );
};

export default TrendingProduct;
