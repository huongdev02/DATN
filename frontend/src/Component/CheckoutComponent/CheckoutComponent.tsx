import Ig from '../../assets/imgs/page/homepage1/instagram6.png'
import IgOne from '../../assets/imgs/page/homepage1/instagram.png'
import IgThree from '../../assets/imgs/page/homepage1/instagram3.png'
import IgFour from '../../assets/imgs/page/homepage1/instagram4.png'
import IgTwo from '../../assets/imgs/page/homepage1/instagram2.png'
import IgFive from '../../assets/imgs/page/homepage1/instagram5.png'
import { Link } from 'react-router-dom'
import { notification } from 'antd'
const CheckoutComponent:React.FC = () => {
    const handleOrderSuccess = () => {
        notification.success({
            message: 'Đặt hàng thành công'
        });
    }
    return (
        <>
<main className="main">
  <section className="section block-blog-single block-cart">
    <div className="container">
      <div className="top-head-blog">
        <div className="text-center">
          <h2 className="font-4xl-bold">Checkout</h2>
          <div className="breadcrumbs d-inline-block">
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">Shop</a></li>
              <li><a href="#">Checkout</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div className="box-table-cart box-form-checkout">
        <div className="box-enter-code"><span className="font-md coupon-code">Have a coupon?<a className="brand-1 text-sm" href="#"> Enter your code here</a></span></div>
        <div className="row">
          <div className="col-lg-7">
            <h4 className="font-2xl-bold mb-25">Billing Details</h4>
            <div className="row">
              <div className="col-lg-6">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="First Name *" />
                </div>
              </div>
              <div className="col-lg-6">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Last Name *" />
                </div>
              </div>
              <div className="col-lg-6">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Email *" />
                </div>
              </div>
              <div className="col-lg-6">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Phone Number *" />
                </div>
              </div>
              <div className="col-lg-12">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Address 1*" />
                </div>
              </div>
              <div className="col-lg-12">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Address 2" />
                </div>
              </div>
              <div className="col-lg-6">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Country*" />
                </div>
              </div>
              <div className="col-lg-6">
                <div className="form-group">
                  <input className="form-control" type="text" placeholder="Post code  / Zip*" />
                </div>
              </div>
              <div className="col-lg-12">
                <div className="form-group">
                  <label className="font-sm">
                    <input className="cb-control" type="checkbox" />Create an account
                  </label>
                </div>
              </div>
            </div>
            <h4 className="font-2xl-bold mb-25 mt-15">Shipping Address</h4>
            <div className="row">
              <div className="col-lg-12">
                <div className="form-group">
                  <label>
                    <input className="cb-control ship-other-address" type="checkbox" />Ship to a different address
                  </label>
                </div>
              </div>
            </div>
            <div className="box-other-address">
              <div className="row">
                <div className="col-lg-12">
                  <div className="form-group">
                    <input className="form-control" type="text" placeholder="Address 1*" />
                  </div>
                </div>
                <div className="col-lg-12">
                  <div className="form-group">
                    <input className="form-control" type="text" placeholder="Address 2" />
                  </div>
                </div>
                <div className="col-lg-6">
                  <div className="form-group">
                    <input className="form-control" type="text" placeholder="Country*" />
                  </div>
                </div>
                <div className="col-lg-6">
                  <div className="form-group">
                    <input className="form-control" type="text" placeholder="Post code  / Zip*" />
                  </div>
                </div>
              </div>
            </div>
            <div className="row">
              <div className="col-lg-12 mt-40"><Link to='/cart' className="btn btn-brand-1-border-2">
                  <svg className="icon-16 mr-10" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                  </svg>Return to Cart</Link></div>
            </div>
          </div>
          <div className="col-lg-5">
            <div className="box-total-checkout">
              <div className="head-total-checkout"><span className="font-xl-bold">Categories</span><span className="font-xl-bold">Total</span></div>
              <div className="box-list-item-checkout">
                <div className="item-checkout"><span className="title-item">Little Stars Dress (Yellow, XL)</span><span className="num-item">x1</span><span className="price-item font-md-bold">$35.62</span></div>
                <div className="item-checkout"><span className="title-item">Petite Parka Jacket (Black, L)</span><span className="num-item">x1</span><span className="price-item font-md-bold">$32.47</span></div>
                <div className="item-checkout"><span className="title-item">Tiny Tulle Skirt (Red, M)</span><span className="num-item">x1</span><span className="price-item font-md-bold">$98.67</span></div>
                <div className="item-checkout"><span className="title-item">Little Leopard Print Dress (Blue, S)</span><span className="num-item">x1</span><span className="price-item font-md-bold">$72.15</span></div>
              </div>
              <div className="box-footer-checkout">
                <div className="item-checkout justify-content-between"><span className="font-xl-bold">Subtotal</span><span className="font-md-bold">$780.00</span></div>
                <div className="item-checkout justify-content-between"><span className="font-sm">Shipping</span><span className="font-md-bold">0</span></div>
                <div className="item-checkout justify-content-between"><span className="font-sm">Tax</span><span className="font-md-bold">0</span></div>
                <div className="item-checkout justify-content-between"><span className="font-sm">Total</span><span className="font-xl-bold">$780.00</span></div>
              </div><Link to='/' className="btn btn-brand-1-xl-bold w-100 font-md-bold" onClick={handleOrderSuccess}>Place an Order
                <svg className="icon-16 ml-5" fill="none" stroke="#ffffff" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                </svg></Link>
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
</main>

        </>
    )
}

export default CheckoutComponent