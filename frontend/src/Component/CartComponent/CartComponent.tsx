import { useSelector } from "react-redux";
import { RootState, useAppDispatch } from "../../Redux/store";
import { useEffect, useState } from "react";
import { fetchCart } from "../../Redux/Reducer/CartReducer";
import { Link } from "react-router-dom";
import { fetchVouchers } from "../../Redux/Reducer/VoucherReducer";

interface CartProps {
    userId: number;
}

const CartComponent: React.FC<CartProps> = ({ userId }) => {
    const dispatch = useAppDispatch();
    const cart = useSelector((state: RootState) => state.cart.items);
    const vouchers = useSelector((state: RootState) => state.voucherReducer);
    const [voucherCode, setVoucherCode] = useState<string>("");
    const [voucherValid, setVoucherValid] = useState<boolean | null>(null);
    const [discountValue, setDiscountValue] = useState<number>(0);
    console.log(vouchers);
    
    useEffect(() => {
        dispatch(fetchCart(userId));
    }, [dispatch, userId]);

    useEffect(() => {
        dispatch(fetchVouchers());
    }, [dispatch]);

    const handleVoucherInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setVoucherCode(e.target.value);
    };

    // const handleVoucherApply = () => {
    //     if (vouchers?.vouchers?.length > 0) {
    //         const foundVoucher = vouchers.vouchers.find(voucher => voucher.code === voucherCode);
    
    //         if (foundVoucher) {
    //             const totalAmount = cart.reduce((acc, item) => acc + (Number(item.price) * Number(item.quantity)), 0);
    //             const discountPercentage = Number(foundVoucher.discount_value); 
    //             const discount = (totalAmount * (discountPercentage / 100));
    //             setDiscountValue(discount);
    //             setVoucherValid(true);
    //             console.log("Voucher code is valid:", voucherCode);
    //         } else {
    //             setVoucherValid(false);
    //             setDiscountValue(0);
    //             console.log("Voucher code is invalid.");
    //         }
    //     } else {
    //         setVoucherValid(false);
    //         setDiscountValue(0);
    //         console.log("No vouchers available.");
    //     }
    // };
    


    // const handleQuantityChange = (itemId: number, newQuantity: number) => {
    //     if (newQuantity <= 0) return;

    //     const updatedItem = cart.find(item => item.id === itemId);
    //     if (updatedItem) {
    //         const sizeId = updatedItem.size_id;
    //         const colorId = updatedItem.color_id;

    //         dispatch(updateCartItem({
    //             cartId: updatedItem.cart_id,
    //             productId: updatedItem.product_id,
    //             quantity: newQuantity,
    //             sizeId: sizeId,
    //             colorId: colorId
    //         }));
    //     }
    // };

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
                                {cart.length === 0 ? (
                                    <p>Giỏ Hàng của bạn trống</p>
                                ) : (
                                    cart.map((item) => (
                                        <tbody key={item.id}>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" />
                                                </td>
                                                <td>
                                                    <div className="box-product-cart">
                                                        <a className="title-product-cart" href="product-single.html">{item.product_name}</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src={`http://127.0.0.1:8000/storage/${item.avatar}`} width={'50px'} alt={item.product_name} />
                                                </td>
                                                <td><span className="brand-1">{(item.price).toLocaleString('vi-VN')} VND</span></td>
                                                <td>
                                                    <div className="product-quantity">
                                                        <div className="quantity">
                                                            {/* <span className="icon icon-minus d-flex align-items-center" onClick={() => handleQuantityChange(item.id, item.quantity - 1)} >
                                                                <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H17.75Z" fill="currentColor" />
                                                                </svg>
                                                            </span> */}
                                                            <input
                                                                className="input-quantity border-0 text-center"
                                                                type="number"
                                                                value={item.quantity}
                                                                // onChange={(e) => handleQuantityChange(item.id, parseInt(e.target.value))}
                                                            />
                                                            {/* <span className="icon icon-plus d-flex align-items-center" onClick={() => handleQuantityChange(item.id, item.quantity + 1)} >
                                                                <svg width={24} height={24} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M17.75 11.25C17.9167 11.25 18 11.3333 18 11.5V12.5C18 12.6667 17.9167 12.75 17.75 12.75H12.75V17.75C12.75 17.9167 12.6667 18 12.5 18H11.5C11.3333 18 11.25 17.9167 11.25 17.75V12.75H6.25C6.08333 12.75 6 12.6667 6 12.5V11.5C6 11.3333 6.08333 11.25 6.25 11.25H11.25V6.25C11.25 6.08333 11.3333 6 11.5 6H12.5C12.6667 6 12.75 6.08333 12.75 6.25V11.25H17.75Z" fill="currentColor" />
                                                                </svg>
                                                            </span> */}
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
    {/* Phần nhập mã giảm giá */}
    <div className="col-lg-5 mb-30">
        <div className="box-form-discount">
            <div className="box-form-discount-inner">
                <input
                    className="form-control"
                    type="text"
                    placeholder="Discount Code"
                    value={voucherCode}
                    onChange={handleVoucherInputChange}
                />
                {/* <button className="btn btn-apply" onClick={handleVoucherApply}>Apply</button> */}
           
            </div>
        </div>
    </div>

    {/* Phần tính tổng tiền giỏ hàng */}
    <div className="col-lg-4 mb-30">
        <div className="box-cart-total">
            {/* Hiển thị subtotal (tổng tiền giỏ hàng trước khi áp dụng voucher) */}
            <div className="item-total">
                <span className="font-sm">Subtotal</span>
                <span className="font-md-bold">
                    <span>
                        {cart.reduce((acc, item) =>
                            acc + (Number(item.price) * Number(item.quantity)), 0)
                            .toLocaleString('vi-VN')} VND
                    </span>
                </span>
            </div>

            {/* Hiển thị phí vận chuyển */}
            <div className="item-total">
                <span className="font-sm">Shipping</span><span className="font-md-bold">Free</span>
            </div>
            <div className="item-total">
                <span className="font-sm">Estimate for</span><span className="font-md-bold">United Kingdom</span>
            </div>

            <div className="item-total border-0">
                <span className="font-sm">Total</span>
                <span className="font-xl-bold">
                    <span>
                        {cart.reduce((acc, item) =>
                            acc + (Number(item.price) * Number(item.quantity)), 0) * (1 - discountValue / 100)
                           } VND
                    </span>
                </span>
            </div>

            <Link to={`/checkout/${userId}`}>
                <button className="btn btn-brand-1-xl-bold w-100 font-sm-bold">Proceed To CheckOut</button>
            </Link>
        </div>
    </div>

    <div className="col-lg-3 mb-30">
        <div className="box-button-checkout">
            <Link to='/product' className="btn btn-brand-1-border-2 mr-10">Continue Shopping
                <svg className="icon-16 ml-5" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                </svg>
            </Link>
        </div>
    </div>
</div>

                    </div>
                </div>
            </section>
        </main>
    );
};

export default CartComponent;
