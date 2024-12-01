import React, { useEffect, useState } from 'react';
import { Navigate, useNavigate, useParams } from 'react-router-dom';
import Star from "../../assets/imgs/template/icons/star.svg";
import { useAppDispatch } from '../../Redux/store';
import axios from 'axios';
import { notification } from 'antd';
import { addToCart } from '../../Redux/Reducer/CartReducer';

const ProductDetailComponent: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const dispatch = useAppDispatch();
    const [product, setProduct] = useState<any>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedSize, setSelectedSize] = useState('');
    const [selectedColor, setSelectedColor] = useState<string | null>(null);
    const [quantity, setQuantity] = useState<number>(1);
    const [selectedIndex, setSelectedIndex] = useState(0);
    const navigate = useNavigate();


    const convertToVND = (usdPrice: number) => {
        return (usdPrice).toLocaleString('vi-VN');
    };

    const handleIncrease = () => {
        setQuantity(prevQuantity => prevQuantity + 1);
    };

    const handleDecrease = () => {
        setQuantity(prevQuantity => (prevQuantity > 1 ? prevQuantity - 1 : 1));
    };

    const handleThumbnailClick = (index: number) => {
        setSelectedIndex(index);
    };

    useEffect(() => {
        const fetchProductDetail = async () => {
            try {
                const response = await axios.get(`http://localhost:8000/api/products/${id}`);
                setProduct(response.data);
            } catch (error) {
                setError('Failed to fetch product details');
            } finally {
                setLoading(false);
            }
        };

        fetchProductDetail();
    }, [id]);

    console.log("dlllllllllllll", product);
    

    // const saveCartToLocalStorage = (cart: any) => {
    //     localStorage.setItem('cart', JSON.stringify(cart));
    //   };

    const handleAddToCart = async () => {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const token = localStorage.getItem('token');

        if (!user?.id) {
            notification.error({
                message: 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!',
            });
            navigate('/login');
            return;
        }

        if (!token) {
            notification.error({
                message: 'Token không hợp lệ. Vui lòng đăng nhập lại!',
            });
            return;
        }

        if (!selectedSize || !selectedColor) {
            notification.warning({
                message: 'Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng!',
            });
            return;
        }

        try {
            const cartData = {
                user_id: user.id,
                productId: product.id,
                quantity,
                price: product.price,
                size: selectedSize,
                color: selectedColor,
            };

            await dispatch(addToCart(cartData));
            // saveCartToLocalStorage(cartData);b
            notification.success({
                message: 'Sản phẩm đã được thêm vào giỏ hàng!',
            });
            navigate('/cart')
        } catch (error) {
            console.error('Lỗi khi thêm sản phẩm vào giỏ hàng:', error);
            notification.error({
                message: 'Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!',
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
                                <li><a href="#">Home</a></li>
                                <li><a href="#">Shop</a></li>
                                <li><a href="#">Boys Clothing</a></li>
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
                                                <div key={gallery.id} onClick={() => handleThumbnailClick(index)}>
                                                    <div className="item-thumb">
                                                        <img src={`${gallery.image_path}`} alt="Thumbnail" />
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
                                                        width={'100%'}
                                                        src={product.galleries && product.galleries[selectedIndex]
                                                            ? `${product.galleries[selectedIndex].image_path}`
                                                            : product.avatar}
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
                                    <label className="flash-sale-red">Extra 2% off</label>
                                    <h2 className="font-2xl-bold">{product.name}</h2>

                                    <div className="block-rating">
                                        {[...Array(5)].map((_, index) => (
                                            <img key={index} src={Star} alt="rating star" />
                                        ))}
                                        <span className="font-md neutral-500">(14 Reviews - 25 Orders)</span>
                                    </div>
                                    <div className="block-price">
                                        <span className="price-main">{(product.price).toLocaleString('vi-VN')} VND</span>
                                    </div>
                                    <div className="block-view">
                                        <p className="font-md neutral-900">{product.description}</p>
                                    </div>

                                    <div className="block-color">
                                        <span>Color:</span>
                                        <label>{selectedColor || 'Chọn Màu'}</label>
                                        <ul className="list-color">
                                            {product.colors.map((color: any) => (
                                                <button
                                                    key={color.id}
                                                    style={{
                                                        padding: '10px 15px',
                                                        border: '1px solid #ebebeb',
                                                        background: selectedColor === color.name_color ? '#ddd' : 'none',
                                                        margin: '0 5px 0 0',
                                                        cursor: 'pointer',
                                                    }}
                                                    onClick={() => setSelectedColor(color.name_color)}
                                                >
                                                    {color.name_color}
                                                </button>
                                            ))}
                                        </ul>
                                    </div>
                                    <div className="block-size">
                                        <span>Size:</span>
                                        <label>{selectedSize || 'Chọn Size'}</label>
                                        <div className="list-sizes">
                                            {product.sizes.map((size: any) => (
                                                <button
                                                    key={size.id}
                                                    style={{
                                                        padding: '10px 15px',
                                                        border: '1px solid #ebebeb',
                                                        background: selectedSize === size.size ? '#ddd' : 'none',
                                                        margin: '0 5px 0 0',
                                                        cursor: 'pointer',
                                                    }}
                                                    onClick={() => setSelectedSize(size.size)}
                                                >
                                                    {size.size}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                    <div className="block-quantity">
                                        <div className="font-sm neutral-500 mb-15">Quantity</div>
                                        <div className="box-form-cart">
                                            <div className="form-cart">
                                                <button className="minus" onClick={handleDecrease}>-</button>
                                                <input
                                                    className="form-control"
                                                    type="text"
                                                    value={quantity}
                                                    readOnly
                                                />
                                                <button className="plus" onClick={handleIncrease}>+</button>
                                            </div>
                                            <button onClick={() => handleAddToCart()} disabled={!selectedColor || !selectedSize}>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                    <div className="box-product-tag d-flex justify-content-between align-items-end">
                                        <div className="box-tag-left">
                                            <p className="font-xs mb-5"><span className="neutral-500">SKU:</span><span className="neutral-900">kid1232568-UYV</span></p>
                                            <p className="font-xs mb-5"><span className="neutral-500">Categories:</span><span className="neutral-900">Girls, Dress</span></p>
                                            <p className="font-xs mb-5"><span className="neutral-500">Tags:</span><span className="neutral-900">fashion, dress, girls, blue</span></p>
                                        </div>
                                        <div className="box-tag-right">
                                            <span className="font-sm">Share:</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </>
    );
};

export default ProductDetailComponent;
