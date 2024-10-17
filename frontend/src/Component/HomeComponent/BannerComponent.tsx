import React from 'react';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination } from 'swiper/modules';
import Banner from '../../assets/imgs/page/homepage1/banner.png';
import BannerTwo from '../../assets/imgs/page/homepage1/banner2.png';
import Sale from '../../assets/imgs/page/homepage1/sale.png';
import Leaf from '../../assets/imgs/page/homepage1/leaf.png'
import Star from '../../assets/imgs/page/homepage1/star.png'
import Arrow from '../../assets/imgs/template/icons/arrow.svg'
const BannerComponent: React.FC = () => {
    return (
        <section className="section banner-homepage1">
            <div className="container">
                <div className="box-swiper">
                    <Swiper
                        modules={[Navigation, Pagination]}
                        navigation
                        pagination={{ clickable: true }}
                        className="swiper-banner pb-0"
                    >
                        <SwiperSlide>
                            <div className="box-banner-home1">
                                <div
                                    className="box-cover-image wow animate__animated animate__fadeInLeft"
                                    style={{ backgroundImage: `url(${Banner})` }}
                                />
                                <div className="box-banner-info">
                                    <div className="block-sale wow animate__animated animate__fadeInTop">
                                        <img src={Sale} alt="Kidify" />
                                    </div>
                                    <div className="blockleaf rotateme">
                                        <img src={Leaf} alt="Kidify" />
                                    </div>
                                    <div className="block-info-banner">
                                        <p className="font-3xl-bold neutral-900 title-line mb-10 wow animate__animated animate__zoomIn">Winter</p>
                                        <h2 className="heading-banner mb-10 wow animate__animated animate__zoomIn">
                                            <span className="text-up">sale off</span>
                                            <span className="text-under">sale off</span>
                                        </h2>
                                        <h4 className="heading-4 title-line-2 mb-30 wow animate__animated animate__zoomIn">Anything for your baby</h4>
                                        <div className="text-center mt-10">
                                            <a className="btn btn-double-border wow animate__animated animate__zoomIn" href="#">
                                                <span>View All Deals</span>
                                            </a>
                                            <a className="btn btn-arrow-right wow animate__animated animate__zoomIn" href="#">
                                                Learn More
                                                <img src={Arrow} alt="Kidify" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </SwiperSlide>
                        <SwiperSlide>
                            <div className="box-banner-home1">
                                <div
                                    className="box-cover-image wow animate__animated animate__fadeInLeft"
                                    style={{ backgroundImage: `url(${BannerTwo})` }}
                                />
                                <div className="box-banner-info wow animate__animated animate__zoomIn">
                                    <div className="block-sale wow animate__animated animate__fadeInTop">
                                        <img src={Sale} alt="Kidify" />
                                    </div>
                                    <div className="blockleaf rotateme">
                                        <img src={Star} alt="Kidify" />
                                    </div>
                                    <div className="block-info-banner">
                                        <p className="font-3xl-bold neutral-900 title-line mb-10 wow animate__animated animate__zoomIn">Winter</p>
                                        <h2 className="heading-banner mb-10 wow animate__animated animate__zoomIn">
                                            <span className="text-up">sale off</span>
                                            <span className="text-under">sale off</span>
                                        </h2>
                                        <h4 className="heading-4 title-line-2 mb-30 wow animate__animated animate__zoomIn">Anything for your baby</h4>
                                        <div className="text-center mt-10">
                                            <a className="btn btn-double-border wow animate__animated animate__zoomIn" href="#">
                                                <span>View All Deals</span>
                                            </a>
                                            <a className="btn btn-arrow-right wow animate__animated animate__zoomIn" href="#">
                                                Learn More
                                                <img src="assets/imgs/template/icons/arrow.svg" alt="Kidify" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </SwiperSlide>
                    </Swiper>
                </div>
            </div>
        </section>
    );
};

export default BannerComponent;
