import ProductThumb from '../../assets/imgs/page/product/thumnb.png'
import Product from "../../assets/imgs/page/product/img.png"
import ProductTwo from "../../assets/imgs/page/product/img-2.png"
import ProductThree from "../../assets/imgs/page/product/img-3.png"
import Star from "../../assets/imgs/template/icons/star.svg"
import { IProduct, Size, Color, Gallery } from "../../types/cart";
import { useParams } from 'react-router-dom'
import { useState, useEffect } from 'react'
import api from '../../configAxios/axios'
import { message } from 'antd'
const ProductDetailComponent: React.FC = () => {
  const [selectedSize, setSelectedSize] = useState('S');
  const {id} = useParams()
  const [products, setProduct] = useState<IProduct | null>(null);
  const handleSizeClick = (size: any) => {
    setSelectedSize(size);
  };

  const GetAllProducts = async () => {
    try {
      const { data } = await api.get(`/products/${id}`);
      setProduct(data);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  console.log("Product", products);
  

  useEffect(() => {
    GetAllProducts();
  }, [id]);
 
  return (
    <>
      <main className="main">
        <div className="section block-shop-head-2 block-breadcrumb-type-1">
          <div className="container">
            <div className="breadcrumbs">
              <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#">Cửa hàng</a></li>
                <li> <a href="#">Chi tiết sản phẩm</a></li>
              </ul>
            </div>
          </div>
        </div>
        <section className="section block-product-content">
          <div className="container">
          {products && (
            <div className="row">
              <div className="col-lg-5 box-images-product-left">
                <div className="detail-gallery">
                  <div className="slider-nav-thumbnails">
                  {products.galleries.map((gallery, index) => (
                <div key={index} className="item-thumb">
                    <img src={gallery.image_path} alt={`Gallery Image ${index + 1}`} />
                </div>
                ))}
                  </div>
                  <div className="box-main-gallery"><a className="zoom-image glightbox" href={products.avatar_url} />
                    <div className="product-image-slider">
                      <figure className="border-radius-10"><a className="glightbox" href={products.avatar_url}><img className= "image_detail" src={products.avatar_url}alt="kidify" /></a></figure>
                      <figure className="border-radius-10"><a className="glightbox" href={products.avatar_url}><img src={products.avatar_url} alt="kidify" /></a></figure>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-lg-5 box-images-product-middle">
                <div className="box-product-info">
                  <h2 className="font-3xl-bold">{products.name}</h2>
                  <div className="block-rating"><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><img src={Star} alt="kidify" /><span className="font-md neutral-500">(14 Reviews - 25 Orders)</span></div>
                  <div className="block-price"><span className="price-main"> {Math.round(products.price).toLocaleString("vi", {style: "currency", currency: "VND",})}</span><span className="price-line">{Math.round(products.price).toLocaleString("vi", {style: "currency", currency: "VND",})}</span></div>
                  <div className="block-view">
                    <p className="font-md neutral-900">{products.description}</p>
                  </div>
                  <div className="block-color"><span>Color:</span>
                    <label style={{textTransform:'uppercase'}}>{products.colors.length > 0 ? products.colors[0].name_color : 'Red'}</label>
                    <ul className="list-color">
                    {products.colors.map((color) => (
                      <li key={color.id}>
                        <span className="box-circle-color">
                          <a href="#" className={`color-${color.name_color}`}/>
                        </span>
                      </li>
                        ))}
                    </ul>
                  </div>
                  <div className="block-size"><span>Size:</span>
                    <label>S</label>
                    <div className="box-list-sizes">
                      <div className="list-sizes">
                        {products.sizes.map((size) => (
                          <span
                            key={size.id}
                            className='item-size'
                            onClick={() => handleSizeClick(size)}
                          >
                            {size.size}
                          </span>
                        ))}
                      </div>
                    </div>
                  </div>
                  <div className="block-quantity">
                    <div className="font-sm neutral-500 mb-15">Quantity</div>
                  </div>
                  <div className="box-form-cart">
                    <div className="form-cart"><span className="minus" />
                      <input className="form-control" type="text" defaultValue={1} /><span className="plus" />
                    </div><a className="btn btn-brand-1-border" href="#">Add to Cart</a><a className="btn btn-brand-1-xl" href="#">Buy Now</a><a className="btn link-add-cart" href="#">Add to Wish list</a>
                  </div>
                  <div className="box-product-tag d-flex justify-content-between align-items-end">
                    <div className="box-tag-left">
                      <p className="font-xs mb-5"><span className="neutral-500">SKU:</span><span className="neutral-900">kid1232568-UYV</span></p>
                      <p className="font-xs mb-5"><span className="neutral-500">Categories:</span><span className="neutral-900">Girls, Dress</span></p>
                      <p className="font-xs mb-5"><span className="neutral-500">Tags:</span><span className="neutral-900">fashion, dress, girls, blue</span></p>
                    </div>
                    <div className="box-tag-right"><span className="font-sm">Share:</span><a className="social-brand-1" href="#">
                      <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.9047 12.75H13.4437V20.1H10.1625V12.75H7.47187V9.73125H10.1625V7.40156C10.1625 4.77656 11.7375 3.3 14.1328 3.3C15.2813 3.3 16.4953 3.52969 16.4953 3.52969V6.12187H15.15C13.8375 6.12187 13.4437 6.90937 13.4437 7.7625V9.73125H16.3641L15.9047 12.75Z" />
                      </svg></a><a className="social-brand-1" href="#">
                        <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M18.6609 8.2875C18.6609 8.45156 18.6609 8.58281 18.6609 8.74687C18.6609 13.3078 15.2156 18.525 8.88281 18.525C6.91406 18.525 5.10937 17.9672 3.6 16.9828C3.8625 17.0156 4.125 17.0484 4.42031 17.0484C6.02812 17.0484 7.50469 16.4906 8.68594 15.5719C7.17656 15.5391 5.89687 14.5547 5.47031 13.1766C5.7 13.2094 5.89687 13.2422 6.12656 13.2422C6.42187 13.2422 6.75 13.1766 7.0125 13.1109C5.4375 12.7828 4.25625 11.4047 4.25625 9.73125V9.69844C4.71562 9.96094 5.27344 10.0922 5.83125 10.125C4.87969 9.50156 4.28906 8.45156 4.28906 7.27031C4.28906 6.61406 4.45312 6.02344 4.74844 5.53125C6.45469 7.59844 9.01406 8.97656 11.8687 9.14062C11.8031 8.87812 11.7703 8.61562 11.7703 8.35312C11.7703 6.45 13.3125 4.90781 15.2156 4.90781C16.2 4.90781 17.0859 5.30156 17.7422 5.99062C18.4969 5.82656 19.2516 5.53125 19.9078 5.1375C19.6453 5.95781 19.1203 6.61406 18.3984 7.04062C19.0875 6.975 19.7766 6.77812 20.3672 6.51562C19.9078 7.20469 19.3172 7.79531 18.6609 8.2875Z" />
                        </svg></a><a className="social-brand-1" href="#">
                        <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M20.1375 11.7C20.1375 16.1953 16.4953 19.8375 12 19.8375C11.1469 19.8375 10.3266 19.7391 9.57187 19.4766C9.9 18.9516 10.3922 18.0656 10.5891 17.3437C10.6875 16.9828 11.0812 15.4078 11.0812 15.4078C11.3437 15.9328 12.1312 16.3594 12.9516 16.3594C15.4125 16.3594 17.1844 14.0953 17.1844 11.3062C17.1844 8.61562 14.9859 6.58125 12.1641 6.58125C8.65312 6.58125 6.78281 8.94375 6.78281 11.5031C6.78281 12.7172 7.40625 14.1937 8.42344 14.6859C8.5875 14.7516 8.68594 14.7187 8.71875 14.5547C8.71875 14.4562 8.88281 13.8984 8.94844 13.6359C8.94844 13.5703 8.94844 13.4719 8.88281 13.4062C8.55469 13.0125 8.29219 12.2578 8.29219 11.5359C8.29219 9.76406 9.6375 8.025 11.9672 8.025C13.9359 8.025 15.3469 9.37031 15.3469 11.3391C15.3469 13.5375 14.2312 15.0469 12.7875 15.0469C12 15.0469 11.4094 14.3906 11.5734 13.6031C11.8031 12.6187 12.2625 11.5687 12.2625 10.8797C12.2625 10.2562 11.9344 9.73125 11.2453 9.73125C10.425 9.73125 9.76875 10.5844 9.76875 11.7C9.76875 12.4219 9.99844 12.9141 9.99844 12.9141C9.99844 12.9141 9.21094 16.3266 9.04687 16.95C8.88281 17.6719 8.94844 18.6563 9.01406 19.2797C5.99531 18.0984 3.8625 15.1781 3.8625 11.7C3.8625 7.20469 7.50469 3.5625 12 3.5625C16.4953 3.5625 20.1375 7.20469 20.1375 11.7Z" />
                        </svg></a><a className="social-brand-1" href="#">
                        <svg width={29} height={28} viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14.6001 10.0078C12.1001 10.0078 10.1079 12.0391 10.1079 14.5C10.1079 17 12.1001 18.9922 14.6001 18.9922C17.061 18.9922 19.0923 17 19.0923 14.5C19.0923 12.0391 17.061 10.0078 14.6001 10.0078ZM14.6001 17.4297C12.9985 17.4297 11.6704 16.1406 11.6704 14.5C11.6704 12.8984 12.9595 11.6094 14.6001 11.6094C16.2017 11.6094 17.4907 12.8984 17.4907 14.5C17.4907 16.1406 16.2017 17.4297 14.6001 17.4297ZM20.3032 9.85156C20.3032 9.26562 19.8345 8.79688 19.2485 8.79688C18.6626 8.79688 18.1938 9.26562 18.1938 9.85156C18.1938 10.4375 18.6626 10.9062 19.2485 10.9062C19.8345 10.9062 20.3032 10.4375 20.3032 9.85156ZM23.272 10.9062C23.1938 9.5 22.8813 8.25 21.8657 7.23438C20.8501 6.21875 19.6001 5.90625 18.1938 5.82812C16.7485 5.75 12.4126 5.75 10.9673 5.82812C9.56104 5.90625 8.3501 6.21875 7.29541 7.23438C6.27979 8.25 5.96729 9.5 5.88916 10.9062C5.81104 12.3516 5.81104 16.6875 5.88916 18.1328C5.96729 19.5391 6.27979 20.75 7.29541 21.8047C8.3501 22.8203 9.56104 23.1328 10.9673 23.2109C12.4126 23.2891 16.7485 23.2891 18.1938 23.2109C19.6001 23.1328 20.8501 22.8203 21.8657 21.8047C22.8813 20.75 23.1938 19.5391 23.272 18.1328C23.3501 16.6875 23.3501 12.3516 23.272 10.9062ZM21.397 19.6562C21.1235 20.4375 20.4985 21.0234 19.7563 21.3359C18.5845 21.8047 15.8501 21.6875 14.6001 21.6875C13.311 21.6875 10.5767 21.8047 9.44385 21.3359C8.6626 21.0234 8.07666 20.4375 7.76416 19.6562C7.29541 18.5234 7.4126 15.7891 7.4126 14.5C7.4126 13.25 7.29541 10.5156 7.76416 9.34375C8.07666 8.60156 8.6626 8.01562 9.44385 7.70312C10.5767 7.23438 13.311 7.35156 14.6001 7.35156C15.8501 7.35156 18.5845 7.23438 19.7563 7.70312C20.4985 7.97656 21.0845 8.60156 21.397 9.34375C21.8657 10.5156 21.7485 13.25 21.7485 14.5C21.7485 15.7891 21.8657 18.5234 21.397 19.6562Z" />
                        </svg></a></div>
                  </div>
                </div>
              </div>
            </div>
             )}
            <div className="box-detail-product">
              <ul className="nav-tabs nav-tab-product" role="tablist">
                <li className="nav-item" role="presentation">
                  <button className="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                </li>
                <li className="nav-item" role="presentation">
                  <button className="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="false">Ingredients</button>
                </li>
                <li className="nav-item" role="presentation">
                  <button className="nav-link" id="vendor-tab" data-bs-toggle="tab" data-bs-target="#vendor" type="button" role="tab" aria-controls="vendor" aria-selected="false">Vendor</button>
                </li>
              </ul>
              <div className="tab-content">
                <div className="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                  <div className="row">
                    <div className="col-lg-9">
                      <p>It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar. The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She packed her seven versalia, put her initial into the belt and made herself on the way.</p>
                    </div>
                    <div className="col-lg-3">
                      <h6 className="font-md-bold brand-1 mb-15">Customer Reviews</h6>
                      <div className="box-info-total-rate">
                        <div className="item-rate-total"><span className="neutral-500 text-star">5 stars</span>
                          <div className="box-info-progress">
                            <div className="progress">
                              <div className="progress-bar" role="progressbar" style={{ width: '80%' }} aria-valuenow={80} aria-valuemin={0} aria-valuemax={100} />
                            </div>
                          </div><span className="num-percen">80%</span>
                        </div>
                        <div className="item-rate-total"><span className="neutral-500 text-star">4 stars</span>
                          <div className="box-info-progress">
                            <div className="progress">
                              <div className="progress-bar" role="progressbar" style={{ width: '72%' }} aria-valuenow={72} aria-valuemin={0} aria-valuemax={100} />
                            </div>
                          </div><span className="num-percen">72%</span>
                        </div>
                        <div className="item-rate-total"><span className="neutral-500 text-star">3 stars</span>
                          <div className="box-info-progress">
                            <div className="progress">
                              <div className="progress-bar" role="progressbar" style={{ width: '25%' }} aria-valuenow={25} aria-valuemin={0} aria-valuemax={100} />
                            </div>
                          </div><span className="num-percen">25%</span>
                        </div>
                        <div className="item-rate-total"><span className="neutral-500 text-star">2 stars</span>
                          <div className="box-info-progress">
                            <div className="progress">
                              <div className="progress-bar" role="progressbar" style={{ width: '16%' }} aria-valuenow={16} aria-valuemin={0} aria-valuemax={100} />
                            </div>
                          </div><span className="num-percen">16%</span>
                        </div>
                        <div className="item-rate-total"><span className="neutral-500 text-star">1 star</span>
                          <div className="box-info-progress">
                            <div className="progress">
                              <div className="progress-bar" role="progressbar" style={{ width: '4%' }} aria-valuenow={4} aria-valuemin={0} aria-valuemax={100} />
                            </div>
                          </div><span className="num-percen">4%</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="tab-pane fade" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
                  <div className="table-responsive">
                    <table className="table table-striped">
                      <tbody>
                        <tr>
                          <th>Color</th>
                          <td> Red</td>
                        </tr>
                        <tr>
                          <th>Size</th>
                          <td> XL</td>
                        </tr>
                        <tr>
                          <th>Weight</th>
                          <td> 300gr</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div className="tab-pane fade" id="vendor" role="tabpanel" aria-labelledby="vendor-tab">
                  <div className="table-responsive">
                    <table className="table table-striped">
                      <tbody>
                        <tr>
                          <th>Color</th>
                          <td> Red</td>
                        </tr>
                        <tr>
                          <th>Size</th>
                          <td> XL</td>
                        </tr>
                        <tr>
                          <th>Weight</th>
                          <td> 300gr</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section className="section block-may-also-like recent-viewed">
          <div className="container">
            <div className="top-head justify-content-center">
              <h4 className="text-uppercase brand-1 brush-bg">Customers Also Viewed</h4>
            </div>
            <div className="row">
              <div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={Product} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                      <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                          <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                        </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg></a></div>
                  </div>
                  <div className="cardInfo"><a href="product-single.html">
                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                    <p className="font-lg cardDesc">$16.00</p>
                  </div>
                </div>
              </div>
              <div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={Product} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                      <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                          <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                        </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg></a></div>
                  </div>
                  <div className="cardInfo"><a href="product-single.html">
                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                    <p className="font-lg cardDesc">$16.00</p>
                  </div>
                </div>
              </div><div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={Product} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                      <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                          <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                        </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg></a></div>
                  </div>
                  <div className="cardInfo"><a href="product-single.html">
                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                    <p className="font-lg cardDesc">$16.00</p>
                  </div>
                </div>
              </div><div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={Product} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                      <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                          <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                        </svg></a><a className="btn" href="#">
                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                          <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg></a></div>
                  </div>
                  <div className="cardInfo"><a href="product-single.html">
                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                    <p className="font-lg cardDesc">$16.00</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

    </>
  )
}
export default ProductDetailComponent