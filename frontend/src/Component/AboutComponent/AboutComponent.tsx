import About from '../../assets/imgs/page/about/img.png'
import Tick from '../../assets/imgs/page/about/tick.png'
import Gallery from '../../assets/imgs/page/about/gallery.png'
import GalleryTwo from '../../assets/imgs/page/about/gallery2.png'
import GalleryThree from '../../assets/imgs/page/about/gallery3.png'
import GalleryFour from '../../assets/imgs/page/about/gallery4.png'
import GalleryFive from '../../assets/imgs/page/about/gallery5.png'
import Ig from '../../assets/imgs/page/homepage1/instagram6.png'
import IgOne from '../../assets/imgs/page/homepage1/instagram.png'
import IgThree from '../../assets/imgs/page/homepage1/instagram3.png'
import IgFour from '../../assets/imgs/page/homepage1/instagram4.png'
import IgTwo from '../../assets/imgs/page/homepage1/instagram2.png'
import IgFive from '../../assets/imgs/page/homepage1/instagram5.png'
import Star from '../../assets/imgs/template/icons/star.svg'
import Avatar from '../../assets/imgs/page/homepage2/avatar-review.png'
import { Swiper, SwiperSlide } from 'swiper/react';
import { Pagination } from 'swiper/modules';
const AboutComponent: React.FC = () => {

    return (
        <>
            <main className="main">
                <section className="section block-blog-single">
                    <div className="container">
                        <div className="top-head-blog">
                            <div className="text-center">
                                <h2 className="font-4xl-bold">About Us</h2>
                                <div className="breadcrumbs d-inline-block">
                                    <ul>
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Blog</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div className="feature-image"><img src={About} alt="kidify" /></div>
                        <div className="content-detail">
                            <h2>Our Stories</h2>
                            <p />
                            <p><strong>As parents, we know how important it is to have a smooth transition from playtime to bedtime. Children often get so involved in their playtime activities that they don't want to stop. However, when it's time to go to bed, they need to change into their pajamas.</strong></p>
                            <p>This can be a hassle, especially if your child is not in the mood to cooperate. But what if there were outfits that could easily transition from playtime to bedtime? This would make our lives as parents so much easier! In this article, we'll discuss some outfits that can do just that.</p>
                            <ul className="list-ticks">
                                <li><img src={Tick} alt="kidify" />We provide qualified &amp; expert</li>
                                <li><img src={Tick} alt="kidify" />Modern tools &amp; technology use</li>
                                <li><img src={Tick} alt="kidify" />Neat &amp; cleaning top Services</li>
                                <li><img src={Tick} alt="kidify" />We Develop Digital Future</li>
                            </ul>
                            <div className="box-experiences">
                                <div className="row">
                                    <div className="col-lg-4"><strong className="font-xl-bold">12 Years</strong>
                                        <p className="font-md neutral-500">We’ve more than 12 years working experience.</p>
                                    </div>
                                    <div className="col-lg-4"><strong className="font-xl-bold">2000+ Employee</strong>
                                        <p className="font-md neutral-500">We’ve more than 2000 employees working near you.</p>
                                    </div>
                                    <div className="col-lg-4"><strong className="font-xl-bold">68 Branches</strong>
                                        <p className="font-md neutral-500">We have 68 branches across the country and are expanding</p>
                                    </div>
                                </div>
                            </div>
                            <h2>Our Mission</h2>
                            <p>This can be a hassle, especially if your child is not in the mood to cooperate. But what if there were outfits that could easily transition from playtime to bedtime? This would make our lives as parents so much easier! In this article, we'll discuss some outfits that can do just that.</p>
                        </div>
                    </div>
                    <div className="box-gallery-about">
                        <div className="container-1190">
                            <h2 className="font-3xl-bold mb-55">Our Gallery</h2>
                            <div className="box-gallery-list">
                                <div className="gallery-main"><a className="glightbox" href={Gallery}><img src={Gallery} alt="kidify" /></a></div>
                                <div className="gallery-sub"><a className="glightbox" href={GalleryTwo}><img src={GalleryTwo} alt="kidify" /></a><a className="glightbox" href={GalleryThree}><img src={GalleryThree} alt="kidify" /></a><a className="glightbox" href={GalleryFour}><img src={GalleryFour} alt="kidify" /></a><a className="glightbox" href={GalleryFive}><img src={GalleryFive} alt="kidify" /></a></div>
                            </div>
                        </div>
                    </div>
                    <div className="box-reviews-about">
                        <div className="content-detail mb-20">
                            <h2 className="font-3xl-bold">Our Happy Customes</h2>
                        </div>
                        <div className="feature-image mb-0"><span className="title-left" /></div>
                        <div className="box-slider-about box-slide-padding-left">
                            <div className="box-swiper">
                                <div className="swiper-container swiper-auto pt-35">
                                    <div className="swiper-wrapper">
                                        <Swiper
                                            modules={[Pagination]} // Sử dụng module Pagination
                                            slidesPerView={3} // Chỉnh số slide hiển thị trên mỗi trang
                                            pagination={{ clickable: true }} // Bật pagination
                                            className="swiper-9-items pb-0"
                                        >

<SwiperSlide>
                                            <div className="swiper-slide">
                                                <div className="cardReview">
                                                    <div className="cardRating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /></div>
                                                    <div className="cardReviewText">
                                                        <p className="font-sm neutral-900">"I recently discovered this fashion shop and I am obsessed! The clothes are of great quality and the designs are unique and stylish. I always receive compliments whenever I wear something from this store. Definitely my new go-to for trendy outfits.</p>
                                                    </div>
                                                    <div className="cardAuthor"><img src={Avatar} alt="kidify" /><span className="font-lg-bold brand-1">Sarah L</span></div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="swiper-slide">
                                                <div className="cardReview">
                                                    <div className="cardRating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /></div>
                                                    <div className="cardReviewText">
                                                        <p className="font-sm neutral-900">"I recently discovered this fashion shop and I am obsessed! The clothes are of great quality and the designs are unique and stylish. I always receive compliments whenever I wear something from this store. Definitely my new go-to for trendy outfits.</p>
                                                    </div>
                                                    <div className="cardAuthor"><img src={Avatar} alt="kidify" /><span className="font-lg-bold brand-1">Sarah L</span></div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="swiper-slide">
                                                <div className="cardReview">
                                                    <div className="cardRating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /></div>
                                                    <div className="cardReviewText">
                                                        <p className="font-sm neutral-900">"I recently discovered this fashion shop and I am obsessed! The clothes are of great quality and the designs are unique and stylish. I always receive compliments whenever I wear something from this store. Definitely my new go-to for trendy outfits.</p>
                                                    </div>
                                                    <div className="cardAuthor"><img src={Avatar} alt="kidify" /><span className="font-lg-bold brand-1">Sarah L</span></div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="swiper-slide">
                                                <div className="cardReview">
                                                    <div className="cardRating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /></div>
                                                    <div className="cardReviewText">
                                                        <p className="font-sm neutral-900">"I recently discovered this fashion shop and I am obsessed! The clothes are of great quality and the designs are unique and stylish. I always receive compliments whenever I wear something from this store. Definitely my new go-to for trendy outfits.</p>
                                                    </div>
                                                    <div className="cardAuthor"><img src={Avatar} alt="kidify" /><span className="font-lg-bold brand-1">Sarah L</span></div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="swiper-slide">
                                                <div className="cardReview">
                                                    <div className="cardRating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /></div>
                                                    <div className="cardReviewText">
                                                        <p className="font-sm neutral-900">"I recently discovered this fashion shop and I am obsessed! The clothes are of great quality and the designs are unique and stylish. I always receive compliments whenever I wear something from this store. Definitely my new go-to for trendy outfits.</p>
                                                    </div>
                                                    <div className="cardAuthor"><img src={Avatar} alt="kidify" /><span className="font-lg-bold brand-1">Sarah L</span></div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                        <SwiperSlide>
                                            <div className="swiper-slide">
                                                <div className="cardReview">
                                                    <div className="cardRating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /></div>
                                                    <div className="cardReviewText">
                                                        <p className="font-sm neutral-900">"I recently discovered this fashion shop and I am obsessed! The clothes are of great quality and the designs are unique and stylish. I always receive compliments whenever I wear something from this store. Definitely my new go-to for trendy outfits.</p>
                                                    </div>
                                                    <div className="cardAuthor"><img src={Avatar} alt="kidify" /><span className="font-lg-bold brand-1">Sarah L</span></div>
                                                </div>
                                            </div>
                                        </SwiperSlide>
                                        </Swiper>
                                 

                                    </div>
                                </div>
                                <div className="box-pagination-button box-pagination-button-center">
                                    <div className="swiper-pagination swiper-pagination-banner swiper-pagination-auto" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="container">
                        <div className="content-detail">
                            <h2>Our Stories</h2>
                            <p>Ex quos nemo a voluptatum delectus et totam soluta sit illo voluptatem in consequuntur sunt vel doloremque sunt eos nihil quas. Ut odit velit cum maxime corrupti qui quia corporis quo explicabo autem et fugit omnis aut fugiat quia sit molestias ipsam. Sit nihil quod non corrupti reprehenderit At saepe ducimus aut dolorem dolorum eum ratione expedita ab aliquid minima. A illum voluptas et inventore totam eum inventore enim in obcaecati aspernatur ea aliquam pariatur.</p>
                            <p>Ad magnam rerum quo magni rerum ut accusamus vitae ut nobis voluptatum est dicta voluptate et libero similique. Sit harum porro non illum voluptatibus eum suscipit facere. Rem dolores dolorum ut doloribus impedit sed expedita quasi qui doloremque consequuntur eum vitae perferendis qui fugit temporibus.</p>
                            <p>Est exercitationem natus eos repudiandae cumque ex voluptas officiis cum laborum aspernatur. Vel accusantium laborum qui modi praesentium hic quia consequatur ea nihil expedita aut tempore illum. Vel vitae praesentium sit neque delectus sit magnam tenetur ea blanditiis consequuntur cum quaerat sapiente in cumque molestias qui quibusdam inventore. Rem facere esse non ipsum quisquam et fugiat accusantium ut maxime blanditiis cum quis sint?</p>
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
            </main>

        </>
    )
}
export default AboutComponent