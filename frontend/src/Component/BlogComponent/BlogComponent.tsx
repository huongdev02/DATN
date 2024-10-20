import Arrow from '../../assets/imgs/template/icons/arrow-sm.svg'
import Blog from '../../assets/imgs/page/blog/blog.png'
import BlogTwo from '../../assets/imgs/page/blog/blog2.png'
import BlogThree from '../../assets/imgs/page/blog/blog3.png'
import BlogFour from '../../assets/imgs/page/blog/blog4.png'
const BlogComponent: React.FC = () => {
    return (
        <>
            <main className="main">
                <section className="section block-shop-head block-blog-head">
                    <div className="container">
                        <h1 className="font-5xl-bold neutral-900">Our Blog</h1>
                        <div className="breadcrumbs">
                            <ul>
                                <li><a href="#">Home </a></li>
                                <li><a href="#">Blog</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section className="section content-products">
                    <div className="container">
                        <div className="box-blog-column">
                            <div className="col-1">
                                <div className="box-inner-blog-padding">
                                    <div className="cardBlogStyle1">
                                        <div className="cardImage"><a className="tag-up" href="#">Kids' Outfits</a><a href="#"><img src={Blog} alt="kidify" /></a></div>
                                        <div className="cardInfo"><a href="#">
                                            <h2 className="font-42-bold mb-10">Styling Children for Formal Events: Tips for Dressing to Impress</h2></a>
                                            <div className="box-meta-post mb-20"><span className="font-sm neutral-500">August 30, 2023</span><span className="font-sm neutral-500">4 Mins read</span><span className="font-sm neutral-500">520 views</span></div>
                                            <p className="font-lg">If you're looking for some inspiration to dress up your child, you've come to the right place! Check out our top 10 picks for adorable outfits that will make your little fashionista stand out.</p>
                                            <div className="mt-25 text-end"><a className="btn btn-arrow-right" href="#">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={Arrow} alt="Kidify" /></a></div>
                                        </div>
                                    </div>
                                    <div className="cardBlogStyle1">
                                        <div className="cardImage"><a className="tag-up" href="#">Fashion Trends</a><a href="#"><img src={BlogTwo} alt="kidify" /></a></div>
                                        <div className="cardInfo"><a href="#">
                                            <h2 className="font-42-bold mb-10">From Cute to Cool: Transforming Your Baby's Wardrobe as They Grow</h2></a>
                                            <div className="box-meta-post mb-20"><span className="font-sm neutral-500">August 30, 2023</span><span className="font-sm neutral-500">4 Mins read</span><span className="font-sm neutral-500">520 views</span></div>
                                            <p className="font-lg">If you're looking for some inspiration to dress up your child, you've come to the right place! Check out our top 10 picks for adorable outfits that will make your little fashionista stand out.</p>
                                            <div className="mt-25 text-end"><a className="btn btn-arrow-right" href="#">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={Arrow} alt="Kidify" /></a></div>
                                        </div>
                                    </div>
                                    <div className="cardBlogStyle1">
                                        <div className="cardImage"><a className="tag-up" href="#">Favorite Brands</a><a href="#"><img src={BlogThree} alt="kidify" /></a></div>
                                        <div className="cardInfo"><a href="#">
                                            <h2 className="font-42-bold mb-10">Gender-Neutral Fashion for Kids: Breaking Down Stereotypes</h2></a>
                                            <div className="box-meta-post mb-20"><span className="font-sm neutral-500">August 30, 2023</span><span className="font-sm neutral-500">4 Mins read</span><span className="font-sm neutral-500">520 views</span></div>
                                            <p className="font-lg">If you're looking for some inspiration to dress up your child, you've come to the right place! Check out our top 10 picks for adorable outfits that will make your little fashionista stand out.</p>
                                            <div className="mt-25 text-end"><a className="btn btn-arrow-right" href="#">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={Arrow} alt="Kidify" /></a></div>
                                        </div>
                                    </div>
                                    <div className="cardBlogStyle1">
                                        <div className="cardImage"><a className="tag-up" href="#">Trends</a><a href="#"><img src={BlogFour} alt="kidify" /></a></div>
                                        <div className="cardInfo"><a href="#">
                                            <h2 className="font-42-bold mb-10">Product Review: Best Sunscreen for Kids' Sensitive Skin</h2></a>
                                            <div className="box-meta-post mb-20"><span className="font-sm neutral-500">August 30, 2023</span><span className="font-sm neutral-500">4 Mins read</span><span className="font-sm neutral-500">520 views</span></div>
                                            <p className="font-lg">If you're looking for some inspiration to dress up your child, you've come to the right place! Check out our top 10 picks for adorable outfits that will make your little fashionista stand out.</p>
                                            <div className="mt-25 text-end"><a className="btn btn-arrow-right" href="#">Keep reading<img src={Arrow} alt="Kidify" /><img className="hover-icon" src={Arrow} alt="Kidify" /></a></div>
                                        </div>
                                    </div>
                                </div>
                                <div className="mb-50">
                                    <nav className="box-pagination">
                                        <ul className="pagination">
                                            <li className="page-item"><a className="page-link page-prev" href="#">
                                                <svg fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                                                </svg></a></li>
                                            <li className="page-item"><a className="page-link" href="#">1</a></li>
                                            <li className="page-item"><a className="page-link active" href="#">2</a></li>
                                            <li className="page-item"><a className="page-link" href="#">3</a></li>
                                            <li className="page-item"><a className="page-link" href="#">...</a></li>
                                            <li className="page-item"><a className="page-link" href="#">10</a></li>
                                            <li className="page-item"><a className="page-link page-next" href="#">
                                                <svg fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                                                </svg></a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div className="col-2">
                                <div className="sidebar-right">
                                    <div className="row" data-masonry="{&quot;percentPosition&quot;: true }">
                                        <div className="col-lg-12 col-md-6">
                                            <div className="sidebar-border">
                                                <h5 className="font-3xl-bold head-sidebar">Categories</h5>
                                                <div className="content-sidebar">
                                                    <ul className="list-filter-checkbox">
                                                        <li><a className="font-xl" href="#">Fashion Trends</a><span className="number-item">12</span></li>
                                                        <li><a className="font-xl" href="#">Kids' Outfits</a><span className="number-item">12</span></li>
                                                        <li><a className="font-xl" href="#">Styling Tips</a><span className="number-item">12</span></li>
                                                        <li><a className="font-xl" href="#">Favorite Brands</a><span className="number-item">12</span></li>
                                                        <li><a className="font-xl" href="#">Best Deals Online</a><span className="number-item">12</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-lg-12 col-md-6">
                                            <div className="sidebar-border">
                                                <h5 className="font-3xl-bold head-sidebar">Feature Posts</h5>
                                                <div className="content-sidebar">
                                                    <ul className="list-featured-posts">
                                                        <li>
                                                            <div className="cardFeaturePost">
                                                                <div className="cardImage"><a href="#"><img src="assets/imgs/page/blog/feature.png" alt="kidify" /></a></div>
                                                                <div className="cardInfo"><span className="lbl-tag-brand">Fashion</span><a className="font-sm-bold link-feature" href="#">Styling Children for Formal Events: Tips for Dressing to Impress</a></div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div className="cardFeaturePost">
                                                                <div className="cardImage"><a href="#"><img src="assets/imgs/page/blog/feature2.png" alt="kidify" /></a></div>
                                                                <div className="cardInfo"><span className="lbl-tag-brand">Trend</span><a className="font-sm-bold link-feature" href="#">The Importance of Sustainable Fashion for Children</a></div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div className="cardFeaturePost">
                                                                <div className="cardImage"><a href="#"><img src="assets/imgs/page/blog/feature3.png" alt="kidify" /></a></div>
                                                                <div className="cardInfo"><span className="lbl-tag-brand">Tips</span><a className="font-sm-bold link-feature" href="#">5 Creative Halloween Costume Ideas for Kids</a></div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div className="cardFeaturePost">
                                                                <div className="cardImage"><a href="#"><img src="assets/imgs/page/blog/feature4.png" alt="kidify" /></a></div>
                                                                <div className="cardInfo"><span className="lbl-tag-brand">Events</span><a className="font-sm-bold link-feature" href="#">How to Dress Your Kid for Winter: Tips and Ideas</a></div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-lg-12 col-md-6">
                                            <div className="sidebar-border">
                                                <h5 className="font-3xl-bold head-sidebar">Tags</h5>
                                                <div className="content-sidebar"><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Top Rated</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Outfits</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">T-Shirts</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Boy Shirts</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Boy Tanks</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Shoes</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Boys Denim</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Toddler Boys</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Boy Swimwear</a><a className="btn btn-tag mr-10 mb-10" href="blog-3.html">Boys Interior</a></div>
                                            </div>
                                        </div>
                                        <div className="col-lg-12 col-md-6">
                                            <div className="sidebar-border">
                                                <h5 className="font-3xl-bold head-sidebar">Gallery</h5>
                                                <div className="content-sidebar">
                                                    <ul className="list-galleries">
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal2.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal3.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal4.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal5.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal6.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal7.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal8.png" alt="kidify" /></a></li>
                                                        <li><a href="#"><img src="assets/imgs/page/blog/gal9.png" alt="kidify" /></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-lg-12 col-md-6">
                                            <div className="sidebar-border">
                                                <h5 className="font-3xl-bold head-sidebar">Archives</h5>
                                                <div className="content-sidebar">
                                                    <ul className="list-filter-checkbox">
                                                        <li><a className="font-xl" href="#">October 2022</a><span className="number-item">136</span></li>
                                                        <li><a className="font-xl" href="#">November 2022</a><span className="number-item">25</span></li>
                                                        <li><a className="font-xl" href="#">December 2022</a><span className="number-item">48</span></li>
                                                        <li><a className="font-xl" href="#">January 2023</a><span className="number-item">164</span></li>
                                                        <li><a className="font-xl" href="#">February 2023</a><span className="number-item">18</span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="section block-section block-subscriber">
                    <div className="container">
                        <div className="box-subscriber-2">
                            <div className="row align-items-end">
                                <div className="col-lg-1"> </div>
                                <div className="col-lg-5">
                                    <h4 className="heading-4 brand-2 mb-20">Sing up and get up to <span className="brand-3">25% </span>off <br className="d-none d-lg-block" />your first purchase </h4>
                                    <p className="font-md neutral-500 mb-20">Receive offter, product alerts, styling inspiration and more. By signing up, you agree to our Privace Policy</p>
                                </div>
                                <div className="col-lg-5">
                                    <div className="box-form-newsletter mb-20">
                                        <form action="#">
                                            <input className="form-control" type="text" placeholder="Enter your email" />
                                            <button className="btn btn-brand-1">Subscriber</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div className="section block-section block-section-gallery block-section-instagram-5">
                    <div className="container">
                        <div className="top-head top-head-abs justify-content-center">
                            <p className="font-md-bold text-uppercase brand-1 wow fadeInDown">FOLLOW US ON INSTAGRAM</p><a className="kidify-icon" href="#">kidify.com</a>
                        </div>
                    </div>
                    <div className="box-gallery-instagram">
                        <div className="box-gallery-instagram-inner">
                            <div className="gallery-item wow fadeInLeft"><img src="assets/imgs/page/homepage4/insta.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInUp"><img src="assets/imgs/page/homepage4/insta2.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInUp"><img src="assets/imgs/page/homepage4/insta3.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInUp"><img src="assets/imgs/page/homepage4/insta4.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInRight"><img src="assets/imgs/page/homepage4/insta5.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInRight"><img src="assets/imgs/page/homepage4/insta6.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInRight d-md-block d-none"><img src="assets/imgs/page/homepage4/insta7.png" alt="kidify" /></div>
                            <div className="gallery-item wow fadeInRight d-md-block d-none"><img src="assets/imgs/page/homepage4/insta8.png" alt="kidify" /></div>
                        </div>
                    </div>
                </div>
            </main>

        </>
    )
}
export default BlogComponent