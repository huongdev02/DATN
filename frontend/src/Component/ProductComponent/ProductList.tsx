import { useState, useEffect } from "react";
import { message } from "antd";
import { IProduct, Size, Color, Category } from "../../types/cart";
// import { Categories } from "../../types/product";
import { Link } from "react-router-dom";
// import api from "../../configAxios/axios";
import axios from 'axios';
interface ProductListProps {
  filters: {
    size: string | null;
    color: string | null;
    category: string | null;
  };
}

const ProductList: React.FC<ProductListProps> = ({ filters }) => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<IProduct[]>([]);

  const GetAllProducts = async () => {
    try {
      const { data } = await axios.get("http://127.0.0.1:8000/api/products");
      setProducts(data.products);
      setFilteredProducts(data.products); // Set filtered products to all initially
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  useEffect(() => {
    GetAllProducts(); 
  }, []); 

  useEffect(() => {
    // Lọc sản phẩm khi bộ lọc thay đổi
    const filterData = products.filter((product) => {
      return (
        // Lọc theo size
        (filters.size ? product.sizes.some((size: Size) => size.size === filters.size) : true) &&
        // Lọc theo color
        (filters.color ? product.colors.some((color: Color) => color.name_color === filters.color) : true) &&
        // Lọc theo category
        (filters.category ? product.categories.name === filters.category : true)
      );
    });

    setFilteredProducts(filterData); 
  }, [filters, products]); 
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
            {filteredProducts.map((product) => (
              <div className="col-xl-4 col-sm-6" key={product.id}>
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    <label className="lbl-hot">hot</label>
                    <Link to={`/product-detail/${product.id}`}>
                      <img
                        className="imageMain"
                        src={product.avatar_url}
                        alt={product.name}
                      />
                      <img
                        className="imageHover"
                        src={product.avatar_url}
                        alt={product.name}
                      />
                    </Link>
                    <div className="button-select">
                      <Link to={`/product-detail/${product.id}`}>Add to Cart</Link>
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
