import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';

// Define the type for the order
export interface Order {
  user_id: string | null;
  total_amount: number;
  ship_method: number;
  payment_method_id: number;
  ship_address_id: number; 
  status: number; 
  order_details?: OrderDetail[]; // Thêm trường cho chi tiết đơn hàng
}

export interface OrderDetail {
  product_detail_id: number;
  quantity: number;
  price: number;
  name_product: string;
  size: string;
  color: string;
  total: any
}

interface OrderState {
  orders: Order[]; 
  loading: boolean;
  error: string | null;
}

const initialState: OrderState = {
  orders: [],
  loading: false,
  error: null,
};

export const postOrder = createAsyncThunk<Order, Order>(
  'order/postOrder',
  async (orderData: Order) => {
    const response = await axios.post('http://127.0.0.1:8000/api/orders', orderData);
    return response.data; 
  }
);

const OrderReducer = createSlice({
  name: 'order',
  initialState,
  reducers: {
  },
  extraReducers: (builder) => {
    builder
      .addCase(postOrder.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(postOrder.fulfilled, (state, action) => {
        state.loading = false;
        state.orders.push(action.payload);
      })
      .addCase(postOrder.rejected, (state, action) => {
        state.loading = false;
        state.error = action.error.message || 'Đã xảy ra lỗi!';
      });
  },
});

export default OrderReducer.reducer;
