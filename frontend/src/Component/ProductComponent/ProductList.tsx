import { Link } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { useEffect } from 'react';
import { fetchProducts } from '../../Redux/Reducer/ProductReducer';
import { RootState, useAppDispatch } from '../../Redux/store';

const ProductList: React.FC = () => {
  const dispatch = useAppDispatch();
  const { products, loading, error } = useSelector((state: RootState) => state.products);

  useEffect(() => {
    dispatch(fetchProducts());
  }, [dispatch]);

  if (loading) return <p>Loading products...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="col-lg-9 order-lg-last">
      <div className="box-filter-top">
        <div className="number-product">
          <p className="body-p2 neutral-medium-dark">
            Showing {products.length} products
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
                Latest products
              </button>
              <ul
                className="dropdown-menu dropdown-menu-light"
                aria-labelledby="dropdownSort2"
                style={{ margin: 0 }}
              >
                <li>
                  <a className="dropdown-item active" href="#">
                    Default Sorting
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    Oldest products
                  </a>
                </li>
                <li>
                  <a className="dropdown-item" href="#">
                    Latest products
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div className="box-product-lists">
        <div className="row">
          {products.map((product) => (
            <div className="col-xl-4 col-sm-6" key={product.id}>
              <div className="cardProduct wow fadeInUp">
                <div className="cardImage">
                  <label className="lbl-hot">Hot</label>
                  <Link to={`/product-detail/${product.id}`}>
                    <img
                      className="imageMain"
                      src={`http://127.0.0.1:8000/storage/${product.avatar}`}
                      alt={product.name}
                    />
                    <img
                      className="imageHover"
                      src={`http://127.0.0.1:8000/storage/${product.avatar}`}
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
                  <p className="font-lg cardDesc">${product.price}</p>
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
  );
};

export default ProductList;
