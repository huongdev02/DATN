import Sale from "../../assets/imgs/page/homepage1/upto60.png";
import Banner from "../../assets/imgs/page/homepage1/bg-section2.png";
import ProductDetail from "../../assets/imgs/page/product/img.png";
import ProductDetailTwo from "../../assets/imgs/page/product/img-2.png";
import ProductDetailThree from "../../assets/imgs/page/product/img-3.png";
import ProductDetailFour from "../../assets/imgs/page/product/img-4.png";
import ProductDetailFive from "../../assets/imgs/page/product/img-5.png";
import ProductDetailSix from "../../assets/imgs/page/product/img-6.png";
import { Swiper, SwiperSlide } from "swiper/react";
import Star from "../../assets/imgs/template/icons/star.svg";
import api from "../../configAxios/axios";
import { message } from "antd";
import { useState, useEffect } from "react";
import { IProduct } from "../../types/cart";
import { Link } from "react-router-dom";
const ProductWithCategories: React.FC = () => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const GetProductCategory = async () => {
    try {
      const { data } = await api.get(`/products`);
      setProducts(data.products);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  const boyProducts = products.filter(
    (product) => product.categories.name === "Nam"
  );
  const girlProducts = products.filter(
    (product) => product.categories.name === "Nữ"
  );
  const kidProducts = products.filter(
    (product) => product.categories.name === "Trẻ em"
  );

  useEffect(() => {
    GetProductCategory();
  }, []);

  return (
    <>
      <section className="section block-section-1">
        <div className="container">
          <div className="text-center">
            <p className="font-xl brand-2 wow animate__animated animate__fadeIn">
              <span className="rounded-text">NEW IN STORE</span>
            </p>
            <div className="box-tabs wow animate__animated animate__fadeIn">
              <ul className="nav nav-tabs" role="tablist">
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link active"
                    id="girls-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#girls"
                    type="button"
                    role="tab"
                    aria-controls="girls"
                    aria-selected="true"
                  >
                    Sản phẩm bán chạy nhất
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link"
                    id="boys-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#boys"
                    type="button"
                    role="tab"
                    aria-controls="boys"
                    aria-selected="false"
                  >
                    Sản phẩm mới về
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link"
                    id="accessories-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#accessories"
                    type="button"
                    role="tab"
                    aria-controls="accessories"
                    aria-selected="false"
                  >
                    Sản phẩm khuyến mãi
                  </button>
                </li>
              </ul>
            </div>
          </div>
          {/* sản phẩm theo danh mục */}
          <div className="tab-content">
            <div
              className="tab-pane fade show active"
              id="girls"
              role="tabpanel"
              aria-labelledby="girls-tab"
            >
              <div className="row">
                {boyProducts.map((product) => (
                  <div
                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                    data-wow-delay=".5s"
                  >
                    <div className="cardProduct wow fadeInUp">
                      <div className="cardImage">
                        <label className="lbl-hot">hot</label>
                        <a href="product-single.html">
                          <img
                            className="imageMain"
                            src={product.avatar_url}
                            alt="kidify"
                          />
                          <img
                            className="imageHover"
                            src={product.avatar_url}
                            alt="kidify"
                          />
                        </a>
                        <div className="button-select">
                          <a href="product-single.html">Add to Cart</a>
                        </div>
                        <div className="box-quick-button">
                          <a
                            className="btn"
                            aria-label="Quick view"
                            data-bs-toggle="modal"
                            data-bs-target="#quickViewModal"
                          >
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              fill="none"
                              stroke="currentColor"
                              strokeWidth="1.5"
                              viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                              />
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                              />
                            </svg>
                          </a>
                          <a className="btn" href="#">
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              width={18}
                              height={18}
                              viewBox="0 0 18 18"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                              />
                              <path
                                d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                              />
                              <path
                                d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                              />
                              <path
                                d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                              />
                            </svg>
                          </a>
                          <a className="btn" href="#">
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              fill="none"
                              stroke="currentColor"
                              strokeWidth="1.5"
                              viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                              />
                            </svg>
                          </a>
                        </div>
                      </div>
                      <div className="cardInfo">
                        <Link to={`/product-detail/${product.id}`}>
                          <h6 className="font-md-bold cardTitle">
                            {product.name}
                          </h6>
                        </Link>
                        <p className="font-lg cardDesc">
                          {" "}
                          {Math.round(product.price ?? 0).toLocaleString(
                            "vi-VN",
                            { style: "currency", currency: "VND" }
                          )}
                        </p>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <div
              className="tab-pane fade"
              id="boys"
              role="tabpanel"
              aria-labelledby="boys-tab"
            >
              <div className="row">
                {girlProducts.map((product) => (
                  <div
                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                    data-wow-delay=".1s"
                  >
                    <div className="cardProduct wow fadeInUp">
                      <div className="cardImage">
                        <label className="lbl-hot">hot</label>
                        <a href="product-single.html">
                          <img
                            className="imageMain"
                            src={product.avatar_url}
                            alt="kidify"
                          />
                          <img
                            className="imageHover"
                            src={product.avatar_url}
                            alt="kidify"
                          />
                        </a>
                        <div className="button-select">
                          <a href="product-single.html">Add to Cart</a>
                        </div>
                        <div className="box-quick-button">
                          <a
                            className="btn"
                            aria-label="Quick view"
                            data-bs-toggle="modal"
                            data-bs-target="#quickViewModal"
                          >
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              fill="none"
                              stroke="currentColor"
                              strokeWidth="1.5"
                              viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                              />
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                              />
                            </svg>
                          </a>
                          <a className="btn" href="#">
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              width={18}
                              height={18}
                              viewBox="0 0 18 18"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                              />
                              <path
                                d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                              />
                              <path
                                d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                              />
                              <path
                                d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                              />
                            </svg>
                          </a>
                          <a className="btn" href="#">
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              fill="none"
                              stroke="currentColor"
                              strokeWidth="1.5"
                              viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                              />
                            </svg>
                          </a>
                        </div>
                      </div>
                      <div className="cardInfo">
                        <Link to={`/product-detail/${product.id}`}>
                          <h6 className="font-md-bold cardTitle">
                            {product.name}
                          </h6>
                        </Link>
                        <p className="font-lg cardDesc">
                          {" "}
                          {Math.round(product.price ?? 0).toLocaleString(
                            "vi-VN",
                            { style: "currency", currency: "VND" }
                          )}
                        </p>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
            <div
              className="tab-pane fade"
              id="accessories"
              role="tabpanel"
              aria-labelledby="children"
            >
              <div className="row">
                {kidProducts.map((product) => (
                  <div
                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                    data-wow-delay=".1s"
                  >
                    <div className="cardProduct wow fadeInUp">
                      <div className="cardImage">
                        <label className="lbl-hot">hot</label>
                        <a href="product-single.html">
                          <img
                            className="imageMain"
                            src={product.avatar_url}
                            alt="kidify"
                          />
                          <img
                            className="imageHover"
                            src={product.avatar_url}
                            alt="kidify"
                          />
                        </a>
                        <div className="button-select">
                          <a href="product-single.html">Add to Cart</a>
                        </div>
                        <div className="box-quick-button">
                          <a
                            className="btn"
                            aria-label="Quick view"
                            data-bs-toggle="modal"
                            data-bs-target="#quickViewModal"
                          >
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              fill="none"
                              stroke="currentColor"
                              strokeWidth="1.5"
                              viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                              />
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                              />
                            </svg>
                          </a>
                          <a className="btn" href="#">
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              width={18}
                              height={18}
                              viewBox="0 0 18 18"
                              fill="none"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                              <path
                                d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                              />
                              <path
                                d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                              />
                              <path
                                d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                              />
                              <path
                                d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75"
                                stroke="#294646"
                                strokeWidth="1.5"
                                strokeLinecap="round"
                              />
                            </svg>
                          </a>
                          <a className="btn" href="#">
                            <svg
                              className="d-inline-flex align-items-center justify-content-center"
                              fill="none"
                              stroke="currentColor"
                              strokeWidth="1.5"
                              viewBox="0 0 24 24"
                              xmlns="http://www.w3.org/2000/svg"
                              aria-hidden="true"
                            >
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                              />
                            </svg>
                          </a>
                        </div>
                      </div>
                      <div className="cardInfo">
                        <Link to={`/product-detail/${product.id}`}>
                          <h6 className="font-md-bold cardTitle">
                            {product.name}
                          </h6>
                        </Link>
                        <p className="font-lg cardDesc">
                          {" "}
                          {Math.round(product.price ?? 0).toLocaleString(
                            "vi-VN",
                            { style: "currency", currency: "VND" }
                          )}
                        </p>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

     
    </>
  );
};

export default ProductWithCategories;
