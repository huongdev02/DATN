import { Link } from "react-router-dom"

const Maps: React.FC = () => {
    return (
        <>
            <section className="section block-blog-single block-contact">
                <div className="container">
                    <div className="top-head-blog">
                        <div className="text-center">
                            <h2 className="font-4xl-bold">Contact Us</h2>
                            <div className="breadcrumbs d-inline-block">
                                <ul>
                                    <li><Link to="/">Home</Link></li>
                                    <li><Link to='/contact'>Contact</Link></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="container-1190">
                    <div className="box-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1575.9094429793793!2d144.96780073900774!3d-37.817711024139996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642b6e9fcc44f%3A0x38e74745ead60eab!2sFed%20Square!5e0!3m2!1svi!2s!4v1684687900354!5m2!1svi!2s" style={{ border: 0 }} allowFullScreen loading="lazy" referrerPolicy="no-referrer-when-downgrade" />
                    </div>
                    <div className="box-info-contact">
                        <div className="row">
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Chat to sales</strong>
                                        <p className="font-md">Speak to our teamcom</p><a className="font-md" href="#">sales@kidify.com</a>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Call us</strong><a className="font-md" href="#">+01 568 253</a><a className="font-md" href="#">+01 568 253</a></div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Postal mail</strong>
                                        <p className="font-md">456 Park Avenue South, Apt 7B<br />New York, NY 10016</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-lg-3 col-md-6 mb-15">
                                <div className="cardContact cardChat">
                                    <div className="cardInfo"><strong className="d-block mb-5 font-xl-bold">Social Network</strong>
                                        <p className="font-md">456 Park Avenue South, Apt 7B<br />New York, NY 10016</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </>
    )
}

export default Maps