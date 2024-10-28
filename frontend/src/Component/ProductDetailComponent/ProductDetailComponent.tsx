import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import Star from "../../assets/imgs/template/icons/star.svg";
import { useAppDispatch } from '../../Redux/store';
import { addToCart } from '../../Redux/Reducer/CartReducer';
import axios from 'axios';

const ProductDetailComponent: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const dispatch = useAppDispatch();
    const [product, setProduct] = useState<any>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [selectedSize, setSelectedSize] = useState('');
    const [selectedColor, setSelectedColor] = useState<string | null>(null);
    const [quantity, setQuantity] = useState<number>(1);

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
        dispatch(addToCart({ product_detail_id, user_id: 1, quantity }));
        console.log(dispatch);

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
                                        <span className="price-main">{product.price}</span>
                                        <span className="price-line">$25.00</span>
                                    </div>
                                    <div className="block-view">
                                        <p className="font-md neutral-900">{product.description}</p>
                                    </div>

                                    <div className="block-color">
                                        <span>Color</span>
                                        <label>{selectedColor || ''}</label>
                                        <ul className="list-color">
                                            {product.product_details.map((color: any) => (
                                                <button
                                                    key={color.id}
                                                    style={{
                                                        padding: '10px 15px',
                                                        border: '1px solid #ebebeb',
                                                        background: 'none',
                                                        margin: '0 5px 0 0',
                                                        cursor: 'pointer',
                                                    }}
                                                    onClick={() => setSelectedColor(color.color_id)}
                                                >
                                                    {color.color_id}
                                                </button>
                                            ))}
                                        </ul>

                                    </div>
                                    <div className="block-size">
                                        <span>Size:</span>
                                        <label>{selectedSize || ''}</label>
                                        <div className="list-sizes">
                                            {product.product_details.map((size: any) => (
                                                <button
                                                    key={size.id}
                                                    style={{
                                                        padding: '10px 15px',
                                                        border: '1px solid #ebebeb',
                                                        background: 'none',
                                                        margin: '0 5px 0 0',
                                                        cursor: 'pointer',
                                                    }}
                                                    onClick={() => setSelectedSize(size.size_id)}
                                                >
                                                    {size.size_id}
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
                                            {product.product_details.map((detail: any) => (
                                                <div key={detail.id}>
                                                    <button className="btn btn-brand-1-border" onClick={() => handleAddToCart(detail.id)}>Add to Cart</button>
                                                </div>
                                            ))}
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
                                            {/* Add your social media sharing links/icons here */}
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
{/* <section className="section block-may-also-like recent-viewed">
    <div className="container">
        <div className="top-head justify-content-center">
            <h4 className="text-uppercase brand-1 brush-bg">Customers Also Viewed</h4>
        </div>
        <div className="row">
            <div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                    <div className="cardImage">
                        <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={Product} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                        <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                    <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg></a></div>
                    </div>
                    <div className="cardInfo"><a href="product-single.html">
                        <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                        <p className="font-lg cardDesc">$16.00</p>
                    </div>
                </div>
            </div>
            <div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                    <div className="cardImage">
                        <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={product.avatar} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                        <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                    <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg></a></div>
                    </div>
                    <div className="cardInfo"><a href="product-single.html">
                        <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                        <p className="font-lg cardDesc">$16.00</p>
                    </div>
                </div>
            </div><div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                    <div className="cardImage">
                        <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={product.avatar} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                        <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                    <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg></a></div>
                    </div>
                    <div className="cardInfo"><a href="product-single.html">
                        <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                        <p className="font-lg cardDesc">$16.00</p>
                    </div>
                </div>
            </div><div className="col-lg-3 col-sm-6">
                <div className="cardProduct wow fadeInUp">
                    <div className="cardImage">
                        <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={Product} alt="kidify" /><img className="imageHover" src={ProductThree} alt="kidify" /></a>
                        <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                        <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                    <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                    <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                </svg></a><a className="btn" href="#">
                                <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg></a></div>
                    </div>
                    <div className="cardInfo"><a href="product-single.html">
                        <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                        <p className="font-lg cardDesc">$16.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> */}
{/* <div className="col-lg-5 box-images-product-left">
<div className="detail-gallery">
    <div className="slider-nav-thumbnails">

    </div> */}
{/* <div className="box-main-gallery"><a className="zoom-image glightbox" href={Product} />
<div className="product-image-slider">
<figure className="border-radius-10"><a className="glightbox" href={Product}><img src={Product} alt="kidify" /></a></figure>
<figure className="border-radius-10"><a className="glightbox" href={ProductTwo}><img src={ProductTwo} alt="kidify" /></a></figure>
<figure className="border-radius-10"><a className="glightbox" href={ProductThree}><img src={ProductThree} alt="kidify" /></a></figure>
<figure className="border-radius-10"><a className="glightbox" href={ProductFour}><img src={ProductFour} alt="kidify" /></a></figure>
<figure className="border-radius-10"><a className="glightbox" href={ProductSix}><img src={ProductSix} alt="kidify" /></a></figure>
<figure className="border-radius-10"><a className="glightbox" href={ProductFive}><img src={ProductFive} alt="kidify" /></a></figure>
</div>
</div> */}
// </div>
// </div>