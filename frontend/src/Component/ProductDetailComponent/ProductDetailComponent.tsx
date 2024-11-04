import React, { useEffect, useState } from 'react';
import { Navigate, useNavigate, useParams } from 'react-router-dom';
import Star from "../../assets/imgs/template/icons/star.svg";
import { useAppDispatch } from '../../Redux/store';
import { addToCart } from '../../Redux/Reducer/CartReducer';
import axios from 'axios';
import ProductThumb from '../../assets/imgs/page/product/thumnb.png'
import ProductThumbTwo from '../../assets/imgs/page/product/thumnb2.png'
import ProductThumbThree from '../../assets/imgs/page/product/thumnb3.png'
import ProductThumbFour from '../../assets/imgs/page/product/thumnb4.png'
import ProductThumbFive from '../../assets/imgs/page/product/thumnb5.png'
import { notification } from 'antd';

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
    const displayedColors = new Set();
    const displayedSizes = new Set();
    const handleThumbnailClick = (index: any) => {
        setSelectedIndex(index);
    };

    const navigate = useNavigate();

    const handleIncrease = () => {
        setQuantity(prevQuantity => prevQuantity + 1);
    };

    const handleDecrease = () => {
        setQuantity(prevQuantity => (prevQuantity > 1 ? prevQuantity - 1 : 1));
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

    const handleAddToCart = (product_detail_id: number) => {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        dispatch(addToCart({ product_detail_id, user_id: user.id, quantity }));
        notification.success({
            message: 'Sản Phẩm đã được thêm vào giỏ hàng'
        })
        navigate('/product');
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
                                                        <img src={`http://localhost:8000/storage/${gallery.image_path}`} alt="Thumbnail" />
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
                                                        src={`http://localhost:8000/storage/${product.galleries[selectedIndex]?.image_path || product.product.avatar
                                                            }`}
                                                        alt="kidify"
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
                                    <h2 className="font-2xl-bold">{product.product.name}</h2>

                                    <div className="block-rating">
                                        {[...Array(5)].map((_, index) => (
                                            <img key={index} src={Star} alt="rating star" />
                                        ))}
                                        <span className="font-md neutral-500">(14 Reviews - 25 Orders)</span>
                                    </div>
                                    <div className="block-price">
                                        <span className="price-main">{product.product.price}</span>
                                        <span className="price-line">$25.00</span>
                                    </div>
                                    <div className="block-view">
                                        <p className="font-md neutral-900">{product.product.description}</p>
                                    </div>

                                    <div className="block-color">
                                        <span>Color:</span>
                                        <label>{selectedColor || ''}</label>
                                        <ul className="list-color">
                                            {product.product_details.map((detail: any) => {
                                                if (displayedColors.has(detail.color)) {
                                                    return null;
                                                }
                                                displayedColors.add(detail.color);

                                                return (
                                                    <button
                                                        key={detail.id}
                                                        style={{
                                                            padding: '10px 15px',
                                                            border: '1px solid #ebebeb',
                                                            background: 'none',
                                                            margin: '0 5px 0 0',
                                                            cursor: 'pointer',
                                                        }}
                                                        onClick={() => setSelectedColor(detail.color)}
                                                    >
                                                        {detail.color}
                                                    </button>
                                                );
                                            })}


                                        </ul>
                                    </div>
                                    <div className="block-size">
                                        <span>Size:</span>
                                        <label>{selectedSize || ''}</label>
                                        <div className="list-sizes">
                                            {product.product_details.map((detail: any) => {
                                                if (displayedSizes.has(detail.size)) {
                                                    return null;
                                                }
                                                displayedSizes.add(detail.size);

                                                return (
                                                    <button
                                                        key={detail.id}
                                                        style={{
                                                            padding: '10px 15px',
                                                            border: '1px solid #ebebeb',
                                                            background: 'none',
                                                            margin: '0 5px 0 0',
                                                            cursor: 'pointer',
                                                        }}
                                                        onClick={() => setSelectedSize(detail.size)}
                                                    >
                                                        {detail.size}
                                                    </button>
                                                );
                                            })}

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
                                            {product.product_details.length > 0 && (
                                                <div key={product.product_details[0].id}>
                                                    <button className="btn btn-brand-1-border" onClick={() => handleAddToCart(product.product_details[0].id)}>Add to Cart</button>
                                                </div>
                                            )}
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
