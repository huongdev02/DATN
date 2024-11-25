import React, { useState, useEffect } from "react";
import { message, Pagination } from "antd";
import { IProduct, Size, Color } from "../../types/cart";
import { Link } from "react-router-dom";
import api from "../../configAxios/axios";
import type { PaginationProps } from 'antd';

interface ProductListProps {
  filters: {
    size: string | null;
    color: string | null;
    category: string | null;
    priceRange: [number, number] | null;
  };
}

const ProductList: React.FC<ProductListProps> = ({ filters }) => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<IProduct[]>([]);
  const [current, setCurrent] = useState(1);
  const pageSize = 20;

  const onChange: PaginationProps['onChange'] = (page) => {
    console.log(page);
    setCurrent(page);
  };

  const GetAllProducts = async () => {
    try {
      const { data } = await api.get("/products");
      setProducts(data.products);
      setFilteredProducts(data.products);
    } catch (error) {
      message.error("Lỗi api!");
    }
  };

  useEffect(() => {
    GetAllProducts();
  }, []);

  useEffect(() => {
    const filterData = products.filter((product) => {
      const matchSize = filters.size ? product.sizes.some((size: Size) => size.size === filters.size) : true;
      const matchColor = filters.color ? product.colors.some((color: Color) => color.name_color === filters.color) : true;
      const matchCategory = filters.category ? product.categories.name === filters.category : true;
      const matchPrice = filters.priceRange ? (product.price >= filters.priceRange[0] && product.price <= filters.priceRange[1]) : true;
      return matchSize && matchColor && matchCategory && matchPrice;
    });

    setFilteredProducts(filterData);
  }, [filters, products]);

  const paginatedProducts = filteredProducts.slice((current - 1) * pageSize, current * pageSize);

  return (
    <>
      <div className="col-lg-9 order-lg-last">
        <div className="box-filter-top">
          <div className="number-product">
            <p className="body-p2 neutral-medium-dark">
              Hiển thị {pageSize} trong số {filteredProducts.length} sản phẩm
            </p>
          </div>
        </div>
        <div className="box-product-lists">
          <div className="row">
            {paginatedProducts.map((product) => (
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
        <nav className="box-pagination" style={{ float: 'right' }}>
          <Pagination current={current} onChange={onChange} total={filteredProducts.length} pageSize={pageSize} />
        </nav>
      </div>
    </>
  );
};

export default ProductList;
