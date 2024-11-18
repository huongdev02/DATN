import { Link, useNavigate } from 'react-router-dom';
import { useSelector } from 'react-redux';
import { RootState, useAppDispatch } from '../../Redux/store';
import { useEffect } from 'react';
import { fetchCart, updateCartItem } from '../../Redux/Reducer/CartReducer'

interface CartProps {
    userId: number;
}

const CartComponent: React.FC<CartProps> = ({ userId }) => {
    const dispatch = useAppDispatch();
    const { cart, status, error } = useSelector((state: RootState) => state.cart);

    useEffect(() => {
        dispatch(fetchCart(userId));
    }, [dispatch, userId]);

    const handleQuantityChange = (itemId: number, newQuantity: number) => {
        if (newQuantity <= 0) return;

        if (cart) {
            const updatedItem = cart.items.find(item => item.id === itemId);
            if (updatedItem) {
                dispatch(updateCartItem({ cartId: cart.id, productId: updatedItem.product_id, quantity: newQuantity }));
            }
        }
    };

    return (
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
                                        <th className="text-start">Product Name</th>
                                        <th className="text-start">Avatar</th>
                                        <th className="text-start">Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                {cart?.items && cart.items.length === 0 ? (
                                    <p>Giỏ Hàng của bạn trống</p>
                                ) : (
                                    cart?.items && cart.items.map((item) => (
                                        <tbody key={item.id}>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" />
                                                </td>
                                                <td>
                                                    <div className="box-product-cart">
                                                        <a className="title-product-cart" href="product-single.html">{item.product.name}</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src={`http://127.0.0.1:8000/storage/${item.product.avatar}`} width={'50px'} alt={item.product.name} />
                                                </td>
                                                <td><span className="brand-1">{(item.price).toLocaleString('vi-VN')} VND</span></td>
                                                <td>
                                                    <div className="product-quantity">
                                                        <div className="quantity">
                                                            <span className="icon icon-minus d-flex align-items-center" onClick={() => handleQuantityChange(item.id, item.quantity - 1)}>
                                                                <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H17.75Z" fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                            <input
                                                                className="input-quantity border-0 text-center"
                                                                type="number"
                                                                value={item.quantity}
                                                                onChange={(e) => handleQuantityChange(item.id, parseInt(e.target.value))}
                                                            />
                                                            <span className="icon icon-plus d-flex align-items-center" onClick={() => handleQuantityChange(item.id, item.quantity + 1)}>
                                                                <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H12.75V17.75C12.75 17.9167 12.6667 18 12.5 18H11.5C11.3333 18 11.25 17.9167 11.25 17.75V12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H11.25V6.25C11.25 6.08333 11.3333 6 11.5 6H12.5C12.6667 6 12.75 6.08333 12.75 6.25V11.25H17.75Z" fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span className="brand-1">
                                                        {(Number(item.price) * Number(item.quantity || 1)).toLocaleString('vi-VN')} VND
                                                    </span>
                                                </td>
                                                <td>Xóa</td>
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
                                            {cart?.items.reduce((acc, item) =>
                                                acc + (Number(item.price) * Number(item.quantity)), 0).toLocaleString('vi-VN')} VND
                                        </span>
                                    </span></div>
                                    <div className="item-total"><span className="font-sm">Shipping</span><span className="font-md-bold">Free</span></div>
                                    <div className="item-total"><span className="font-sm">Estimate for</span><span className="font-md-bold">United Kingdom</span></div>
                                    <div className="item-total border-0"><span className="font-sm">Total</span><span className="font-xl-bold">
                                        <span>
                                            {cart?.items.reduce((acc, item) =>
                                                acc + (Number(item.price) * Number(item.quantity)), 0).toLocaleString('vi-VN')} VND
                                        </span>
                                    </span></div>
                                    <Link to={`/checkout/${userId}`}>
                                    <button className="btn btn-brand-1-xl-bold w-100 font-sm-bold">Proceed To CheckOut</button>
                                    </Link>
                                </div>
                            </div>
                            <div className="col-lg-3 mb-30">
                                <div className="box-button-checkout"><Link to='/product' className="btn btn-brand-1-border-2 mr-10" >Continue Shopping
                                    <svg className="icon-16 ml-5" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                                    </svg></Link></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    );
}

export default CartComponent;
