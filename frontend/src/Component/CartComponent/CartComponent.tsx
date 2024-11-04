import Ig from '../../assets/imgs/page/homepage1/instagram6.png'
import IgOne from '../../assets/imgs/page/homepage1/instagram.png'
import IgThree from '../../assets/imgs/page/homepage1/instagram3.png'
import IgFour from '../../assets/imgs/page/homepage1/instagram4.png'
import IgTwo from '../../assets/imgs/page/homepage1/instagram2.png'
import IgFive from '../../assets/imgs/page/homepage1/instagram5.png'
import { Link, useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { fetchCart, removeFromCart } from '../../Redux/Reducer/CartReducer';
import { RootState, useAppDispatch } from '../../Redux/store';
import { useEffect, useState } from 'react';
import { notification } from 'antd';

const CartComponent: React.FC = () => {
    const dispatch = useAppDispatch();
    const items = useSelector((state: RootState) => state.cart.items);
    console.log(items);

    const navigate = useNavigate();

    const handleCheckout = () => {
        navigate("/check-out", { state: { items } });
    };

    useEffect(() => {
        const userId = 1;
        dispatch(fetchCart(userId));
    }, [dispatch]);

    const handleRemoveFromCart = async (cartId: number, productDetailId: number) => {
        const resultAction = await dispatch(removeFromCart({ cartId, productDetailId }));
        if (removeFromCart.fulfilled.match(resultAction)) {
            notification.success({
                message: 'Xóa sản phẩm thành công!',
            })
            dispatch(fetchCart(1));

        } else {
            notification.success({
                message: 'Xóa sản phẩm thất bại!',
            })
        }
    };

    return (
        <>
            <main className="main">
                <section className="section block-blog-single block-cart">
                    <div className="container">
                        <div className="top-head-blog">
                            <div className="text-center">
                                <h2 className="font-4xl-bold">Shop Cart</h2>
                                <div className="breadcrumbs d-inline-block">
                                    <ul>
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Shop</a></li>
                                        <li><a href="#">Cart</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div className="box-table-cart">
                            <div className="table-responsive">
                                <table className="table table-striped table-cart">
                                    <thead>
                                        <tr>
                                            <th />
                                            <th className="text-start">Product</th>
                                            <th>Size</th>
                                            <th>Unit Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    {Array.isArray(items) && items.length === 0 ? (
                                        <p>Giỏ Hàng của bạn trống</p>
                                    ) : (
                                        Array.isArray(items) && items.map((item) => (
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" />
                                                    </td>
                                                    <td>
                                                        <div className="box-product-cart"><a className="image-product-cart" href="#"><img src={item.avatar} alt="kidify" /></a><a className="title-product-cart" href="product-single.html">{item.name}</a></div>
                                                    </td>
                                                    <td><span className="size-product">{item.size}</span></td>
                                                    <td><span className="brand-1">{item.price}</span></td>
                                                    <td>
                                                        <div className="product-quantity">
                                                            <div className="quantity">
                                                                <span className="icon icon-minus d-flex align-items-center">
                                                                    <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H17.75Z" fill="currentColor" />
                                                                    </svg>
                                                                </span>
                                                                <input
                                                                    className="input-quantity border-0 text-center"
                                                                    type="text"
                                                                    value={item.pivot.quantity}
                                                                    readOnly
                                                                />
                                                                <span className="icon icon-plus d-flex align-items-center">
                                                                    <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H12.75V17.75C12.75 17.9167 12.6667 18 12.5 18H11.5C11.3333 18 11.25 17.9167 11.25 17.75V12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H11.25V6.25C11.25 6.08333 11.3333 6 11.5 6H12.5C12.6667 6 12.75 6.08333 12.75 6.25V11.25H17.75Z" fill="currentColor" />
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span className="brand-1"> Tổng giá: {(parseFloat(item.price) * item.pivot.quantity).toLocaleString()} VND</span></td>
                                                    <td>
                                                        <button onClick={() => handleRemoveFromCart(item.pivot.product_detail_id, item.pivot.cart_id)} className="btn-remove">
                                                            <svg className="d-inline-flex align-items-center justify-content-center" width={12} height={12} viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6.00011 4.82166L10.1251 0.696655L11.3034 1.87499L7.17844 5.99999L11.3034 10.125L10.1251 11.3033L6.00011 7.17832L1.87511 11.3033L0.696777 10.125L4.82178 5.99999L0.696777 1.87499L1.87511 0.696655L6.00011 4.82166Z" fill="#111111" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        ))
                                    )}

                                </table>
                            </div>
                            <div className="row">
                                <div className="col-lg-5 mb-30">
                                    <div className="box-form-discount">
                                        <div className="box-form-discount-inner">
                                            <input className="form-control" type="text" placeholder="Discount Code" />
                                            <button className="btn btn-apply">Apply</button>
                                        </div>
                                    </div>
                                    <div className="box-calc-shipping">
                                        <h6 className="font-md-bold mb-10">Calculate Shipping</h6>
                                        <p className="mb-5 font-sm">Flat rate: 5%</p>
                                        <div className="row">
                                            <div className="col-lg-12">
                                                <div className="form-group">
                                                    <select className="form-control" name="state">
                                                        <option value="">Country</option>
                                                        <option value="Japan">Japan</option>
                                                        <option value="USA">USA</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div className="col-lg-6">
                                                <div className="form-group">
                                                    <input className="form-control" type="text" placeholder="Stage / Country" />
                                                </div>
                                            </div>
                                            <div className="col-lg-6">
                                                <div className="form-group">
                                                    <input className="form-control" type="text" placeholder="PostCode / ZIP" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-lg-4 mb-30">
                                    <div className="box-cart-total">
                                        <div className="item-total"><span className="font-sm">Subtotal</span><span className="font-md-bold">
                                            <span>
                                                {items.reduce((acc, item) =>
                                                    acc + (Number(item.price) * Number(item.pivot.quantity)), 0).toLocaleString()} VND
                                            </span>
                                        </span></div>
                                        <div className="item-total"><span className="font-sm">Shipping</span><span className="font-md-bold">Free</span></div>
                                        <div className="item-total"><span className="font-sm">Estimate for</span><span className="font-md-bold">United Kingdom</span></div>
                                        <div className="item-total border-0"><span className="font-sm">Total</span><span className="font-xl-bold">
                                            {(
                                                items.reduce((acc, item) =>
                                                    acc + (Number(item.price) * Number(item.pivot.quantity)), 0)
                                            ).toLocaleString()} VND
                                        </span></div><button onClick={handleCheckout} className="btn btn-brand-1-xl-bold w-100 font-sm-bold">Proceed To CheckOut</button>
                                    </div>
                                </div>
                                <div className="col-lg-3 mb-30">
                                    <div className="box-button-checkout"><Link to='/product' className="btn btn-brand-1-border-2 mr-10" >Continue Shopping
                                        <svg className="icon-16 ml-5" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                                        </svg></Link><a className="btn btn-brand-1-bold" href="#">
                                            <svg className="icon-16 mr-5" width={16} height={16} viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.00002 8C2.00002 4.68629 4.68631 2 8.00002 2C9.88486 2 11.5667 2.86911 12.6667 4.22844" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M13 2L13 4.66667L10.3333 4.66667" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M14 8C14 11.3137 11.3137 14 8.00001 14C6.11517 14 4.43331 13.1309 3.33334 11.7716" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M3 14L3 11.3333L5.66667 11.3333" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
                                            </svg>Update Cart</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section className="section block-section-10">
                    <div className="container">
                        <div className="top-head justify-content-center">
                            <h4 className="text-uppercase brand-1 wow fadeInDown">instagram feed</h4>
                        </div>
                    </div>
                    <div className="box-gallery-instagram">
                        <div className="box-gallery-instagram-inner">
                            <div className="gallery-item wow fadeInLeft"><img src={Ig} alt="kidify" /></div>
                            <div className="gallery-item wow fadeInUp"><img src={IgTwo} alt="kidify" /></div>
                            <div className="gallery-item wow fadeInUp"><img src={IgThree} alt="kidify" /></div>
                            <div className="gallery-item wow fadeInUp"><img src={IgFour} alt="kidify" /></div>
                            <div className="gallery-item wow fadeInRight"><img src={IgFive} alt="kidify" /></div>
                            <div className="gallery-item wow fadeInRight"><img src={IgOne} alt="kidify" /></div>
                        </div>
                    </div>
                </section>
            </main>

        </>
    )
}

export default CartComponent