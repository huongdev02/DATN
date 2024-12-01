import Blog from '../../assets/imgs/page/homepage1/blog1.png'
import BlogTwo from '../../assets/imgs/page/homepage1/blog2.png'
import BlogThree from '../../assets/imgs/page/homepage1/blog3.png'
import Arrow from '../../assets/imgs/template/icons/arrow-hover.svg'
import ArrowHover from '../../assets/imgs/template/icons/arrow-hover.svg'
import Ig from '../../assets/imgs/page/homepage1/instagram6.png'
import IgOne from '../../assets/imgs/page/homepage1/instagram.png'
import IgThree from '../../assets/imgs/page/homepage1/instagram3.png'
import IgFour from '../../assets/imgs/page/homepage1/instagram4.png'
import IgTwo from '../../assets/imgs/page/homepage1/instagram2.png'
import IgFive from '../../assets/imgs/page/homepage1/instagram5.png'
import Promotion from '../../assets/imgs/template/promotion.png'
import PromotionBanner from '../../assets/imgs/template/promotion-banner.png'
import { useState } from 'react'
const New: React.FC = () => {
    const [isOpen, setIsOpen] = useState(true);

    const closeModal = (e: React.MouseEvent) => {
        e.preventDefault();
        setIsOpen(false);
    };
    return (
        <>
            <section className="section block-section-8">
                <div className="container">
                    <div className="text-center">
                        <h4 className="text-uppercase brand-1 mb-15 brush-bg wow animate__fadeIn animated">Latest News and Events</h4>
                        <p className="font-lg neutral-500 mb-30 wow animate__animated animate__fadeIn">Don't miss out on great promotional news or upcoming<br className="d-none d-lg-block" />events in our store system</p>
                    </div>
                    <div className="row">
                        <div className="col-lg-4 col-md-6 wow animate__animated animate__fadeIn" data-wow-delay="0s">
                            <div className="cardBlog wow fadeInUp">
                                <div className="cardImage">
                                    <div className="box-date-info">
                                        <div className="box-inner-date">
                                            <div className="heading-6">21</div>
                                            <p className="font-md neutral-900">Jun</p>
                                        </div>
                                    </div><a href="blog-single.html"><img src={Blog} alt="kidify" /></a>
                                </div>
                                <div className="cardInfo"><a className="cardTitle" href="blog-single.html">
                                    <h5 className="font-xl-bold">Eco-Friendly Children's Clothing: 5 Sustainable Brands</h5></a>
                                    <p className="cardDesc font-lg neutral-500">Prioritize sustainability with 5 eco-friendly brands that offer organic cotton and recycled materials for children's clothing</p><a className="btn btn-arrow-right" href="blog-single.html">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={ArrowHover} alt="Kidify" /></a>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6 wow animate__animated animate__fadeIn" data-wow-delay="0.2s">
                            <div className="cardBlog wow fadeInUp">
                                <div className="cardImage">
                                    <div className="box-date-info">
                                        <div className="box-inner-date">
                                            <div className="heading-6">21</div>
                                            <p className="font-md neutral-900">Jun</p>
                                        </div>
                                    </div><a href="blog-single.html"><img src={BlogTwo} alt="kidify" /></a>
                                </div>
                                <div className="cardInfo"><a className="cardTitle" href="blog-single.html">
                                    <h5 className="font-xl-bold">Styling Children for Formal Events: Tips for Dressing to Impress</h5></a>
                                    <p className="cardDesc font-lg neutral-500">Get tips on how to dress your child for formal events, including choosing the right attire and accessories for both style and comfort</p><a className="btn btn-arrow-right" href="blog-single.html">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={ArrowHover} alt="Kidify" /></a>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-6 d-md-none d-lg-block wow animate__animated animate__fadeIn" data-wow-delay="0.4s">
                            <div className="cardBlog wow fadeInUp">
                                <div className="cardImage">
                                    <div className="box-date-info">
                                        <div className="box-inner-date">
                                            <div className="heading-6">21</div>
                                            <p className="font-md neutral-900">Jun</p>
                                        </div>
                                    </div><a href="blog-single.html"><img src={BlogThree} alt="kidify" /></a>
                                </div>
                                <div className="cardInfo"><a className="cardTitle" href="blog-single.html">
                                    <h5 className="font-xl-bold">Discover the Latest Trends for Kids Fashion in 2023: Top 10 Trendy Styles</h5></a>
                                    <p className="cardDesc font-lg neutral-500">As the fashion industry continues to evolve, children's fashion is no exception. This article explores the latest trends for kids fashion in 2023</p><a className="btn btn-arrow-right" href="blog-single.html">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={ArrowHover} alt="Kidify" /></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section className="section block-section-10">
                <div className="container">
                    <div className="top-head justify-content-center">
                        <h4 className="text-uppercase brand-1 wow fadeInDown">instagram feed</h4>
                    </div>
                </div>
                <div className="box-gallery-instagram">
                    <div className="box-gallery-instagram-inner">
                        <div className="gallery-item wow fadeInLeft"><img src={Ig} alt="kidify" /></div>
                        <div className="gallery-item wow fadeInUp"><img src={IgTwo} alt="kidify" /></div>
                        <div className="gallery-item wow fadeInUp"><img src={IgThree} alt="kidify" /></div>
                        <div className="gallery-item wow fadeInUp"><img src={IgFour} alt="kidify" /></div>
                        <div className="gallery-item wow fadeInRight"><img src={IgFive} alt="kidify" /></div>
                        <div className="gallery-item wow fadeInRight"><img src={IgOne} alt="kidify" /></div>
                    </div>
                </div>
            </section>
            {isOpen && (
                <div className="box-popup-newsletter">
                    <div className="box-newsletter-overlay" onClick={closeModal} /> {/* Overlay có thể đóng modal */}
                    <div className="box-newsletter-wrapper">
                        <div className="box-newsletter-inner">
                            <a className="btn-close-popup btn-close-popup-newsletter" href="#" onClick={closeModal}>
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
                            <div className="promotion-content">
                                <div className="block-info-banner">
                                    <p className="font-3xl-bold neutral-900 title-line mb-10 wow animate__animated animate__zoomIn">Winter</p>
                                    <h2 className="heading-banner mb-10 wow animate__animated animate__shakeX">
                                        <span className="text-up">sale off</span>
                                        <span className="text-under">sale off</span>
                                    </h2>
                                    <h4 className="heading-4 title-line-2 mb-30 wow animate__animated animate__zoomIn">Anything for your baby</h4>
                                    <div className="mt-10">
                                        <a className="btn btn-double-border wow animate__animated animate__zoomIn" href="#">
                                            <span>View All Deals</span>
                                        </a>
                                    </div>
                                </div>
                                <div className="promotion-label"><img src={Promotion} alt="Kidify" /></div>
                                <div className="promotion-banner"><img src={PromotionBanner} alt="Kidify" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            )}

        </>
    )
}
export default New