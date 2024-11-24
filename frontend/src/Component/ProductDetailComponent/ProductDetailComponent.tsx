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
                  <label>S</label>
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
                    <div className="form-cart"><span className="minus" /><input className="form-control" type="text" defaultValue={1} /><span className="plus" />
                    </div><a className="btn btn-brand-1-border" href="#">Add to Cart</a><a className="btn btn-brand-1-xl" href="#">Buy Now</a><a className="btn link-add-cart" href="#">Add to Wish list</a>
                  </div>
                </div>
              </div>
            </div>
             )}
             {/* Tab thành phần */}
            <div className="box-detail-product">
              <ul className="nav-tabs nav-tab-product" role="tablist">
                <li className="nav-item" role="presentation">
                  <button className="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Mô tả</button>
                </li>
                <li className="nav-item" role="presentation">
                  <button className="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="false">Thành phần</button>
                </li>
                <li className="nav-item" role="presentation">
                  <button className="nav-link" id="vendor-tab" data-bs-toggle="tab" data-bs-target="#vendor" type="button" role="tab" aria-controls="vendor" aria-selected="false">Nguồn gốc</button>
                </li>
              </ul>
              {/* Tab */}
              <div className="tab-content">
                <div className="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
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
        {/* Sản phẩm cùng danh mục */}
        <section className="section block-may-also-like recent-viewed">
          <div className="container">
            <div className="top-head justify-content-center">
              <h4 className="text-uppercase brand-1 brush-bg">Sản phẩm liên quan</h4>
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
            </div>
          </div>
        </section>
      </main>

    </>
  )
}
export default ProductDetailComponent