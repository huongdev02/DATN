import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';

// Định nghĩa kiểu cho sản phẩm
export interface ProductDetail {
    id: number;
    product_id: number;
    size_id: number;
    color_id: number;
    quantity: number;
    sell_quantity: number;
    number_statictis: number;
    name: string;
    color: string;
    size: string;
    price: string;
    pivot: {
        cart_id: number;
        product_detail_id: number;
        quantity: number;
    };
}

// Định nghĩa trạng thái giỏ hàng
interface CartState {
    items: ProductDetail[];
    loading: boolean;
    error: string | null;
}

// Trạng thái khởi tạo
const initialState: CartState = {
    items: [],
    loading: false,
    error: null,
};

// Thunk để lấy giỏ hàng
export const fetchCart = createAsyncThunk(
    'cart/fetchCart',
    async (userId: number, { rejectWithValue }) => {
        try {
            const response = await axios.get(`http://localhost:8000/api/carts/${userId}`);
            return response.data.data.product_details; // Thay đổi theo cấu trúc API của bạn
        } catch (error) {
            return rejectWithValue('Failed to fetch cart');
        }
    }
);

// Thunk để thêm sản phẩm vào giỏ hàng
export const addToCart = createAsyncThunk(
    'cart/addToCart',
    async (
        { product_detail_id, user_id, quantity }: { product_detail_id: number; user_id: number; quantity: number },
        { rejectWithValue }
    ) => {
        try {
            const response = await axios.post('http://localhost:8000/api/carts', {
                product_detail_id,
                user_id,
                quantity,
            });
            return response.data; // Thay đổi theo cấu trúc API của bạn
        } catch (error) {
            return rejectWithValue('Failed to add to cart');
        }
    }
);

// Thunk để xóa sản phẩm khỏi giỏ hàng
export const removeFromCart = createAsyncThunk(
    'cart/removeFromCart',
    async ({ cartId, productDetailId }: { cartId: number; productDetailId: number }) => {
        const response = await axios.delete(`http://127.0.0.1:8000/api/carts/destroyOne/${cartId}&${productDetailId}`);
        return response.data; // Giả sử API trả về dữ liệu cần thiết
    }
);

// Tạo slice cho giỏ hàng
const CartReducer = createSlice({
    name: 'cart',
    initialState,
    reducers: {
        clearCart(state) {
            state.items = [];
        },
    },
    extraReducers: (builder) => {
        builder
            .addCase(addToCart.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(addToCart.fulfilled, (state, action) => {
                state.loading = false;
                state.items.push(action.payload);
            })
            .addCase(addToCart.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload as string;
            })
            .addCase(fetchCart.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(fetchCart.fulfilled, (state, action) => {
                state.loading = false;
                state.items = action.payload;
            })
            .addCase(fetchCart.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload as string;
            })
            .addCase(removeFromCart.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(removeFromCart.fulfilled, (state, action) => {
                state.loading = false;
                state.items = state.items.filter(
                    (item) => item.id !== action.payload
                );
            })
            .addCase(removeFromCart.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload as string;
            });
    },
});

// Xuất ra action và reducer
export const { clearCart } = CartReducer.actions;
export default CartReducer.reducer;
