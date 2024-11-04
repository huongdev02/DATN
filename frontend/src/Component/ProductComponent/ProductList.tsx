import ProductOne from "../../assets/imgs/page/homepage1/product1.png";
import ProductFour from "../../assets/imgs/page/homepage1/product4.png";
import ProductThree from "../../assets/imgs/page/homepage1/product3.png";
import ProductTwo from "../../assets/imgs/page/homepage1/product2.png";
import ProductSix from "../../assets/imgs/page/homepage1/product6.png";
import ProductSeven from "../../assets/imgs/page/homepage1/product7.png";
import { useState, useEffect } from "react";
import api from "../../configAxios/axios";
import { message } from "antd";
import { IProduct, Size, Color } from "../../types/cart";
import { Link } from "react-router-dom";
const ProductList: React.FC = () => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const [sizes, setSizes] = useState<Size[]>([]);
  const [colors, setColors] = useState<Color[]>([]);
  const GetAllProducts = async () => {
    try {
      const { data } = await api.get("/products");
      setProducts(data.products);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  useEffect(() => {
    GetAllProducts();
  }, []);

  return (
    <>
      <div className="col-lg-9 order-lg-last">
        <div className="box-filter-top">
          <div className="number-product">
            <p className="body-p2 neutral-medium-dark">
              Hiển thị 9 trong số 18 sản phẩm
            </p>
          </div>
          <div className="box-sort">
            <div className="box-sortby d-flex align-items-center">
              <div className="dropdown dropdown-sort">
                <button
                  className="btn dropdown-toggle"
                  id="dropdownSort2"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Sản phẩm mới nhất
                </button>
                <ul
                  className="dropdown-menu dropdown-menu-light"
                  aria-labelledby="dropdownSort2"
                  style={{ margin: 0 }}
                >
                  <li>
                    <a className="dropdown-item active" href="#">
                      Sắp xếp mặc định
                    </a>
                  </li>
                  <li>
                    <a className="dropdown-item" href="#">
                      Sản phẩm cũ
                    </a>
                  </li>
                  <li>
                    <a className="dropdown-item" href="#">
                      Sản phẩm mới nhất{" "}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div className="box-sortby d-flex align-items-center">
              <div className="dropdown dropdown-sort">
                <button
                  className="btn dropdown-toggle"
                  id="dropdownSort"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  30 mục
                </button>
                <ul
                  className="dropdown-menu dropdown-menu-light"
                  aria-labelledby="dropdownSort"
                  style={{ margin: 0 }}
                >
                  <li>
                    <a className="dropdown-item active" href="#">
                      30 mục
                    </a>
                  </li>
                  <li>
                    <a className="dropdown-item" href="#">
                      50 mục
                    </a>
                  </li>
                  <li>
                    <a className="dropdown-item" href="#">
                      100 mục{" "}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <div className="box-view-style">
              {" "}
              <a className="view-type view-grid active" href="#" />
              <a className="view-type view-list" href="#" />
            </div>
          </div>
        </div>
        <div className="box-product-lists">
          <div className="row">
            {products.map((product) => (
              <div className="col-xl-4 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    <label className="lbl-hot">hot</label>
                    <Link to="/product-detail">
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
                    </Link>
                    <div className="button-select">
                      <Link to={`/product-detail/${product.id}`}>Add to Cart</Link>
                    </div>
                    <div className="box-quick-button">
                      <a href={`/product-detail/${product.id}`} className="btn">
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
                      <h6 className="font-md-bold cardTitle">{product.name}</h6>
                    </Link>
                    <p className="font-lg cardDesc">
                      {Math.round(product.price).toLocaleString("vi", {
                        style: "currency",
                        currency: "VND",
                      })}
                    </p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
        <nav className="box-pagination">
          <ul className="pagination">
            <li className="page-item">
              <a className="page-link page-prev" href="#">
                <svg
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
                    d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"
                  />
                </svg>
              </a>
            </li>
            <li className="page-item">
              <a className="page-link" href="#">
                1
              </a>
            </li>
            <li className="page-item">
              <a className="page-link active" href="#">
                2
              </a>
            </li>
            <li className="page-item">
              <a className="page-link" href="#">
                3
              </a>
            </li>
            <li className="page-item">
              <a className="page-link" href="#">
                ...
              </a>
            </li>
            <li className="page-item">
              <a className="page-link" href="#">
                10
              </a>
            </li>
            <li className="page-item">
              <a className="page-link page-next" href="#">
                <svg
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
                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                  />
                </svg>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </>
  );
};

export default ProductList;
