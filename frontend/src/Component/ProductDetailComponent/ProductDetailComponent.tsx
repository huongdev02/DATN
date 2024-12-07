import React, { useEffect, useState } from "react";
import { Navigate, useNavigate, useParams } from "react-router-dom";
import Star from "../../assets/imgs/template/icons/star.svg";
import { useAppDispatch } from "../../Redux/store";
import axios from "axios";
import { notification } from "antd";
import { addToCart } from "../../Redux/Reducer/CartReducer";
import {
  MinusOutlined,
  PlusOutlined,
  ShoppingOutlined,
} from "@ant-design/icons";
import "./ProductDetail.css";
const ProductDetailComponent: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const dispatch = useAppDispatch();
  const [product, setProduct] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [selectedSize, setSelectedSize] = useState("");
  const [selectedColor, setSelectedColor] = useState<string | null>(null);
  const [quantity, setQuantity] = useState<number>(1);
  const [selectedIndex, setSelectedIndex] = useState(0);
  const navigate = useNavigate();

  const convertToVND = (usdPrice: number) => {
    return usdPrice.toLocaleString("vi-VN");
  };

  const handleIncrease = () => {
    setQuantity((prevQuantity) => prevQuantity + 1);
  };

  const handleDecrease = () => {
    setQuantity((prevQuantity) => (prevQuantity > 1 ? prevQuantity - 1 : 1));
  };

  const handleThumbnailClick = (index: number) => {
    setSelectedIndex(index);
  };

  useEffect(() => {
    const fetchProductDetail = async () => {
      try {
        const response = await axios.get(
          `http://localhost:8000/api/products/${id}`
        );
        setProduct(response.data);
      } catch (error) {
        setError("Failed to fetch product details");
      } finally {
        setLoading(false);
      }
    };

    fetchProductDetail();
  }, [id]);

  console.log(product, "kkkkkkkkkkkkk");

  const handleAddToCart = async () => {
    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const token = localStorage.getItem("token");

    if (!user?.id) {
      notification.error({
        message: "Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!",
      });
      navigate("/login");
      return;
    }

    if (!token) {
      notification.error({
        message: "Token không hợp lệ. Vui lòng đăng nhập lại!",
      });
      return;
    }

    if (!selectedSize || !selectedColor) {
      notification.warning({
        message:
          "Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng!",
      });
      return;
    }

    const sizeId = product.sizes.find(
      (size: any) => size.size === selectedSize
    )?.id;
    const colorId = product.colors.find(
      (color: any) => color.name_color === selectedColor
    )?.id;

    console.log("Selected Size ID:", sizeId);
    console.log("Selected Color ID:", colorId);

    try {
      const cartData = {
        productId: product.id,
        quantity,
        sizeId,
        colorId,
      };
      console.log(cartData);

      await dispatch(addToCart(cartData));
      notification.success({
        message: "Sản phẩm đã được thêm vào giỏ hàng!",
      });
      // navigate('/cart')
    } catch (error) {
      console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
      notification.error({
        message: "Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!",
      });
    }
  };

  if (loading) return <div>Loading...</div>;
  if (error) return <div>{error}</div>;
  if (!product) return <div>Product not found.</div>;

  return (
    <>
      <main className="main">
        <div className="section block-shop-head-2 block-breadcrumb-type-1">
          <div className="container">
            <div className="breadcrumbs">
              <ul>
                <li>
                  <a href="#">Home</a>
                </li>
                <li>
                  <a href="#">Shop</a>
                </li>
                <li>
                  <a href="#">Boys Clothing</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <section className="section block-product-content">
          <div className="container">
            <div className="row">
              <div className="col-lg-5 box-images-product-left">
                <div className="detail-gallery">
                  <div className="slider-nav-thumbnails">
                    {product.galleries && product.galleries.length > 0 ? (
                      product.galleries.map((gallery: any, index: any) => (
                        <div
                          key={gallery.id}
                          onClick={() => handleThumbnailClick(index)}
                        >
                          <div className="item-thumb">
                            <img
                              src={`${gallery.image_path}`}
                              alt="Thumbnail"
                            />
                          </div>
                        </div>
                      ))
                    ) : (
                      <p>Không có ảnh trong thư viện.</p>
                    )}
                  </div>
                  <div className="box-main-gallery">
                    <a className="zoom-image glightbox" />
                    <div className="product-image-slider">
                      <figure className="border-radius-10">
                        <a className="glightbox">
                          <img
                            width={"100%"}
                            src={
                              product.galleries &&
                              product.galleries[selectedIndex]
                                ? `${product.galleries[selectedIndex].image_path}`
                                : product.avatar
                            }
                            alt={product.name}
                          />
                        </a>
                      </figure>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-lg-5 box-images-product-middle">
                <div className="box-product-info">
                  {/* <label className="flash-sale-red">Extra 2% off</label> */}
                  <h2 style={{ fontFamily: "Raleway" }} className="font-2xl">
                    {product.name}
                  </h2>

                  <div className="block-rating">
                    {[...Array(5)].map((_, index) => (
                      <img key={index} src={Star} alt="rating star" />
                    ))}
                    <span
                      style={{ fontFamily: "Raleway" }}
                      className="font-md neutral-500"
                    >
                      (14 Reviews - 25 Orders)
                    </span>
                  </div>
                  <div className="block-price">
                    <span
                      style={{ fontFamily: "Raleway", fontSize: "25px" }}
                      className="price-main"
                    >
                      {Math.round(product.price).toLocaleString("vi", {
                        style: "currency",
                        currency: "VND",
                      })}
                    </span>
                  </div>
                  <div className="block-view">
                    <p
                      style={{ fontFamily: "Raleway" }}
                      className="font-md neutral-900"
                    >
                      {product.description}
                    </p>
                  </div>

                  <div className="block-color">
                    <span style={{ fontFamily: "Raleway" }}>Color:</span>
                    <label style={{ fontFamily: "Raleway" }}>
                      {selectedColor || "Chọn Màu"}
                    </label>
                    <ul className="list-color">
                      {product.colors.map((color: any) => (
                        <button
                          className="button-color"
                          key={color.id}
                          style={{
                            padding: "10px 15px",
                            border:
                              selectedColor === color.name_color
                                ? "1px solid rgb(159,137,219)"
                                : "1px solid gray",
                            borderRadius: "8px",
                            backgroundColor: "white",
                            // background: selectedColor === color.name_color ? 'rgb(159,137,219)' : 'none',
                            margin: "0 5px 0 0",
                            color:
                              selectedColor === color.name_color
                                ? "rgb(159,137,219)"
                                : "black",
                            cursor: "pointer",
                          }}
                          onClick={() => setSelectedColor(color.name_color)}
                        >
                          {color.name_color}
                        </button>
                      ))}
                    </ul>
                  </div>
                  <div className="block-size">
                    <span style={{ fontFamily: "Raleway" }}>Size:</span>
                    <label style={{ fontFamily: "Raleway" }}>
                      {selectedSize || "Chọn Size"}
                    </label>
                    <div className="list-sizes">
                      {product.sizes.map((size: any) => (
                        <button
                          className="button-size"
                          key={size.id}
                          style={{
                            padding: "10px 15px",
                            border:
                              selectedSize === size.size
                                ? "1px solid rgb(159,137,219)"
                                : "1px solid gray",
                            borderRadius: "8px",
                            backgroundColor: "white",
                            color:
                              selectedSize === size.size
                                ? "rgb(159,137,219)"
                                : "black",
                            margin: "0 5px 0 0",
                            cursor: "pointer",
                          }}
                          onClick={() => setSelectedSize(size.size)}
                        >
                          {size.size}
                        </button>
                      ))}
                    </div>
                  </div>
                  <div className="block-quantity">
                    {/* <div className="font-sm neutral-500 mb-15">Quantity</div> */}
                    <div className="box-form-cart">
                      <div className="form-cart">
                        <button className="minus" onClick={handleDecrease}>
                          <MinusOutlined />
                        </button>
                        <input
                          className="form-control"
                          type="text"
                          style={{ border: "1px solid gray", fontSize: "18px" }}
                          value={quantity}
                          readOnly
                        />
                        <button className="plus" onClick={handleIncrease}>
                          <PlusOutlined />
                        </button>
                      </div>
                      <button
                        className="css-button-add"
                        onClick={() => handleAddToCart()}
                        disabled={!selectedColor || !selectedSize}
                      >
                        <ShoppingOutlined /> Thêm vào giỏ hàng
                      </button>
                    </div>
                  </div>
                  {/* <div className="box-product-tag d-flex justify-content-between align-items-end">
                                        <div className="box-tag-left">
                                            <p className="font-xs mb-5"><span className="neutral-500">SKU:</span><span className="neutral-900">kid1232568-UYV</span></p>
                                            <p className="font-xs mb-5"><span className="neutral-500">Categories:</span><span className="neutral-900">Girls, Dress</span></p>
                                            <p className="font-xs mb-5"><span className="neutral-500">Tags:</span><span className="neutral-900">fashion, dress, girls, blue</span></p>
                                        </div>
                                        <div className="box-tag-right">
                                            <span className="font-sm">Share:</span>
                                        </div>
                                    </div> */}
                </div>
              </div>
            </div>
            {/* Tab mô tả */}
            <div className="box-detail-product">
              <ul className="nav-tabs nav-tab-product" role="tablist">
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link active"
                    id="description-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#description"
                    type="button"
                    role="tab"
                    aria-controls="description"
                    aria-selected="true"
                  >
                    Mô tả
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link"
                    id="ingredients-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#ingredients"
                    type="button"
                    role="tab"
                    aria-controls="ingredients"
                    aria-selected="false"
                  >
                    Thành phần
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link"
                    id="vendor-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#vendor"
                    type="button"
                    role="tab"
                    aria-controls="vendor"
                    aria-selected="false"
                  >
                    Nguồn gốc
                  </button>
                </li>
              </ul>
              {/* Tab */}
              <div className="tab-content">
                <div
                  className="tab-pane fade show active"
                  id="description"
                  role="tabpanel"
                  aria-labelledby="description-tab"
                ></div>
                <div
                  className="tab-pane fade"
                  id="ingredients"
                  role="tabpanel"
                  aria-labelledby="ingredients-tab"
                >
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
                <div
                  className="tab-pane fade"
                  id="vendor"
                  role="tabpanel"
                  aria-labelledby="vendor-tab"
                >
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
              {/* Sản phẩm cùng danh mục */}
        <section className="section block-may-also-like recent-viewed">
        <div className="container">
            <div className="top-head justify-content-center">
              <h4 className="text-uppercase brand-1 brush-bg">
                Sản phẩm liên quan
              </h4>
            </div>
            <div className="row">
              {productById.map((product, index) => (
                <div className="col-lg-3 col-sm-6">
                  <div className="cardProduct wow fadeInUp" key={index}>
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
                    </div>
                    <div className="cardInfo">
                    <Link to={`/product-detail/${product.id}`}>
                      <h6 className="font-md-bold cardTitle">{product.name}</h6>
                    </Link>
                      <p className="font-lg cardDesc">
                        {" "}
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
        </section>
          </div>
        </section>
      </main>
    </>
  );
};

export default ProductDetailComponent;
