import Ig from '../../assets/imgs/page/homepage1/instagram6.png'
import IgOne from '../../assets/imgs/page/homepage1/instagram.png'
import IgThree from '../../assets/imgs/page/homepage1/instagram3.png'
import IgFour from '../../assets/imgs/page/homepage1/instagram4.png'
import IgTwo from '../../assets/imgs/page/homepage1/instagram2.png'
import IgFive from '../../assets/imgs/page/homepage1/instagram5.png'
const ContactForm: React.FC = () => {
    return (
        <div className="section block-blog-single block-contact">
            <div className="container-1190">
                <div className="box-form-contact">
                    <h3 className="font-4xl-bold mb-40">Get in Touch</h3>
                    <div className="row">
                        <div className="col-lg-6">
                            <form action="#">
                                <div className="row">
                                    <div className="col-lg-6">
                                        <div className="form-group">
                                            <input className="form-control" type="text" placeholder="Your name *" />
                                        </div>
                                    </div>
                                    <div className="col-lg-6">
                                        <div className="form-group">
                                            <input className="form-control" type="text" placeholder="Your email *" />
                                        </div>
                                    </div>
                                    <div className="col-lg-6">
                                        <div className="form-group">
                                            <input className="form-control" type="text" placeholder="Your phone number" />
                                        </div>
                                    </div>
                                    <div className="col-lg-6">
                                        <div className="form-group">
                                            <input className="form-control" type="text" placeholder="Subject" />
                                        </div>
                                    </div>
                                    <div className="col-lg-12">
                                        <div className="form-group">
                                            <textarea className="form-control" rows={6} placeholder="Message / Note" defaultValue={""} />
                                        </div>
                                    </div>
                                    <div className="col-lg-12">
                                        <div className="form-group">
                                            <button className="btn btn-brand-1-medium">Submit Now</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div className="col-lg-6">
                            <div className="box-contact-right">
                                <h4 className="font-2xl-bold mb-10">Looking for support or chat with sales?</h4>
                                <p className="font-md mb-40">If you need support with an existing Kidify issued card, please email: support@kidkify.com To talk with someone on our sales team, please Talk to an expert</p>
                                <h4 className="font-2xl-bold mb-10">Address</h4>
                                <p className="font-md"><strong className="font-md-bold">Kidify Corp</strong><br />456 Park Avenue South, Apt 7B<br />New York, NY 10016</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        </div>
    )
}

export default ContactForm