import { createSlice, createAsyncThunk, PayloadAction } from '@reduxjs/toolkit';
import axios from 'axios';

// Định nghĩa kiểu dữ liệu
export interface CartItem {
  id: number;
  product_id: number;
  cart_id: number;
  avatar: string;
  product_name: string;
  quantity: number;
  price: number;
  total: number;
  product: {
    id: number;
    name: string;
    avatar: string;
    price: number;
  };
}

interface Cart {
  id: number;
  user_id: number;
  created_at: string;
  updated_at: string;
  items: CartItem[];
}

interface CartState {
  cart: Cart | null;
  items: CartItem[];
  status: 'idle' | 'loading' | 'succeeded' | 'failed';
  error: string | null;
}

const initialState: CartState = {
  cart: null,
  // local: JSON.parse(localStorage.getItem('cartItems') || '[]'),
  items: [],
  status: 'idle',
  error: null,
};

// API actions
export const fetchCart = createAsyncThunk<Cart, number, { rejectValue: string }>(
  'cart/fetchCart',
  async (userId, { rejectWithValue }) => {
    try {
      const response = await fetch(`http://127.0.0.1:8000/api/cart/${userId}`);
      if (!response.ok) {
        throw new Error('Failed to fetch cart');
      }
      const data = await response.json();
      return data.cart;
    } catch (error) {
      return rejectWithValue('Error fetching cart');
    }
  }
);

export const addToCart = createAsyncThunk<
  CartItem,
  { productId: number; quantity: number },
  { rejectValue: { message: string } }
>('cart/addToCart', async ({ productId, quantity }, { rejectWithValue }) => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.post(
      'http://127.0.0.1:8000/api/carts',
      { product_id: productId, quantity },
      { headers: 
        { Authorization: `Bearer ${token}` }
       }
    );
    return response.data as CartItem; 
  } catch (error: any) {
    return rejectWithValue(error.response.data);
  }
});

// Update cart item API
export const updateCartItem = createAsyncThunk<
  CartItem,
  { cartId: number; productId: number; quantity: number },
  { rejectValue: string }
>(
  'cart/updateCartItem',
  async ({ cartId, productId, quantity }, { rejectWithValue }) => {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.put(
        `http://127.0.0.1:8000/api/cart/${cartId}/update/${productId}`,
        { quantity },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      return response.data.cart_item;
    } catch (error: any) {
      return rejectWithValue(error.response.data.message || 'Error updating cart item');
    }
  }
);

// Slice
const CartReducer = createSlice({
  name: 'cart',
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchCart.pending, (state) => {
        state.status = 'loading';
        state.error = null;
      })
      .addCase(fetchCart.fulfilled, (state, action: PayloadAction<Cart>) => {
        state.status = 'succeeded';
        state.cart = action.payload;
      })
      .addCase(fetchCart.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.payload as string;
      })
      .addCase(addToCart.pending, (state) => {
        state.status = 'loading';
      })
      .addCase(addToCart.fulfilled, (state, action: PayloadAction<CartItem>) => {
        state.status = 'succeeded';

        const existingItem = state.items.find(
          (item) => item.product_id === action.payload.product_id
        );

        if (existingItem) {
          // Cập nhật số lượng và tổng
          existingItem.quantity = action.payload.quantity;
          existingItem.total = action.payload.total;
        } else {
          // Thêm sản phẩm mới
          state.items.push(action.payload);
        }
      })
      .addCase(addToCart.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.payload?.message || 'Đã xảy ra lỗi';
      })
      .addCase(updateCartItem.pending, (state) => {
        state.status = 'loading';
      })
      .addCase(updateCartItem.fulfilled, (state, action: PayloadAction<CartItem>) => {
        state.status = 'succeeded';

        if (state.cart) {
          // Cập nhật sản phẩm trong giỏ hàng
          const cartItemIndex = state.cart.items.findIndex(
            (item) => item.product_id === action.payload.product_id
          );
          if (cartItemIndex !== -1) {
            state.cart.items[cartItemIndex] = {
              ...state.cart.items[cartItemIndex],
              quantity: action.payload.quantity,
              total: action.payload.total,
            };
          }

          // Cập nhật sản phẩm trong items
          const itemIndex = state.items.findIndex(
            (item) => item.product_id === action.payload.product_id
          );
          if (itemIndex !== -1) {
            state.items[itemIndex] = {
              ...state.items[itemIndex],
              quantity: action.payload.quantity,
              total: action.payload.total,
            };
          }
        }

        // Lưu cập nhật vào localStorage
        const updatedLocalCart = state.items.map((item) => ({
          product_name: item.product_name,
          price: item.price,
          avatar: item.avatar,
          quantity: item.quantity,
        }));

        localStorage.setItem('cartItems', JSON.stringify(updatedLocalCart));
      })
      .addCase(updateCartItem.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.payload as string;
      });
  },
});

export default CartReducer.reducer;
