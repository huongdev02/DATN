import { useState, useEffect } from "react";
import { Categories } from "../../types/product";
import { IProduct, Size, Color } from "../../types/cart";
import api from "../../configAxios/axios";
import { message } from "antd";

interface AsideFilterProps {
  setFilters: React.Dispatch<
    React.SetStateAction<{
      category: string | null;
      size: string | null;
      color: string | null;
      priceRange: [number, number] | null;
      brands: string[];
    }>
  >;
}

const AsideFilter: React.FC<AsideFilterProps> = ({ setFilters }) => {
  const [categories, setCategories] = useState<Categories[]>([]);
  const [sizes, setSizes] = useState<Size[]>([]);
  const [colors, setColors] = useState<Color[]>([]);
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [selectedBrands, setSelectedBrands] = useState<string[]>([]);
  const [priceRange, setPriceRange] = useState<[number, number] | null>(null);

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

  const handleSizeClick = (size: string) => {
    setFilters((prev) => ({ ...prev, size }));
  };

  const handleCategoryChange = (categoryName: string) => {
    setSelectedCategories((prevState) => {
      // Tạo danh sách danh mục đã chọn hoặc bỏ chọn
      const updatedCategories = prevState.includes(categoryName)
        ? prevState.filter((name) => name !== categoryName) // Nếu đã chọn, bỏ chọn
        : [...prevState, categoryName]; // Nếu chưa chọn, thêm vào
      setFilters((prevFilters) => ({
        ...prevFilters,
        category: updatedCategories.length > 0 ? updatedCategories.join(', ') : null, // Dùng join để lưu lại mảng dưới dạng chuỗi
      }));
  
      return updatedCategories;
    });
  };
  


  const handleColorClick = (color: string) => {
    setFilters((prev) => ({ ...prev, color }));
  };

  const handleBrandClick = (brand: string) => {
    setSelectedBrands((prev) => {
      const newSelectedBrands = prev.includes(brand)
        ? prev.filter((item) => item !== brand)
        : [...prev, brand];
      setFilters((prevFilters) => ({
        ...prevFilters,
        brands: newSelectedBrands,
      }));
      return newSelectedBrands;
    });
  };

  const handlePriceRangeChange = (min: number, max: number) => {
    setPriceRange([min, max]);
    setFilters((prev) => ({ ...prev, priceRange: [min, max] }));
  };

  return (
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
                      <li
                        key={category.id}
                      >
                        <label className="cb-container">
                          <input
                            type="checkbox"
                            checked={selectedCategories.includes(category.name)} // Kiểm tra xem category có được chọn hay không
                            onChange={() => handleCategoryChange(category.name)} // Thay đổi khi người dùng click
                          />
                          <span className="text-small">{category.name}</span>
                          <span className="checkmark" />
                        </label>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
            Price Range
            <div className="col-lg-12 col-md-6">
              <div className="block-filter">
                <h6 className="title-filter">Giá tiền</h6>
                <div className="box-collapse">
                  <div className="box-slider-range mt-20 mb-25">
                    {/* Slider implementation */}
                    <input
                      type="range"
                      min="0"
                      max="10000000"
                      onChange={(e) =>
                        handlePriceRangeChange(0, parseInt(e.target.value))
                      }
                    />
                    <span>Price range: {priceRange ? `${priceRange[0]} - ${priceRange[1]}` : "All"}</span>
                  </div>
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
                        <span
                          key={size.id}
                          onClick={() => handleSizeClick(size.size)}
                        >
                          {size.size}
                        </span>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* Color */}
            <div className="col-lg-12 col-md-6">
              <div className="block-filter">
                <h6 className="item-collapse">Màu sắc</h6>
                <div className="box-collapse">
                  <ul className="list-color">
                    {colors.map((color) => (
                      <li key={color.id} onClick={() => handleColorClick(color.name_color)}>
                        <span className="box-circle-color">
                          <a
                            href="#"
                            className={`color-${color.name_color}`}
                          />
                        </span>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
            {/* Tags and other filters */}
          </div>
        </div>
      </div>
    </div>
  );
};

export default AsideFilter;
