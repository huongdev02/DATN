import { useState, useEffect } from "react";
import { Categories } from "../../types/product";
import api from "../../configAxios/axios";
import { message } from "antd";
const AsideFilter: React.FC = () => {
  const [categories, setCategories] = useState<Categories[]>([]);

  const GetAllCategory = async () => {
    try {
      const { data } = await api.get("/categories");
      setCategories(data);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  useEffect(() => {
    GetAllCategory();
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
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="item-collapse">Size</h6>
                  <div className="box-collapse">
                    <div className="block-size">
                      <div className="list-sizes">
                        <span className="item-size">XS</span>
                        <span className="item-size active">S</span>
                        <span className="item-size">M</span>
                        <span className="item-size">XL</span>
                        <span className="item-size">XXL</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-lg-12 col-md-6">
                <div className="block-filter">
                  <h6 className="item-collapse">Màu sắc</h6>
                  <div className="box-collapse">
                    <ul className="list-color">
                      <li className="active">
                        <span className="box-circle-color">
                          <a className="color-red active" href="#" />
                        </span>
                        <span className="font-xs">Red</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-green" href="#" />
                        </span>
                        <span className="font-xs">Green</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-orange" href="#" />
                        </span>
                        <span className="font-xs">Orange</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-yellow" href="#" />
                        </span>
                        <span className="font-xs">Yellow</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-blue" href="#" />
                        </span>
                        <span className="font-xs">Blue</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-gray" href="#" />
                        </span>
                        <span className="font-xs">Gray</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-brown" href="#" />
                        </span>
                        <span className="font-xs">Brown</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-cyan" href="#" />
                        </span>
                        <span className="font-xs">Cyan</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-cyan-2" href="#" />
                        </span>
                        <span className="font-xs">Cyan 2</span>
                      </li>
                      <li>
                        <span className="box-circle-color">
                          <a className="color-purple" href="#" />
                        </span>
                        <span className="font-xs">Purple</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
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
