import { useState, useEffect } from "react";
import { Categories } from "../../types/product";
import { IProduct, Size, Color } from "../../types/cart";
import api from "../../configAxios/axios";
import { message } from "antd";
const AsideFilter: React.FC = () => {
  const [categories, setCategories] = useState<Categories[]>([]);
  const [sizes, setSizes] = useState<Size[]>([]);
  const [colors, setColors] = useState<Color[]>([]);
  const GetAllCategory = async () => {
    try {
      const { data } = await api.get("/categories");
      setCategories(data);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  const GetAllProducts = async () => {
    try {
      const { data } = await api.get("/products");
      setSizes(data.all_sizes);
      setColors(data.all_colors);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  useEffect(() => {
    GetAllCategory();
    GetAllProducts();
  }, []);

  return (
    <>
      <div className="col-lg-3 order-lg-first">
        <div className="sidebar-left">
          <div className="box-filters-sidebar">
            <div className="row">
              <div className="col-lg-12 col-md-6">
                <h5 className="font-3xl-bold mt-5">Lọc sản phẩm</h5>
                <div className="block-filter">
                  <h6 className="item-collapse">Danh mục</h6>
                  <div className="box-collapse">
                    <ul className="list-filter-checkbox">
                      {categories.map((category) => (
                        <li key={category.id}>
                          <label className="cb-container">
                            <input type="checkbox" />
                            <span className="text-small">{category.name}</span>
                            <span className="checkmark" />
                          </label>
                          <span className="number-item">12</span>
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              </div>
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="title-filter">Giá tiền</h6>
                  <div className="box-collapse">
                    <div className="box-slider-range mt-20 mb-25">
                      <div className="row mb-20">
                        <div className="col-sm-12">
                          <div id="slider-range" />
                        </div>
                      </div>
                      <div className="row">
                        <div className="col-lg-12">
                          <label className="lb-slider font-sm neutral-500 mr-5">
                            Price Range:
                          </label>
                          <span className="min-value-money font-sm neutral-900" />
                          <label className="lb-slider font-sm neutral-900" />-
                          <span className="max-value-money font-sm neutral-900" />
                        </div>
                        <div className="col-lg-12">
                          <input
                            className="form-control min-value"
                            type="hidden"
                            name="min-value"
                          />
                          <input
                            className="form-control max-value"
                            type="hidden"
                            name="max-value"
                          />
                        </div>
                      </div>
                    </div>
                    <ul className="list-filter-checkbox">
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">$100 - $200</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">12</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">$200 - $400</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">24</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">$400 - $600</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">54</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">$600 - $800</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">78</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">Over $1000</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">125</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              {/* Size */}
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="item-collapse">Size</h6>
                  <div className="box-collapse">
                    <div className="block-size">
                      <div className="list-sizes">
                        {sizes.map((size) => (
                          <span key={size.id} >
                            {size.size}
                          </span>
                        ))}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              {/* end */}
              {/* Màu sắc */}
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="item-collapse">Màu sắc</h6>
                  <div className="box-collapse">
                    <ul className="list-color">
                    {colors.map((color) => (
                      <li key={color.id}>
                        <span className="box-circle-color">
                          <a href="#" className={`color-${color.name_color}`}/>
                        </span>
                      </li>
                        ))}
                    </ul>
                  </div>
                </div>
              </div>
              {/* end */}
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="item-collapse">Brand</h6>
                  <div className="box-collapse">
                    <ul className="list-filter-checkbox">
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">Seraphine</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">136</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">Monica + Andy</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">136</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">Maisonette</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">136</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">Pink Chicken</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">136</span>
                      </li>
                      <li>
                        <label className="cb-container">
                          <input type="checkbox" />
                          <span className="text-small">Hanna Andersson</span>
                          <span className="checkmark" />
                        </label>
                        <span className="number-item">136</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="item-collapse">Tags</h6>
                  <div className="box-collapse">
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Top Rated
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Outfits
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html#"
                    >
                      T-Shirts
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Boy Shirts
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Boy Tanks
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Shoes
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Boys Denim
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Toddler Boys
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Boy Swimwear
                    </a>
                    <a
                      className="btn btn-tag mr-10 mb-10"
                      href="product-list-2.html"
                    >
                      Boys Interior
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default AsideFilter;
